# Getting Started with VDI-Starter-v5

This guide walks you through setting up a local development environment for the VDI-Starter-v5 WordPress theme from scratch. It covers prerequisites, installation, configuration, and common development workflows.

---

## Table of Contents

1. [Overview](#overview)
2. [Prerequisites](#prerequisites)
3. [Setting Up a Local WordPress Environment](#setting-up-a-local-wordpress-environment)
4. [Installing the Theme](#installing-the-theme)
5. [Environment Configuration](#environment-configuration)
6. [Building Assets](#building-assets)
7. [Activating the Theme](#activating-the-theme)
8. [Development Workflow](#development-workflow)
9. [Project Architecture](#project-architecture)
10. [Creating Custom ACF Blocks](#creating-custom-acf-blocks)
11. [Testing](#testing)
12. [Code Quality](#code-quality)
13. [Troubleshooting](#troubleshooting)

---

## Overview

VDI-Starter-v5 is a minimal WordPress theme designed as a starting point for custom theme development. It uses a modern stack:

- **Tailwind CSS v4** for utility-first styling, compiled via the Tailwind CLI
- **ACF Pro** for custom field management and block registration
- **WordPress Script Modules** (`wp_enqueue_script_module`) for JavaScript, requiring WordPress 6.5+
- **BrowserSync** for live-reloading during development
- **Playwright** for end-to-end accessibility testing
- **PHP_CodeSniffer** with WordPress coding standards for linting

The theme intentionally avoids heavyweight frameworks. Every PHP file in `lib/` is loaded automatically via `functions.php`, which means you add a new file to `lib/` and it is included -- no manual `require` statements needed.

---

## Prerequisites

Before you begin, make sure the following tools are installed on your machine.

| Tool        | Minimum Version | Why It Is Needed                                                                                     |
|-------------|-----------------|------------------------------------------------------------------------------------------------------|
| Node.js     | 22+             | Tailwind CSS v4 CLI requires a modern Node runtime. Older versions will fail during `npm run build`. |
| npm         | Latest (bundled with Node) | Package management for JavaScript dependencies and build scripts.                            |
| PHP         | 8.0+            | WordPress core requirement and theme compatibility.                                                 |
| Composer    | 2.x             | Installs PHP_CodeSniffer with WordPress coding standards for linting.                                |
| WordPress   | 6.5+            | The theme uses `wp_enqueue_script_module()`, which was introduced in WordPress 6.5.                 |
| ACF Pro     | Latest          | All custom blocks depend on ACF Pro. Blocks will not register without it.                           |

### Checking Your Versions

Run these commands to verify your environment:

```bash
node --version    # Should be v22 or higher
npm --version     # Any recent version is fine
php --version     # Should be 8.0 or higher
composer --version # Should be 2.x
```

If any of these commands fail, install the missing tool before proceeding.

---

## Setting Up a Local WordPress Environment

You need a running WordPress instance before you can activate or test the theme. Choose one of these options based on your preference and operating system.

### Option A: Local by Flywheel (Recommended for macOS/Windows)

Local by Flywheel (often just called "Local") is the easiest way to get a WordPress site running on macOS or Windows.

1. Download and install [Local](https://localwp.com/).
2. Click **Create a new site** and follow the prompts.
3. Choose **Preferred** environment (NGINX, PHP 8.x, MySQL 8.x).
4. Once the site is created, note the local URL (e.g., `https://vdi-starter.local`).
5. Click **WP Admin** to open the WordPress dashboard.

The site URL from Local is what you will put in your `.env` file as `LOCALHOST_URL`.

### Option B: DevKinsta (Windows/macOS/Linux)

DevKinsta is Kinsta's free local development tool. It is a good choice if your production site is hosted on Kinsta because you can push/pull databases directly.

1. Download and install [DevKinsta](https://kinsta.com/devkinsta/).
2. Create a new custom WordPress site.
3. Note the local URL (typically `http://localhost:xxxxx`).

### Option C: Docker (Any OS)

Docker gives you the most control and works on any operating system, but it requires more manual setup.

1. Install [Docker Desktop](https://www.docker.com/products/docker-desktop/).
2. Use the official `wordpress` Docker image with a custom theme mount. A minimal `docker-compose.yml` might look like:

```yaml
version: '3.8'
services:
  db:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: wordpress
      MYSQL_DATABASE: wordpress
      MYSQL_USER: wordpress
      MYSQL_PASSWORD: wordpress
    volumes:
      - db_data:/var/lib/mysql

  wordpress:
    image: wordpress:latest
    ports:
      - "8080:80"
    environment:
      WORDPRESS_DB_HOST: db:3306
      WORDPRESS_DB_USER: wordpress
      WORDPRESS_DB_PASSWORD: wordpress
      WORDPRESS_DB_NAME: wordpress
    volumes:
      - ./themes/vdi-starter-v5:/var/www/html/wp-content/themes/vdi-starter-v5
    depends_on:
      - db

volumes:
  db_data:
```

3. Run `docker compose up -d` and visit `http://localhost:8080` to complete the WordPress installation wizard.

---

## Installing the Theme

### Step 1: Clone the Repository

```bash
git clone https://github.com/Vincent-Design-Inc/VDI-Starter-v5.git
cd VDI-Starter-v5
```

If you are contributing to an existing project, clone it into your local WordPress `wp-content/themes/` directory so WordPress can detect it:

```bash
cd /path/to/your/local-wp-site/wp-content/themes/
git clone https://github.com/Vincent-Design-Inc/VDI-Starter-v5.git
```

If you cloned it elsewhere, you can symlink it into the themes directory:

```bash
# macOS/Linux
ln -s /path/to/VDI-Starter-v5 /path/to/wp-content/themes/vdi-starter-v5

# Windows (run in an elevated Command Prompt)
mklink /D "C:\path\to\wp-content\themes\vdi-starter-v5" "C:\path\to\VDI-Starter-v5"
```

Using a symlink means your local edits are immediately reflected in WordPress without copying files.

### Step 2: Install PHP Dependencies

```bash
composer install
```

This installs PHP_CodeSniffer and the WordPress Coding Standards (WPCS) ruleset. These are dev dependencies used for linting, not runtime dependencies, but they are required for `composer lint` and `composer fix` to work.

### Step 3: Install JavaScript Dependencies

```bash
npm install
```

This installs the frontend build toolchain: Tailwind CSS v4 and its CLI, BrowserSync, Playwright, dotenv, and other utilities. Tailwind v4 uses the `@tailwindcss/cli` package directly (no `tailwind.config.js` file is needed -- configuration lives in `styles/theme.css`).

### Step 4: Configure Environment Variables

Copy the example environment file and edit it:

```bash
cp .env.example .env
```

Open `.env` and set the two variables:

| Variable          | Description                                                                                   | Example Value                |
|-------------------|-----------------------------------------------------------------------------------------------|------------------------------|
| `LOCALHOST_URL`   | The full URL of your local WordPress site, including the scheme (`http` or `https`)           | `https://vdi-starter.local`  |
| `BROWSERSYNC_PORT`| The port BrowserSync should listen on. Defaults to `5000` if not set.                        | `5000`                       |

The `LOCALHOST_URL` must match exactly what your local WordPress environment responds to. If you are using Local by Flywheel with SSL enabled, include `https://`. If you are using Docker on port 8080, use `http://localhost:8080`.

BrowserSync proxies this URL and injects a live-reload script, so any change you make to PHP templates, CSS, or JS files will automatically refresh the browser.

### Step 5: Build Assets for the First Time

Before activating the theme, compile the Tailwind CSS so the theme has its stylesheet:

```bash
npm run build
```

This runs:

```
npx @tailwindcss/cli -i ./styles/theme.css -o ./static/dist/theme.css --optimize
```

It reads `styles/theme.css` (the entry point that imports all sub-stylesheets and the Tailwind framework), compiles all utility classes, and writes the output to `static/dist/theme.css`. The `--optimize` flag minifies the output for production.

The `static/dist/` directory is where WordPress loads the compiled stylesheet from. The theme's `Enqueue` class (`lib/class-enqueue.php`) checks for `static/dist/theme.css` and enqueues it with a file-mtime version string for cache busting.

> **Why build before activating?** If the compiled CSS file does not exist, the theme will still activate, but the frontend will be completely unstyled. The `Enqueue` class only enqueues `theme.css` if the file exists on disk -- it does not fail gracefully with a fallback, it simply does not load any stylesheet.

---

## Activating the Theme

Log into your local WordPress admin dashboard and navigate to **Appearance > Themes**. You should see **VDI Starter v5** listed. Click **Activate**.

> **CRITICAL WARNING: What happens on activation**
>
> When this theme is activated on a fresh WordPress install, `lib/activation.php` automatically performs the following actions. Read this list carefully before activating on an existing site:
>
> - **Creates 4 default pages:** Home, News, Page Not Found (Error 404), and Contact Us.
> - **Sets WordPress to use a static front page:** Home becomes the front page, News becomes the posts page. This overrides any existing "Reading Settings".
> - **Deletes the default "Hello World" post** (ID 1) and the **sample page** (ID 2). These are trashed permanently (`wp_delete_post` with `$force_delete = true`).
> - **Installs 7 plugins** from external URLs and selectively activates them:
>   1. **ACF Pro** -- installed and activated (from `https://docs.vincentdevelopment.ca/files/advanced-custom-fields-pro.zip`)
>   2. **Gravity Forms** -- installed and activated (from `https://docs.vincentdevelopment.ca/files/gravity-forms.zip`)
>   3. **UpdraftPlus** -- installed, NOT activated (from WordPress.org)
>   4. **Simple History** -- installed and activated (from WordPress.org)
>   5. **The SEO Framework** -- installed and activated (from WordPress.org)
>   6. **Better Search Replace** -- installed and activated (from WordPress.org)
>   7. **Google Site Kit** -- installed, NOT activated (from WordPress.org)
> - **Creates an "Owner" role** (administrator capabilities minus plugin/theme management).
> - **Writes an installation log** to `wp-content/mu-plugin-install.log`.
>
> **Do NOT activate this theme on an existing production site without reviewing `lib/activation.php` first.** The activation routine is designed for fresh installs and will modify pages, settings, and plugin state without confirmation.

### Optional: Import Sample Content

If you want test content (posts, pages, etc.) to work with, the repository includes a WordPress XML export file:

```bash
# In WordPress admin: Tools > Import > WordPress > Run Importer
# Upload: content/basic-wp-test-content.xml
```

This gives you sample pages and posts to verify that templates and blocks render correctly.

---

## Development Workflow

### Starting the Dev Server

```bash
npm run start
# or equivalently:
npm run watch
```

This runs `bin/.watch.js`, which starts BrowserSync and watches for file changes. When you edit a `.php` or `.css` file, BrowserSync:

1. Detects the change.
2. Recompiles Tailwind CSS (using the same `@tailwindcss/cli` command, but without `--optimize` so it is faster).
3. Reloads the browser.

When you edit a `.js` file in `static/js/`, BrowserSync injects the updated script without a full page reload (hot injection).

The dev server is accessible at the `LOCALHOST_URL` you configured, proxied through the `BROWSERSYNC_PORT`. For example, if your `.env` has `LOCALHOST_URL=https://vdi-starter.local` and `BROWSERSYNC_PORT=5000`, your dev URL is `https://vdi-starter.local` with BrowserSync overlay on port 5000.

### Building for Production

Before deploying or pushing changes that affect styles, always run:

```bash
npm run build
```

This compiles Tailwind CSS with `--optimize` enabled, which minifies the output and removes unused styles. The result is written to `static/dist/theme.css`.

> **Why not use `npm run watch` output for production?** The watch mode compiles Tailwind without optimization for speed. Production builds are significantly smaller because `--optimize` removes unused utility classes and minifies the CSS.

---

## Project Architecture

Understanding the directory layout helps you know where to find things and where to put new files.

```
VDI-Starter-v5/
├── acf/                          # ACF Pro field group JSON (auto-synced)
│   └── group_*.json              # One file per field group
├── bin/
│   ├── .watch.js                 # BrowserSync dev server script
│   └── .utils.js                # Shared build utilities (Tailwind compilation)
├── content/
│   └── basic-wp-test-content.xml # Sample content for testing
├── docs/                         # Documentation (this guide lives here)
├── lib/                          # PHP utility classes (auto-loaded)
│   ├── activation.php            # Theme activation routine (pages, plugins, settings)
│   ├── class-acf.php             # ACF JSON load/save path configuration
│   ├── class-breadcrumbs.php     # Breadcrumb navigation helper
│   ├── class-enqueue.php         # Frontend/backend/editor asset enqueueing
│   ├── class-menuitems.php      # Custom menu item handling
│   ├── class-resources.php       # Resource management
│   ├── extras.php               # Miscellaneous helper functions
│   ├── helpers.php               # Template helper functions
│   ├── hooks.php                 # Theme hooks (menus, sidebars, cleanup, SVG uploads)
│   ├── search-features.php      # Enhanced search functionality
│   └── show-template.php         # Template debugging (shows which template is loaded)
├── static/
│   ├── dist/
│   │   └── theme.css             # Compiled Tailwind output (generated, do not edit)
│   ├── img/                      # Theme images
│   └── js/
│       ├── admin.js              # Backend/editor JavaScript
│       ├── theme.js              # Frontend JavaScript (loaded as a script module)
│       ├── components/           # JS components loaded as script modules
│       │   ├── backToTop.js
│       │   ├── button.js
│       │   ├── GetHeaderHeight.js
│       │   ├── Navigation.js
│       │   └── TagExternalLinks.js
│       └── modules/              # JS modules
├── styles/
│   ├── theme.css                 # Tailwind entry point (imports all sub-stylesheets)
│   ├── base/                     # Base/reset styles and break-out utilities
│   ├── backend/                  # Admin and editor styles (admin.css, editor.css)
│   ├── blocks/                   # Per-block styles
│   ├── components/               # Reusable component styles
│   ├── fonts/                    # Icon font (Lineicons)
│   └── navigation/               # Navigation styles
├── views/
│   └── blocks/                   # ACF block definitions (block.json + template)
│       ├── accordion/
│       ├── boilerplate/          # Starter template for new blocks (excluded from registration)
│       ├── button/
│       ├── buttons/
│       ├── contact-info/
│       ├── grid/
│       ├── grid-cell/
│       ├── homepage-hero/
│       ├── media-text/
│       ├── media-text-innerblocks/
│       ├── page-children/
│       └── section/
├── tests/
│   └── site-a11y.spec.js        # Playwright accessibility tests
├── .env.example                  # Environment variable template
├── composer.json                 # PHP dependencies (PHPCS + WPCS)
├── functions.php                 # Theme bootstrap (loads all lib/*.php files, registers ACF blocks)
├── package.json                  # Node dependencies and build scripts
├── playwright.config.js          # Playwright test configuration
├── style.css                     # WordPress theme metadata header
├── theme.json                    # WordPress theme.json (colors, typography, layout settings)
└── whitelist.php                 # Playwright whitelist for testing
```

### How Auto-Loading Works

`functions.php` loads every PHP file in `lib/` using a glob pattern:

```php
foreach ( glob( __DIR__ . '/lib/*.php' ) as $filename ) {
    include_once $filename;
}
```

This means adding a new file to `lib/` automatically includes it. No manual `require` statements are needed. However, be aware that files are loaded in alphabetical order. If one file depends on something defined in another, you may need to rename files with numeric prefixes to control load order.

### How ACF Blocks Are Registered

The `regACFBlocks()` function in `functions.php` scans the `views/blocks/` directory at runtime:

```php
function regACFBlocks() {
    define( 'BLOCKS_DIR', get_stylesheet_directory() . '/views/blocks' );
    if ( is_dir( BLOCKS_DIR ) ) {
        foreach ( scandir( BLOCKS_DIR ) as $folder ) {
            if ( ( '.' !== $folder && '..' !== $folder && 'boilerplate' !== $folder ) && is_dir( BLOCKS_DIR . '/' . $folder ) ) {
                register_block_type( BLOCKS_DIR . '/' . $folder );
            }
        }
    }
}
add_action( 'init', __NAMESPACE__ . '\\regACFBlocks', 5 );
```

Each subdirectory that contains a `block.json` file is registered as a Gutenberg block. The `boilerplate` directory is explicitly excluded -- it is a starter template for creating new blocks, not a block itself.

### How Stylesheets Are Organized

The Tailwind entry point is `styles/theme.css`. It imports sub-stylesheets using CSS `@import` directives:

```css
@import "tailwindcss";           /* Tailwind v4 framework */
@import "./base/index.css";      /* Base styles */
@import "./navigation/index.css";/* Navigation styles */
@import "./fonts/lineicons.css"; /* Icon font */
@import "./base/break-out.css";  /* Break-out utility */
@import "./components/index.css";/* Component styles */
@import "./blocks/index.css";    /* Block-specific styles */
@plugin "@tailwindcss/typography"; /* Typography plugin */
```

When you create a new block or component, add its styles to the appropriate subdirectory and make sure it is imported through the corresponding `index.css` file.

---

## Creating Custom ACF Blocks

To create a new ACF block, use the `boilerplate` directory as a starting point:

1. Copy `views/blocks/boilerplate/` to a new directory with your block name (use lowercase, hyphen-separated):

   ```bash
   cp -r views/blocks/boilerplate views/blocks/my-block
   ```

2. Edit `views/blocks/my-block/block.json`:
   - Change `"name"` to `"acf/my-block"`
   - Change `"title"` to a human-readable name like `"My Block (VDI)"`
   - Update `"description"`, `"icon"`, and `"keywords"` as appropriate
   - If this block should only be nested inside another block, add a `"parent"` array (see `button/block.json` for an example)

3. Edit the PHP template file (`my-block.php`) to render your block's HTML. ACF fields are available via `get_fields()`.

4. If the block needs ACF field groups, create them in the WordPress admin under **Custom Fields > Field Groups** and associate them with the block. ACF will save the field group JSON to the `acf/` directory, which you should commit to version control.

5. Add block-specific styles in `styles/blocks/` and import them through `styles/blocks/index.css`.

The block will be automatically discovered and registered on the next page load because `regACFBlocks()` scans the directory on every `init` hook.

---

## Testing

### Playwright Accessibility Tests

The theme includes a Playwright configuration for end-to-end testing, with accessibility checks powered by `@axe-core/playwright`.

To set up Playwright for the first time:

```bash
npx playwright install
```

This downloads the browser binaries. Then run the tests:

```bash
npx playwright test
```

The test suite lives in `tests/site-a11y.spec.js`. By default, Playwright is configured to test against Chromium only (see `playwright.config.js`). You can uncomment other browser projects (Firefox, WebKit, mobile viewports) as needed.

If you want to initialize Playwright from scratch with all browsers:

```bash
npm init playwright@latest --yes "--" . '--quiet' '--browser=chromium' '--browser=firefox' '--browser=webkit' '--lang=js'
```

> **Note:** Playwright tests need a running WordPress instance to test against. Make sure your local environment is up and the theme is activated before running tests. You may also need to set a `baseURL` in `playwright.config.js` to point to your local site.

---

## Code Quality

### PHP Linting

Run PHP_CodeSniffer against the WordPress coding standards:

```bash
composer lint
```

This runs `phpcs` with the ruleset defined in `.phpcs.xml` and writes results to `phpcs-results.txt`. Fix violations manually, or use the auto-fixer:

```bash
composer fix
```

This runs `phpcbf` (PHP Code Beautifier and Fixer) to automatically correct fixable violations. Always run `composer lint` after `composer fix` to verify that remaining issues are intentional.

---

## Troubleshooting

### CSS Is Not Compiling or Styles Are Missing

**Symptom:** The frontend loads but looks completely unstyled, or changes you made to Tailwind classes are not appearing.

**Solution:** Run `npm run build`. The theme loads CSS from `static/dist/theme.css`, which is a compiled file. If this file does not exist (e.g., after a fresh clone), nothing will be styled. If you added new utility classes and they are not appearing, the file may be stale -- rebuild it.

The compilation pipeline is: `styles/theme.css` (entry point with `@import` directives) --> Tailwind CLI --> `static/dist/theme.css` (output).

### ACF Blocks Are Not Appearing in the Editor

**Symptom:** The block inserter in the Gutenberg editor does not show custom blocks like "Homepage Hero" or "Section".

**Solution:** ACF Pro must be installed and activated. Blocks are registered by scanning `views/blocks/*/block.json` on the `init` hook. Without ACF Pro, `register_block_type()` still runs, but ACF blocks require the ACF plugin to provide field data and rendering.

Also check that:
- The block directory contains a valid `block.json` file.
- The block directory is not named `boilerplate` (this is excluded by design).
- ACF Pro is activated (not just installed).

### Menus Are Not Rendering

**Symptom:** Navigation menus appear empty or show a fallback message.

**Solution:** The theme registers three menu locations in `lib/hooks.php`:

- **Main Navigation** (`main_navigation`)
- **Auxiliary Navigation** (`aux_navigation`)
- **Footer Navigation** (`footer_navigation`)

You must assign menus to these locations manually. Go to **Appearance > Menus** in WordPress admin, create a menu, and check the appropriate "Display Location" checkbox.

### JavaScript Is Not Loading

**Symptom:** Interactive features like the mobile navigation toggle or back-to-top button do not work.

**Solution:** The theme uses `wp_enqueue_script_module()`, which was introduced in WordPress 6.5. If you are running an older version of WordPress, script modules will not be loaded. Verify your WordPress version is 6.5 or higher.

You can check by looking at the page source for `<script type="module">` tags. If they are absent, your WordPress version likely does not support script modules.

### BrowserSync Is Not Connecting

**Symptom:** Running `npm run watch` starts BrowserSync but the browser does not auto-refresh on file changes.

**Solution:** Check these common issues:

1. **Wrong `LOCALHOST_URL`:** The URL in `.env` must exactly match your local WordPress site URL, including the scheme (`http` vs `https`) and any port numbers. Open the URL directly in a browser first to confirm it loads.

2. **Port conflict:** If another service is using port 5000, BrowserSync will fail to start or behave unpredictably. Change `BROWSERSYNC_PORT` in `.env` to an available port (e.g., `3000` or `8080`).

3. **SSL certificates:** If your local site uses HTTPS with a self-signed certificate (common with Local by Flywheel), BrowserSync may reject the connection. The `bin/.watch.js` configuration does not currently set `https: true` or `cert`/`key` paths, so BrowserSync proxies over HTTP by default. If your WordPress site forces HTTPS, you may need to adjust the BrowserSync configuration in `bin/.watch.js`.

4. **Firewall:** Ensure your firewall allows connections on the BrowserSync port.

### Plugin Installation Failures on Theme Activation

**Symptom:** Some or all plugins fail to install when the theme is activated.

**Solution:** The activation routine in `lib/activation.php` downloads plugin ZIP files from external URLs. Failures can occur if:

- Your local environment does not have internet access.
- The download URLs have changed (check the URLs in the source code).
- The `WP_CONTENT_DIR` directory is not writable.
- WordPress filesystem credentials are required but not available (the routine forces `direct` filesystem access, which may not work on all server configurations).

Check `wp-content/mu-plugin-install.log` for detailed error messages. Each plugin installation step logs success or failure with a timestamp.

### Tailwind Utility Classes Not Appearing in Output

**Symptom:** You added a Tailwind class to a template (e.g., `bg-blue-500`) but it does not appear in the compiled CSS.

**Solution:** Tailwind CSS v4 uses a content detection approach by default, scanning your template files for class names. Make sure the file containing the class is within the theme directory and uses a recognized extension (`.php`, `.html`, `.js`, etc.). If you are using dynamic class names (concatenating strings or storing classes in variables), Tailwind may not be able to detect them. In that case, use a Tailwind `@source` directive in your CSS or add the class to a safelist.

---

## Quick Reference

| Command             | Purpose                                                            |
|---------------------|--------------------------------------------------------------------|
| `npm run start`     | Start BrowserSync dev server with live reload (alias for `watch`)  |
| `npm run watch`     | Start BrowserSync dev server with live reload                      |
| `npm run build`     | Compile Tailwind CSS for production (minified, optimized)         |
| `composer lint`     | Run PHP_CodeSniffer against WordPress coding standards             |
| `composer fix`      | Auto-fix PHP_CodeSniffer violations                                |
| `npx playwright test` | Run Playwright end-to-end tests                                 |

| File                              | Purpose                                              |
|-----------------------------------|------------------------------------------------------|
| `.env`                            | Local environment config (`LOCALHOST_URL`, `BROWSERSYNC_PORT`) |
| `styles/theme.css`                | Tailwind CSS entry point (edit this to add imports)  |
| `static/dist/theme.css`           | Compiled CSS output (generated, do not edit manually)|
| `views/blocks/*/block.json`       | ACF block registration files                         |
| `lib/activation.php`              | Theme activation routine (creates pages, installs plugins) |
| `lib/class-enqueue.php`           | Frontend/backend/editor asset loading                |
| `lib/hooks.php`                   | Menu registration, sidebars, theme support, cleanup  |
| `functions.php`                   | Theme bootstrap (auto-loads all `lib/*.php` files)   |
| `theme.json`                      | WordPress theme configuration (colors, typography, layout) |
| `acf/group_*.json`                | ACF field group definitions (version-controlled)     |