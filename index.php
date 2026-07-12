<?php
// index.php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Fetch 3 latest blog posts
$latest_blogs = [];
try {
    $stmt = $pdo->query("SELECT * FROM blogs ORDER BY created_at DESC LIMIT 3");
    $latest_blogs = $stmt->fetchAll();
} catch (PDOException $e) {
    // Gracefully handle query issue if any
}
?>

<!-- Hero Slider Section -->
<section class="hero">
    <div class="hero-slider">
        <!-- Slide 1 -->
        <div class="slide active" style="background-image: linear-gradient(rgba(0,47,93,0.35), rgba(0,47,93,0.35)), url('assets/images/church_banner.png'); background-size: cover; background-position: center;">
            <div class="container">
                <div class="hero-content">
                    <span class="section-subtitle" style="color: var(--accent); font-weight: 800;">Welcome to Membley SDA Church</span>
                    <h1 class="hero-title">A Sanctuary of <span>Hope</span>, Faith & Love</h1>
                    <p class="hero-desc">We invite you to worship with us this Sabbath and experience the transformative power of God's grace in Ruiru.</p>
                    <div class="hero-actions">
                        <a href="about.php" class="btn btn-primary">Learn More About Us</a>
                        <a href="giving.php" class="btn btn-accent"><i class="fa-solid fa-heart"></i> Give Online</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Slide 2 -->
        <div class="slide" style="background-image: linear-gradient(rgba(0,47,93,0.5), rgba(0,47,93,0.5)), url('assets/images/adventurer_banner.jpg'); background-size: cover; background-position: center;">
            <div class="container">
                <div class="hero-content">
                    <span class="section-subtitle" style="color: var(--accent); font-weight: 800;">Nurturing the Next Generation</span>
                    <h1 class="hero-title">Youth, Pathfinders & <span>Adventurers</span></h1>
                    <p class="hero-desc">Discover vibrant programs designed to guide our children and youth into a lifelong relationship with Jesus.</p>
                    <div class="hero-actions">
                        <a href="ministries.php" class="btn btn-primary">Our Departments</a>
                        <a href="contact.php" class="btn btn-outline" style="color: white; border-color: white;">Get in Touch</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Service Times Banner Overlay -->
<section style="position: relative; z-index: 20;">
    <div class="container">
        <div class="services-banner">
            <h2 style="color: white; font-size: 1.6rem; margin-bottom: 1.5rem; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.15); padding-bottom: 1rem;">
                <i class="fa-regular fa-clock"></i> Weekly Worship Services
            </h2>
            <div class="services-grid">
                <div class="service-item">
                    <div class="service-time-title">Sabbath School</div>
                    <div class="service-time-hours">Saturdays | 9:00 AM - 10:40 AM</div>
                    <p style="font-size: 0.85rem; color: rgba(255,255,255,0.7); margin-top: 0.5rem;">Interactive study of the quarterly Bible Lesson in classes.</p>
                </div>
                <div class="service-item">
                    <div class="service-time-title">Divine Service</div>
                    <div class="service-time-hours">Saturdays | 10:50 AM - 12:30 PM</div>
                    <p style="font-size: 0.85rem; color: rgba(255,255,255,0.7); margin-top: 0.5rem;">Praise, prayer, and sermon delivery by the Pastor or Elders.</p>
                </div>
                <div class="service-item">
                    <div class="service-time-title">Adventist Youth (AY)</div>
                    <div class="service-time-hours">Saturdays | 4:00 PM - 5:30 PM</div>
                    <p style="font-size: 0.85rem; color: rgba(255,255,255,0.7); margin-top: 0.5rem;">Vibrant sessions including youth discussions, singing, and quizzes.</p>
                </div>
                <div class="service-item">
                    <div class="service-time-title">Mid-Week Prayer</div>
                    <div class="service-time-hours">Wednesdays | 6:30 PM - 7:30 PM</div>
                    <p style="font-size: 0.85rem; color: rgba(255,255,255,0.7); margin-top: 0.5rem;">Midweek devotional session for spiritual rejuvenation and prayer requests.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Welcome Section -->
