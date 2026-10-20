# Onboarding Documentation Design

## Goal

Write thorough documentation that enables a new developer (team member or external contributor) to set up, understand, and work with the VDI-Starter-v5 WordPress theme with minimal hand-holding.

## Audience

Both internal team members and external contributors, served with layered docs — quick-start up top, deep dives below.

## Approach

Keep the existing README.md as a project overview (trimmed of deep-dive content that moves to `docs/`). Add a `docs/` directory with four focused guides. The README links to each guide.

## Doc Structure

```
docs/
├── getting-started.md        # Setup from zero, local WP, env config, first build
├── architecture.md           # Bootstrap flow, layers, conventions, namespacing, globals
├── creating-blocks.md        # Step-by-step ACF block creation with block.json, fields, CSS
└── reference.md              # Hooks/filters, theme.json tokens, CSS architecture, JS modules, deployment
```

Plus updates to README.md: trim deep-dive content, add Documentation section with links, fix known discrepancies.

## Writing Style

Detailed and explanatory — include context and rationale ("why"), not just "what" and "how". Short paragraphs with code examples and tables where appropriate. Designed to be read end-to-end by a newcomer, but also scannable for quick lookup.

## Guide Contents

### 1. getting-started.md

**Purpose:** Get a developer from zero to a running local environment.

- Prerequisites (Node 22+, pnpm/npm, PHP 8+, Composer, Local by Flywheel or equivalent)
- Local WordPress setup options (Local by Flywheel, DevKinsta, Docker) with pros/cons
- Clone, install, build, run steps (npm install, composer install, npm run build, npm run start/watch)
- .env configuration (LOCALHOST_URL, BROWSERSYNC_PORT)
- BrowserSync behavior (what triggers reload, which port, how to configure)
- What happens on theme activation — **important warning**:
  - Plugin auto-installation (7 plugins downloaded from URLs)
  - Default page creation (Home, News, 404, Contact Us)
  - WordPress reading settings configuration
  - Owner role creation
  - Warning: do not activate on existing sites without reviewing `lib/activation.php`
- Troubleshooting common issues:
  - CSS not compiling → run `npm run build`
  - Blocks not appearing → ACF Pro must be active
  - Menu not rendering → must assign menu to location in WP admin
  - JavaScript not loading → check script module support (WP 6.5+)

### 2. architecture.md

**Purpose:** Explain how the theme is organized and how it boots.

- Bootstrap flow: `functions.php` → glob autoload of `lib/*.php` → `init` hook → class instantiation
- The 7 architectural layers (from knowledge graph):
  1. **Entry** — `functions.php` and `style.css` (theme declaration)
  2. **Service Layer** — PHP classes in `lib/` (Enqueue, MenuItems, Breadcrumbs, ACF, Resources)
  3. **UI Templates** — WordPress template hierarchy, ACF blocks, components, partials, icons
  4. **Styling** — Layered CSS with Tailwind v4 (base → components → blocks → navigation)
  5. **Client Scripts** — JS modules (Navigation, BackToTop, Button, GetHeaderHeight, TagExternalLinks)
  6. **Data Layer** — ACF field group JSON schemas in `acf/`
  7. **Infrastructure** — Build scripts, CI/CD pipelines, project configs, tests
- Namespace conventions:
  - PHP namespace: `BasicWP`
  - Text domain: `basicwp`
  - Block category: `vdi-blocks`
  - WP Engine folder: `vdi-v5`
  - Explain the relationship between these naming choices
- Global variables: `$views` (path to views/ directory) and `$theme` (theme directory path)
- WordPress hooks cleanup in `hooks.php`: what gets removed (emojis, block library styles, REST links, XML-RPC) and why — performance and security rationale
- Enqueue system: `wp_enqueue_script_module()` for JS (dependency graph: theme → button, backToTop, Navigation, etc.) vs traditional `wp_enqueue_script` for admin
- theme.json design system: color palette (12 colors via CSS custom properties), font sizes (15 presets), spacing, layout

