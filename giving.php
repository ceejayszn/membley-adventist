<?php
// giving.php
require_once 'includes/db.php';

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $amount = floatval($_POST['amount'] ?? 0);
    $pledge_type = trim($_POST['pledge_type'] ?? 'Development (DV)');
    $notes = trim($_POST['notes'] ?? '');

    if (empty($name) || empty($email) || $amount <= 0) {
        $error_msg = 'Please fill in all required fields and enter a valid amount.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO submissions (type, name, email, phone, subject_message, amount) VALUES ('pledge', :name, :email, :phone, :message, :amount)");
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':phone' => $phone,
                ':message' => "Pledge Allocation: $pledge_type. Notes: $notes",
                ':amount' => $amount
            ]);
            $success_msg = 'Thank you! Your pledge has been registered successfully. May God bless you abundantly.';
        } catch (PDOException $e) {
            $error_msg = 'Failed to submit pledge. Please try again later.';
        }
    }
}

require_once 'includes/header.php';
?>

<!-- Banner Section -->
<section style="background-color: var(--primary-dark); color: white; padding: 4rem 0; text-align: center; background-image: linear-gradient(rgba(4,25,40,0.85), rgba(4,25,40,0.85)), url('https://images.unsplash.com/photo-1438232992991-995b7058bbb3?auto=format&fit=crop&q=80&w=1200'); background-size: cover; background-position: center;">
    <div class="container">
        <h1 style="color: white; font-size: 2.75rem; margin-bottom: 0.5rem;">Worship Through Giving</h1>
        <p style="color: var(--accent); font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">"Bring the whole tithe into the storehouse..." — Malachi 3:10</p>
    </div>
</section>