<section class="section-padding container">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center;">
        <div>
            <span class="section-subtitle">Welcome message</span>
            <h2 style="font-size: 2.2rem; margin-bottom: 1.5rem; color: var(--primary);">A Warm Welcome From Our Pastor</h2>
            <p style="margin-bottom: 1.25rem; color: var(--text-muted);">
                Welcome to the Membley Seventh-day Adventist Church community portal. It is our joy to receive you here. We believe that God has a special purpose for everyone, and our mission is to assist one another on this journey towards the Heavenly Canaan.
            </p>
            <p style="margin-bottom: 2rem; color: var(--text-muted);">
                Whether you are a resident in Ruiru seeking a regular place of worship, a visitor seeking truth, or just looking to participate in fellowship, we invite you to join us this Sabbath. May the Lord bless and keep you.
            </p>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background-color: #cbd5e1; background-image: url('https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=150'); background-size: cover;"></div>
                <div>
                    <h4 style="color: var(--primary); margin: 0;">Pr. Joseph Mwaniki</h4>
                    <small style="color: var(--text-muted); font-weight: 600;">District Pastor, Membley SDA</small>
                </div>
            </div>
        </div>
        <div style="position: relative;">
            <!-- Church Building Mock/Placeholder -->
            <div style="height: 380px; border-radius: 12px; background-image: linear-gradient(rgba(8,43,67,0.1), rgba(8,43,67,0.1)), url('https://images.unsplash.com/photo-1548625361-155deee223d2?auto=format&fit=crop&q=80&w=600'); background-size: cover; background-position: center; box-shadow: var(--shadow-lg);"></div>
            <div style="position: absolute; bottom: -20px; left: -20px; background-color: var(--accent); color: var(--primary-dark); padding: 1.5rem; border-radius: 8px; box-shadow: var(--shadow-md); font-weight: 700;">
                <span style="font-size: 2rem; display: block; line-height: 1;">10+</span>
                <span style="font-size: 0.85rem; font-weight: 600;">Active Ministries</span>
            </div>
        </div>
    </div>
</section>

<!-- Departments Highlights -->
<section style="background-color: var(--bg-white);" class="section-padding">
    <div class="container">
        <div class="section-header">
            <span class="section-subtitle">Ministries & Departments</span>
            <h2 class="section-title">Nurturing All Age Groups</h2>
        </div>
        <div class="grid-3">
            <!-- Kids -->
            <div class="card">
                <div class="card-img" style="background-image: url('https://images.unsplash.com/photo-1503919545889-aef636e10ad4?auto=format&fit=crop&q=80&w=400');">
                    <span class="card-tag">Children</span>
                </div>
                <div class="card-body">
                    <h3 class="card-title">Sabbath School Kids</h3>
                    <p class="card-desc">Catering to beginners, primary, and junior groups with exciting activities, songs, and Bible lessons to build a firm spiritual foundation.</p>
                    <a href="ministries.php?tab=children" class="btn btn-outline btn-sm">Learn More</a>
                </div>
            </div>
            
            <!-- Pathfinders -->
            <div class="card">
                <div class="card-img" style="background-image: url('https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&q=80&w=400');">
                    <span class="card-tag">Club</span>
                </div>
                <div class="card-body">
                    <h3 class="card-title">Pathfinder & Adventurer Clubs</h3>
                    <p class="card-desc">Instilling Christian values, life skills, camping, and outdoor exploration in children aged 6 to 15 years through the worldwide club movement.</p>
                    <a href="ministries.php?tab=clubs" class="btn btn-outline btn-sm">Learn More</a>
                </div>
            </div>

            <!-- Youth -->
            <div class="card">
                <div class="card-img" style="background-image: url('https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&q=80&w=400');">
                    <span class="card-tag">Youth</span>
                </div>
                <div class="card-body">
                    <h3 class="card-title">Adventist Youth (AY)</h3>
                    <p class="card-desc">Providing a vibrant space for ambassadors, young adults, and teens to share fellowship, discuss contemporary issues, and conduct evangelism.</p>
                    <a href="ministries.php?tab=youth" class="btn btn-outline btn-sm">Learn More</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Latest Blogs & News -->
<section class="section-padding container">
    <div class="section-header">
        <span class="section-subtitle">News & Sermons</span>
        <h2 class="section-title">Latest Updates & Sermons</h2>
    </div>
    
    <div class="grid-3">
        <?php if (!empty($latest_blogs)): ?>
            <?php foreach ($latest_blogs as $post): ?>
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
            <p style="text-align: center; grid-column: span 3; color: var(--text-muted);">No blog posts found. Check back soon!</p>
        <?php endif; ?>
    </div>
    
    <div style="text-align: center; margin-top: 3rem;">
        <a href="blog.php" class="btn btn-primary">View All Blog Posts</a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
