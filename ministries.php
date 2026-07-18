<?php
// ministries.php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Check if tab parameter is passed
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'youth';
?>

<!-- Banner Section -->
<section style="background-color: var(--primary-dark); color: white; padding: 4rem 0; text-align: center; background-image: linear-gradient(rgba(4,25,40,0.8), rgba(4,25,40,0.8)), url('https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&q=80&w=1200'); background-size: cover; background-position: center;">
    <div class="container">
        <h1 style="color: white; font-size: 2.75rem; margin-bottom: 0.5rem;">Church Departments & Ministries</h1>
        <p style="color: var(--accent); font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Discover a place to grow and serve</p>
    </div>
</section>

<!-- Tab Navigation -->
<div class="container tab-container" style="padding-top: 3rem;">
    <div class="tab-buttons">
        <button class="tab-btn <?php echo ($active_tab == 'youth') ? 'active' : ''; ?>" data-tab="youth"><i class="fa-solid fa-users"></i> Adventist Youth (AY)</button>
        <button class="tab-btn <?php echo ($active_tab == 'clubs') ? 'active' : ''; ?>" data-tab="clubs"><i class="fa-solid fa-compass"></i> Pathfinders</button>
        <button class="tab-btn <?php echo ($active_tab == 'children') ? 'active' : ''; ?>" data-tab="children"><i class="fa-solid fa-child"></i> Adventurous Club</button>
        <button class="tab-btn <?php echo ($active_tab == 'general') ? 'active' : ''; ?>" data-tab="general"><i class="fa-solid fa-church"></i> Church Ministries</button>
        <button class="tab-btn <?php echo ($active_tab == 'development') ? 'active' : ''; ?>" data-tab="development"><i class="fa-solid fa-trowel-bricks"></i> Church Development</button>
    </div>

    <!-- Youth Content -->
    <div class="tab-content <?php echo ($active_tab == 'youth') ? 'active' : ''; ?>" id="youth">
        <div style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 3rem; align-items: center;">
            <div style="height: 380px; border-radius: 12px; background-image: url('https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&q=80&w=600'); background-size: cover; background-position: center;"></div>
            <div>
                <span class="section-subtitle">Ages 16 - 30+</span>
                <h2 style="color: var(--primary); margin-bottom: 1rem; font-size: 2rem;">Adventist Youth Ministries (AYM)</h2>
                <p style="color: var(--text-muted); margin-bottom: 1.25rem;">
                    The Adventist Youth Ministries department coordinates fellowship, bible study, missionary outreach, and leadership development programs. Our goal is to empower young adults to use their talents in service to God and humanity.
                </p>
                <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
                    We host weekly AY sessions every Sabbath afternoon at 4:00 PM, featuring lively discussions, guest speakers, debates, drama, and praise services. We also engage in community services (cleaning, visiting children's homes, hospital ministry) and yearly youth camps.
                </p>
                <div style="background-color: var(--bg-white); padding: 1.25rem; border-radius: 8px; border-left: 4px solid var(--accent); box-shadow: var(--shadow-sm); font-size: 0.95rem;">
                    <strong>AY Motto:</strong> "The love of Christ compels us." <br>
                    <strong>AY Aim:</strong> "The Advent Message to all the world in my generation."
                </div>
            </div>
        </div>
    </div>

    <!-- Clubs Content -->
    <div class="tab-content <?php echo ($active_tab == 'clubs') ? 'active' : ''; ?>" id="clubs">
        <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 3rem; align-items: center;">
            <div>
                <span class="section-subtitle">Ages 10 - 15</span>
                <h2 style="color: var(--primary); margin-bottom: 1rem; font-size: 2rem;">Pathfinder Club</h2>
                <p style="color: var(--text-muted); margin-bottom: 1.25rem;">
                    The **Pathfinder Club** (ages 10-15) is a worldwide organization sponsored by the Seventh-day Adventist Church. It operates similarly to scouting, but with a distinct spiritual focus.
                </p>
                <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
                    Through weekly meetings, outdoor camps, drills, honors classes, and community projects, children discover their God-given potential and develop life-saving skills. Honours covered include camping, first aid, astronomy, nature studies, and bible markings.
                </p>
                <div style="background-color: var(--bg-white); padding: 1.25rem; border-radius: 8px; border-left: 4px solid var(--primary); box-shadow: var(--shadow-sm); font-size: 0.95rem;">
                    <strong>Club Meeting Days:</strong> Sundays at 9:00 AM to 12:00 PM. <br>
                    <strong>Activities:</strong> Camporees, nature exploration, first-aid drills, physical fitness, and honors curriculum.
                </div>
            <div style="display: flex; gap: 2rem; justify-content: center; align-items: center; background-color: var(--bg-white); border-radius: 12px; padding: 2rem; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); height: 380px;">
                <img src="assets/images/pathfinder_logo.png" alt="Pathfinder Club Logo" style="max-height: 150px; width: auto; object-fit: contain;">
            </div>
        </div>
    </div>

    <!-- Children Content -->
    <div class="tab-content <?php echo ($active_tab == 'children') ? 'active' : ''; ?>" id="children">
        <div style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 3rem; align-items: center;">
            <div style="height: 380px; border-radius: 12px; background-color: var(--bg-white); border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; background-image: url('assets/images/adventurer_logo.png'); background-size: contain; background-repeat: no-repeat; background-position: center;">
                <span style="color: transparent; font-size: 0.9rem;">[Adventurous Club Logo Placeholder]</span>
            </div>
            <div>
                <span class="section-subtitle">Ages 0 - 9</span>
                <h2 style="color: var(--primary); margin-bottom: 1rem; font-size: 2rem;">Adventurous Club</h2>
                <p style="color: var(--text-muted); margin-bottom: 1.25rem;">
                    The Adventurous Club is committed to making church a fun, welcoming, and life-changing place for kids. We use age-appropriate activities to introduce children to the character of God.
                </p>
                <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
                    Every Sabbath morning at 9:00 AM, children engage in singing, quizzes, memory verse recitation, crafts, and interactive storytelling.
                </p>
            </div>
        </div>
    </div>

    <!-- General Content -->
    <div class="tab-content <?php echo ($active_tab == 'general') ? 'active' : ''; ?>" id="general">
        <div class="grid-3" style="margin-top: 1rem;">
            <!-- Sabbath School -->
            <div style="background-color: var(--bg-white); padding: 2rem; border-radius: 10px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
                <h3 style="color: var(--primary); margin-bottom: 1rem;"><i class="fa-solid fa-book-open" style="color: var(--accent);"></i> Sabbath School</h3>
                <p style="font-size: 0.95rem; color: var(--text-muted);">The primary Bible study school of the church. We meet in classes every Sabbath morning to read, discuss, and apply the weekly Sabbath School Lesson quarterly guide.</p>
            </div>

            <!-- AMO -->
            <div style="background-color: var(--bg-white); padding: 2rem; border-radius: 10px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
                <h3 style="color: var(--primary); margin-bottom: 1rem;"><i class="fa-solid fa-person" style="color: var(--primary);"></i> Men's Ministry (AMO)</h3>
                <p style="font-size: 0.95rem; color: var(--text-muted);">Adventist Men Organization (AMO) aims to unite men in service, strengthening them in their roles as spiritual leaders in their families, church, and local communities.</p>
            </div>

            <!-- Women -->
            <div style="background-color: var(--bg-white); padding: 2rem; border-radius: 10px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
                <h3 style="color: var(--primary); margin-bottom: 1rem;"><i class="fa-solid fa-person-dress" style="color: var(--accent);"></i> Women's Ministry</h3>
                <p style="font-size: 0.95rem; color: var(--text-muted);">Nurtures, supports, and inspires Seventh-day Adventist women in their daily spiritual walks, hosting prayer cells, seminars, and charity programs.</p>
            </div>

            <!-- Music -->
            <div style="background-color: var(--bg-white); padding: 2rem; border-radius: 10px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
                <h3 style="color: var(--primary); margin-bottom: 1rem;"><i class="fa-solid fa-music" style="color: var(--primary);"></i> Music & Choir</h3>
                <p style="font-size: 0.95rem; color: var(--text-muted);">Enhances our worship through uplifting hymns and spiritual songs. We have church choir groups, youth quartets, and children singing bands.</p>
            </div>

            <!-- Health -->
            <div style="background-color: var(--bg-white); padding: 2rem; border-radius: 10px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
                <h3 style="color: var(--primary); margin-bottom: 1rem;"><i class="fa-solid fa-heart-pulse" style="color: var(--accent);"></i> Health Ministry</h3>
                <p style="font-size: 0.95rem; color: var(--text-muted);">Promotes the holistic health message of the church, organizing medical camps, healthy living seminars, and temperance campaigns.</p>
            </div>

            <!-- Chaplaincy -->
            <div style="background-color: var(--bg-white); padding: 2rem; border-radius: 10px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
                <h3 style="color: var(--primary); margin-bottom: 1rem;"><i class="fa-solid fa-hands-praying" style="color: var(--primary);"></i> Personal Ministries</h3>
                <p style="font-size: 0.95rem; color: var(--text-muted);">Coordinates our evangelistic efforts, Bible study registrations, neighborhood campaigns, and visitor welcoming programs.</p>
            </div>
        </div>
    </div>

    <!-- Church Development Content -->
    <div class="tab-content <?php echo ($active_tab == 'development') ? 'active' : ''; ?>" id="development">
        <div style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 3rem; align-items: start;">
            <div style="background-color: var(--bg-white); padding: 2rem; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: var(--shadow-md);">
                <h3 style="color: var(--primary); margin-bottom: 1.5rem; text-align: center; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Sanctuary Construction Project</h3>
                <p style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 1rem;">
                    To accommodate our growing membership, the Membley SDA Church board is overseeing a development project to construct a new multi-purpose sanctuary.
                </p>
                <ul style="padding-left: 1.25rem; font-size: 0.95rem; color: var(--text-muted); margin-bottom: 1.5rem;">
                    <li style="margin-bottom: 0.5rem;">Phase 1: Foundation and Pillar works (Completed)</li>
                    <li style="margin-bottom: 0.5rem;">Phase 2: Main Walls and Roofing (In Progress)</li>
                    <li style="margin-bottom: 0.5rem;">Phase 3: Finishes, Windows, Doors, Pews & Sound</li>
                </ul>
                <div style="background-color: #f1f5f9; padding: 1rem; border-radius: 6px; font-size: 0.9rem; text-align: center; margin-bottom: 1.5rem;">
                    <strong>Target Fund:</strong> KES 25,000,000 | <strong>Raised:</strong> KES 15,400,000
                </div>
                <a href="giving.php" class="btn btn-accent" style="width: 100%;"><i class="fa-solid fa-hand-holding-dollar"></i> Support the Building Fund</a>
            </div>
            
            <div>
                <h2 style="color: var(--primary); margin-bottom: 1rem; font-size: 2rem;">Development Committee</h2>
                <p style="color: var(--text-muted); margin-bottom: 1.25rem;">
                    The Development Committee is comprised of dedicated elders, engineers, planners, and volunteers from the congregation who plan, design, and manage the execution of our construction works. We prioritize transparency, quality construction, and stewardship of the congregation’s funds.
                </p>
                <p style="color: var(--text-muted); margin-bottom: 1.25rem;">
                    Members and well-wishers can support either through financial donations, supply of building materials, or volunteering services on community work days (Bulky Sabbath / Development Sundays). Let us build together for the honor of our Lord!
                </p>
                <blockquote style="border-left: 4px solid var(--primary); padding-left: 1.5rem; font-style: italic; color: var(--text-muted); font-size: 1.1rem; margin: 2rem 0;">
                    "Let us rise up and build. So they strengthened their hands for this good work." <br>
                    <span style="font-size: 0.9rem; font-weight: 700; color: var(--primary); font-style: normal;">— Nehemiah 2:18</span>
                </blockquote>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
