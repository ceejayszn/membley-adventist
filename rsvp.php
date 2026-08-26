<?php
// rsvp.php - Event Attendance Registration & Tracking
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
        // Look for common Android build models (e.g., SM-S918B, TECNO KI7, Infinix X6833B, Redmi Note 12)
        if (preg_match('/;\s*([^;]+?)\s*Build\//i', $user_agent, $matches)) {
            $raw_model = trim($matches[1]);
            // Clean common prefixes
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

// Process Attendance RSVP Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_rsvp'])) {
    $full_name = trim($_POST['full_name'] ?? '');
    $is_membley_member = isset($_POST['is_membley_member']) ? 1 : 0;
    $church_from = trim($_POST['church_from'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $attendees_count = intval($_POST['attendees_count'] ?? 1);
    $inquiry = trim($_POST['inquiry'] ?? '');

    if (empty($full_name) || empty($phone)) {
        $error_msg = "Please enter both your full name and phone number to confirm attendance.";
    } else {
        // If user ticked Membley member and left church blank, fill Membley SDA
        if ($is_membley_member && empty($church_from)) {
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
                ':church'       => !empty($church_from) ? $church_from : 'Not Specified',
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
<section style="background-color: var(--primary-dark); color: white; padding: 3.5rem 0; text-align: center; background-image: linear-gradient(rgba(4,25,40,0.85), rgba(4,25,40,0.85)), url('assets/images/church_banner.png'); background-size: cover; background-position: center;">
    <div class="container">
        <span style="background-color: #84cc16; color: #041928; font-weight: 800; text-transform: uppercase; font-size: 0.8rem; padding: 0.35rem 0.85rem; border-radius: 50px; display: inline-block; margin-bottom: 0.75rem; letter-spacing: 0.5px;">
            <i class="fa-solid fa-calendar-check"></i> Attendance Confirmation
        </span>
        <h1 style="color: white; font-size: 2.4rem; margin-bottom: 0.5rem;">Will You Be Attending?</h1>
        <p style="color: rgba(255,255,255,0.85); font-size: 1.05rem; max-width: 650px; margin: 0 auto;">
            <strong>Homecoming Sabbath</strong> — Celebrating 10 Yrs of Fellowship and Family<br>
            <span style="color: #a3e635;"><i class="fa-solid fa-calendar-day"></i> Sabbath, 31 OCT 2026 | 8:00 AM</span>
        </p>
    </div>
</section>

<!-- Content Section -->
<section class="section-padding container">
    <?php if ($submitted): ?>
        <!-- Thank You Confirmation Card -->
        <div class="rsvp-thankyou-card">
            <div style="width: 70px; height: 70px; background: #84cc16; color: #041928; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.5rem auto;">
                <i class="fa-solid fa-check"></i>
            </div>
            <h2 style="color: var(--primary); font-size: 2rem; margin-bottom: 0.5rem;">Thank You, <?php echo htmlspecialchars($registered_name); ?>!</h2>
            <p style="font-size: 1.1rem; color: var(--text-dark); margin-bottom: 1.5rem;">
                Your attendance for the <strong>Homecoming Sabbath (10 Yrs Celebration)</strong> has been successfully registered. We look forward to welcoming and fellowshipping with you!
            </p>

            <div style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: 10px; padding: 1.25rem; margin-bottom: 2rem; text-align: left;">
                <h4 style="color: var(--primary); margin-bottom: 0.75rem; font-size: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.4rem;">
                    <i class="fa-solid fa-circle-info" style="color: #84cc16;"></i> Event Details
                </h4>
                <p style="font-size: 0.95rem; margin-bottom: 0.3rem;"><strong>Date:</strong> Sabbath, 31 October 2026</p>
                <p style="font-size: 0.95rem; margin-bottom: 0.3rem;"><strong>Time:</strong> Starts at 8:00 AM</p>
                <p style="font-size: 0.95rem; margin-bottom: 0.3rem;"><strong>Venue:</strong> Membley Park Estate, Ruiru, Kenya</p>
            </div>

            <!-- Share Buttons -->
            <div style="display: flex; flex-direction: column; gap: 1rem; align-items: center;">
                <a href="https://api.whatsapp.com/send?text=<?php echo urlencode("Hello! I just confirmed my attendance for the Membley SDA Homecoming Sabbath (Celebrating 10 Yrs of Fellowship & Family) on Oct 31, 2026. Will you be attending too? Register here: https://" . $_SERVER['HTTP_HOST'] . "/rsvp.php"); ?>" target="_blank" class="btn btn-lime" style="width: 100%; max-width: 380px;">
                    <i class="fa-brands fa-whatsapp" style="font-size: 1.2rem;"></i> Share & Invite a Friend on WhatsApp
                </a>
                
                <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; justify-content: center; margin-top: 0.5rem;">
                    <a href="index.php" class="btn btn-primary btn-sm"><i class="fa-solid fa-house"></i> Return Home</a>
                    <a href="events.php" class="btn btn-outline btn-sm"><i class="fa-solid fa-calendar-days"></i> View Events</a>
                </div>
            </div>
        </div>

    <?php else: ?>

        <!-- RSVP Form Box -->
        <div class="rsvp-box-card">
            <?php if (!empty($error_msg)): ?>
                <div style="background-color: #fee2e2; border: 1px solid #ef4444; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlspecialchars($error_msg); ?>
                </div>
            <?php endif; ?>

            <div style="text-align: center; margin-bottom: 2rem;">
                <h2 style="color: var(--primary); font-size: 1.8rem; margin-bottom: 0.5rem;">Confirm Your Attendance</h2>
                <p style="color: var(--text-muted); font-size: 0.95rem;">
                    Please enter your <strong>Full Name</strong> and <strong>Phone Number</strong> to confirm your registration for Homecoming Sabbath.
                </p>
            </div>

            <form action="rsvp.php" method="POST">
                
                <!-- 1. Full Name (Required) -->
                <div class="form-group">
                    <label class="form-label" for="full_name">Your Full Name <span style="color: #e11d48;">*</span></label>
                    <input type="text" id="full_name" name="full_name" class="form-control" placeholder="e.g. John Doe / Sarah Mwangi" required value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>">
                </div>

                <!-- 2. Phone Number (Required) -->
                <div class="form-group">
                    <label class="form-label" for="phone">Phone Number <span style="color: #e11d48;">*</span></label>
                    <input type="tel" id="phone" name="phone" class="form-control" placeholder="e.g. 0712 345 678" required value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                </div>

                <!-- 3. Membley Member Checkbox -->
                <div class="form-group" style="background: #f8fafc; border: 1px solid var(--border-color); padding: 1rem; border-radius: 8px;">
                    <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; font-weight: 600; color: var(--primary); margin: 0;">
                        <input type="checkbox" name="is_membley_member" id="is_membley_member" value="1" <?php echo (isset($_POST['is_membley_member'])) ? 'checked' : ''; ?> style="width: 20px; height: 20px; accent-color: #84cc16; cursor: pointer;">
                        <span>I am a regular Membley SDA Church Member</span>
                    </label>
                </div>

                <!-- 4. Church / Home Congregation From (Optional) -->
                <div class="form-group" id="church_group">
                    <label class="form-label" for="church_from">Church / Congregation You Are From <small style="color: var(--text-muted); font-weight: normal;">(Optional if Membley member)</small></label>
                    <input type="text" id="church_from" name="church_from" class="form-control" placeholder="e.g. Ruiru SDA, Nairobi Central, Kahawa West, Visitor, etc." value="<?php echo htmlspecialchars($_POST['church_from'] ?? ''); ?>">
                </div>

                <!-- 5. Number of Attendees -->
                <div class="form-group">
                    <label class="form-label" for="attendees_count">Number of Attendees <small style="color: var(--text-muted); font-weight: normal;">(You + family/friends joining)</small></label>
                    <select id="attendees_count" name="attendees_count" class="form-control">
                        <option value="1" selected>1 Person (Just Me)</option>
                        <option value="2">2 Persons</option>
                        <option value="3">3 Persons</option>
                        <option value="4">4 Persons</option>
                        <option value="5">5+ Persons (Family / Group)</option>
                    </select>
                </div>

                <!-- 6. Inquiry / Questions (Optional) -->
                <div class="form-group">
                    <label class="form-label" for="inquiry">Any Inquiries / Special Prayer / Notes? <small style="color: var(--text-muted); font-weight: normal;">(Optional)</small></label>
                    <textarea id="inquiry" name="inquiry" class="form-control" rows="3" placeholder="Feel free to ask any questions about parking, program schedule, choir items, etc."><?php echo htmlspecialchars($_POST['inquiry'] ?? ''); ?></textarea>
                </div>

                <div style="margin-top: 2rem;">
                    <button type="submit" name="submit_rsvp" class="btn btn-lime" style="width: 100%; font-size: 1.05rem; padding: 0.9rem; justify-content: center;">
                        <i class="fa-solid fa-check-circle"></i> Yes, I Will Be Attending — Confirm RSVP
                    </button>
                </div>

            </form>
        </div>

    <?php endif; ?>
</section>

<script>
// Auto-fill or adjust church input when Membley member is toggled
document.addEventListener('DOMContentLoaded', function() {
    const memberCheckbox = document.getElementById('is_membley_member');
    const churchInput = document.getElementById('church_from');
    
    if (memberCheckbox && churchInput) {
        memberCheckbox.addEventListener('change', function() {
            if (this.checked && !churchInput.value) {
                churchInput.placeholder = "Membley SDA Church";
            } else {
                churchInput.placeholder = "e.g. Ruiru SDA, Nairobi Central, Kahawa West, Visitor, etc.";
            }
        });
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>
