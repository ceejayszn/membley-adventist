<?php
// admin/submissions.php
require_once 'auth.php';
check_auth();

require_once '../includes/db.php';

$filter_type = isset($_GET['type']) ? $_GET['type'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 1. PROCESS ACTION (Mark read/resolved)
if (!empty($action) && $id > 0) {
    $new_status = 'unread';
    if ($action == 'read') $new_status = 'read';
    if ($action == 'resolve') $new_status = 'resolved';

    try {
        $stmt = $pdo->prepare("UPDATE submissions SET status = :status WHERE id = :id");
        $stmt->execute([':status' => $new_status, ':id' => $id]);
        header('Location: submissions.php' . (!empty($filter_type) ? '?type=' . urlencode($filter_type) : ''));
        exit;
    } catch (PDOException $e) {
        // Fail silently or handle error
    }
}

// 2. CSV EXPORT FUNCTIONALITY
if ($action == 'export') {
    // Determine export SQL
    $export_sql = "SELECT created_at, type, name, email, phone, subject_message, amount, status FROM submissions";
    $params = [];
    if (!empty($filter_type)) {
        $export_sql .= " WHERE type = :type";
        $params[':type'] = $filter_type;
    }
    $export_sql .= " ORDER BY created_at DESC";

    try {
        $stmt = $pdo->prepare($export_sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Set download headers
        $filename = "submissions_" . (!empty($filter_type) ? $filter_type . "_" : "") . date('Ymd_His') . ".csv";
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        
        $output = fopen('php://output', 'w');
        
        // Write column headers
        fputcsv($output, ['Created At', 'Type', 'Name', 'Email', 'Phone', 'Subject/Message/Notes', 'Amount (KES)', 'Status']);
        
        // Write data rows
        foreach ($rows as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit;
    } catch (PDOException $e) {
        // Graceful error if export fails
        die("Export failed: " . $e->getMessage());
    }
}

// 3. FETCH SUBMISSIONS FOR VIEWING
$sql = "SELECT * FROM submissions";
$params = [];
if (!empty($filter_type)) {
    $sql .= " WHERE type = :type";
    $params[':type'] = $filter_type;
}
$sql .= " ORDER BY created_at DESC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $submissions = $stmt->fetchAll();
} catch (PDOException $e) {
    $submissions = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submissions - Membley SDA Admin</title>
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
                <li><a href="app_downloads.php" class="sidebar-link"><i class="fa-solid fa-download" style="margin-right: 0.5rem;"></i> App Downloads</a></li>
                <li><a href="submissions.php" class="sidebar-link active"><i class="fa-solid fa-envelope-open-text" style="margin-right: 0.5rem;"></i> Submissions</a></li>
            </ul>
            <div class="sidebar-footer">
                <a href="logout.php" class="sidebar-link" style="color: #ff8b8b;"><i class="fa-solid fa-right-from-bracket" style="margin-right: 0.5rem;"></i> Sign Out</a>
            </div>
        </aside>

        <!-- Main Workspace -->
        <main class="admin-main">
            <header class="admin-header">
                <div class="admin-title">Form Submissions</div>
                <div class="admin-user">
                    Welcome, <span style="color: var(--primary-light);"><?php echo htmlspecialchars(get_logged_in_user()); ?></span>
                </div>
            </header>

            <div class="admin-content">
                <!-- Filters and Actions Toolbar -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
                    <!-- Filter Links -->
                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        <a href="submissions.php" class="admin-btn-outline" style="margin: 0; padding: 0.5rem 0.75rem; font-size: 0.85rem; border-color: <?php echo empty($filter_type) ? 'var(--primary)' : '#cbd5e1'; ?>; background-color: <?php echo empty($filter_type) ? '#f1f5f9' : 'transparent'; ?>;">All</a>
                        <a href="submissions.php?type=member_registration" class="admin-btn-outline" style="margin: 0; padding: 0.5rem 0.75rem; font-size: 0.85rem; border-color: <?php echo ($filter_type == 'member_registration') ? 'var(--primary)' : '#cbd5e1'; ?>; background-color: <?php echo ($filter_type == 'member_registration') ? '#f1f5f9' : 'transparent'; ?>;">Members</a>
                        <a href="submissions.php?type=pledge" class="admin-btn-outline" style="margin: 0; padding: 0.5rem 0.75rem; font-size: 0.85rem; border-color: <?php echo ($filter_type == 'pledge') ? 'var(--primary)' : '#cbd5e1'; ?>; background-color: <?php echo ($filter_type == 'pledge') ? '#f1f5f9' : 'transparent'; ?>;">Pledges</a>
                        <a href="submissions.php?type=prayer" class="admin-btn-outline" style="margin: 0; padding: 0.5rem 0.75rem; font-size: 0.85rem; border-color: <?php echo ($filter_type == 'prayer') ? 'var(--primary)' : '#cbd5e1'; ?>; background-color: <?php echo ($filter_type == 'prayer') ? '#f1f5f9' : 'transparent'; ?>;">Prayers</a>
                        <a href="submissions.php?type=contact" class="admin-btn-outline" style="margin: 0; padding: 0.5rem 0.75rem; font-size: 0.85rem; border-color: <?php echo ($filter_type == 'contact') ? 'var(--primary)' : '#cbd5e1'; ?>; background-color: <?php echo ($filter_type == 'contact') ? '#f1f5f9' : 'transparent'; ?>;">Contacts</a>
                    </div>
                    
                    <!-- Export Button -->
                    <a href="submissions.php?action=export<?php echo !empty($filter_type) ? '&type='.$filter_type : ''; ?>" class="admin-btn" style="background-color: var(--success);"><i class="fa-solid fa-file-excel"></i> Export as CSV</a>
                </div>

                <!-- Submissions Table Card -->
                <div class="card-table-wrap">
                    <div class="card-table-header">
                        <span class="table-title">
                            <?php 
                                if (empty($filter_type)) echo 'All Submissions';
                                else echo ucfirst(htmlspecialchars($filter_type)) . ' Submissions';
                            ?>
                        </span>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th>Contact Info</th>
                                    <th>Pledge / Message</th>
                                    <th>Status</th>
                                    <th style="text-align: right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($submissions)): ?>
                                    <?php foreach ($submissions as $sub): ?>
                                        <tr>
                                            <td><?php echo date('M d, Y H:i', strtotime($sub['created_at'])); ?></td>
                                            <td>
                                                <span class="badge badge-<?php echo htmlspecialchars($sub['type']); ?>">
                                                    <?php echo ucfirst(htmlspecialchars($sub['type'])); ?>
                                                </span>
                                            </td>
                                            <td style="font-weight: 600;"><?php echo htmlspecialchars($sub['name']); ?></td>
                                            <td>
                                                <div style="font-size: 0.85rem; color: #334155;"><?php echo htmlspecialchars($sub['email']); ?></div>
                                                <div style="font-size: 0.8rem; color: #64748b;"><?php echo htmlspecialchars($sub['phone']); ?></div>
                                            </td>
                                            <td>
                                                <?php if ($sub['type'] == 'pledge'): ?>
                                                    <strong>KES <?php echo number_format($sub['amount'], 2); ?></strong>
                                                    <div style="font-size: 0.85rem; color: #64748b; margin-top: 0.25rem;">
                                                        <?php echo htmlspecialchars($sub['subject_message']); ?>
                                                    </div>
                                                <?php else: ?>
                                                    <div style="font-size: 0.9rem; color: #334155; max-width: 400px; word-wrap: break-word;">
                                                        <?php echo nl2br(htmlspecialchars($sub['subject_message'])); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?php echo htmlspecialchars($sub['status']); ?>">
                                                    <?php echo ucfirst(htmlspecialchars($sub['status'])); ?>
                                                </span>
                                            </td>
                                            <td style="text-align: right; white-space: nowrap;">
                                                <?php if ($sub['status'] == 'unread'): ?>
                                                    <a href="submissions.php?action=read&id=<?php echo $sub['id']; ?><?php echo !empty($filter_type) ? '&type='.$filter_type : ''; ?>" class="btn-sm btn-edit"><i class="fa-regular fa-eye"></i> Mark Read</a>
                                                <?php elseif ($sub['status'] == 'read'): ?>
                                                    <a href="submissions.php?action=resolve&id=<?php echo $sub['id']; ?><?php echo !empty($filter_type) ? '&type='.$filter_type : ''; ?>" class="btn-sm btn-edit" style="background-color: rgba(46,133,64,0.1); color: var(--success); border-color: rgba(46,133,64,0.2);"><i class="fa-regular fa-circle-check"></i> Resolve</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" style="text-align: center; color: #637381; padding: 2rem;">No submissions found.</td>
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
