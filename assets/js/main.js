// assets/js/main.js

document.addEventListener('DOMContentLoaded', function() {
    
    // --- Mobile Menu Toggle ---
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileNav = document.getElementById('mobileNav');
    
    if (mobileMenuBtn && mobileNav) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenuBtn.classList.toggle('open');
            mobileNav.classList.toggle('open');
        });
    }

    // --- Hero Image Slider ---
    const slides = document.querySelectorAll('.hero-slider .slide');
    if (slides.length > 1) {
        let currentSlide = 0;
        const slideInterval = setInterval(nextSlide, 6000); // Change image every 6 seconds

        function nextSlide() {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }
    }

    // --- Ministries Tabs System ---
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    if (tabButtons.length > 0 && tabContents.length > 0) {
        tabButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');

                // Remove active classes
                tabButtons.forEach(b => b.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));

                // Add active classes to current selection
                this.classList.add('active');
                const targetElement = document.getElementById(targetTab);
                if (targetElement) {
                    targetElement.classList.add('active');
                }
            });
        });
    }

    // --- Copy to Clipboard Helper ---
    const copyBtns = document.querySelectorAll('.copy-btn, .copy-chip');
    copyBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const textToCopy = this.getAttribute('data-copy');
            if (textToCopy) {
                navigator.clipboard.writeText(textToCopy).then(() => {
                    const originalHtml = this.innerHTML;
                    this.innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
                    setTimeout(() => {
                        this.innerHTML = originalHtml;
                    }, 2000);
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                });
            }
        });
    });
});

