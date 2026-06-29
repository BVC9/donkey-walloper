# Visual Builder — WordPress Page Builder Plugin

A Divi-style visual drag-and-drop page builder plugin for WordPress.

## Install
1. Zip the whole plugin folder so `visual-builder.php` is at the root of the zip (not inside an extra parent folder).
2. In WP Admin: Plugins → Add New → Upload Plugin → choose the zip → Install → Activate.
3. Open any Page or Post → you'll see a "Visual Builder" box in the editor → click
   **Launch Visual Builder**.

## How it works
- **Rows & Columns**: click "+ Add Row", pick a 1–4 column layout.
- **Modules**: hover a column → "+" icon → choose a module (Text, Heading, Button,
  Image, Video, Spacer, Gallery).
- **Editing**: click any module to open the right-hand settings panel (Divi-style
  dark sidebar) and edit content/colors/sizing live.
- **Drag & drop**: drag rows to reorder them; drag modules within or between columns.
- **Save**: click Save in the top bar — saves layout as JSON to post meta
  (`_vb_layout`) via AJAX.
- **Frontend**: when "Use Visual Builder" is checked, the saved layout replaces
  the normal post content output, rendered with `VB_Renderer`.

## File structure
```
visual-builder.php              Plugin bootstrap, post meta, content filter
includes/class-vb-modules.php   Module registry (fields/defaults per module type)
includes/class-vb-renderer.php  JSON layout -> frontend HTML
includes/class-vb-builder.php   Full-screen builder admin page + asset loading
includes/class-vb-ajax.php      AJAX save handler
assets/css/builder.css          Builder UI styling (dark sidebar, topbar, modals)
assets/css/frontend.css         Frontend rendering styles
assets/js/builder.js            Drag-and-drop engine (Sortable.js), settings panel
```

## Extending it
- **New module type**: add an entry to `VB_Modules::get_modules()` (fields +
  defaults), add a `case` in `VB_Renderer::render_module()`, and add a `case` in
  `moduleHtmlPreview()` in `builder.js`. No other wiring needed — it'll
  automatically show up in the "Add Module" picker.
- **Row/column-level design settings** (background image, custom CSS, responsive
  visibility, hover animations) are the natural next addition — the `settings`
  object already exists on rows/columns/modules for this.
- **Global templates/library**: store layouts as a custom post type so they can be
  reused across pages (currently layouts are per-page only).

## Known limitations of this first version
- No undo/redo history yet.
- No responsive (mobile/tablet) value overrides yet — frontend uses one CSS
  breakpoint that stacks columns under 768px.
- No nested rows/columns inside columns.
- Video module relies on `wp_oembed_get()`; raw MP4 needs a small renderer tweak.

This is a solid, working foundation you can keep building on — not a
feature-complete Divi replacement.

## 1.1.0 enhancements added

- Template / Layout Library button in the builder topbar.
- Three starter templates: Hero Opt-in, Sales Page Starter, Gallery Page.
- Desktop, Tablet and Mobile preview buttons in the builder.
- Responsive value override fields:
  - Heading: desktop/tablet/mobile font size.
  - Text: desktop/tablet/mobile font size.
  - Slider: desktop/tablet/mobile height.
- New modules:
  - Contact Form layout module.
  - Tabs module.
  - Accordion module.
  - Slider module.
- Frontend responsive CSS improvements for mobile columns, galleries, rows and module spacing.
- Frontend JavaScript for tabs and automatic slider rotation.

Note: the Contact Form module is a visual/layout form only. It does not yet send email or store submissions.

## 1.2.0 enhancements added

- Global Design System modal:
  - Primary color
  - Secondary color
  - Font family
  - Content width
  - Button radius
- Undo / Redo controls with lightweight in-session history.
- Duplicate controls for rows and modules.
- Responsive visibility per module:
  - all
  - desktop-only
  - tablet-only
  - mobile-only
  - hide-mobile
  - hide-tablet
  - hide-desktop
- Extra marketing/conversion modules:
  - Countdown Timer
  - Pricing Table
  - Testimonial
  - FAQ Schema accordion with JSON-LD output
- Saved layout format now supports global page settings as well as rows. Existing older array-only layouts are still supported.

Notes:
- Undo/redo history is in-session only; it resets when the builder is reloaded.
- The Contact Form remains a visual form and still does not submit/store leads.
- FAQ Schema outputs JSON-LD for the three FAQ items configured in the module.

## Version 1.3.0 refinements

This build adds a more practical professional workflow layer:

- Real drag-and-drop row and module reordering using the row toolbar as a drag handle.
- Saved reusable blocks: save any row/section and insert it later from the Saved Blocks library.
- Layout JSON import/export for moving designs between sites or backing up templates.
- Client Mode: lets clients edit simpler content modules while hiding structural controls that can break layouts.
- Working contact forms: AJAX submission, spam honeypot, admin email notification, and saved submissions under Tools > VB Form Submissions.
- Popup CTA module with button, timed, and exit-intent triggers.
- Animation controls: none, fade in, slide up, and zoom in.

Notes:
- Form submissions are stored in the WordPress options table and capped at the most recent 250 entries.
- Email delivery depends on the host WordPress mail configuration. For best reliability use an SMTP plugin.
- Popup exit intent is desktop-oriented; mobile devices do not consistently expose exit-intent behaviour.
