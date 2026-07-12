<?php
// blog.php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Handle filters
$category = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Build SQL
$sql = "SELECT * FROM blogs WHERE 1=1";
$params = [];

if (!empty($category)) {
    $sql .= " AND category = :category";
    $params[':category'] = $category;
}

if (!empty($search)) {
    $sql .= " AND (title LIKE :search OR content LIKE :search OR excerpt LIKE :search)";
    $params[':search'] = '%' . $search . '%';
}

$sql .= " ORDER BY created_at DESC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    $posts = [];
}

// Fetch all unique categories for filter sidebar
try {
    $cat_stmt = $pdo->query("SELECT DISTINCT category FROM blogs WHERE category IS NOT NULL");
    $categories = $cat_stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $categories = [];
}
?>

<!-- Banner Section -->
<section style="background-color: var(--primary-dark); color: white; padding: 4rem 0; text-align: center; background-image: linear-gradient(rgba(4,25,40,0.8), rgba(4,25,40,0.8)), url('https://images.unsplash.com/photo-1490730141103-6cac27aaab94?auto=format&fit=crop&q=80&w=1200'); background-size: cover; background-position: center;">
    <div class="container">
        <h1 style="color: white; font-size: 2.75rem; margin-bottom: 0.5rem;">Church Blog & Sermons</h1>
        <p style="color: var(--accent); font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Stay updated with news, notes, and theological lessons</p>
    </div>
</section>

<!-- Main Feed -->
<section class="section-padding container">
    <div style="display: flex; gap: 3rem; align-items: start; flex-wrap: wrap;">
        
        <!-- Main Feed List -->
        <div style="flex: 1; min-width: 320px;">
            <div class="grid-3" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <div class="card">
                            <div class="card-img" style="background-image: url('<?php echo !empty($post['image_url']) ? htmlspecialchars($post['image_url']) : 'https://images.unsplash.com/photo-1490730141103-6cac27aaab94?auto=format&fit=crop&q=80&w=400'; ?>');">
                                <span class="card-tag"><?php echo htmlspecialchars($post['category']); ?></span>
                            </div>
                            <div class="card-body">
                                <span class="card-date"><i class="fa-regular fa-calendar"></i> <?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                                <h3 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h3>
                                <p class="card-desc"><?php echo htmlspecialchars($post['excerpt']); ?></p>
                                <a href="blog-single.php?slug=<?php echo htmlspecialchars($post['slug']); ?>" class="btn btn-outline btn-sm">Read Post</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center; grid-column: span 2; color: var(--text-muted); padding: 3rem 0;">No matching posts found. Try checking other categories.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar Filters -->
        <div style="flex: 0 0 300px; width: 100%;">
            <!-- Search Widget -->
            <div style="background-color: var(--bg-white); padding: 1.5rem; border-radius: 10px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm); margin-bottom: 2rem;">
                <h3 style="color: var(--primary); margin-bottom: 1rem; font-size: 1.1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Search Posts</h3>
                <form action="blog.php" method="GET">
                    <div style="display: flex; gap: 0.5rem;">
                        <input type="text" name="search" placeholder="Type keywords..." value="<?php echo htmlspecialchars($search); ?>" class="form-control" style="padding: 0.5rem 0.75rem; font-size: 0.9rem;">
                        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem;"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </form>
            </div>

            <!-- Categories Widget -->
            <div style="background-color: var(--bg-white); padding: 1.5rem; border-radius: 10px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
                <h3 style="color: var(--primary); margin-bottom: 1rem; font-size: 1.1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Categories</h3>
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.5rem;">
                    <li>
                        <a href="blog.php" style="font-weight: <?php echo empty($category) ? '700' : '500'; ?>; color: <?php echo empty($category) ? 'var(--primary)' : 'var(--text-muted)'; ?>;">
                            All Categories
                        </a>
                    </li>
                    <?php foreach ($categories as $cat): ?>
                        <li>
                            <a href="blog.php?category=<?php echo urlencode($cat); ?>" style="font-weight: <?php echo ($category == $cat) ? '700' : '500'; ?>; color: <?php echo ($category == $cat) ? 'var(--primary)' : 'var(--text-muted)'; ?>;">
                                <?php echo htmlspecialchars($cat); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
