# Get On Track — WordPress Theme

A premium WordPress theme for [getontrack.live](https://getontrack.live), promoting health, longevity, and peptide-based anti-aging wellness.

## Features

- **Full homepage** with hero, trust bar, benefits, peptide showcase, science section, process steps, testimonials, FAQ, and consultation CTA
- **Responsive design** optimized for mobile, tablet, and desktop
- **Customizer integration** for hero text, CTA buttons, contact info, and footer content
- **Modern aesthetic** with navy/teal/gold palette suited for medical wellness branding
- **Block editor support** via `theme.json`
- **Blog templates** for posts, archives, and search

## Installation

1. Copy the `getontrack` folder to `wp-content/themes/getontrack/`
2. In WordPress Admin, go to **Appearance → Themes** and activate **Get On Track**
3. Go to **Settings → Reading** and set "Your homepage displays" to **A static page**
4. Create a page titled "Home" and select it as the homepage
5. Go to **Appearance → Customize → Get On Track Settings** to edit hero text, contact details, and more
6. Set up navigation menus under **Appearance → Menus** (Primary and Footer locations)

## Customization

| Setting | Location |
|---------|----------|
| Logo | Appearance → Customize → Site Identity |
| Hero headline & description | Customize → Get On Track Settings → Hero Section |
| CTA button text & URL | Customize → Get On Track Settings → Branding |
| Contact email & phone | Customize → Get On Track Settings → Contact |
| Medical disclaimer | Customize → Get On Track Settings → Footer |

## File Structure

```
getontrack/
├── style.css              Theme header
├── functions.php          Theme setup & enqueues
├── theme.json             Block editor design tokens
├── front-page.php         Homepage template
├── header.php / footer.php
├── inc/
│   ├── customizer.php     Theme customizer settings
│   └── template-tags.php  Helpers, icons, default content
├── template-parts/
│   ├── section-*.php      Homepage sections
│   └── content*.php       Post templates
└── assets/
    ├── css/main.css       All frontend styles
    └── js/main.js         Navigation, animations, form
```

## Notes

- The consultation form is a front-end demo. Connect it to a form plugin (e.g. Contact Form 7, WPForms) or custom AJAX handler for production use.
- Add a `screenshot.png` (1200×900px) in the theme root for the theme preview thumbnail in WordPress admin.

## License

GNU General Public License v2 or later
