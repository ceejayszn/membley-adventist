<?php
// events.php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Fetch upcoming events from database (today <= event_date + 2 days)
$upcoming_events = [];
try {
    $stmt = $pdo->query("SELECT * FROM events ORDER BY event_date ASC");
    $all_events = $stmt->fetchAll();
    $today = date('Y-m-d');
    foreach ($all_events as $ev) {
        $expiry_date = date('Y-m-d', strtotime($ev['event_date'] . ' +2 days'));
        if ($today <= $expiry_date) {
            $upcoming_events[] = $ev;
        }
    }
} catch (PDOException $e) {
    // Gracefully handle database query issues
}
?>

<!-- Banner Section -->
<section style="background-color: var(--primary-dark); color: white; padding: 4rem 0; text-align: center; background-image: linear-gradient(rgba(4,25,40,0.8), rgba(4,25,40,0.8)), url('https://images.unsplash.com/photo-1544427920-c49ccfb85579?auto=format&fit=crop&q=80&w=1200'); background-size: cover; background-position: center;">
    <div class="container">
        <h1 style="color: white; font-size: 2.75rem; margin-bottom: 0.5rem;">Events & Announcements</h1>
        <p style="color: var(--accent); font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Join us in our upcoming activities & convocations</p>
    </div>
</section>

<!-- Sub Navigation Bar -->
<div style="background-color: var(--bg-white); border-bottom: 1px solid var(--border-color); padding: 1rem 0;">
    <div class="container" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; gap: 0.75rem;">
            <a href="events.php" class="btn btn-primary btn-sm"><i class="fa-solid fa-calendar-star"></i> Upcoming Events</a>
            <a href="past-events.php" class="btn btn-outline btn-sm"><i class="fa-solid fa-box-archive"></i> Past Events Archive</a>
        </div>
        <div style="font-size: 0.85rem; color: var(--text-muted);">
            <i class="fa-solid fa-circle-info"></i> Events auto-move to archive 2 days after event date
        </div>
    </div>
</div>

