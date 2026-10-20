# Architecture

A deep dive into how VDI-Starter-v5 is organized, how it boots, and the conventions it uses.

## Table of Contents

- [Bootstrap Flow](#bootstrap-flow)
- [Architectural Layers](#architectural-layers)
- [Namespace Conventions](#namespace-conventions)
- [Global Variables](#global-variables)
- [WordPress Hooks Cleanup](#wordpress-hooks-cleanup)
- [Enqueue System](#enqueue-system)
- [theme.json Design System](#themjson-design-system)

## Bootstrap Flow

When WordPress loads a theme, it starts with `style.css` (for theme metadata) and `functions.php` (for logic). Here's exactly what happens in VDI-Starter-v5:

```
WordPress loads the theme
  │
  ├─ style.css          → Theme declaration (name, description, version)
  │
  └─ functions.php      → Entry point
       │
       ├─ namespace BasicWP
       │
       ├─ glob(__DIR__ . '/lib/*.php')   → Autoloads every PHP file in lib/
       │    ├─ activation.php            → Theme activation handler (runs once)
       │    ├─ class-acf.php             → ACF JSON sync paths
       │    ├─ class-breadcrumbs.php      → Breadcrumb generation
       │    ├─ class-enqueue.php         → Asset loading (CSS, JS, fonts)
       │    ├─ class-menuitems.php       → Nav menu rendering
       │    ├─ class-resources.php        → Custom post type
       │    ├─ extras.php                → Sidebar, page header, Owner role, etc.
       │    ├─ helpers.php                → Utility functions, globals, ACF options page
       │    ├─ hooks.php                  → WordPress hooks, cleanup, SVG support
       │    ├─ search-features.php        → Enhanced search
       │    └─ show-template.php          → Debug template path display
       │
       └─ regACFBlocks()                → Registers ACF blocks (init hook, priority 5)
            └─ Scans views/blocks/*/block.json (skips 'boilerplate')
```

The glob autoload means every file in `lib/` is loaded on every request. This is intentional — it keeps the architecture flat and predictable. If you add a new file to `lib/`, it's automatically available without modifying `functions.php`.

When the `init` hook fires (priority 1), `hooks.php::init()` runs its cleanup routine and adds theme supports. Then at priority 5, `regACFBlocks()` registers all ACF blocks. The `Enqueue` class constructor hooks into `wp_enqueue_scripts`, `admin_enqueue_scripts`, and `enqueue_block_editor_assets`.

## Architectural Layers

The theme is organized into seven distinct layers, each with a clear responsibility:

### 1. Entry Layer

The two files WordPress needs to recognize the theme:

| File | Purpose |
|------|---------|
| `functions.php` | Autoloads `lib/*.php`, registers ACF blocks on `init` |
| `style.css` | Theme declaration — name, description, version, author |

**Why it matters:** `functions.php` is the only file you should never edit directly. All logic lives in `lib/`.

### 2. Service Layer

PHP classes and utility functions in `lib/` that provide core functionality:

| File | Class/Function | Purpose |
|------|---------------|---------|
| `class-enqueue.php` | `Enqueue` | Loads all frontend CSS, JS, and fonts with cache-busting |
| `class-menuitems.php` | `MenuItems` | Resolves WordPress nav menus into renderable item trees |
| `class-breadcrumbs.php` | `Breadcrumbs` | Context-aware breadcrumb trails with Schema.org markup |
| `class-acf.php` | `ACF` | Configures ACF JSON save/load paths for version control |
| `class-resources.php` | `Resources` | Registers the "Resources" custom post type with URL rewriting |
| `class-resources.php` | `ShowTemplate` | Adds HTML comment to footer showing active template (debug) |
| `hooks.php` | `init()` | Aggressive WordPress cleanup, theme supports, SVG uploads |
| `helpers.php` | Various | `getFieldValue()`, `blockWrapperAttributes()`, `blockCategories()`, etc. |
| `extras.php` | Various | `createOwnerRole()`, `hasSidebar()`, `hasPageHeader()`, `divWrapper()` |
| `search-features.php` | Various | `pageSearch()`, `dedupe()`, `postSort()`, `searchResultFilter()` |
| `activation.php` | Various | Auto-installs plugins, creates pages, configures settings on theme activation |

### 3. UI Templates and Components

WordPress template hierarchy files, ACF blocks, reusable components, and icons:

**Template Hierarchy:**

| File | WordPress Template For |
|------|----------------------|
| `front-page.php` | The front page |
| `index.php` | Blog posts listing (fallback for all) |
| `single.php` | Individual posts |
| `page.php` | Static pages (with optional sidebar) |
| `search.php` | Search results |
| `404.php` | Page not found |
| `header.php` | Site header (included by other templates) |
| `footer.php` | Site footer (included by other templates) |
| `sidebar.php` | Primary sidebar |
| `sidebar-page.php` | Page-specific sidebar |

**ACF Blocks (in `views/blocks/`):**

Each block follows a consistent three-file pattern: `block.json` (registration) + `{name}.php` (template) + `{name}.css` (styles).

| Block | Purpose |
|-------|---------|
| `accordion` | Collapsible content sections |
| `boilerplate` | Starting template for new blocks (not registered) |
| `button` | Single configurable button element |
| `buttons` | Container that restricts children to `button` blocks |
| `contact-info` | Contact information display |
| `grid` | Flexible grid layout (restricts children to `grid-cell`) |
| `grid-cell` | Individual grid item |
| `homepage-hero` | Hero section for the front page |
| `media-text` | Image/video with accompanying text |
| `media-text-innerblocks` | Media-text with nested block support |
| `page-children` | Displays child pages of the current page |
| `section` | Container with background and width options |

**Components (in `views/components/`):**

| Component | Purpose |
|-----------|---------|
| `nav-aux.php` | Auxiliary navigation bar (social links + search) |
| `nav-main.php` | Primary navigation menu |
| `nav-main__toggle.php` | Mobile menu toggle button |
| `menu-items/` | Recursive menu item rendering (index, has-children, single) |

**Partials (in `views/partials/`):**

| Partial | Purpose |
|---------|---------|
| `page-hero.php` | Page hero section |
| `social-media.php` | Social media links |

**Icons (in `views/icons/`):**

SVG icon partials for Facebook, Instagram, LinkedIn, Pinterest, Twitter, YouTube, and others. Each is a minimal PHP file that outputs an SVG element.

### 4. Styling

CSS is organized as a layered cascade, managed through Tailwind CSS v4:

```
styles/theme.css                 ← Entry point (imports everything below)
├── @import "tailwindcss"        ← Tailwind CSS v4 base
├── @import "./base/index.css"   ← Base styles barrel file
│   ├── break-out.css            ← Container break-out utilities
│   ├── colors.css               ← Color custom properties
│   ├── forms.css                ← Form element styles
│   ├── global.css               ← Global resets and base styles
│   ├── misc.css                 ← Miscellaneous utilities
│   ├── prose.css                ← Prose/typography styles
│   ├── skip-link.css            ← Accessibility skip link
│   └── typography.css           ← Typography scale and fonts
├── @import "./navigation/index.css"  ← Navigation barrel file
│   ├── nav-aux.css              ← Auxiliary nav
│   ├── nav-footer.css           ← Footer nav
│   ├── nav-functional.css       ← Functional nav styles
│   ├── nav-main-default.css     ← Default main nav
│   ├── nav-main-mega.css        ← Mega menu nav
│   ├── nav-mobile-accordion.css ← Accordion mobile nav
│   └── nav-mobile-sliding.css  ← Sliding mobile nav
├── @import "./fonts/lineicons.css"   ← Icon font (600+ glyphs)
├── @import "./base/break-out.css"    ← Break-out utilities (loaded after nav)
├── @import "./components/index.css"  ← Components barrel file
│   ├── breadcrumbs.css          ← Breadcrumbs
│   ├── pagination.css           ← Pagination
│   ├── post-list.css            ← Post listings
│   ├── sidebar.css              ← Sidebar
│   ├── site-footer.css          ← Footer
│   └── site-header.css          ← Header
└── @import "./blocks/index.css"      ← Block styles barrel file
    ├── buttons.css               ← Button styles with CSS custom properties
    └── core.css                  ← Core block overrides
```

**Why this structure?** The barrel files (`index.css`) make it easy to add or remove stylesheets without modifying `theme.css`. Navigation styles are grouped because you typically only use one variant (default vs mega menu, accordion vs sliding mobile). Block-specific CSS lives alongside each block in `views/blocks/` and is auto-loaded by WordPress when the block renders.

**Important:** There is no `tailwind.config.js`. Tailwind CSS v4 uses CSS-first configuration via `@import "tailwindcss"` and `@plugin` directives in `theme.css`. Custom colors, fonts, and spacing are defined in `theme.json` and `styles/base/colors.css`.

### 5. Client Scripts

JavaScript modules loaded via WordPress's `wp_enqueue_script_module()` API (requires WordPress 6.5+):

```
static/js/theme.js (entry point for frontend)
├── Navigation.js    → Mobile menu, sliding viewport, keyboard nav
├── backToTop.js      → BackToTopButton custom element
├── button.js         → ButtonComponent custom element (<x-button>)
├── GetHeaderHeight.js → Sets --header-height CSS variable
└── TagExternalLinks.js → Adds target="_blank" rel="noopener" to external links

static/js/admin.js (entry point for editor)
└── button.js         → ButtonComponent for editor context
```

**How script modules work:** WordPress's `wp_enqueue_script_module()` creates proper ES module dependencies. The `Enqueue` class registers `basicwp-theme` (theme.js) as a root module, and `basicwp-button` (button.js) declares a dependency on it. This means button.js won't load until theme.js has loaded — no more manual script ordering.

**Passive event listener polyfill:** `theme.js` includes a polyfill that makes scroll, touch, and mouse event listeners passive by default. This improves scrolling performance without requiring `addEventListener(..., { passive: true })` on every listener.

**Custom elements:** The `ButtonComponent` (`<x-button>`) and `BackToTopButton` (`<back-to-top>`) are Web Components registered via `customElements.define()`. They accept attributes for styling, URL, target, and behavior.

### 6. Data Layer

ACF field group JSON files in the `acf/` directory:

| File | Block/Feature |
|------|-------------|
| `group_5f7f85a2a3e13.json` | Accordion block fields |
| `group_5fd3e006e5da5.json` | Global Fields (site-wide contact, social, footer settings) |
| `group_600f5a9e242c3.json` | Grid block fields |
| `group_60106ed700da3.json` | Button block fields |
| `group_60bfb84ae973c.json` | Media Text block fields |
| `group_60bfdb328901d.json` | Section block fields |
| `group_6261bc658dd80.json` | Homepage Hero and Section fields |
| `group_645e51f721207.json` | Grid Cell block fields |
| `group_645e7cf448e66.json` | Contact Info block fields |

**Why JSON sync?** The `ACF` class in `class-acf.php` sets custom save/load paths so that field groups created in the WordPress admin are automatically saved as JSON files. This means:
- Field group configurations are version-controlled in Git
- Field groups survive database resets
- Multiple environments stay in sync
- You can edit field groups in code or in the admin UI

### 7. Infrastructure

Build, CI/CD, and configuration files that support development and deployment:

| File | Purpose |
|------|---------|
| `bin/.build.js` | Production build script — compiles Tailwind CSS with `--optimize` |
| `bin/.watch.js` | Development server — BrowserSync with live reload |
| `bin/.utils.js` | Shared utilities for build scripts (`tailwindToCSS`, debounce) |
| `.github/workflows/wpengine.yml` | Deploys to WP Engine via rsync on manual trigger |
| `.github/workflows/phpcs.yml` | Runs PHP CodeSniffer on pull requests |
| `.github/workflows/todos.yml` | Syncs code TODOs to GitHub Issues |
| `package.json` | Node dependencies and build/watch scripts |
| `composer.json` | PHP dependencies (PHP_CodeSniffer, WordPress coding standards) |
| `theme.json` | WordPress block editor configuration (colors, fonts, spacing) |
| `.phpcs.xml` | PHP CodeSniffer ruleset |
| `.env.example` | Environment variable template (`LOCALHOST_URL`, `BROWSERSYNC_PORT`) |
| `playwright.config.js` | Playwright accessibility test configuration |
| `tests/site-a11y.spec.js` | Accessibility test suite using @axe-core/playwright |

## Namespace Conventions

The project uses several naming conventions that can be confusing at first:

| Convention | Value | Where Used |
|-----------|-------|-----------|
| PHP namespace | `BasicWP` | All PHP files use `namespace BasicWP;` |
| Text domain | `basicwp` | WordPress translation functions (`__()` , `_e()`) |
| Block category | `vdi-blocks` | Groups custom blocks in the editor (defined in `helpers.php::blockCategories()`) |
| Script module IDs | `basicwp-theme`, `basicwp-button`, `basicwp-admin` | JavaScript module registration in `class-enqueue.php` |
| WP Engine folder | `vdi-v5` | Deployment target in `.github/workflows/wpengine.yml` |
| Git repo name | `VDI-Starter-v5` | The repository and theme directory name |

**The story:** "VDI" is Vincent Design Inc., the agency. "BasicWP" was the original internal code name. "vdi-blocks" and "vdi-v5" are deployment-facing names that align with the client's branding. They all refer to the same theme — just used in different contexts.

## Global Variables

Two global variables are defined in `helpers.php`:

```php
global $theme, $views;
$theme = get_template_directory();    // e.g., /var/www/wp-content/themes/VDI-Starter-v5
$views = $theme . '/views';           // e.g., /var/www/wp-content/themes/VDI-Starter-v5/views
```

**`$theme`** — Absolute path to the theme directory. Used when including files that need the full server path.

**`$views`** — Absolute path to the views directory. Used by `MenuItems::render()` to include navigation templates:
```php
include $views . '/components/menu-items/index.php';
```

These are available everywhere because `helpers.php` is loaded early via the glob autoload in `functions.php`.

## WordPress Hooks Cleanup

The `init()` function in `hooks.php` runs on every page load at priority 1. It performs aggressive cleanup of default WordPress output for performance and security reasons:

**What gets removed:**

| What | Why |
|------|-----|
| Emoji detection scripts & styles | Most sites don't use WordPress emojis; they add ~10KB to every page |
| `wp-block-library` styles | Theme provides its own block styles; core defaults add ~100KB |
| `global-styles` & `core-block-styles` | Theme overrides these via `theme.json` and custom CSS |
| `core-block-supports` | Duplicate of styling already handled by the theme |
| WordPress generator meta tag | Security — hides WordPress version from page source |
| RSD link | Rarely used XML-RPC discovery |
| WLW manifest | Windows Live Writer support (deprecated) |
| Shortlink | Removes `<link rel="shortlink">` from head |
| REST API link in head | The API still works; only the discoverable link is removed |
| oEmbed discovery links | Removes auto-embed discovery from head |
| Canonical URL | Theme handles SEO via The SEO Framework plugin |
| DNS prefetch hints | Removes unused `<link rel="dns-prefetch">` tags |
| XML-RPC | Disabled via `xmlrpc_enabled` filter — prevents brute-force attacks |
| Intrinsic image sizes | Prevents WordPress from adding width/height to img tags |
| Auto sizes | Prevents automatic `sizes` attribute on images |

**What gets added:**

| Feature | Why |
|---------|-----|
| `post-thumbnails` | Featured image support |
| `title-tag` | WordPress manages `<title>` tag |
| `html5` (caption, comment-form, comment-list, gallery, search-form, script, style) | Modern HTML5 markup |
| `align-wide` | Wide/full alignment for blocks |
| `editor-styles` | Theme styles appear in the block editor |
| `responsive-embeds` | Embeds respond to container width |
| `customize-selective-refresh-widgets` | Widget changes update without full page reload |
| SVG upload support | Allows SVG files in the media library |

**Important:** This cleanup is aggressive. If a plugin requires one of the removed features (like oEmbed discovery or XML-RPC), you'll need to comment out the corresponding `remove_action` line in `hooks.php`.

## Enqueue System

The `Enqueue` class (in `class-enqueue.php`) manages all asset loading:

### Frontend (`enqFEAssets()`)

| Asset | Method | Notes |
|-------|--------|-------|
| `static/dist/theme.css` | `wp_enqueue_style()` | Compiled Tailwind CSS, cache-busted with `filemtime()` |
| Raleway font | `wp_enqueue_style()` | Google Fonts with `preconnect` hint |
| `basicwp-theme` (theme.js) | `wp_enqueue_script_module()` | Frontend entry point |
| `basicwp-button` (button.js) | `wp_enqueue_script_module()` | Depends on `basicwp-theme` |
| jQuery | `wp_enqueue_script()` | Needed by downstream scripts; modules can't depend on classic scripts |

### Admin (`enqBEAssets()`)

| Asset | Method | Notes |
|-------|--------|-------|
| Raleway font | `wp_enqueue_style()` | Same Google Fonts |
| `styles/backend/admin.css` | `wp_enqueue_style()` | Admin-specific overrides |
| `basicwp-admin` (admin.js) | `wp_enqueue_script_module()` | Admin entry point |
| `basicwp-button` (button.js) | `wp_enqueue_script_module()` | Depends on `basicwp-admin` |

### Block Editor (`enqEditorAssets()`)

| Asset | Method | Notes |
|-------|--------|-------|
| Raleway font | `wp_enqueue_style()` | Same Google Fonts |
| `styles/backend/editor.css` | `wp_enqueue_style()` | Editor-specific styles, scoped to block editor |

**Cache busting:** All enqueued files use `filemtime()` as the version number. This means the browser cache is automatically busted whenever a file changes — no manual version bumps needed.

**Script modules:** The theme uses `wp_enqueue_script_module()` (WordPress 6.5+) instead of traditional `wp_enqueue_script()` for frontend and admin JavaScript. This creates proper ES module dependencies where `basicwp-button` won't load until `basicwp-theme` has loaded.

## theme.json Design System

The `theme.json` file (WordPress block editor v3 schema) defines the design system for both the block editor and the frontend:

### Colors

Colors are defined as CSS custom properties and mapped to WordPress editor slugs:

| Editor Slug | CSS Variable | Purpose |
|------------|-------------|---------|
| `black` | `#000` | Pure black |
| `white` | `#fff` | Pure white |
| `theme-bg` | `var(--color-background)` | Page background |
| `theme-text` | `var(--color-text)` | Body text |
| `theme-primary` | `var(--color-primary)` | Primary brand color |
| `theme-secondary` | `var(--color-secondary)` | Secondary brand color |
| `theme-bodylinks` | `var(--color-bodylinks)` | Body link color |
| `theme-footerlinks` | `var(--color-footlinks)` | Footer link color |
| `theme-success` | `var(--color-success)` | Success/positive |
| `theme-warning` | `var(--color-warning)` | Warning/caution |
| `theme-danger` | `var(--color-danger)` | Danger/error |
| `theme-info` | `var(--color-info)` | Informational |

The actual color values for the CSS variables are defined in `styles/base/colors.css`.

### Typography

One font family (`var(--font-sans)`) and 15 size presets:

| Slug | Variable | Typical Use |
|------|----------|-------------|
| `base` | `var(--text-base)` | Body text |
| `text-14px` | `var(--text-14px)` | Small text |
| `text-16px` | `var(--text-16px)` | Standard text |
| `text-18px` | `var(--text-18px)` | Large text |
| `text-20px` | `var(--text-20px)` | Subheadings |
| `text-22px` | `var(--text-22px)` | H4 |
| `text-25px` | `var(--text-25px)` | H3 |
| `text-30px` | `var(--text-30px)` | H2 |
| `text-35px` | `var(--text-35px)` | Large heading |
| `text-38px` | `var(--text-38px)` | H1 (small) |
| `text-40px` | `var(--text-40px)` | H1 |
| `text-45px` | `var(--text-45px)` | Hero heading |
| `text-50px` | `var(--text-50px)` | Large hero |
| `text-70px` | `var(--text-70px)` | Display size |
| `text-75px` | `var(--text-75px)` | Maximum display |

### Layout

| Property | Value | Meaning |
|----------|-------|---------|
| `contentSize` | `100%` | Default content width (full-width by default) |
| `wideSize` | `1536px` | Wide-alignment max width |

### Spacing

Available units: `px`, `em`, `rem`, `vh`, `vw`, `%`

### Global Styles

| Property | Value |
|----------|-------|
| Background | `var(--wp--preset--color--background)` |
| Text color | `var(--wp--preset--color--text)` |
| Link color | `var(--wp--preset--color--theme-bodylinks)` |
| Font family | `var(--wp--preset--font-family--theme-sans)` |
| Line height | `1.5` |

**Why `theme.json` matters:** Changes to this file immediately affect the block editor UI — colors appear in the palette, font sizes in the typography controls, and spacing in the spacing panel. This is the single source of truth for the design system, and CSS custom properties cascade from here into the frontend styles.