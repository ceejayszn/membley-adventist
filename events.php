<?php
// events.php
require_once 'includes/db.php';
require_once 'includes/header.php';
?>

<!-- Banner Section -->
<section style="background-color: var(--primary-dark); color: white; padding: 4rem 0; text-align: center; background-image: linear-gradient(rgba(4,25,40,0.8), rgba(4,25,40,0.8)), url('https://images.unsplash.com/photo-1544427920-c49ccfb85579?auto=format&fit=crop&q=80&w=1200'); background-size: cover; background-position: center;">
    <div class="container">
        <h1 style="color: white; font-size: 2.75rem; margin-bottom: 0.5rem;">Events & Announcements</h1>
        <p style="color: var(--accent); font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Join us in our upcoming activities & convocations</p>
    </div>
</section>

<!-- Filterable Events -->
<section class="section-padding container">
    <div class="responsive-flex-events">
        
        <!-- Left: Filters & Service Info -->
        <div class="responsive-flex-events-sidebar">
            <div style="background-color: var(--bg-white); padding: 1.5rem; border-radius: 10px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
                <h3 style="color: var(--primary); margin-bottom: 1rem; font-size: 1.1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Regular Worship</h3>
                <ul style="list-style: none; font-size: 0.9rem; display: flex; flex-direction: column; gap: 0.75rem;">
                    <li><strong>Sabbaths:</strong> 9:00 AM – 5:00 PM</li>
                    <li><strong>Fridays:</strong> 6:00 PM – 7:00 PM (Vespers)</li>
                </ul>
            </div>
            
            <div style="background-color: var(--bg-white); padding: 1.5rem; border-radius: 10px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
                <h3 style="color: var(--primary); margin-bottom: 1rem; font-size: 1.1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Quick Contact</h3>
                <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1rem;">Have questions about an upcoming event? Contact our clerk's office.</p>
                <a href="contact.php" class="btn btn-primary btn-sm" style="width: 100%;">Contact Us</a>
            </div>
        </div>

        <!-- Right: Event Cards List -->
        <div style="flex: 1; display: flex; flex-direction: column; gap: 1.5rem;">
            
            <!-- Event 1: Camp Meeting -->
            <div class="event-list-card">
                <div style="background-color: var(--primary-dark); color: white; padding: 1rem; border-radius: 8px; text-align: center; min-width: 100px;">
                    <span style="font-size: 1.8rem; font-weight: 800; display: block; line-height: 1; color: var(--accent);">16</span>
                    <span style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">AUG</span>
                </div>
                <div style="flex: 1;">
                    <span style="background-color: rgba(242,169,0,0.15); color: #a67300; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.5rem; border-radius: 4px; display: inline-block; margin-bottom: 0.5rem;">Special Convocation</span>
                    <h3 style="color: var(--primary); margin-bottom: 0.5rem; font-size: 1.4rem;">Annual Church Camp Meeting 2026</h3>
                    <p style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 0.5rem;"><i class="fa-solid fa-clock"></i> 8:00 AM – 5:00 PM Daily (Aug 16th – Aug 23rd)</p>
                    <p style="font-size: 0.95rem; color: var(--text-muted);"><i class="fa-solid fa-location-dot"></i> Membley SDA Church Sanctuary</p>
                </div>
            </div>

            <!-- Event 1.5: Youth Hike -->
            <div class="event-list-card">
                <div style="background-color: var(--primary-dark); color: white; padding: 1rem; border-radius: 8px; text-align: center; min-width: 100px;">
                    <span style="font-size: 1.8rem; font-weight: 800; display: block; line-height: 1; color: var(--accent);">19</span>
                    <span style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">JUL</span>
                </div>
                <div style="flex: 1;">
                    <span style="background-color: rgba(8,43,67,0.1); color: var(--primary); font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.5rem; border-radius: 4px; display: inline-block; margin-bottom: 0.5rem;">Youth / AY</span>
                    <h3 style="color: var(--primary); margin-bottom: 0.5rem; font-size: 1.4rem;">Youth Hike to KIMAKIA Forest</h3>
                    <p style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 0.5rem;"><i class="fa-solid fa-clock"></i> Tomorrow, 7:00 AM Departure</p>
                    <p style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 1rem;"><i class="fa-solid fa-location-dot"></i> KIMAKIA Forest, Murang'a</p>
                    <p style="font-size: 0.95rem; color: var(--text-muted);">Join the youth for a refreshing hike and team-building at Kimakia Forest. Pack a lunch, carry some water, and wear comfortable hiking shoes!</p>
                </div>
                <!-- Photo placeholder for the hike -->
                <div style="width: 150px; height: 150px; border-radius: 8px; background-color: #f1f5f9; border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; text-align: center; background-image: url('assets/images/hike_photo.jpg'); background-size: cover; background-position: center;">
                </div>
            </div>

            <!-- Event 2: Youth Week of Prayer -->
            <div class="event-list-card">
                <div style="background-color: var(--primary-dark); color: white; padding: 1rem; border-radius: 8px; text-align: center; min-width: 100px;">
                    <span style="font-size: 1.8rem; font-weight: 800; display: block; line-height: 1; color: var(--accent);">11</span>
                    <span style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">OCT</span>
                </div>
                <div style="flex: 1;">
                    <span style="background-color: rgba(8,43,67,0.1); color: var(--primary); font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.5rem; border-radius: 4px; display: inline-block; margin-bottom: 0.5rem;">Youth / AY</span>
                    <h3 style="color: var(--primary); margin-bottom: 0.5rem; font-size: 1.4rem;">District Youth Week of Prayer</h3>
                    <p style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 0.5rem;"><i class="fa-solid fa-clock"></i> 6:00 PM – 7:30 PM Daily (Oct 11th – Oct 17th)</p>
                    <p style="font-size: 0.95rem; color: var(--text-muted);"><i class="fa-solid fa-location-dot"></i> District-wide Churches</p>
                </div>
            </div>

            <!-- Event 3: Pathfinder Induction -->
            <div class="event-list-card">
                <div style="background-color: var(--primary-dark); color: white; padding: 1rem; border-radius: 8px; text-align: center; min-width: 100px;">
                    <span style="font-size: 1.8rem; font-weight: 800; display: block; line-height: 1; color: var(--accent);">29</span>
                    <span style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">NOV</span>
                </div>
                <div style="flex: 1;">
                    <span style="background-color: rgba(46,133,64,0.1); color: #2e8540; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.5rem; border-radius: 4px; display: inline-block; margin-bottom: 0.5rem;">Club / Pathfinder</span>
                    <h3 style="color: var(--primary); margin-bottom: 0.5rem; font-size: 1.4rem;">Pathfinder & Adventurer Induction Ceremony</h3>
                    <p style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 0.5rem;"><i class="fa-solid fa-clock"></i> 9:00 AM – 1:00 PM</p>
                    <p style="font-size: 0.95rem; color: var(--text-muted);"><i class="fa-solid fa-location-dot"></i> Membley Church Grounds</p>
                </div>
            </div>

        </div>

    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
