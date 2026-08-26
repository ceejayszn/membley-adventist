# Membley Adventist Church — Project Rulebook & Style Guidelines

This rulebook defines the architectural, design, and functional standards established for the Membley Adventist Church platform. All future development and modifications must strictly adhere to these rules.

---

## 1. Color Palette & Theme Rules
- **Official Palette**:
  - `var(--primary)`: `#002f5d` (Official Church Dark Navy)
  - `var(--primary-dark)`: `#001a35` (Deep Navy Background)
  - `var(--primary-light)`: `#0d4b85` (Theme Blue Button / Highlight)
  - `var(--accent)`: `#d99e1a` (Official Gold from Church Logo)
  - `var(--accent-hover)`: `#b88310`
  - `var(--bg-light)`: `#f8fafc`
  - `var(--bg-white)`: `#ffffff`
  - `var(--border-color)`: `#e2e8f0`
- **Simplicity & Contrast Constraints**:
  - **No weird neon greens, bright orange gradients, or rainbow styles.** Use only the official palette above.
  - **No glowing / pulsating animations or shimmers** (`shimmerGlow`, heavy glow box-shadows). Keep aesthetics clean, solid, and professional.
  - **High Contrast Rule**: Never place white text on white backgrounds or dark text on dark backgrounds. On dark cards, use pure white `#ffffff` or `#f8fafc` text.

---

## 2. Event Showcase & Poster Handling
- **Never Crop Posters**: Event flyers/posters must always stay 100% full, sharp, uncropped, and rendered in their natural aspect ratio:
  ```css
  max-width: 440px;
  width: 100%;
  height: auto;
  object-fit: contain;
  ```
- **Text Enclosure**: Event details (Presenter, Title, Subtitle, 10 Yrs Badge, Date, Time, Venue) must be enclosed inside a structured card container (`.flyer-main-details`) rather than floating in open space.
- **Clickable Navigation**:
  - Poster on `index.php` links to `events.php`.
  - Poster on `events.php` links to `rsvp.php`.

---

## 3. Standard Buttons & Actions
- **RSVP / Fill Info Button (`.btn-fill-info`)**:
  - Background: Solid Theme Blue `var(--primary-light)` (`#0d4b85`)
  - Text: Pure White (`#ffffff`)
  - Hover: Darker Navy (`#0b3d6d`)
- **WhatsApp Share Button (`.btn-whatsapp-share`)**:
  - Background: Clean WhatsApp Green (`#16a34a`)
  - Text: Pure White (`#ffffff`)
  - Hover: `#15803d`
- **Border & Radius**: Clean, rounded pill shape or 6px-8px border radius with subtle shadows.

---

## 4. RSVP & Attendance Experience (`rsvp.php`)
- **Required Fields**: Full Name (`* required`) and Phone Number (`* required`).
- **Dynamic Additional Attendees**:
  - When **Number of Attendees is > 1** (e.g. 2, 3, 4, 5+), generate **mandatory text inputs for each attendee's name** (*Attendee 2 Full Name \**, *Attendee 3 Full Name \**).
- **Manual Church Entry**: Allow visitors/members to type church/congregation manually in a text field (no rigid pre-selected chips).
- **Tone**: Always positive and welcoming. Never include sad prompts, crying faces, or "No I Can't" rejection buttons.
- **Confirmation Screen**:
  - Spiritual blessing headline: *"✨ Feel at the feet of Jesus ✨"*
  - Scripture quote: **Psalm 95:1–2** (*"Come, let us sing for joy to the Lord..."*)
  - Quick WhatsApp invitation share button.

---

## 5. Tiny & Compact Footer
- The footer (`.main-footer`) must remain **tiny, compact, and take at most half a page height** when scrolled to the bottom.
- Spacing: `padding: 2.25rem 0 1.25rem 0; margin-top: 2rem;` on desktop; `padding: 1.25rem 0 0.75rem 0; margin-top: 1.5rem;` on mobile.
- On mobile screens (`max-width: 768px`), arrange columns into a compact 2-column grid instead of stacking 4 full-height columns.

---

## 6. Analytics & Device Tracking
- Every RSVP submission logs client device details: IP Address, Device Type (Mobile/Tablet/Desktop), Phone Model (iPhone, Samsung, Tecno, Infinix, Xiaomi, OPPO, Vivo, Pixel, etc.), Browser, OS, and Location via `detect_device_details()` and SQLite DB `includes/church.db`.