<!-- Giving Instructions & Pledge Form -->
<section class="section-padding container">
    
    <?php if (!empty($success_msg)): ?>
        <div style="background-color: rgba(46,133,64,0.1); color: #2e8540; border: 1px solid rgba(46,133,64,0.2); padding: 1rem; border-radius: 8px; margin-bottom: 2rem; font-weight: 600;">
            <i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($success_msg); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_msg)): ?>
        <div style="background-color: rgba(217,83,79,0.1); color: #d9534f; border: 1px solid rgba(217,83,79,0.2); padding: 1rem; border-radius: 8px; margin-bottom: 2rem; font-weight: 600;">
            <i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($error_msg); ?>
        </div>
    <?php endif; ?>

    <div class="giving-grid">
        <!-- Left: Payment Methods & Instructions -->
        <div>
            <h2 style="color: var(--primary); margin-bottom: 1rem; font-size: 1.8rem;">How to Give</h2>
            <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
                Giving tithes and offerings is a response of gratitude to God’s blessings. You can safely channel your contributions through our official M-PESA Paybill or Direct Bank Deposit.
            </p>

            <!-- M-PESA Main Highlight Box -->
            <div class="paybill-box">
                <div class="paybill-header">
                    <span class="paybill-badge"><i class="fa-solid fa-mobile-screen-button"></i> M-PESA PAYBILL</span>
                    <span style="font-size: 0.85rem; opacity: 0.9;"><i class="fa-solid fa-shield-halved"></i> Official Church Account</span>
                </div>
                
                <div class="paybill-details-grid">
                    <div>
                        <div style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; opacity: 0.85; font-weight: 600;">Pay Bill No:</div>
                        <div class="paybill-number-wrap">
                            <span class="paybill-number">4141491</span>
                            <button type="button" class="copy-btn" data-copy="4141491" title="Copy Paybill Number">
                                <i class="fa-regular fa-copy"></i> Copy
                            </button>
                        </div>
                    </div>
                    <div>
                        <div style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; opacity: 0.85; font-weight: 600;">Account Field:</div>
                        <div style="font-size: 1.15rem; font-weight: 700; color: #ffdd67; margin-top: 0.4rem;">
                            Purpose of payment
                        </div>
                        <div style="font-size: 0.8rem; opacity: 0.85; margin-top: 0.2rem;">
                            Specify allocation codes (e.g. 5000T 2000WB)
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step-by-Step Payment Instructions -->
            <div style="background: var(--bg-white); border-radius: 12px; padding: 1.75rem; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm); margin-bottom: 2rem;">
                <h3 style="color: var(--primary); font-size: 1.25rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-list-check" style="color: var(--accent);"></i> Step-by-Step Payment Instructions
                </h3>

                <div class="instruction-step">
                    <div class="step-num">1</div>
                    <div>Open <strong>M-PESA Menu</strong> or the <strong>M-PESA App</strong> on your phone.</div>
                </div>
                <div class="instruction-step">
                    <div class="step-num">2</div>
                    <div>Select <strong>Lipa na M-PESA</strong> and choose <strong>Pay Bill</strong>.</div>
                </div>
                <div class="instruction-step">
                    <div class="step-num">3</div>
                    <div>Enter Business No: <strong>4141491</strong></div>
                </div>
                <div class="instruction-step">
                    <div class="step-num">4</div>
                    <div>Enter Account No: <strong>Purpose of payment</strong> (Combine your amount with allocation codes, e.g. <code>5000T 2000WB</code>).</div>
                </div>
                <div class="instruction-step" style="margin-bottom: 0;">
                    <div class="step-num">5</div>
                    <div>Enter total sum amount, input your <strong>M-PESA PIN</strong>, and press <strong>Send</strong>.</div>
                </div>
            </div>

            <!-- Payment Purpose Codes / Acronyms -->
            <div style="background: var(--bg-white); border-radius: 12px; padding: 1.75rem; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm); margin-bottom: 2rem;">
                <h3 style="color: var(--primary); font-size: 1.25rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-tags" style="color: var(--accent);"></i> Account Allocation Codes & Acronyms
                </h3>
                <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1rem;">
                    Use these official code letters in your M-PESA Account field so the treasury team correctly credits your contribution:
                </p>

                <div class="codes-grid">
                    <div class="code-card">
                        <span class="code-badge">T</span>
                        <span class="code-desc">Tithe</span>
                    </div>
                    <div class="code-card">
                        <span class="code-badge">WB</span>
                        <span class="code-desc">Wages & Bills</span>
                    </div>
                    <div class="code-card">
                        <span class="code-badge">LCB</span>
                        <span class="code-desc">Local church budget</span>
                    </div>
                    <div class="code-card">
                        <span class="code-badge">DV</span>
                        <span class="code-desc">Development</span>
                    </div>
                    <div class="code-card">
                        <span class="code-badge">CO</span>
                        <span class="code-desc">Combined Offering</span>
                    </div>
                    <div class="code-card">
                        <span class="code-badge">CME</span>
                        <span class="code-desc">Camp Meeting Expenses</span>
                    </div>
                    <div class="code-card">
                        <span class="code-badge">CMO</span>
                        <span class="code-desc">Camp Meeting Offering</span>
                    </div>
                    <div class="code-card">
                        <span class="code-badge">TS</span>
                        <span class="code-desc">Thirteenth Sabbath</span>
                    </div>
                    <div class="code-card">
                        <span class="code-badge">AWR</span>
                        <span class="code-desc">Evangelism</span>
                    </div>
                    <div class="code-card">
                        <span class="code-badge">TG</span>
                        <span class="code-desc">Thanksgiving or Special offering</span>
                    </div>
                </div>

                <!-- Formatting Examples -->
                <div class="example-box">
                    <h4 style="color: var(--primary); font-size: 1.05rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
                        <i class="fa-solid fa-lightbulb" style="color: var(--accent);"></i> Examples for Account Field:
                    </h4>
                    <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 0.75rem;">
                        You can specify a single offering or combine multiple allocations in one transaction:
                    </p>

                    <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                        <div>
                            <span class="example-chip copy-chip" data-copy="5000T 2000WB 1000DV" style="cursor: pointer;" title="Click to copy example">
                                5000T 2000WB 1000DV
                            </span>
                            <span style="font-size: 0.85rem; color: var(--text-muted);">(KES 5,000 Tithe + 2,000 Wages & Bills + 1,000 Development)</span>
                        </div>
                        <div>
                            <span class="example-chip copy-chip" data-copy="2000LCB" style="cursor: pointer;" title="Click to copy example">
                                2000LCB
                            </span>
                            <span style="font-size: 0.85rem; color: var(--text-muted);">(KES 2,000 Local Church Budget)</span>
                        </div>
                        <div>
                            <span class="example-chip copy-chip" data-copy="200T 300AWR 300CME" style="cursor: pointer;" title="Click to copy example">
                                200T 300AWR 300CME
                            </span>
                            <span style="font-size: 0.85rem; color: var(--text-muted);">(KES 200 Tithe + 300 Evangelism + 300 Camp Meeting Expenses)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bank Transfer Method -->
            <div style="background: var(--bg-white); border-radius: 12px; padding: 1.75rem; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
                <h3 style="color: var(--primary); font-size: 1.25rem; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-building-columns" style="color: var(--primary-light);"></i> Direct Bank Deposit / Transfer
                </h3>
                <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1rem;">
                    For large contributions or electronic fund transfers (EFT/RTGS):
                </p>
                <div style="background: var(--bg-light); border-radius: 8px; padding: 1rem; border-left: 4px solid var(--primary);">
                    <p style="font-size: 0.95rem; line-height: 1.7; color: var(--text-dark);">
                        <strong>Bank Name:</strong> Cooperative Bank of Kenya <br>
                        <strong>Account Name:</strong> Membley Seventh-day Adventist Church <br>
                        <strong>Branch:</strong> Ruiru Branch <br>
                        <strong>Account Number:</strong> <em>Contact church treasurer for account number details</em>
                    </p>
                </div>
            </div>
        </div>

        <!-- Right: Pledge Form -->
        <div style="background-color: var(--bg-white); padding: 2.25rem; border-radius: 12px; box-shadow: var(--shadow-md); border: 1px solid var(--border-color); position: sticky; top: 100px;">
            <h2 style="color: var(--primary); margin-bottom: 0.5rem; font-size: 1.75rem; text-align: center;">Submit a Pledge</h2>
            <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 1.75rem; text-align: center;">Commit to supporting church projects, tithes, or special departments.</p>

            <form action="giving.php" method="POST">
                <div class="form-group">
                    <label class="form-label" for="name">Full Name *</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="e.g. John Doe" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">Email Address *</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="e.g. john@example.com" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="form-control" placeholder="e.g. +254 700 123456">
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label" for="amount">Pledge Amount (KES) *</label>
                        <input type="number" id="amount" name="amount" class="form-control" min="1" step="any" placeholder="5000" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="pledge_type">Allocation Purpose</label>
                        <select id="pledge_type" name="pledge_type" class="form-control" style="background-color: white;">
                            <option value="Tithe (T)">Tithe (T)</option>
                            <option value="Wages & Bills (WB)">Wages & Bills (WB)</option>
                            <option value="Local Church Budget (LCB)">Local Church Budget (LCB)</option>
                            <option value="Development (DV)" selected>Development (DV)</option>
                            <option value="Combined Offering (CO)">Combined Offering (CO)</option>
                            <option value="Camp Meeting Expenses (CME)">Camp Meeting Expenses (CME)</option>
                            <option value="Camp Meeting Offering (CMO)">Camp Meeting Offering (CMO)</option>
                            <option value="Thirteenth Sabbath (TS)">Thirteenth Sabbath (TS)</option>
                            <option value="Evangelism (AWR)">Evangelism (AWR)</option>
                            <option value="Thanksgiving / Special (TG)">Thanksgiving / Special (TG)</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="notes">Notes / Redemption Plan</label>
                    <textarea id="notes" name="notes" class="form-control" placeholder="Optional notes, fulfillment date, or prayer requests..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1.05rem; padding: 0.85rem;"><i class="fa-solid fa-paper-plane"></i> Submit Pledge Commitment</button>
            </form>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
