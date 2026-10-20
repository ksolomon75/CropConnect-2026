# VDI-Starter-v5 Theme Reference

> Quick-lookup reference for the VDI-Starter-v5 WordPress theme. Covers hooks, filters, design tokens, CSS architecture, JS modules, helper functions, class APIs, CLI commands, deployment, and testing.

---

## Table of Contents

- [Hooks and Filters](#hooks-and-filters)
  - [hooks.php (BasicWP Namespace)](#hooksphp-basicwp-namespace)
  - [extras.php](#extrasphp)
  - [helpers.php](#helpersphp)
  - [class-enqueue.php](#class-enqueuephp)
  - [class-breadcrumbs.php](#class-breadcrumbsphp)
  - [class-resources.php](#class-resourcesphp)
- [theme.json Design Tokens](#themejson-design-tokens)
  - [Colors](#colors)
  - [Font Sizes](#font-sizes)
  - [Font Family](#font-family)
  - [Layout](#layout)
  - [Spacing Units](#spacing-units)
- [CSS Architecture](#css-architecture)
  - [Import Order](#import-order)
  - [Adding a New Stylesheet](#adding-a-new-stylesheet)
- [JS Module Dependency Graph](#js-module-dependency-graph)
  - [Script Module IDs](#script-module-ids)
- [Navigation Class API](#navigation-class-api)
- [Helper Functions](#helper-functions)
- [Class Reference](#class-reference)
- [CLI Commands](#cli-commands)
- [Deployment (GitHub Actions)](#deployment-github-actions)
- [Testing](#testing)

---

## Hooks and Filters

### hooks.php (BasicWP Namespace)

All hooks in this file live under the `BasicWP` namespace.

| Hook | Type | Priority | Args | Description |
|------|------|----------|------|-------------|
| `wp_head` | Action | 0 | — | Adds Google Fonts `<link rel="preconnect">` tags |
| `register_nav_menus()` | Direct call | — | — | Registers three menus: `main_navigation`, `aux_navigation`, `footer_navigation` |
| `widgets_init` | Action | — | — | Registers four sidebars: `sidebar-primary`, `sidebar-page`, `footer-1`, `footer-2`, `footer-3` |
| `wp_title` | Filter | — | — | Formats page titles as `Site Name: Page Title` |
| `excerpt_more` | Filter | — | — | Replaces default excerpt ellipsis with `&hellip;` |
| `include_page_title_in_hero` | Filter | — | — | Removes page title from hero section on single posts |
| `init` | Action | 1 | — | Aggressive WP cleanup and theme support registration (see below) |
| `wp_check_filetype_and_ext` | Filter | 10 | 4 | Allows SVG uploads (fix for WP 4.7.1 filetype check) |
| `upload_mimes` | Filter | — | — | Adds `svg` mime type (`image/svg+xml`) |
| `admin_head` | Action | — | — | Injects CSS to fix SVG display in the admin area |
| `wp_mail_from` | Filter | — | — | Sets the `From` email address to the admin email |
| `wp_mail_from_name` | Filter | — | — | Sets the `From` name to the blog name |

**The `init` (priority 1) hook performs aggressive cleanup:**

Removes:
- Emoji detection and styles (`remove_action` on `wp_head`)
- Block library styles (`wp-block-library`)
- Global styles (`global-styles`)
- REST API link tag from `wp_head`
- REST API link tag from `template_redirect`
- XML-RPC link (`rsd_link`)
- WP generator tag (`wp_generator`)
- WLW manifest link (`wlwmanifest_link`)

Adds theme supports:
- `post-thumbnails`
- `title-tag`
- `html5` (search-form, comment-form, comment-list, gallery, caption, style, script)
- `align-wide`
- `editor-styles`
- `responsive-embeds`
- `customize-selective-refresh-widgets`

---

### extras.php

| Hook / Filter | Type | Description |
|---------------|------|-------------|
| `hasSidebar` | Filter | Controls sidebar display based on an ACF true/false field on the current page |
| `body_class` | Filter | Appends `has-sidebar` class to `<body>` when a sidebar is present |
| `the_content` | Filter | `divWrapper()` wraps `<iframe>` and embed elements in `<div class="embed">` |
| `acf/include_fields` | Action | Registers "Page Sidebar" ACF field group (a true/false toggle for pages) |
| `init` | Action | `createOwnerRole()` creates the Owner role on every init |

**Owner role details:** The Owner role is equivalent to Administrator minus plugin management, theme management, and core update capabilities.

---

### helpers.php

| Hook / Filter | Type | Priority | Args | Description |
|---------------|------|----------|------|-------------|
| `custom_menu_order` | Filter | 10 | 1 | Enables custom admin menu ordering |
| `menu_order` | Filter | 10 | 1 | Defines the custom admin menu order |
| `block_categories_all` | Filter | 10 | — | Adds the `vdi-blocks` category to the block editor |
| `init` | Action | — | — | Registers the ACF "Global Fields" options page |

---

### class-enqueue.php

| Hook | Type | Method | Description |
|------|------|--------|-------------|
| `wp_enqueue_scripts` | Action | `enqFEAssets()` | Loads frontend CSS and JS |
| `admin_enqueue_scripts` | Action | `enqBEAssets()` | Loads admin CSS and JS |
| `enqueue_block_editor_assets` | Action | `enqEditorAssets()` | Loads block editor CSS |

---

### class-breadcrumbs.php

The `Breadcrumbs` class generates Schema.org-compatible breadcrumb markup. Context-specific methods:

| Method | Context |
|--------|---------|
| `getHomeBreadcrumb()` | Site front page |
| `getBlogPostsIndexBreadcrumb()` | Blog posts index |
| `getSinglePostBreadcrumbs()` | Single post |
| `getCustomPostTypeBreadcrumbs()` | Custom post type single |
| `getStaticPageBreadcrumbs()` | Static page |
| `getTaxonomyArchiveBreadcrumb()` | Taxonomy archive |
| `getPostTypeArchiveBreadcrumb()` | Post type archive |
| `getDateArchiveBreadcrumbs()` | Date archive (day/month/year) |
| `getSearchBreadcrumb()` | Search results |
| `get404Breadcrumb()` | 404 page |

---

### class-resources.php

| Hook | Type | Description |
|------|------|-------------|
| `init` | Action | Registers the `resources` custom post type |
| `post_type_link` | Filter | Customizes resource permalinks to `/resources/{term-slug}/{post-name}` |

The `resources` CPT uses a URL rewrite pattern that incorporates the first taxonomy term slug into the permalink path.

---

## theme.json Design Tokens

### Colors

All theme colors map to CSS custom properties. Use the CSS variable in your stylesheets or reference the slug in the block editor.

| Slug | CSS Variable | Name | Value |
|------|-------------|------|-------|
| `black` | — | Black | `#000` |
| `white` | — | White | `#fff` |
| `theme-bg` | `var(--color-background)` | Theme Background | Dynamic |
| `theme-text` | `var(--color-text)` | Theme Text | Dynamic |
| `theme-primary` | `var(--color-primary)` | Theme Primary | Dynamic |
| `theme-secondary` | `var(--color-secondary)` | Theme Secondary | Dynamic |
| `theme-bodylinks` | `var(--color-bodylinks)` | Theme Body Links | Dynamic |
| `theme-footerlinks` | `var(--color-footlinks)` | Theme Footer Links | Dynamic |
| `theme-success` | `var(--color-success)` | Theme Success | Dynamic |
| `theme-warning` | `var(--color-warning)` | Theme Warning | Dynamic |
| `theme-danger` | `var(--color-danger)` | Theme Danger | Dynamic |
| `theme-info` | `var(--color-info)` | Theme Info | Dynamic |

The dynamic colors (`theme-*`) resolve to CSS custom properties that can be overridden per-site in the customizer or ACF options pages. The static colors (`black`, `white`) are literal hex values.

**Usage example in CSS:**

```css
.my-element {
  color: var(--color-primary);
  background-color: var(--color-background);
}
```

**Usage in a block template:**

```html
<div class="has-theme-primary-color has-theme-bg-background-color">
  ...
</div>
```

---

### Font Sizes

| Slug | CSS Variable | Name |
|------|-------------|------|
| `base` | `var(--text-base)` | Base |
| `text-14px` | `var(--text-14px)` | Text 14px |
| `text-16px` | `var(--text-16px)` | Text 16px |
| `text-18px` | `var(--text-18px)` | Text 18px |
| `text-20px` | `var(--text-20px)` | Text 20px |
| `text-22px` | `var(--text-22px)` | Text 22px |
| `text-25px` | `var(--text-25px)` | Text 25px |
| `text-30px` | `var(--text-30px)` | Text 30px |
| `text-35px` | `var(--text-35px)` | Text 35px |
| `text-38px` | `var(--text-38px)` | Text 38px |
| `text-40px` | `var(--text-40px)` | Text 40px |
| `text-45px` | `var(--text-45px)` | Text 45px |
| `text-50px` | `var(--text-50px)` | Text 50px |
| `text-70px` | `var(--text-70px)` | Text 70px |
| `text-75px` | `var(--text-75px)` | Text 75px |

**Usage example in CSS:**

```css
.hero-title {
  font-size: var(--text-50px);
}
```

**Usage in a block template:**

```html
<p class="has-text-50px-font-size">Big headline text</p>
```

---

### Font Family

| Slug | CSS Variable | Name |
|------|-------------|------|
| `theme-sans` | `var(--font-sans)` | Theme Sans |

```css
body {
  font-family: var(--font-sans);
}
```

---

### Layout

| Setting | Value |
|---------|-------|
| `contentSize` | `100%` |
| `wideSize` | `1536px` |

These control the WordPress block editor content width and wide-alignment width.

---

### Spacing Units

Available spacing units for the block editor spacing scale:

`px`, `em`, `rem`, `vh`, `vw`, `%`

---

## CSS Architecture

The entry point is `styles/theme.css`. All imports use the CSS `@import` syntax processed by Tailwind CSS v4.

### Import Order

```
styles/theme.css
|
+-- @import "tailwindcss"                          # Tailwind CSS v4 base
|
+-- @import "./base/index.css"                     # Base styles
|   +-- break-out.css
|   +-- colors.css
|   +-- forms.css
|   +-- global.css
|   +-- misc.css
|   +-- prose.css
|   +-- skip-link.css
|   +-- typography.css
|
+-- @import "./navigation/index.css"                # Navigation styles
|   +-- nav-aux.css
|   +-- nav-footer.css
|   +-- nav-functional.css
|   +-- nav-main-default.css
|   +-- nav-main-mega.css
|   +-- nav-mobile-accordion.css
|   +-- nav-mobile-sliding.css
|
+-- @import "./fonts/lineicons.css"                 # Icon font
|
+-- @import "./base/break-out.css"                  # Break-out utilities (repeated for cascade)
|
+-- @import "./components/index.css"                # Component styles
|   +-- breadcrumbs.css
|   +-- pagination.css
|   +-- post-list.css
|   +-- sidebar.css
|   +-- site-footer.css
|   +-- site-header.css
|
+-- @import "./blocks/index.css"                    # Block styles
|   +-- buttons.css
|   +-- core.css
|
+-- @plugin "@tailwindcss/typography"               # Tailwind prose plugin
```

### Adding a New Stylesheet

1. Create the `.css` file in the appropriate directory (`base/`, `navigation/`, `components/`, or `blocks/`).
2. Add an `@import` line to that directory's `index.css`.

Example -- adding a new `cards.css` component:

```css
/* styles/components/index.css */
@import "cards.css";
```

```css
/* styles/components/cards.css */
.card {
  /* styles here */
}
```

---

## JS Module Dependency Graph

```
theme.js (entry point)
├── Navigation.js        # Mobile menu, sliding viewport, keyboard nav
├── backToTop.js          # BackToTopButton custom element
├── button.js             # ButtonComponent custom element, registerButtonComponent
├── GetHeaderHeight.js   # Sets --header-height CSS variable
└── TagExternalLinks.js   # Adds target="_blank" rel="noopener" to external links

admin.js
└── button.js             # ButtonComponent for editor context
```

### Script Module IDs

WordPress registers these script modules via `wp_register_script_module()`:

| Module ID | Source | Dependencies |
|-----------|--------|--------------|
| `basicwp-theme` | `theme.js` | None |
| `basicwp-button` | `button.js` | `basicwp-theme` |
| `basicwp-admin` | `admin.js` | `basicwp-button` |

**Loading in a template:**

```php
wp_enqueue_script_module('basicwp-theme');
wp_enqueue_script_module('basicwp-button');
```

---

## Navigation Class API

The `Navigation` class is located at `static/js/modules/Navigation.js`.

### Constructor

```js
const nav = new Navigation(toggleId, menuSelector);
// toggleId:      ID of the hamburger toggle button (e.g., 'menu-toggle')
// menuSelector:  CSS selector for the nav menu container (e.g., '.nav-main')
```

### Methods

| Method | Description |
|--------|-------------|
| `desktopMenuDropdowns()` | Enables dropdown menus for desktop navigation |
| `mobileMenuToggle()` | Toggles the mobile hamburger menu open/closed |
| `initializeSlidingViewport()` | Sets up the sliding mobile menu structure |
| `navigateToLevel(level)` | Navigates the sliding menu to a specific depth level |
| `navigateBack()` | Goes back one level in the sliding menu |
| `animateToLevel(level)` | Animates the sliding transition to a target depth level |
| `resetSlidingNavigation()` | Resets sliding nav to the root level |
| `cleanupSlidingStructure()` | Removes sliding nav DOM elements (cleanup/teardown) |
| `createBackButton(label)` | Creates a back button element for the sliding menu |
| `setupSlidingClickHandlers()` | Attaches click handlers for sliding menu items |
| `shouldEnableSlidingViewport()` | Returns `true` if the current viewport width warrants the sliding menu |

**Usage example:**

```js
import Navigation from './modules/Navigation.js';

const nav = new Navigation('menu-toggle', '.nav-main');
nav.desktopMenuDropdowns();
nav.mobileMenuToggle();
nav.initializeSlidingViewport();
```

---

## Helper Functions

| Function | File | Signature | Description |
|----------|------|-----------|-------------|
| `getFieldValue` | helpers.php | `getFieldValue($field_path)` | Retrieves nested ACF values using dot notation. E.g., `getFieldValue('contact_info.phone')` resolves `get_field('contact_info', 'option')['phone']`. Uses `'option'` for Global Fields. |
| `blockWrapperAttributes` | helpers.php | `blockWrapperAttributes($classes, $is_preview)` | Returns block wrapper attributes. In preview mode returns `class="..."`; on frontend returns `get_block_wrapper_attributes()`. |
| `customMenuOrder` | helpers.php | `customMenuOrder($menu_ord)` | Customizes WordPress admin menu order. |
| `blockCategories` | helpers.php | `blockCategories($categories)` | Adds the `vdi-blocks` category to the block editor. |
| `consoleLog` | helpers.php | `consoleLog($data)` | Outputs data to the browser console via `<script>console.log()</script>`. |
| `customExcerpt` | helpers.php | `customExcerpt($text, $number_of_words, $more)` | Generates custom excerpts that end at sentence boundaries instead of mid-sentence. |
| `escEmbeds` | helpers.php | `escEmbeds()` | Returns an allowed HTML array for iframe/embed content (used with `wp_kses`). |
| `strposArray` | helpers.php | `strposArray($haystack, $needles, $offset)` | Finds the position of the first occurrence of any needle from an array. |
| `getChildrenPages` | extras.php | `getChildrenPages()` | Gets child pages of the current page, sorted by `menu_order`. |
| `hasSidebar` | extras.php | `hasSidebar()` | Checks if the current page should render a sidebar (controlled by ACF field). |
| `hasPageHeader` | extras.php | `hasPageHeader()` | Checks if the page should render a page header (based on `hero_style` ACF field). |
| `createOwnerRole` | extras.php | `createOwnerRole()` | Creates the Owner role (admin minus plugin/theme/core management). Runs on every `init`. |
| `getTheTitle` | extras.php | `getTheTitle()` | Gets the appropriate title for the current context (home, single, archive, search, 404). |
| `divWrapper` | extras.php | `divWrapper($content)` | Wraps iframes and embeds in `<div class="embed">`. |

**Usage examples:**

```php
// Get a nested ACF option field
$phone = getFieldValue('contact_info.phone');

// Block wrapper attributes (works in both editor and frontend)
$attrs = blockWrapperAttributes('my-block-class', $is_preview);
echo '<div ' . $attrs . '>';

// Custom excerpt ending at sentence boundaries
$excerpt = customExcerpt(get_the_content(), 30, '...');
```

---

## Class Reference

| Class | File | Key Methods | Description |
|-------|------|-------------|-------------|
| `Enqueue` | `class-enqueue.php` | `enqFEAssets()`, `enqBEAssets()`, `enqEditorAssets()` | Manages all asset loading: frontend, admin, and editor |
| `ACF` | `class-acf.php` | `saveJson($path)`, `loadJson($paths)` | Sets ACF JSON save/load paths for field group synchronization |
| `Breadcrumbs` | `class-breadcrumbs.php` | `generate()`, `render()`, plus per-context methods (see below) | Generates Schema.org-compatible breadcrumb markup |
| `MenuItems` | `class-menuitems.php` | `render()` | Renders nav menu items using `$views . '/components/menu-items/index.php'` |
| `Resources` | `class-resources.php` | CPT registration, `postTypeLink` filter | Registers the `resources` CPT with custom permalink structure |
| `ShowTemplate` | `class-show-template.php` | HTML comment in footer | Adds an HTML comment to the footer showing the active template path (debugging) |

### Breadcrumbs Method Details

| Method | Returns | Description |
|--------|---------|-------------|
| `generate()` | `array` | Builds breadcrumb data array for the current context |
| `render()` | `string` | Outputs breadcrumb HTML with Schema.org markup |
| `getHomeBreadcrumb()` | `array` | Breadcrumb for the front page |
| `getBlogPostsIndexBreadcrumb()` | `array` | Breadcrumb for the blog posts index |
| `getSinglePostBreadcrumbs()` | `array` | Breadcrumbs for a single post (includes category) |
| `getCustomPostTypeBreadcrumbs()` | `array` | Breadcrumbs for a custom post type single |
| `getStaticPageBreadcrumbs()` | `array` | Breadcrumbs for a static page (includes parent pages) |
| `getTaxonomyArchiveBreadcrumb()` | `array` | Breadcrumb for a taxonomy archive |
| `getPostTypeArchiveBreadcrumb()` | `array` | Breadcrumb for a post type archive |
| `getDateArchiveBreadcrumbs()` | `array` | Breadcrumbs for date archives (day/month/year) |
| `getSearchBreadcrumb()` | `array` | Breadcrumb for search results |
| `get404Breadcrumb()` | `array` | Breadcrumb for 404 pages |

**Usage example:**

```php
$breadcrumbs = new Breadcrumbs();
echo $breadcrumbs->render();
```

---

## CLI Commands

| Command | Description |
|---------|-------------|
| `npm run build` | Compiles Tailwind CSS v4 from `styles/theme.css` to `static/dist/theme.css` with `--optimize` |
| `npm run start` | Starts BrowserSync dev server with live reloading (alias for `npm run watch`) |
| `npm run watch` | Runs `.watch.js` -- BrowserSync with CSS injection on changes |
| `composer lint` | Runs PHP_CodeSniffer against WordPress coding standards; outputs to `phpcs-results.txt` |
| `composer fix` | Auto-fixes PHPCS violations |
| `npx playwright test` | Runs Playwright accessibility tests |
| `npx playwright test --ui` | Opens Playwright interactive UI |

---

## Deployment (GitHub Actions)

The deployment workflow is defined in `.github/workflows/wpengine.yml`.

| Setting | Value |
|---------|-------|
| Trigger | `workflow_dispatch` (manual). Push to `main` trigger is commented out. |
| Skip condition | Commits containing `#skipGA` in the message are skipped |
| Target path | `wp-content/themes/vdi-v5` |
| WP Engine environment | `vdiv5` |
| SSH key secret | `WPE_SSHG_KEY_PRIVATE` |

### Deployment Steps

1. **Checkout** the repository
2. **Composer install** -- `composer install`
3. **npm install** -- `npm install`
4. **Build** -- `npm run build`
5. **Remove node_modules** -- deleted before deploy
6. **rsync** to WP Engine

### rsync Flags

```
-azvr --inplace --delete --exclude=".*"
```

| Flag | Meaning |
|------|---------|
| `-a` | Archive mode (preserve permissions, timestamps, etc.) |
| `-z` | Compress during transfer |
| `-v` | Verbose output |
| `-r` | Recursive |
| `--inplace` | Update files in-place on the target |
| `--delete` | Remove files on target that no longer exist in source |
| `--exclude=".*"` | Exclude dotfiles (e.g., `.git`, `.env`) |

---

## Testing

### Accessibility Tests

```bash
npx playwright test
```

Runs `tests/site-a11y.spec.js` using `@axe-core/playwright`. Tests scan pages for WCAG violations.

```bash
npx playwright test --ui
```

Opens the Playwright interactive UI for step-by-step test debugging.

### PHP Linting

```bash
composer lint
```

Runs PHP_CodeSniffer against WordPress coding standards. Results are written to `phpcs-results.txt`.

```bash
composer fix
```

Auto-fixes PHPCS violations where possible.

### Playwright Configuration

The Playwright config (`playwright.config.js`) is currently set to run on **Chromium only**. Firefox and WebKit browsers are commented out but available for enabling.