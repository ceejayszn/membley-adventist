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
    $pledge_type = trim($_POST['pledge_type'] ?? 'Building Fund');
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
                ':message' => "Pledge Type: $pledge_type. Notes: $notes",
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
<section style="background-color: var(--primary-dark); color: white; padding: 4rem 0; text-align: center; background-image: linear-gradient(rgba(4,25,40,0.8), rgba(4,25,40,0.8)), url('https://images.unsplash.com/photo-1438232992991-995b7058bbb3?auto=format&fit=crop&q=80&w=1200'); background-size: cover; background-position: center;">
    <div class="container">
        <h1 style="color: white; font-size: 2.75rem; margin-bottom: 0.5rem;">Worship Through Giving</h1>
        <p style="color: var(--accent); font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">"Bring the whole tithe into the storehouse..." — Malachi 3:10</p>
    </div>
</section>

<!-- Giving Instructions & Pledge Form -->
<section class="section-padding container">
    
    <?php if (!empty($success_msg)): ?>
        <div style="background-color: rgba(46,133,64,0.1); color: #2e8540; border: 1px solid rgba(46,133,64,0.2); padding: 1rem; border-radius: 8px; margin-bottom: 2rem; font-weight: 600;">
            <i class="fa-solid fa-circle-check"></i> <?php echo $success_msg; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_msg)): ?>
        <div style="background-color: rgba(217,83,79,0.1); color: #d9534f; border: 1px solid rgba(217,83,79,0.2); padding: 1rem; border-radius: 8px; margin-bottom: 2rem; font-weight: 600;">
            <i class="fa-solid fa-triangle-exclamation"></i> <?php echo $error_msg; ?>
        </div>
    <?php endif; ?>

    <div class="giving-grid">
        <!-- Left: Payment Methods Details -->
        <div>
            <h2 style="color: var(--primary); margin-bottom: 1.5rem; font-size: 1.8rem;">How to Give</h2>
            <p style="color: var(--text-muted); margin-bottom: 2rem;">
                Giving tithes and offerings is a response of gratitude to God’s blessings. At Membley SDA, you can channel your funds safely through the following official channels:
            </p>

            <div class="giving-card">
                <!-- Mpesa -->
                <div class="payment-method">
                    <span class="badge-paybill"><i class="fa-solid fa-mobile-screen-button"></i> M-PESA PAYBILL</span>
                    <p style="font-size: 0.95rem; color: var(--text-dark); margin-top: 1rem; margin-bottom: 0.5rem;">For M-PESA Paybill details, please contact the church treasurer or check the bulletin.</p>
                </div>

                <!-- Bank -->
                <div class="payment-method">
                    <h3 style="color: var(--primary);"><i class="fa-solid fa-building-columns"></i> Cooperative Bank</h3>
                    <p style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 0.5rem;">Direct deposit / Bank transfers:</p>
                    <p style="font-size: 1rem; font-weight: 700; color: var(--text-dark);">
                        Account Name: Membley Seventh-day Adventist Church <br>
                        Account Number: [To be provided] <br>
                        Branch: Ruiru Branch
                    </p>
                </div>
            </div>
        </div>

        <!-- Right: Pledge Form -->
        <div style="background-color: var(--bg-white); padding: 2.5rem; border-radius: 12px; box-shadow: var(--shadow-md); border: 1px solid var(--border-color);">
            <h2 style="color: var(--primary); margin-bottom: 1rem; font-size: 1.8rem; text-align: center;">Submit a Pledge</h2>
            <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 2rem; text-align: center;">Make a commitment to support church development or special project funds.</p>

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
                        <input type="number" id="amount" name="amount" class="form-control" min="1" step="any" placeholder="1000" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="pledge_type">Pledge Allocation</label>
                        <select id="pledge_type" name="pledge_type" class="form-control" style="background-color: white;">
                            <option value="Building Fund">Building Fund</option>
                            <option value="Tithe Pledge">Tithe Pledge</option>
                            <option value="Camp Meeting">Camp Meeting</option>
                            <option value="Youth Department">Youth Department</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="notes">Notes / Prayer Requests with Pledge</label>
                    <textarea id="notes" name="notes" class="form-control" placeholder="Any special note or redemption plan details..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1.1rem; padding: 0.85rem;"><i class="fa-solid fa-paper-plane"></i> Submit Pledge</button>
            </form>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
