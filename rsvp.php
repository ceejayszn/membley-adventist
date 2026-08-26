<?php
// rsvp.php - Interactive Attendance Registration & Celebration
require_once 'includes/db.php';
require_once 'includes/header.php';

$event_title = "Homecoming Sabbath (10 Yrs Celebration)";
$submitted = false;
$error_msg = "";
$registered_name = "";

// Helper function to extract detailed Phone Model, Device, and OS from User-Agent
function detect_device_details($user_agent) {
    $device_type = 'Desktop';
    $phone_model = 'Desktop PC / Mac';
    $os = 'Unknown OS';
    $browser = 'Unknown Browser';

    // 1. Operating System Detection
    if (preg_match('/windows nt 10.0/i', $user_agent)) $os = 'Windows 10/11';
    elseif (preg_match('/windows nt 6.3/i', $user_agent)) $os = 'Windows 8.1';
    elseif (preg_match('/windows nt 6.1/i', $user_agent)) $os = 'Windows 7';
    elseif (preg_match('/macintosh|mac os x/i', $user_agent)) $os = 'macOS';
    elseif (preg_match('/android/i', $user_agent)) $os = 'Android';
    elseif (preg_match('/iphone/i', $user_agent)) $os = 'iOS (iPhone)';
    elseif (preg_match('/ipad/i', $user_agent)) $os = 'iPadOS (iPad)';
    elseif (preg_match('/linux/i', $user_agent)) $os = 'Linux';

    // 2. Device Type Detection
    if (preg_match('/ipad|tablet|playbook|silk/i', $user_agent)) {
        $device_type = 'Tablet';
    } elseif (preg_match('/mobile|phone|ipod|android|blackberry|webos|iemobile/i', $user_agent)) {
        $device_type = 'Mobile';
    }

    // 3. Browser Detection
    if (preg_match('/edg|edge/i', $user_agent)) $browser = 'Microsoft Edge';
    elseif (preg_match('/samsungbrowser/i', $user_agent)) $browser = 'Samsung Internet';
    elseif (preg_match('/opr|opera/i', $user_agent)) $browser = 'Opera';
    elseif (preg_match('/chrome|crios/i', $user_agent)) $browser = 'Chrome';
    elseif (preg_match('/firefox|fxios/i', $user_agent)) $browser = 'Firefox';
    elseif (preg_match('/safari/i', $user_agent)) $browser = 'Safari';

    // 4. Detailed Phone Model Extraction
    if (preg_match('/iPhone/i', $user_agent)) {
        $phone_model = 'Apple iPhone';
        if (preg_match('/iPhone\s?OS\s?([\d_]+)/i', $user_agent, $m)) {
            $phone_model .= ' (iOS ' . str_replace('_', '.', $m[1]) . ')';
        }
    } elseif (preg_match('/iPad/i', $user_agent)) {
        $phone_model = 'Apple iPad';
    } elseif (preg_match('/Android/i', $user_agent)) {
        if (preg_match('/;\s*([^;]+?)\s*Build\//i', $user_agent, $matches)) {
            $raw_model = trim($matches[1]);
            $phone_model = $raw_model;
            if (stripos($raw_model, 'SM-') === 0 || stripos($raw_model, 'SAMSUNG') !== false) {
                $phone_model = 'Samsung ' . $raw_model;
            } elseif (stripos($raw_model, 'TECNO') !== false) {
                $phone_model = $raw_model;
            } elseif (stripos($raw_model, 'Infinix') !== false) {
                $phone_model = $raw_model;
            } elseif (stripos($raw_model, 'Redmi') !== false || stripos($raw_model, 'POCO') !== false || stripos($raw_model, 'Xiaomi') !== false) {
                $phone_model = 'Xiaomi ' . $raw_model;
            } elseif (stripos($raw_model, 'CPH') === 0 || stripos($raw_model, 'OPPO') !== false) {
                $phone_model = 'OPPO ' . $raw_model;
            } elseif (stripos($raw_model, 'V2') === 0 || stripos($raw_model, 'vivo') !== false) {
                $phone_model = 'Vivo ' . $raw_model;
            } elseif (stripos($raw_model, 'Pixel') !== false) {
                $phone_model = 'Google ' . $raw_model;
            }
        } else {
            $phone_model = 'Android Smartphone';
        }
    } else {
        if ($os === 'macOS') $phone_model = 'Apple Mac Computer';
        elseif (strpos($os, 'Windows') !== false) $phone_model = 'Windows PC';
        else $phone_model = 'Desktop Computer';
    }

    return [
        'device_type' => $device_type,
        'phone_model' => $phone_model,
        'os'          => $os,
        'browser'     => $browser
    ];
}