### 3. creating-blocks.md

**Purpose:** Teach a developer how to create a new ACF block from scratch.

- Anatomy of a block: `block.json` (registration) + PHP template (rendering) + CSS file (scoped styles)
- Step-by-step tutorial creating a new "Testimonial" block:
  1. Create directory `views/blocks/testimonial/`
  2. Create `block.json` with required fields (name, title, description, category, icon, supports)
  3. Create `testimonial.php` template using `blockWrapperAttributes()` and `getFieldValue()`
  4. Create `testimonial.css` for block-specific styles
  5. Create ACF field group in WP admin → exported to `acf/` as JSON
- How blocks are auto-registered: `regACFBlocks()` in `functions.php` scans `views/blocks/*/block.json`
- Helper functions: `blockWrapperAttributes()` for class/id passthrough, `getFieldValue()` for dot-notation ACF access
- Parent-child block patterns: buttons→button, grid→grid-cell (using `allowed_blocks` or `InnerBlocks`)
- Tailwind in blocks: how the whitelist system works (`whitelist.php`), how to add editor-only classes
- Block CSS loading: each block's CSS file is auto-enqueued by the block registration

### 4. reference.md

**Purpose:** Complete API and configuration reference for quick lookup.

- **Hooks and Filters** — Complete table of all `add_action`/`add_filter` calls from `hooks.php`, `extras.php`, `search-features.php`, and class files, with priority, callback, and purpose
- **theme.json Design Tokens** — Color palette (12 colors with CSS variable names), font sizes (15 presets with rem values), spacing scale, layout sizes
- **CSS Architecture** — Import tree from `theme.css`, explanation of each layer (base, components, blocks, navigation), how to add new stylesheets
- **JS Module Dependency Graph** — Visual/text tree showing `theme.js` → Navigation, BackToTop, Button, GetHeaderHeight, TagExternalLinks; `admin.js` → Button
- **Navigation Class API** — Methods for the sliding viewport feature (initializeSlidingViewport, navigateToLevel, navigateBack, etc.), configuration options
- **Helper Functions** — `getFieldValue()`, `blockWrapperAttributes()`, `customMenuOrder()`, `blockCategories()`, `consoleLog()`, `customExcerpt()`, `escEmbeds()`
- **Class Reference** — Enqueue (enqFEAssets, enqBEAssets, enqEditorAssets), ACF (saveJson, loadJson), Breadcrumbs (generate, render, and per-page-type methods), MenuItems (render), Resources (CPT + permalink rewrite), ShowTemplate (debug template path)
- **CLI Commands** — `npm run build`, `npm run start`/`npm run watch`, `composer lint`, `composer fix`, Playwright test commands
- **Deployment Workflow** — GitHub Actions `wpengine.yml` pipeline: install deps → build Tailwind → rsync to WP Engine
- **Testing** — Playwright a11y test setup and commands, PHPCS configuration and usage

## README.md Changes

- Trim: Remove the detailed function-level API docs (they move to `reference.md`)
- Trim: Remove the detailed project structure tree (keep a shorter version, point to `architecture.md` for the full picture)
- Add: "Documentation" section with links to all four guides
- Fix: Wrong CSS paths (`views/styles/` → `styles/`)
- Fix: Missing `contact-info` block from the block list
- Fix: Deployment filename typo (`wpengine,yml` → `wpengine.yml`)
- Fix: Note that `class-enqueue.php` also has `enqEditorAssets()`
- Add: Mention `backToTop.js` custom element (missing from current README)
- Add: Clarify namespace conventions (BasicWP, basicwp, vdi-blocks, vdi-v5)

## Implementation Plan

This is a documentation-only change — no code modifications. Implementation consists of:

1. Create `docs/` directory with 4 markdown files
2. Update `README.md` with trimmed content and links
3. Fix known discrepancies in README

Estimated scope: ~4 documentation files of ~200-400 lines each, plus README edits.