/* ============================================
   FIDELIS LOGISTICS - MAIN JAVASCRIPT
   ============================================ */

// DOM Elements
const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('.nav-menu');
const navLinks = document.querySelectorAll('.nav-menu a');

// Mobile Menu Toggle
if (hamburger) {
    hamburger.addEventListener('click', () => {
        navMenu.classList.toggle('active');
        hamburger.classList.toggle('active');
    });
}

// Close menu when link is clicked
navLinks.forEach(link => {
    link.addEventListener('click', () => {
        navMenu.classList.remove('active');
        hamburger.classList.remove('active');
    });
});

// Update active nav link on scroll
window.addEventListener('scroll', () => {
    let current = '';
    const sections = document.querySelectorAll('section');
    
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        if (pageYOffset >= sectionTop - 200) {
            current = section.getAttribute('id');
        }
    });
    
    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href').slice(1) === current) {
            link.classList.add('active');
        }
    });
});

// Form Validation
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function validatePhone(phone) {
    const phoneRegex = /^[\d\s\-\+\(\)]{10,}$/;
    return phoneRegex.test(phone.replace(/\s/g, ''));
}

function validateForm(form) {
    let isValid = true;
    const inputs = form.querySelectorAll('input, textarea, select');
    
    inputs.forEach(input => {
        const errorElement = input.parentElement.querySelector('.form-error');
        
        if (input.type === 'checkbox' && input.hasAttribute('required')) {
            if (!input.checked) {
                if (errorElement) {
                    errorElement.textContent = 'This field is required';
                }
                input.parentElement.classList.add('error');
                isValid = false;
            } else {
                input.parentElement.classList.remove('error');
                if (errorElement) {
                    errorElement.textContent = '';
                }
            }
        } else if (input.hasAttribute('required') && !input.value.trim()) {
            if (errorElement) {
                errorElement.textContent = 'This field is required';
            }
            input.parentElement.classList.add('error');
            isValid = false;
        } else if (input.type === 'email' && input.value && !validateEmail(input.value)) {
            if (errorElement) {
                errorElement.textContent = 'Please enter a valid email';
            }
            input.parentElement.classList.add('error');
            isValid = false;
        } else if (input.name === 'phone' && input.value && !validatePhone(input.value)) {
            if (errorElement) {
                errorElement.textContent = 'Please enter a valid phone number';
            }
            input.parentElement.classList.add('error');
            isValid = false;
        } else {
            input.parentElement.classList.remove('error');
            if (errorElement) {
                errorElement.textContent = '';
            }
        }
    });
    
    return isValid;
}

// Real-time validation
document.querySelectorAll('input, textarea, select').forEach(input => {
    input.addEventListener('blur', () => {
        const errorElement = input.parentElement.querySelector('.form-error');
        
        if (input.type === 'checkbox' && input.hasAttribute('required')) {
            if (!input.checked) {
                if (errorElement) {
                    errorElement.textContent = 'This field is required';
                }
                input.parentElement.classList.add('error');
            } else {
                input.parentElement.classList.remove('error');
                if (errorElement) {
                    errorElement.textContent = '';
                }
            }
        } else if (input.hasAttribute('required') && !input.value.trim()) {
            if (errorElement) {
                errorElement.textContent = 'This field is required';
            }
            input.parentElement.classList.add('error');
        } else if (input.type === 'email' && input.value && !validateEmail(input.value)) {
            if (errorElement) {
                errorElement.textContent = 'Please enter a valid email';
            }
            input.parentElement.classList.add('error');
        } else if (input.name === 'phone' && input.value && !validatePhone(input.value)) {
            if (errorElement) {
                errorElement.textContent = 'Please enter a valid phone number';
            }
            input.parentElement.classList.add('error');
        } else {
            input.parentElement.classList.remove('error');
            if (errorElement) {
                errorElement.textContent = '';
            }
        }
    });
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Fade in elements on scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

document.querySelectorAll('.service-card, .card, .team-member').forEach(el => {
    el.classList.add('fade-in');
    observer.observe(el);
});

// NOTE: Form submission is handled entirely by the inline script in each
// page (contact.html and request-quote.html). No generic form/button
// handlers here to avoid conflicts with the inline AJAX submission logic.

// Smooth scroll on page load
window.addEventListener('load', () => {
    const hash = window.location.hash;
    if (hash) {
        const target = document.querySelector(hash);
        if (target) {
            setTimeout(() => {
                target.scrollIntoView({ behavior: 'smooth' });
            }, 100);
        }
    }
});

// Add animation to stat cards
const statCards = document.querySelectorAll('.stat-card');
const animateStats = () => {
    statCards.forEach(card => {
        const h2 = card.querySelector('h2');
        if (h2 && h2.textContent.includes('+')) {
            const finalValue = parseInt(h2.textContent);
            let currentValue = 0;
            const increment = Math.ceil(finalValue / 50);
            
            const counter = setInterval(() => {
                currentValue += increment;
                if (currentValue >= finalValue) {
                    h2.textContent = finalValue + '+';
                    clearInterval(counter);
                } else {
                    h2.textContent = currentValue + '+';
                }
            }, 30);
        }
    });
};

// Trigger animation when stats section is visible
const statsSection = document.querySelector('.stats-section');
if (statsSection) {
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateStats();
                statsObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    
    statsObserver.observe(statsSection);
}

// Responsive menu for mobile
window.addEventListener('resize', () => {
    if (window.innerWidth > 768) {
        navMenu.classList.remove('active');
        hamburger.classList.remove('active');
    }
});

console.log('Fidelis Logistics - Website Loaded Successfully');