// Process Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_rsvp'])) {
    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $is_membley_member = isset($_POST['is_membley_member']) ? intval($_POST['is_membley_member']) : 0;
    $church_from = trim($_POST['church_from'] ?? '');
    $attendees_count = intval($_POST['attendees_count'] ?? 1);
    $inquiry = trim($_POST['inquiry'] ?? '');

    if (empty($full_name) || empty($phone)) {
        $error_msg = "Please enter both your Full Name and Phone Number to confirm attendance.";
    } else {
        if ($is_membley_member == 1 && empty($church_from)) {
            $church_from = "Membley SDA Church";
        }

        // IP Detection
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        $ip = trim($ip);

        // User Agent & Device info
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $device_info = detect_device_details($user_agent);

        // Location & ISP
        $location = 'Kenya / Local';
        $network_isp = 'Internet Network';
        if ($ip !== '127.0.0.1' && $ip !== '::1' && $ip !== 'localhost' && $ip !== 'Unknown') {
            $ctx = stream_context_create(['http' => ['timeout' => 2]]);
            $geo_json = @file_get_contents("http://ip-api.com/json/" . urlencode($ip), false, $ctx);
            if ($geo_json) {
                $geo_data = json_decode($geo_json, true);
                if (($geo_data['status'] ?? '') === 'success') {
                    $city = $geo_data['city'] ?? '';
                    $country = $geo_data['country'] ?? '';
                    $location = (!empty($city) ? $city . ', ' : '') . $country;
                    $network_isp = $geo_data['isp'] ?? ($geo_data['as'] ?? 'Unknown ISP');
                }
            }
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO event_rsvps 
                (event_id, event_title, full_name, is_membley_member, church_from, phone, attendees_count, inquiry, ip_address, device_type, phone_model, browser, os, location, network_isp, user_agent)
                VALUES 
                (:event_id, :event_title, :full_name, :is_member, :church, :phone, :attendees, :inquiry, :ip, :device_type, :phone_model, :browser, :os, :location, :isp, :ua)");
            
            $stmt->execute([
                ':event_id'     => 1,
                ':event_title'  => $event_title,
                ':full_name'    => $full_name,
                ':is_member'    => $is_membley_member,
                ':church'       => !empty($church_from) ? $church_from : 'Visitor',
                ':phone'        => $phone,
                ':attendees'    => max(1, $attendees_count),
                ':inquiry'      => $inquiry,
                ':ip'           => $ip,
                ':device_type'  => $device_info['device_type'],
                ':phone_model'  => $device_info['phone_model'],
                ':browser'      => $device_info['browser'],
                ':os'           => $device_info['os'],
                ':location'     => $location,
                ':isp'          => $network_isp,
                ':ua'           => $user_agent
            ]);

            $submitted = true;
            $registered_name = $full_name;
        } catch (PDOException $e) {
            $error_msg = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!-- Banner Section -->
<section style="background-color: var(--primary-dark); color: white; padding: 3rem 0; text-align: center; background-image: linear-gradient(rgba(4,25,40,0.88), rgba(4,25,40,0.88)), url('assets/images/church_banner.png'); background-size: cover; background-position: center;">
    <div class="container">
        <span style="background-color: #84cc16; color: #041928; font-weight: 800; text-transform: uppercase; font-size: 0.8rem; padding: 0.35rem 0.85rem; border-radius: 50px; display: inline-block; margin-bottom: 0.75rem; letter-spacing: 0.5px;">
            <i class="fa-solid fa-calendar-check"></i> Homecoming Registration
        </span>
        <h1 style="color: white; font-size: 2.3rem; margin-bottom: 0.4rem;">Will You Be Attending?</h1>
        <p style="color: rgba(255,255,255,0.85); font-size: 1rem; max-width: 600px; margin: 0 auto;">
            <strong>Homecoming Sabbath</strong> — Celebrating 10 Yrs of Fellowship and Family<br>
            <span style="color: #a3e635; font-weight: 700;"><i class="fa-solid fa-calendar-day"></i> Sabbath, 31 OCT 2026 | Starts 8:00 AM</span>
        </p>
    </div>
</section>

<!-- Content Section -->
<section class="section-padding container">

    <?php if ($submitted): ?>
        
        <!-- Floating Balloons Animation Container -->
        <div class="balloon-container" id="balloonContainer">
            <span class="balloon" style="left: 10%; animation-delay: 0s;">🎈</span>
            <span class="balloon" style="left: 25%; animation-delay: 0.8s;">🎉</span>
            <span class="balloon" style="left: 40%; animation-delay: 0.3s;">🎈</span>
            <span class="balloon" style="left: 60%; animation-delay: 1.2s;">✨</span>
            <span class="balloon" style="left: 75%; animation-delay: 0.5s;">🎈</span>
            <span class="balloon" style="left: 88%; animation-delay: 1.5s;">🎉</span>
        </div>

        <!-- Thank You Confirmation Card -->
        <div class="rsvp-thankyou-card">
            
            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🎉</div>
            
            <h2 style="color: var(--primary); font-size: 2.1rem; margin-bottom: 0.25rem;">
                Thank You, <?php echo htmlspecialchars($registered_name); ?>!
            </h2>
            
            <p style="font-size: 1.25rem; font-weight: 700; color: #65a30d; margin-bottom: 1.25rem;">
                ✨ Feel at the feet of Jesus ✨
            </p>

            <p style="font-size: 1rem; color: var(--text-dark); margin-bottom: 1.5rem; line-height: 1.6;">
                Your attendance for the <strong>Homecoming Sabbath (10 Yrs Celebration)</strong> is confirmed. We cannot wait to welcome and praise God together with you!
            </p>

            <!-- Scripture Quote Card -->
            <div class="scripture-card">
                <div class="scripture-verse">
                    "Come, let us sing for joy to the Lord; let us shout aloud to the Rock of our salvation. Let us come before him with thanksgiving and extol him with music and song."
                </div>
                <div class="scripture-ref">
                    <i class="fa-solid fa-book-bible"></i> Psalm 95:1–2
                </div>
            </div>

            <!-- Event Details Pill -->
            <div style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: 10px; padding: 1.25rem; margin: 1.5rem 0; text-align: left;">
                <h4 style="color: var(--primary); margin-bottom: 0.6rem; font-size: 0.95rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.4rem;">
                    <i class="fa-solid fa-location-dot" style="color: #84cc16;"></i> Event Information
                </h4>
                <p style="font-size: 0.9rem; margin-bottom: 0.25rem;"><strong>Date:</strong> Sabbath, 31 October 2026</p>
                <p style="font-size: 0.9rem; margin-bottom: 0.25rem;"><strong>Time:</strong> Starts at 8:00 AM</p>
                <p style="font-size: 0.9rem;"><strong>Venue:</strong> Membley Park Estate, Ruiru, Kenya</p>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; flex-direction: column; gap: 0.75rem; align-items: center;">
                <a href="https://api.whatsapp.com/send?text=<?php echo urlencode("Hello! I just confirmed my attendance for the Membley SDA Homecoming Sabbath (Celebrating 10 Yrs of Fellowship & Family) on Oct 31, 2026. Will you be attending too? Register here: https://" . ($_SERVER['HTTP_HOST'] ?? 'membleyadventist.org') . "/rsvp.php"); ?>" target="_blank" class="btn btn-lime" style="width: 100%; max-width: 380px;">
                    <i class="fa-brands fa-whatsapp" style="font-size: 1.2rem;"></i> Invite a Friend on WhatsApp
                </a>
                
                <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; justify-content: center; margin-top: 0.5rem;">
                    <a href="index.php" class="btn btn-primary btn-sm"><i class="fa-solid fa-house"></i> Home</a>
                    <a href="events.php" class="btn btn-outline btn-sm"><i class="fa-solid fa-calendar-days"></i> All Events</a>
                </div>
            </div>
        </div>

    <?php else: ?>

        <!-- Event Poster Display -->
        <div style="max-width: 440px; margin: 0 auto 2rem auto; text-align: center;">
            <img src="assets/images/homecoming_flyer.png" alt="Homecoming Sabbath 10 Yrs Poster" style="width: 100%; border-radius: 14px; border: 2.5px solid #84cc16; box-shadow: 0 8px 25px rgba(0,0,0,0.25); display: block;">
        </div>

        <!-- RSVP Interactive Box -->
        <div class="rsvp-box-card" id="rsvpCard">
            
            <?php if (!empty($error_msg)): ?>
                <div style="background-color: #fee2e2; border: 1px solid #ef4444; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlspecialchars($error_msg); ?>
                </div>
            <?php endif; ?>

            <!-- STEP 1: Will you be attending? (YES / NO) -->
            <div id="step1_choice" style="text-align: center; padding: 1rem 0;">
                <h2 style="color: var(--primary); font-size: 1.7rem; margin-bottom: 0.5rem;">
                    Will you be attending Homecoming Sabbath?
                </h2>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1.75rem;">
                    Celebrating 10 Years of Fellowship & Family at Membley Park Estate, Ruiru.
                </p>

                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    <button type="button" id="btnYes" class="btn btn-lime" style="font-size: 1.05rem; padding: 0.85rem 1.75rem;">
                        😊 YES, I'LL ATTEND
                    </button>
                    <button type="button" id="btnNo" class="btn btn-outline" style="font-size: 1.05rem; padding: 0.85rem 1.75rem; border-color: #94a3b8; color: #64748b;">
                        🥺 NO, I CAN'T
                    </button>
                </div>
            </div>

            <!-- "NO" Message Modal/Box -->
            <div id="noMessageBox" class="rsvp-step hidden" style="text-align: center; padding: 2rem 1rem; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1; margin-top: 1rem;">
                <div style="font-size: 3rem; margin-bottom: 0.5rem;">🥺</div>
                <h3 style="color: var(--primary); font-size: 1.4rem; margin-bottom: 0.5rem;">Please attend!</h3>
                <p style="color: var(--text-dark); font-size: 1rem; max-width: 480px; margin: 0 auto 1.5rem auto; line-height: 1.5;">
                    We would truly love to have you with us as we celebrate God's grace and 10 years of fellowship. We hope you can make it!
                </p>
                <button type="button" id="btnChangeMind" class="btn btn-lime">
                    😊 Change Mind — I'll Attend!
                </button>
            </div>

            <!-- MAIN REGISTRATION FORM -->
            <form action="rsvp.php" method="POST" id="mainRsvpForm" class="rsvp-step hidden" style="margin-top: 1.5rem;">
                
                <!-- Welcome Banner -->
                <div style="background: rgba(132, 204, 22, 0.12); border: 1px solid #84cc16; border-radius: 10px; padding: 0.9rem 1.2rem; margin-bottom: 1.75rem; display: flex; align-items: center; gap: 0.75rem;">
                    <span style="font-size: 1.8rem;">😊</span>
                    <div>
                        <strong style="color: var(--primary); display: block; font-size: 1rem;">You are warmly welcomed!</strong>
                        <small style="color: var(--text-dark);">Please enter your name and phone number to complete your RSVP.</small>
                    </div>
                </div>

                <!-- 1. Full Name (Required) -->
                <div class="form-group">
                    <label class="form-label" for="full_name">Full Name <span style="color: #e11d48;">*</span></label>
                    <input type="text" id="full_name" name="full_name" class="form-control" placeholder="e.g. John Doe / Sarah Mwangi" required value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>">
                </div>

                <!-- 2. Phone Number (Required) -->
                <div class="form-group">
                    <label class="form-label" for="phone">Phone Number <span style="color: #e11d48;">*</span></label>
                    <input type="tel" id="phone" name="phone" class="form-control" placeholder="e.g. 0712 345 678" required value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                </div>

                <!-- 3. Member Status Choice -->
                <div class="form-group">
                    <label class="form-label">Are you a Membley SDA Member?</label>
                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        <input type="hidden" name="is_membley_member" id="is_membley_member" value="0">
                        <button type="button" id="btnMemberYes" class="choice-chip">
                            <i class="fa-solid fa-church"></i> I am a Membley SDA Member
                        </button>
                        <button type="button" id="btnMemberVisitor" class="choice-chip active">
                            <i class="fa-solid fa-hand-holding-heart"></i> I am a Visitor / Other Church
                        </button>
                    </div>
                </div>

                <!-- 4. Church / Congregation Section (If visitor) -->
                <div class="form-group" id="churchSection">
                    <label class="form-label" for="church_from">Previous / Home Church You Are From:</label>
                    
                    <!-- Quick Select Chips -->
                    <div style="display: flex; gap: 0.4rem; flex-wrap: wrap; margin-bottom: 0.6rem;">
                        <span class="choice-chip church-pill" data-church="Ruiru SDA">Ruiru SDA</span>
                        <span class="choice-chip church-pill" data-church="Nairobi Central">Nairobi Central</span>
                        <span class="choice-chip church-pill" data-church="Kahawa West SDA">Kahawa West SDA</span>
                        <span class="choice-chip church-pill" data-church="Juja SDA">Juja SDA</span>
                        <span class="choice-chip church-pill" data-church="Githurai SDA">Githurai SDA</span>
                        <span class="choice-chip church-pill" data-church="Visiting Guest">Guest / Visitor</span>
                    </div>

                    <input type="text" id="church_from" name="church_from" class="form-control" placeholder="Type or click a church above" value="<?php echo htmlspecialchars($_POST['church_from'] ?? ''); ?>">
                </div>

                <!-- 5. Number of Attendees -->
                <div class="form-group">
                    <label class="form-label" for="attendees_count">Number of Attendees <small style="color: var(--text-muted); font-weight: normal;">(You + friends/family)</small></label>
                    <select id="attendees_count" name="attendees_count" class="form-control">
                        <option value="1" selected>1 Person (Just Me)</option>
                        <option value="2">2 Persons</option>
                        <option value="3">3 Persons</option>
                        <option value="4">4 Persons</option>
                        <option value="5">5+ Persons (Family / Group)</option>
                    </select>
                </div>

                <!-- 6. Inquiry / Questions / Prayer Box (Editable) -->
                <div class="form-group">
                    <label class="form-label" for="inquiry">Any Inquiries, Questions or Special Prayer Requests? <small style="color: var(--text-muted); font-weight: normal;">(Optional)</small></label>
                    <textarea id="inquiry" name="inquiry" class="form-control" rows="3" placeholder="Feel free to write any inquiry or special note here..."><?php echo htmlspecialchars($_POST['inquiry'] ?? ''); ?></textarea>
                </div>

                <!-- Submit Button -->
                <div style="margin-top: 2rem;">
                    <button type="submit" name="submit_rsvp" class="btn btn-lime" style="width: 100%; font-size: 1.1rem; padding: 0.95rem; justify-content: center;">
                        <i class="fa-solid fa-check-circle"></i> Confirm & Submit Attendance
                    </button>
                </div>

            </form>
        </div>

    <?php endif; ?>

</section>

<script>
// Interactive Client-side flow (Fast, lightweight Vanilla JS)
document.addEventListener('DOMContentLoaded', function() {
    const btnYes = document.getElementById('btnYes');
    const btnNo = document.getElementById('btnNo');
    const btnChangeMind = document.getElementById('btnChangeMind');
    const step1 = document.getElementById('step1_choice');
    const noMessageBox = document.getElementById('noMessageBox');
    const mainRsvpForm = document.getElementById('mainRsvpForm');

    const btnMemberYes = document.getElementById('btnMemberYes');
    const btnMemberVisitor = document.getElementById('btnMemberVisitor');
    const isMemberInput = document.getElementById('is_membley_member');
    const churchSection = document.getElementById('churchSection');
    const churchInput = document.getElementById('church_from');
    const churchPills = document.querySelectorAll('.church-pill');

    // 1. Click YES -> Open Form
    if (btnYes) {
        btnYes.addEventListener('click', function() {
            if (noMessageBox) noMessageBox.classList.add('hidden');
            if (step1) step1.classList.add('hidden');
            if (mainRsvpForm) {
                mainRsvpForm.classList.remove('hidden');
                document.getElementById('full_name').focus();
            }
        });
    }

    // 2. Click NO -> Show "Please attend 🥺" message
    if (btnNo) {
        btnNo.addEventListener('click', function() {
            if (mainRsvpForm) mainRsvpForm.classList.add('hidden');
            if (noMessageBox) noMessageBox.classList.remove('hidden');
        });
    }

    // 3. Click Change Mind -> Open Form
    if (btnChangeMind) {
        btnChangeMind.addEventListener('click', function() {
            if (noMessageBox) noMessageBox.classList.add('hidden');
            if (step1) step1.classList.add('hidden');
            if (mainRsvpForm) {
                mainRsvpForm.classList.remove('hidden');
                document.getElementById('full_name').focus();
            }
        });
    }

    // 4. Membership toggle
    if (btnMemberYes && btnMemberVisitor) {
        btnMemberYes.addEventListener('click', function() {
            btnMemberYes.classList.add('active');
            btnMemberVisitor.classList.remove('active');
            isMemberInput.value = '1';
            churchInput.value = 'Membley SDA Church';
            churchSection.style.display = 'none';
        });

        btnMemberVisitor.addEventListener('click', function() {
            btnMemberVisitor.classList.add('active');
            btnMemberYes.classList.remove('active');
            isMemberInput.value = '0';
            if (churchInput.value === 'Membley SDA Church') churchInput.value = '';
            churchSection.style.display = 'block';
        });
    }

    // 5. Quick church pill selection
    churchPills.forEach(pill => {
        pill.addEventListener('click', function() {
            churchPills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            churchInput.value = this.getAttribute('data-church');
        });
    });

    // Auto-reveal form if there was a server-side error
    <?php if (!empty($error_msg)): ?>
        if (step1) step1.classList.add('hidden');
        if (mainRsvpForm) mainRsvpForm.classList.remove('hidden');
    <?php endif; ?>
});
</script>

<?php require_once 'includes/footer.php'; ?>
