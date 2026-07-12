<?php
// admin/dashboard.php
require_once 'auth.php';
check_auth();

require_once '../includes/db.php';

// Fetch stats counts
try {
    $blog_count = $pdo->query("SELECT COUNT(*) FROM blogs")->fetchColumn();
    $contact_count = $pdo->query("SELECT COUNT(*) FROM submissions WHERE type = 'contact' AND status = 'unread'")->fetchColumn();
    $prayer_count = $pdo->query("SELECT COUNT(*) FROM submissions WHERE type = 'prayer' AND status = 'unread'")->fetchColumn();
    $pledge_count = $pdo->query("SELECT COUNT(*) FROM submissions WHERE type = 'pledge' AND status = 'unread'")->fetchColumn();
    
    // Fetch 5 latest submissions
    $stmt = $pdo->query("SELECT * FROM submissions ORDER BY created_at DESC LIMIT 5");
    $recent_submissions = $stmt->fetchAll();
} catch (PDOException $e) {
    $blog_count = 0;
    $contact_count = 0;
    $prayer_count = 0;
    $pledge_count = 0;
    $recent_submissions = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Membley SDA Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-brand">
                <i class="fa-solid fa-church"></i> Membley SDA Admin
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php" class="sidebar-link active"><i class="fa-solid fa-gauge" style="margin-right: 0.5rem;"></i> Dashboard</a></li>
                <li><a href="blogs.php" class="sidebar-link"><i class="fa-solid fa-newspaper" style="margin-right: 0.5rem;"></i> Manage Blogs</a></li>
                <li><a href="submissions.php" class="sidebar-link"><i class="fa-solid fa-envelope-open-text" style="margin-right: 0.5rem;"></i> Submissions</a></li>
            </ul>
            <div class="sidebar-footer">
                <a href="logout.php" class="sidebar-link" style="color: #ff8b8b;"><i class="fa-solid fa-right-from-bracket" style="margin-right: 0.5rem;"></i> Sign Out</a>
            </div>
        </aside>

        <!-- Main Workspace -->
        <main class="admin-main">
            <header class="admin-header">
                <div class="admin-title">Dashboard Overview</div>
                <div class="admin-user">
                    Welcome, <span style="color: var(--primary-light);"><?php echo htmlspecialchars(get_logged_in_user()); ?></span>
                </div>
            </header>

            <div class="admin-content">
                <!-- Statistics Row -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-label">Total Blog Posts</div>
                        <div class="stat-val"><?php echo $blog_count; ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">New Messages</div>
                        <div class="stat-val" style="color: #0369a1;"><?php echo $contact_count; ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Prayer Requests</div>
                        <div class="stat-val" style="color: #9d174d;"><?php echo $prayer_count; ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Pledges Received</div>
                        <div class="stat-val" style="color: #1e40af;"><?php echo $pledge_count; ?></div>
                    </div>
                </div>

                <!-- Recent Submissions Section -->
                <div class="card-table-wrap">
                    <div class="card-table-header">
                        <span class="table-title">Recent Submissions</span>
                        <a href="submissions.php" class="admin-btn-outline" style="padding: 0.35rem 0.75rem; font-size: 0.8rem; margin: 0;">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th>Contact Details</th>
                                    <th>Subject / Pledge Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recent_submissions)): ?>
                                    <?php foreach ($recent_submissions as $sub): ?>
                                        <tr>
                                            <td><?php echo date('M d, Y H:i', strtotime($sub['created_at'])); ?></td>
                                            <td>
                                                <span class="badge badge-<?php echo htmlspecialchars($sub['type']); ?>">
                                                    <?php echo ucfirst(htmlspecialchars($sub['type'])); ?>
                                                </span>
                                            </td>
                                            <td style="font-weight: 600;"><?php echo htmlspecialchars($sub['name']); ?></td>
                                            <td>
                                                <div style="font-size: 0.85rem; color: #555;"><?php echo htmlspecialchars($sub['email']); ?></div>
                                                <div style="font-size: 0.8rem; color: #888;"><?php echo htmlspecialchars($sub['phone']); ?></div>
                                            </td>
                                            <td>
                                                <?php if ($sub['type'] == 'pledge'): ?>
                                                    <strong>KES <?php echo number_format($sub['amount'], 2); ?></strong>
                                                <?php else: ?>
                                                    <span style="font-size: 0.9rem; color: #475569; display: inline-block; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                        <?php echo htmlspecialchars($sub['subject_message']); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?php echo htmlspecialchars($sub['status']); ?>">
                                                    <?php echo ucfirst(htmlspecialchars($sub['status'])); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; color: #637381; padding: 2rem;">No submissions yet.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

</body>
</html>