<!-- Filterable Events -->
<section class="section-padding container">
    <div class="responsive-flex-events">
        
        <!-- Left: Sidebar Service Info & Archive Quick Link -->
        <div class="responsive-flex-events-sidebar">
            <div style="background-color: var(--bg-white); padding: 1.5rem; border-radius: 10px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
                <h3 style="color: var(--primary); margin-bottom: 1rem; font-size: 1.1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Regular Worship</h3>
                <ul style="list-style: none; font-size: 0.9rem; display: flex; flex-direction: column; gap: 0.75rem;">
                    <li><strong>Sabbaths:</strong> 9:00 AM – 5:00 PM</li>
                    <li><strong>Fridays:</strong> 6:00 PM – 7:00 PM (Vespers)</li>
                </ul>
            </div>
            
            <div style="background-color: var(--bg-white); padding: 1.5rem; border-radius: 10px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
                <h3 style="color: var(--primary); margin-bottom: 1rem; font-size: 1.1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Event Archives</h3>
                <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1rem;">Looking for past church convocations and camp meetings?</p>
                <a href="past-events.php" class="btn btn-outline btn-sm" style="width: 100%;"><i class="fa-solid fa-clock-rotate-left"></i> View Past Events</a>
            </div>

            <div style="background-color: var(--bg-white); padding: 1.5rem; border-radius: 10px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
                <h3 style="color: var(--primary); margin-bottom: 1rem; font-size: 1.1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Quick Contact</h3>
                <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1rem;">Have questions about an upcoming event? Contact our clerk's office.</p>
                <a href="contact.php" class="btn btn-primary btn-sm" style="width: 100%;">Contact Us</a>
            </div>
        </div>

        <!-- Right: Event Cards List -->
        <div style="flex: 1; display: flex; flex-direction: column; gap: 1.5rem;">
            
            <?php if (!empty($upcoming_events)): ?>
                <?php foreach ($upcoming_events as $index => $event): ?>
                    <?php 
                        $is_featured = ($event['is_featured'] == 1);
                        $formatted_day = date('d', strtotime($event['event_date']));
                        $formatted_month = date('M', strtotime($event['event_date']));
                    ?>
                    <?php if ($is_featured): ?>
                        <!-- Green Outline Featured Card -->
                        <div class="event-card-green-outline">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                                <span class="badge-green-outline"><i class="fa-solid fa-sparkles"></i> Featured Upcoming Event</span>
                                <span style="font-size: 0.8rem; color: #10b981; font-weight: 700;">Active Event</span>
                            </div>
                            <div class="event-flyer-box">
                                <div class="flyer-main-details">
                                    <span class="flyer-presenter"><?php echo htmlspecialchars(!empty($event['subtitle']) ? 'MEMBLEY ADVENTIST PRESENTS' : 'MEMBLEY SDA CHURCH'); ?></span>
                                    <h3 class="flyer-title"><?php echo htmlspecialchars($event['title']); ?></h3>
                                    <?php if (!empty($event['subtitle'])): ?>
                                        <div class="flyer-subtitle">
                                            <span class="ribbon-10yrs">CELEBRATING 10 YRS</span>
                                            <span>OF FELLOWSHIP AND FAMILY</span>
                                        </div>
                                    <?php endif; ?>
                                    <p style="color: rgba(255,255,255,0.85); font-size: 0.95rem;">
                                        <?php echo htmlspecialchars($event['description']); ?>
                                    </p>
                                    <div class="flyer-meta-grid">
                                        <div class="flyer-meta-card">
                                            <div class="flyer-meta-icon"><i class="fa-solid fa-calendar"></i></div>
                                            <div class="flyer-meta-text">
                                                <small>Date</small>
                                                <strong><?php echo date('d M Y', strtotime($event['event_date'])); ?></strong>
                                            </div>
                                        </div>
                                        <div class="flyer-meta-card">
                                            <div class="flyer-meta-icon"><i class="fa-solid fa-clock"></i></div>
                                            <div class="flyer-meta-text">
                                                <small>Time</small>
                                                <strong><?php echo htmlspecialchars($event['event_time']); ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flyer-visual-badge">
                                    <div class="flyer-date-pill">
                                        <span class="flyer-date-big"><?php echo $formatted_day; ?></span>
                                        <span class="flyer-date-month"><?php echo date('M Y', strtotime($event['event_date'])); ?></span>
                                    </div>
                                    <div style="font-size: 0.85rem; color: white; font-weight: 700;">
                                        <i class="fa-solid fa-location-dot" style="color: #10b981;"></i> <?php echo htmlspecialchars($event['location']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Standard Event List Card -->
                        <div class="event-list-card">
                            <div style="background-color: var(--primary-dark); color: white; padding: 1rem; border-radius: 8px; text-align: center; min-width: 100px;">
                                <span style="font-size: 1.8rem; font-weight: 800; display: block; line-height: 1; color: var(--accent);"><?php echo $formatted_day; ?></span>
                                <span style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase;"><?php echo $formatted_month; ?></span>
                            </div>
                            <div style="flex: 1;">
                                <span style="background-color: rgba(242,169,0,0.15); color: #a67300; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.5rem; border-radius: 4px; display: inline-block; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($event['category']); ?></span>
                                <h3 style="color: var(--primary); margin-bottom: 0.5rem; font-size: 1.4rem;"><?php echo htmlspecialchars($event['title']); ?></h3>
                                <p style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 0.5rem;"><i class="fa-solid fa-clock"></i> <?php echo htmlspecialchars($event['event_time']); ?></p>
                                <p style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 0.5rem;"><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($event['location']); ?></p>
                                <p style="font-size: 0.95rem; color: var(--text-dark);"><?php echo htmlspecialchars($event['description']); ?></p>
                            </div>
                            <?php if (!empty($event['image_url'])): ?>
                                <div style="width: 130px; height: 130px; flex-shrink: 0; border-radius: 8px; background-image: url('<?php echo htmlspecialchars($event['image_url']); ?>'); background-size: cover; background-position: center; border: 1px solid var(--border-color);">
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 3rem; background: var(--bg-white); border-radius: 10px; border: 1px solid var(--border-color);">
                    <p style="color: var(--text-muted); margin-bottom: 1rem;">No upcoming events scheduled right now.</p>
                    <a href="past-events.php" class="btn btn-outline btn-sm">Check Past Events Archive</a>
                </div>
            <?php endif; ?>

        </div>

    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
