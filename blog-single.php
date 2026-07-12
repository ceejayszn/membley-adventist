<?php
// blog-single.php
require_once 'includes/db.php';
require_once 'includes/header.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$post = null;

if (!empty($slug)) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM blogs WHERE slug = :slug");
        $stmt->execute([':slug' => $slug]);
        $post = $stmt->fetch();
    } catch (PDOException $e) {
        // Handle error
    }
}

if (!$post) {
    // If not found, redirect to blog page or show error
    echo "<div class='container section-padding' style='text-align: center;'><h2>Blog Post Not Found</h2><p><a href='blog.php'>Return to Blog</a></p></div>";
    require_once 'includes/footer.php';
    exit;
}
?>

<!-- Article Header -->
<section style="background-color: var(--primary-dark); color: white; padding: 5rem 0; background-image: linear-gradient(rgba(4,25,40,0.85), rgba(4,25,40,0.85)), url('<?php echo !empty($post['image_url']) ? htmlspecialchars($post['image_url']) : 'https://images.unsplash.com/photo-1490730141103-6cac27aaab94?auto=format&fit=crop&q=80&w=1200'; ?>'); background-size: cover; background-position: center;">
    <div class="container" style="max-width: 800px; text-align: center;">
        <span style="background-color: var(--accent); color: var(--primary-dark); font-size: 0.8rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: 50px; text-transform: uppercase; display: inline-block; margin-bottom: 1rem;">
            <?php echo htmlspecialchars($post['category']); ?>
        </span>
        <h1 style="color: white; font-size: 2.5rem; margin-bottom: 1rem; line-height: 1.2;"><?php echo htmlspecialchars($post['title']); ?></h1>
        <div style="font-size: 0.95rem; color: rgba(255,255,255,0.8);">
            <i class="fa-regular fa-calendar"></i> Published on <?php echo date('F d, Y', strtotime($post['created_at'])); ?>
        </div>
    </div>
</section>

<!-- Article Content -->
<article class="section-padding container" style="max-width: 800px; background-color: var(--bg-white); border-radius: 12px; box-shadow: var(--shadow-sm); margin-top: -3rem; position: relative; z-index: 10; padding: 3rem 2.5rem;">
    <?php if (!empty($post['image_url'])): ?>
        <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" style="width: 100%; border-radius: 8px; margin-bottom: 2rem; box-shadow: var(--shadow-sm);">
    <?php endif; ?>

    <div class="blog-content" style="color: var(--text-dark); font-size: 1.1rem; line-height: 1.8;">
        <!-- Echo safe allowed html formatting -->
        <?php echo $post['content']; ?>
    </div>

    <!-- Back to feed -->
    <div style="border-top: 1px solid var(--border-color); margin-top: 3rem; padding-top: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <a href="blog.php" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Back to Blog</a>
        
        <!-- Social Share placeholder icons -->
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <span style="font-size: 0.9rem; font-weight: 700; color: var(--text-muted);">Share:</span>
            <a href="#" style="color: #3b5998;"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#" style="color: #1da1f2;"><i class="fa-brands fa-twitter"></i></a>
            <a href="#" style="color: #25d366;"><i class="fa-brands fa-whatsapp"></i></a>
        </div>
    </div>
</article>

<?php require_once 'includes/footer.php'; ?>
