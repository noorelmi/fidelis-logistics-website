# 🚛 Fidelis Logistics | Professional Logistics Website

A modern, responsive multi-page website built for **Fidelis Logistics Inc.** | a Texas-based transportation and logistics company offering truckload, LTL, and supply chain solutions across the United States.

**🔗 Live Site:** [fidelistexas.com](https://fidelistexas.com)

---

## 📸 Preview

| Home | Services | Contact |
|------|----------|---------|
| *Hero slider, services grid, stats counter, CTA* | *Service cards, guarantees section* | *Contact form with SMTP, company info* |

---

## ✨ Features

- **6 Fully Responsive Pages** | Home, About, Services, Career, Contact, Request Quote
- **Hero Slider** | CSS-only slideshow with gradient overlay
- **Animated Stats Counter** | Scroll-triggered number animation
- **Scroll-Reveal Cards** | IntersectionObserver-powered fade-in animations
- **Mobile-First Design** | Hamburger menu, responsive grid, optimized for all devices
- **PHP Contact Forms** | Three forms (Contact, Quote, Job Application) with server-side validation
- **SMTP Email Delivery** | Google Workspace integration via PHPMailer
- **Anti-Spam Protection** | Honeypot fields + input sanitization
- **Privacy Policy Page** | SMS consent, data handling compliance
- **Corporate Blue Theme** | CSS custom properties for easy rebranding

---

## 🛠 Tech Stack

| Layer | Technology |
|-------|-----------|
| **Frontend** | HTML5, CSS3 (Grid, Flexbox, Custom Properties), Vanilla JavaScript |
| **Icons** | Font Awesome 6 |
| **Fonts** | Playfair Display, Inter (system fallback) |
| **Backend** | PHP 7.4+ |
| **Email** | PHPMailer 6.x with Google Workspace SMTP |
| **Server** | cPanel (Apache) |

---

## 📁 Project Structure

```
fidelis-logistics-website/
├── index.html                 # Homepage with hero slider
├── about-us.html              # Company overview, team, mission/vision
├── services.html              # Service cards, guarantees
├── career.html                # Job openings + application form
├── contact.html               # Contact form + company info
├── request-quote.html         # Quote request form
├── privacy-policy.html        # Privacy & SMS terms
├── css/
│   ├── style.css              # Main stylesheet (1400+ lines)
│   └── responsive.css         # Mobile/tablet breakpoints
├── js/
│   └── main.js                # Navigation, animations, form validation
├── php/
│   ├── mail-config.php        # SMTP settings (gitignored 🔒)
│   ├── mailer.php             # PHPMailer wrapper
│   ├── send-contact.php       # Contact form handler
│   ├── send-quote.php         # Quote form handler
│   ├── send-application.php   # Career application handler
│   ├── .htaccess              # Block direct access to config
│   └── PHPMailer/             # PHPMailer library
├── images/
│   ├── hero/                  # Slider backgrounds
│   ├── logos/                 # Brand assets
│   ├── team/                  # Leadership photos
│   ├── career/                # Career page images
│   └── content/               # Inline content images
└── .gitignore                  # Excludes sensitive config files
```

---

## 🚀 Deployment

This is a PHP-based website designed for Apache/Nginx hosting. Contact forms require PHPMailer and SMTP configuration.

> Detailed setup instructions are provided privately to the client.

---

## 🔒 Security

- `mail-config.php` is **gitignored** | never committed to version control
- `.htaccess` blocks direct web access to PHP config files
- All form inputs are sanitized server-side (XSS prevention)
- Honeypot anti-spam fields on all forms
- No database | submissions are emailed only, no data stored

---

## 📄 Pages

| Page | Description |
|------|-------------|
| **Home** | Hero slider, 4 service cards, stats counter, about preview, CTA |
| **About Us** | Company overview, mission/vision/values cards, leadership team, why choose us |
| **Services** | Detailed service cards, 6-point guarantees section |
| **Career** | Job listings, work-with-us banner, application form |
| **Contact** | Contact form with SMS consent, phone/email/location info |
| **Request Quote** | Detailed quote form with service type selection |
| **Privacy Policy** | Data handling, SMS terms, user rights |

---

## 👨‍💻 Developer

**Noorulhaq Elmi** | Digital Solutions Developer

- 🌐 [noorulhaqelmi.com](https://noorulhaqelmi.com)
- 💼 [LinkedIn](https://linkedin.com/in/noorulhaq-elmi)
- 🐙 [GitHub](https://github.com/noorelmi)

---

## 📝 License

This project is proprietary. All rights reserved by Fidelis Logistics Inc. Contact the developer for usage inquiries.
