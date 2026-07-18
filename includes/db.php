<?php
// includes/db.php

$db_file = __DIR__ . '/church.db';

try {
    // Connect to SQLite database (will be created automatically if not exists)
    $dsn = "sqlite:$db_file";
    $pdo = new PDO($dsn, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_TIMEOUT => 5
    ]);

    // Create tables if they do not exist
    
    // 1. Users Table (Admin Portal)
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // 2. Blogs Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS blogs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        slug TEXT UNIQUE NOT NULL,
        content TEXT NOT NULL,
        excerpt TEXT NOT NULL,
        image_url TEXT,
        category TEXT DEFAULT 'General',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // 3. Submissions Table (Contacts, Prayer Requests, Pledges)
    $pdo->exec("CREATE TABLE IF NOT EXISTS submissions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        type TEXT NOT NULL, -- 'contact', 'prayer', 'pledge'
        name TEXT NOT NULL,
        email TEXT NOT NULL,
        phone TEXT,
        subject_message TEXT,
        amount REAL DEFAULT 0.00,
        status TEXT DEFAULT 'unread', -- 'unread', 'read', 'resolved'
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // 4. Analytics Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS analytics (
        page TEXT PRIMARY KEY,
        views INTEGER DEFAULT 0,
        clicks INTEGER DEFAULT 0,
        time_spent INTEGER DEFAULT 0
    )");

    // 5. Visitor Tracking Table (IPs, Devices, Location, ISPs)
    $pdo->exec("CREATE TABLE IF NOT EXISTS visitor_tracking (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        ip_address TEXT NOT NULL,
        user_agent TEXT NOT NULL,
        device_type TEXT,
        browser TEXT,
        location TEXT DEFAULT 'Unknown',
        network_isp TEXT DEFAULT 'Unknown',
        time_spent INTEGER DEFAULT 0,
        last_seen DATETIME DEFAULT CURRENT_TIMESTAMP,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // 6. Applications Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS applications (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        description TEXT,
        file_url TEXT NOT NULL,
        platform TEXT,
        version TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // 7. App Downloads Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS app_downloads (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        app_id INTEGER NOT NULL,
        ip_address TEXT NOT NULL,
        mac_address TEXT DEFAULT 'Unknown',
        device_info TEXT,
        downloaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (app_id) REFERENCES applications(id)
    )");

    // Insert default applications if applications table is empty
    $stmt_apps = $pdo->query("SELECT COUNT(*) FROM applications");
    if ($stmt_apps->fetchColumn() == 0) {
        $insertApp = $pdo->prepare("INSERT INTO applications (name, description, file_url, platform, version) VALUES (:name, :description, :file_url, :platform, :version)");
        $seedApps = [
            [
                'name' => 'Membley Adventist Mobile',
                'description' => 'The official church mobile app for sermons, hymns, and online giving.',
                'file_url' => 'assets/apps/membley-adventist.apk',
                'platform' => 'Android',
                'version' => '1.0.0'
            ],
            [
                'name' => 'Sabbath School Quarterly',
                'description' => 'Digital study guide for adults and youth.',
                'file_url' => 'assets/apps/ss-quarterly.pdf',
                'platform' => 'All',
                'version' => '2026.3'
            ]
        ];
        foreach ($seedApps as $app) {
            $insertApp->execute([
                ':name' => $app['name'],
                ':description' => $app['description'],
                ':file_url' => $app['file_url'],
                ':platform' => $app['platform'],
                ':version' => $app['version']
            ]);
        }
    }

    // Insert default admin if users table is empty (admin / admin123)
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    if ($stmt->fetchColumn() == 0) {
        $defaultPassword = password_hash('admin123', PASSWORD_BCRYPT);
        $insertUser = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $insertUser->execute([
            ':username' => 'admin',
            ':password' => $defaultPassword
        ]);
        
        // Seed initial blog posts
        $seedBlogs = [
            [
                'title' => 'Welcome to our New Website!',
                'slug' => 'welcome-to-our-new-website',
                'content' => '<p>We are delighted to launch the brand new website for Membley Seventh-day Adventist Church. Our goal is to provide a platform that connects our members, visitors, and community closer together.</p><p>Here you will find updates on church ministries, youth and kids departments, pathfinders and adventurers, upcoming events, and opportunities to give back online. Join us in worship every Sabbath!</p>',
                'excerpt' => 'Welcome to the launch of our new church portal. Explore events, ministries, and sermon resources.',
                'category' => 'Announcements'
            ],
            [
                'title' => 'The Power of Prayer',
                'slug' => 'the-power-of-prayer',
                'content' => '<p>In these challenging times, prayer remains our constant link to the Almighty. Join us every Wednesday evening for our Mid-week prayer service as we lift our voices in praise and supplication.</p><p>If you have any prayer requests, you can now submit them directly online via our new contact page, and our elder’s council will pray over them.</p>',
                'excerpt' => 'Join us as we explore the scriptural power of prayer and worship together in community.',
                'category' => 'Sermons'
            ],
            [
                'title' => 'Understanding the Adventurer Club Ministry',
                'slug' => 'understanding-the-adventurer-club-ministry',
                'content' => '<p>The Adventurer Club is a Seventh-day Adventist Church-sponsored program for children in grades 1–4 (ages 6–9). It was created to assist parents in making the development of their child as a Christian both meaningful and fun.</p><h4>Objectives of the Adventurer Club</h4><ul><li>Demonstrate God\'s love for children.</li><li>Promote Christian values and life skills.</li><li>Encourage family involvement through joint activities.</li></ul><h4>Curriculum Levels</h4><p>The Adventurer curriculum is divided into four main progression levels:</p><ol><li><strong>Busy Bee:</strong> First Grade (Age 6)</li><li><strong>Sunbeam:</strong> Second Grade (Age 7)</li><li><strong>Builder:</strong> Third Grade (Age 8)</li><li><strong>Helping Hand:</strong> Fourth Grade (Age 9)</li></ol><p>Each level covers Bible study, nature, safety, and outdoor activities, culminating in beautiful badges and awards for child achievement.</p>',
                'excerpt' => 'Discover how the Adventurer Club supports parents in guiding children aged 6 to 9 in their Christian walk.',
                'category' => 'Youth & Kids'
            ],
            [
                'title' => 'The Pathfinder Club: Building Leaders of Tomorrow',
                'slug' => 'the-pathfinder-club-building-leaders-of-tomorrow',
                'content' => '<p>The Pathfinder Club is a department of the Seventh-day Adventist Church, which works specifically with the cultural, social, and spiritual education of children and teens aged 10–15. Operating similarly to scouting, the Pathfinder Club emphasizes Christian leadership and character development.</p><h4>The Pathfinder Pledge & Law</h4><p>Every Pathfinder commits to the Pathfinder Pledge and Law, which guides their behavior and lifestyle:</p><blockquote>"By the grace of God, I will be pure, and kind, and true. I will keep the Pathfinder Law. I will be a servant of God and a friend to man."</blockquote><h4>Pathfinder Classes</h4><p>Pathfinders progress through six classes based on age and grade levels:</p><ul><li><strong>Friend:</strong> Grade 5 (Age 10)</li><li><strong>Companion:</strong> Grade 6 (Age 11)</li><li><strong>Explorer:</strong> Grade 7 (Age 12)</li><li><strong>Ranger:</strong> Grade 8 (Age 13)</li><li><strong>Voyager:</strong> Grade 9 (Age 14)</li><li><strong>Guide:</strong> Grade 10 (Age 15)</li></ul><p>By engaging in outdoor camping, marching, pathfinder honors (like first-aid, survival skills, astronomy), and missionary work, teens build long-term friendships and strong faith.</p>',
                'excerpt' => 'Learn about the Pathfinder Club\'s mission, pledge, classes, and activities for teens and pre-teens aged 10 to 15.',
                'category' => 'Youth & Kids'
            ]
        ];

        $insertBlog = $pdo->prepare("INSERT INTO blogs (title, slug, content, excerpt, category) VALUES (:title, :slug, :content, :excerpt, :category)");
        foreach ($seedBlogs as $b) {
            $insertBlog->execute([
                ':title' => $b['title'],
                ':slug' => $b['slug'],
                ':content' => $b['content'],
                ':excerpt' => $b['excerpt'],
                ':category' => $b['category']
            ]);
        }
    }

} catch (PDOException $e) {
    header('Content-Type: text/html; charset=utf-8');
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Database Connection Required</title>
        <style>
            body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background-color: #f4f6f9; color: #333; padding: 2rem; display: flex; align-items: center; justify-content: center; min-height: 80vh; }
            .error-card { background: white; padding: 2.5rem; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); max-width: 600px; width: 100%; border-top: 5px solid #d9534f; }
            h1 { color: #d9534f; margin-top: 0; font-size: 1.8rem; }
            p { line-height: 1.6; color: #555; }
        </style>
    </head>
    <body>
        <div class="error-card">
            <h1>Database Connection Required</h1>
            <p>We are unable to connect to the SQLite database. Make sure the web server has write permissions to the <code>includes/</code> folder.</p>
            <p><strong>Error Message:</strong> <code><?php echo htmlspecialchars($e->getMessage()); ?></code></p>
        </div>
    </body>
    </html>
    <?php
    exit;
}
