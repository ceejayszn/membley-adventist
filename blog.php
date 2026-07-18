<?php
// blog.php
require_once 'includes/db.php';
require_once 'includes/header.php';

<!-- Coming Soon Section -->
<section class="section-padding container">
    <div style="text-align: center; max-width: 600px; margin: 0 auto; padding: 4rem 2rem; background-color: var(--bg-white); border-radius: 12px; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color);">
        <i class="fa-solid fa-pen-nib" style="font-size: 3rem; color: var(--accent); margin-bottom: 1.5rem;"></i>
        <h2 style="color: var(--primary); font-size: 2rem; margin-bottom: 1rem;">Blogs are Coming Soon!</h2>
        <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 2rem;">
            We are currently working on a brand new blog platform to share devotions, theological notes, and church news. Stay tuned!
        </p>
        <div style="background-color: #f1f5f9; padding: 1.5rem; border-radius: 8px; border-left: 4px solid var(--primary);">
            <h3 style="color: var(--primary); font-size: 1.1rem; margin-bottom: 0.5rem;"><i class="fa-solid fa-bullhorn"></i> Want to write a post?</h3>
            <p style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 0;">
                Any church member who wishes to post a blog or share an article can contact the <strong>Media Team</strong> for publishing details.
            </p>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
