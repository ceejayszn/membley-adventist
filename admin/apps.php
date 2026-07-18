<?php
// admin/apps.php
require_once 'auth.php';
check_auth();

require_once '../includes/db.php';

// Handle Add App
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $file_url = $_POST['file_url'] ?? '';
    $platform = $_POST['platform'] ?? '';
    $version = $_POST['version'] ?? '';

    if (!empty($name) && !empty($file_url)) {
        $stmt = $pdo->prepare("INSERT INTO applications (name, description, file_url, platform, version) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $file_url, $platform, $version]);
        header("Location: apps.php?success=1");
        exit;
    }
}

// Fetch Apps
try {
    $stmt = $pdo->query("SELECT a.*, (SELECT COUNT(*) FROM app_downloads d WHERE d.app_id = a.id) as download_count FROM applications a ORDER BY a.created_at DESC");
    $apps = $stmt->fetchAll();
} catch (PDOException $e) {
    $apps = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications - Membley SDA Admin</title>
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
                <li><a href="apps.php" class="sidebar-link active"><i class="fa-solid fa-mobile-screen" style="margin-right: 0.5rem;"></i> Applications</a></li>
                <li><a href="app_downloads.php" class="sidebar-link"><i class="fa-solid fa-download" style="margin-right: 0.5rem;"></i> App Downloads</a></li>
                <li><a href="submissions.php" class="sidebar-link"><i class="fa-solid fa-envelope-open-text" style="margin-right: 0.5rem;"></i> Submissions</a></li>
            </ul>
            <div class="sidebar-footer">
                <a href="logout.php" class="sidebar-link" style="color: #ff8b8b;"><i class="fa-solid fa-right-from-bracket" style="margin-right: 0.5rem;"></i> Sign Out</a>
            </div>
        </aside>

        <!-- Main Workspace -->
        <main class="admin-main">
            <header class="admin-header">
                <div class="admin-title">Manage Applications</div>
                <div class="admin-user">
                    Welcome, <span style="color: var(--primary-light);"><?php echo htmlspecialchars(get_logged_in_user()); ?></span>
                </div>
            </header>

            <div class="admin-content">
                <?php if (isset($_GET['success'])): ?>
                    <div style="background-color: #d1fae5; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-weight: 600;">
                        Application added successfully.
                    </div>
                <?php endif; ?>

                <div class="card-table-wrap" style="margin-bottom: 2rem;">
                    <div class="card-table-header">
                        <span class="table-title">Add New Application</span>
                    </div>
                    <div style="padding: 1.5rem;">
                        <form action="apps.php" method="POST">
                            <input type="hidden" name="action" value="add">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                                <div>
                                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">App Name *</label>
                                    <input type="text" name="name" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px;">
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">File URL / Path *</label>
                                    <input type="text" name="file_url" required placeholder="e.g. assets/apps/app.apk" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px;">
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Platform</label>
                                    <input type="text" name="platform" placeholder="Android, iOS, Windows, etc." style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px;">
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Version</label>
                                    <input type="text" name="version" placeholder="e.g. 1.0.0" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px;">
                                </div>
                            </div>
                            <div style="margin-bottom: 1.5rem;">
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Description</label>
                                <textarea name="description" rows="3" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px;"></textarea>
                            </div>
                            <button type="submit" class="admin-btn-outline" style="background-color: var(--primary); color: white; border: none; padding: 0.75rem 1.5rem; cursor: pointer; border-radius: 6px; font-weight: 600;">Add Application</button>
                        </form>
                    </div>
                </div>

                <div class="card-table-wrap">
                    <div class="card-table-header">
                        <span class="table-title">Deployed Applications</span>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>App Name</th>
                                    <th>Platform / Version</th>
                                    <th>File URL</th>
                                    <th>Download Link</th>
                                    <th>Total Downloads</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($apps)): ?>
                                    <?php foreach ($apps as $app): ?>
                                        <tr>
                                            <td>#<?php echo htmlspecialchars($app['id']); ?></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($app['name']); ?></strong>
                                                <div style="font-size: 0.85rem; color: #64748b; margin-top: 0.25rem;"><?php echo htmlspecialchars($app['description']); ?></div>
                                            </td>
                                            <td>
                                                <span class="badge badge-read"><?php echo htmlspecialchars($app['platform']); ?></span>
                                                <span style="font-size: 0.85rem; color: #64748b; margin-left: 0.5rem;">v<?php echo htmlspecialchars($app['version']); ?></span>
                                            </td>
                                            <td style="font-family: monospace; color: #3b82f6;"><?php echo htmlspecialchars($app['file_url']); ?></td>
                                            <td>
                                                <?php 
                                                // Create full URL for tracking
                                                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
                                                $host = $_SERVER['HTTP_HOST'];
                                                $base_url = $protocol . "://" . $host . dirname(dirname($_SERVER['PHP_SELF']));
                                                $download_link = $base_url . "/download.php?app_id=" . $app['id'];
                                                ?>
                                                <input type="text" value="<?php echo htmlspecialchars($download_link); ?>" readonly style="padding: 0.25rem; font-size: 0.8rem; width: 200px; border: 1px solid #cbd5e1; border-radius: 4px; background: #f8fafc;">
                                            </td>
                                            <td><strong style="color: var(--primary); font-size: 1.1rem;"><?php echo number_format($app['download_count']); ?></strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; color: #637381; padding: 2rem;">No applications deployed yet.</td>
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
