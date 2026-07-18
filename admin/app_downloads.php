<?php
// admin/app_downloads.php
require_once 'auth.php';
check_auth();

require_once '../includes/db.php';

try {
    $stmt = $pdo->query("
        SELECT d.*, a.name as app_name, a.platform 
        FROM app_downloads d 
        JOIN applications a ON d.app_id = a.id 
        ORDER BY d.downloaded_at DESC 
        LIMIT 500
    ");
    $downloads = $stmt->fetchAll();
} catch (PDOException $e) {
    $downloads = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Downloads - Membley SDA Admin</title>
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
                <li><a href="dashboard.php" class="sidebar-link"><i class="fa-solid fa-gauge" style="margin-right: 0.5rem;"></i> Dashboard</a></li>
                <li><a href="forms.php" class="sidebar-link"><i class="fa-solid fa-wpforms" style="margin-right: 0.5rem;"></i> Manage Forms</a></li>
                <li><a href="analytics.php" class="sidebar-link"><i class="fa-solid fa-chart-line" style="margin-right: 0.5rem;"></i> Visitor Analytics</a></li>
                <li><a href="blogs.php" class="sidebar-link"><i class="fa-solid fa-newspaper" style="margin-right: 0.5rem;"></i> Manage Blogs</a></li>
                <li><a href="apps.php" class="sidebar-link"><i class="fa-solid fa-mobile-screen" style="margin-right: 0.5rem;"></i> Applications</a></li>
                <li><a href="app_downloads.php" class="sidebar-link active"><i class="fa-solid fa-download" style="margin-right: 0.5rem;"></i> App Downloads</a></li>
                <li><a href="submissions.php" class="sidebar-link"><i class="fa-solid fa-envelope-open-text" style="margin-right: 0.5rem;"></i> Submissions</a></li>
            </ul>
            <div class="sidebar-footer">
                <a href="logout.php" class="sidebar-link" style="color: #ff8b8b;"><i class="fa-solid fa-right-from-bracket" style="margin-right: 0.5rem;"></i> Sign Out</a>
            </div>
        </aside>

        <!-- Main Workspace -->
        <main class="admin-main">
            <header class="admin-header">
                <div class="admin-title">App Downloads Log</div>
                <div class="admin-user">
                    Welcome, <span style="color: var(--primary-light);"><?php echo htmlspecialchars(get_logged_in_user()); ?></span>
                </div>
            </header>

            <div class="admin-content">
                <div class="card-table-wrap">
                    <div class="card-table-header">
                        <span class="table-title">Recent App Downloads</span>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Date/Time</th>
                                    <th>Application</th>
                                    <th>Platform</th>
                                    <th>IP Address</th>
                                    <th>MAC Address</th>
                                    <th>Device Info / User Agent</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($downloads)): ?>
                                    <?php foreach ($downloads as $d): ?>
                                        <tr>
                                            <td style="white-space: nowrap;"><?php echo date('M d, Y H:i:s', strtotime($d['downloaded_at'])); ?></td>
                                            <td><strong><?php echo htmlspecialchars($d['app_name']); ?></strong></td>
                                            <td><span class="badge badge-read"><?php echo htmlspecialchars($d['platform']); ?></span></td>
                                            <td style="font-family: monospace; font-weight: 600; color: #b91c1c;"><?php echo htmlspecialchars($d['ip_address']); ?></td>
                                            <td style="font-family: monospace; color: #64748b;"><?php echo htmlspecialchars($d['mac_address']); ?></td>
                                            <td>
                                                <div style="font-size: 0.85rem; color: #475569; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?php echo htmlspecialchars($d['device_info']); ?>">
                                                    <?php echo htmlspecialchars($d['device_info']); ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; color: #637381; padding: 2rem;">No apps have been downloaded yet.</td>
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
