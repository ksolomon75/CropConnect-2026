# VDI WordPress Theme Starter v5

VDI WordPress Theme Starter v5 is a minimal WordPress theme designed as a starting point for custom theme development. It focuses on modern development approaches with a lean architecture that avoids the overhead of theme frameworks.

Repo: [https://github.com/Vincent-Design-Inc/VDI-Starter-v5](https://github.com/Vincent-Design-Inc/VDI-Starter-v5)
AC Bug/Issue Tracking: [https://next-app.activecollab.com/119590/projects/4553?modal=Task-98192-4553](https://next-app.activecollab.com/119590/projects/4553?modal=Task-98192-4553)

## Key Features

- Tailwind CSS v4 (CSS-first configuration, no `tailwind.config.js`)
- Namespaced PHP (`BasicWP` namespace) for isolation
- Built-in support for ACF blocks with auto-registration
- Fast development workflow with BrowserSync live reload
- WordPress 6.5+ script modules for modern JS loading
- Automated theme activation (plugin installs, page creation, settings)

## Recent Changes

- **2026-09-28:** Cleaned repository-only development metadata from the theme and added local tooling directories to `.gitignore`. The WP Engine deployment workflow now uses a configurable `theme` directory placeholder for `REMOTE_PATH`.
- **2026-09-25:** Improved navigation rendering by indexing menu children once per instance and memoizing menu lookups per request. Desktop and mobile menu templates now reuse the computed child data.
- **2026-09-25:** Removed the duplicate hard-coded `<title>` element from `header.php`; the theme's `title-tag` support now owns document titles.
- **2026-09-09:** Updated all ACF blocks to use block API version 3 by default.
- **2026-09-28:** Disabled comments and trackbacks across supported post types, including existing comment output.
- **2026-09-28:** Improved the button custom element's ARIA handling so it applies an explicitly supplied `aria-label` and removes stale labels when none is provided.
- **2026-09-28:** Updated external-link handling to use `aria-description` for its new-tab announcement while retaining secure new-tab attributes and styling hooks.
- **2026-09-28:** Refactored menu item constructor assignments for improved readability and consistency.

## Documentation

| Guide | Description |
|-------|-------------|
| [Getting Started](docs/getting-started.md) | Setup from zero, local WordPress, environment config, troubleshooting |
| [Architecture](docs/architecture.md) | Bootstrap flow, layers, namespace conventions, hooks, enqueue system, theme.json |
| [Creating Blocks](docs/creating-blocks.md) | Step-by-step ACF block creation tutorial with examples |
| [Reference](docs/reference.md) | Hooks/filters, design tokens, CSS architecture, JS modules, CLI commands, deployment |

## Project Structure

```
VDI-Starter-v5/
├── acf/                            # ACF field group JSON definitions
├── bin/                            # Build scripts (.build.js, .watch.js, .utils.js)
├── content/                        # Sample page and post content for testing
├── docs/                            # Documentation guides
├── lib/                            # PHP library files
│   ├── activation.php              # Theme activation handler (plugins, pages, settings)
│   ├── class-acf.php               # ACF integration (JSON sync paths)
│   ├── class-breadcrumbs.php       # Breadcrumb generation with Schema.org markup
│   ├── class-enqueue.php           # Assets enqueuing (CSS, JS, fonts)
│   ├── class-menuitems.php         # Navigation menu builder
│   ├── class-resources.php          # Custom Resources post type
│   ├── extras.php                  # Sidebar, page header, Owner role, embed wrapper
│   ├── helpers.php                 # Utility functions and globals ($theme, $views)
│   ├── hooks.php                   # WordPress hooks, filters, and cleanup
│   ├── search-features.php         # Enhanced search functionality
│   └── show-template.php           # Debug: shows active template in HTML comment
├── static/
│   ├── dist/                        # Compiled assets (theme.css)
│   └── js/                         # JavaScript files
│       ├── components/             # JS custom elements (button, backToTop)
│       ├── modules/                # JS theme modules (Navigation, GetHeaderHeight, TagExternalLinks)
│       ├── admin.js                # Admin-specific JS
│       └── theme.js                # Main theme JS (entry point)
├── styles/                         # CSS styles
│   ├── backend/                    # Admin and editor styles
│   ├── base/                       # Base styles (typography, colors, forms, etc.)
│   ├── blocks/                     # Block-specific styles
│   ├── components/                 # Component styles (breadcrumbs, pagination, etc.)
│   ├── fonts/                      # Icon font styles (Lineicons)
│   ├── navigation/                 # Navigation styles (default, mega, accordion, sliding)
│   └── theme.css                   # Main CSS entry point (Tailwind + imports)
├── tests/                           # Automated Playwright tests
│   └── site-a11y.spec.js           # Site Accessibility tests
├── views/                           # Template views
│   ├── blocks/                     # Custom ACF blocks (accordion, button, grid, section, etc.)
│   ├── icons/                      # SVG icon templates
│   ├── forms/                      # Form templates (search)
│   └── partials/                   # Reusable template parts (page-hero, social-media)
├── 404.php                         # 404 error template
├── footer.php                      # Footer template
├── front-page.php                  # Front page template
├── functions.php                   # Main functions file (autoloads lib/)
├── header.php                      # Header template
├── index.php                       # Main template
├── page.php                        # Page template
├── search.php                      # Search results template
├── sidebar.php                     # Primary sidebar template
├── sidebar-page.php                # Page sidebar template
├── single.php                      # Single post template
├── style.css                       # Theme metadata
├── theme.json                      # Block editor design system (colors, fonts, spacing)
└── whitelist.php                   # Tailwind class whitelist for editor-only classes
```

## Namespace Conventions

The project uses several naming conventions. Here's how they relate:

| Convention | Value | Where Used |
|-----------|-------|-----------|
| PHP namespace | `BasicWP` | All PHP files |
| Text domain | `basicwp` | WordPress translations |
| Block category | `vdi-blocks` | Groups blocks in the editor |
| Script module IDs | `basicwp-theme`, `basicwp-button`, `basicwp-admin` | JS module registration |
| WP Engine folder | `vdi-v5` | Deployment target |
| Theme directory | `VDI-Starter-v5` | Git repo name |

## Included Blocks

| Block | Purpose |
|-------|---------|
| Accordion | Collapsible content sections |
| Buttons | Container for Button blocks |
| Button | Configurable button element (`<x-button>` custom element) |
| Contact Info | Contact information display |
| Grid | Flexible grid layout (restricts children to Grid Cell) |
| Grid Cell | Individual grid item |
| Homepage Hero | Hero section for the front page |
| Media Text | Image or video with accompanying text |
| Media Text with Inner Blocks | Media-text with nested block support |
| Page Children | Displays child pages of current page |
| Section | Container with background and width options |

## Development

### Setup

1. Create a new repo using this one as a template.
2. Clone the repo to your local dev folder.
3. Install dependencies:

   ```bash
   composer install
   npm install
   ```

4. Copy `.env.example` to `.env` and set:
   - `LOCALHOST_URL`: Your local development URL (e.g., `http://localhost:1000`)
   - `BROWSERSYNC_PORT`: Port for BrowserSync (default: `5000`)

5. Run the initial build:

   ```bash
   npm run build
   ```

6. Activate the theme in WordPress admin.

> **Warning:** Theme activation automatically installs plugins, creates pages, and modifies WordPress settings. See [Getting Started](docs/getting-started.md#theme-activation-warning) for details.

### Development Server

- Run `npm run start` or `npm run watch` to start the development server with live reloading.
- Changes to PHP, CSS, and JS files trigger automatic reload.

### Production Build

- Run `npm run build` to compile and optimize CSS for production.
- This is normally handled by GitHub Actions during deployment.

### Deployment

- Deployment to WP Engine is handled via GitHub Actions.
- Update `.github/workflows/wpengine.yml` with the correct WP Engine deployment configuration.

## Testing

### Accessibility

Tests use Playwright and Axe:

- Run via the [VSCode Playwright extension](https://marketplace.visualstudio.com/items?itemName=ms-playwright.playwright) or `npx playwright test --ui`
- Test file: `tests/site-a11y.spec.js`

### PHP Code Standards

- Run `composer lint` to check PHP files against WordPress coding standards.
- Results are saved to `phpcs-results.txt`.
- Auto-fix with `composer fix`.
