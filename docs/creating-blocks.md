# Creating Blocks in VDI-Starter-v5

## Table of Contents

- [Overview](#overview)
- [How Block Registration Works](#how-block-registration-works)
- [Block Anatomy: The Three-File Pattern](#block-anatomy-the-three-file-pattern)
  - [block.json -- The Registration Manifest](#blockjson----the-registration-manifest)
  - [The PHP Template -- Rendering the Block](#the-php-template----rendering-the-block)
  - [The CSS File -- Scoped Styles](#the-css-file----scoped-styles)
- [Helper Functions](#helper-functions)
  - [blockWrapperAttributes()](#blockwrapperattributes)
  - [getFieldValue()](#getfieldvalue)
  - [escEmbeds()](#escembeds)
- [ACF Field Groups](#acf-field-groups)
- [Parent-Child Block Patterns (InnerBlocks)](#parent-child-block-patterns-innerblocks)
- [Tailwind CSS in Blocks](#tailwind-css-in-blocks)
- [Step-by-Step: Creating a New Block](#step-by-step-creating-a-new-block)
  - [1. Create the Block Directory](#1-create-the-block-directory)
  - [2. Create block.json](#2-create-blockjson)
  - [3. Create the PHP Template](#3-create-the-php-template)
  - [4. Create the CSS File](#4-create-the-css-file)
  - [5. Create ACF Field Groups in WordPress Admin](#5-create-acf-field-groups-in-wordpress-admin)
  - [6. Build and Verify](#6-build-and-verify)
- [Real-World Examples from This Theme](#real-world-examples-from-this-theme)
  - [Simple Block: Homepage Hero](#simple-block-homepage-hero)
  - [Parent Block with InnerBlocks: Section](#parent-block-with-innerblocks-section)
  - [Restricted Parent Block: Buttons](#restricted-parent-block-buttons)
  - [Dynamic Parent Block: Grid](#dynamic-parent-block-grid)
  - [Block Using Global Fields: Contact Info](#block-using-global-fields-contact-info)
- [Common Pitfalls and Best Practices](#common-pitfalls-and-best-practices)

---

## Overview

VDI-Starter-v5 uses Advanced Custom Fields (ACF) blocks to build page content. ACF blocks are a type of WordPress Gutenberg block where the editing interface comes from ACF field groups and the rendering is handled by a PHP template (instead of React). This approach lets you build rich, structured content blocks using familiar PHP templating and Tailwind CSS, without writing JavaScript.

Every block in this theme follows the same three-file pattern inside `views/blocks/{block-name}/`, and new blocks are automatically discovered and registered -- no manual registration required.

---

## How Block Registration Works

Block registration is handled by the `regACFBlocks()` function in `functions.php`. You never need to register a block manually; the function handles discovery for you.

```php
// functions.php
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

Here is what happens:

1. `BLOCKS_DIR` is defined as `get_stylesheet_directory() . '/views/blocks'`, pointing to the `views/blocks/` directory inside the theme.
2. The function scans every subdirectory inside `views/blocks/`.
3. It skips `.` (current dir), `..` (parent dir), and the `boilerplate` directory (the boilerplate is a template for creating new blocks, not a real block).
4. For every remaining directory, it calls WordPress's `register_block_type()`, which reads the `block.json` file inside that directory and registers the block.
5. The function runs on the `init` hook at priority 5, ensuring blocks are registered before the editor needs them.

**What this means for you:** To create a new block, you only need to add a new subdirectory under `views/blocks/` with a valid `block.json` file. WordPress discovers and registers it automatically the next time the theme loads.

---

## Block Anatomy: The Three-File Pattern

Every ACF block in this theme consists of exactly three files inside `views/blocks/{block-name}/`:

```
views/blocks/
  boilerplate/          <-- Template for creating new blocks (not registered)
    block.json
    boilerplate.php
    boilerplate.css
  section/
    block.json
    section.php
    section.css
  homepage-hero/
    block.json
    homepage-hero.php
    homepage-hero.css
  ...
```

The naming convention is consistent: the directory name, the PHP file, and the CSS file all share the same slug (e.g., `homepage-hero`). The `block.json` file always uses the literal name `block.json`.

### block.json -- The Registration Manifest

The `block.json` file tells WordPress and ACF everything they need to know about the block. Here is the boilerplate version:

```json
{
    "name": "acf/boilerplate",
    "title": "Block Boilerplate",
    "description": "Boilerplate code to create ACF blocks.",
    "style": ["file:./boilerplate.css"],
    "category": "vdi-blocks",
    "icon": "block-default",
    "keywords": ["boilerplate"],
    "acf": {
        "mode": "preview",
        "renderTemplate": "boilerplate.php"
    },
    "supports": {
        "align": true,
        "anchor": true,
        "color": true,
        "html": false,
        "jsx": false,
        "mode": true,
        "multiple": false
    }
}
```

**Field-by-field explanation:**

| Field | Purpose |
|---|---|
| `name` | The unique block identifier. **Must be prefixed with `acf/`** for ACF blocks. This becomes the machine name WordPress uses internally (e.g., `acf/testimonial`). |
| `title` | The human-readable name shown in the block editor inserter (e.g., "Testimonial"). |
| `description` | A short description shown in the block editor to help editors understand what the block does. |
| `style` | An array of CSS files to load when this block renders. Use the `file:./` prefix for block-relative paths. WordPress only loads these stylesheets when the block is actually present on the page. |
| `category` | Determines which section of the inserter the block appears under. **Always use `vdi-blocks`** in this theme -- this is the custom category registered in `helpers.php` that groups all theme blocks together under "VDI Custom Blocks". |
| `icon` | A Dashicon name (without the `dashicons-` prefix) shown next to the block in the inserter. Browse available icons at https://developer.wordpress.org/resource/dashicons/. |
| `keywords` | Additional search terms that help editors find the block in the inserter. For example, a "Testimonial" block might include `["testimonial", "quote", "review"]`. |
| `acf.mode` | Controls how the block appears in the editor. `preview` shows the rendered block output; `edit` shows the ACF field inputs directly. Most blocks use `preview`. |
| `acf.renderTemplate` | The PHP file that renders the block on the frontend and in preview mode. This filename must match the actual file in the directory. |
| `supports.align` | Whether editors can choose alignment (left, center, right, wide, full). |
| `supports.anchor` | Whether editors can set an HTML anchor (id attribute) for linking. |
| `supports.color` | Whether editors can set text and background colors via the block editor. |
| `supports.html` | Whether the block supports HTML editing mode in the editor. Set to `false` for ACF blocks since the template controls the markup. |
| `supports.jsx` | Whether the block supports InnerBlocks (nesting other blocks inside it). Set to `true` if your block uses `<InnerBlocks />`. |
| `supports.mode` | Whether editors can toggle between preview and edit mode in the editor. |
| `supports.multiple` | Whether the block can be inserted more than once per post. Set to `false` for blocks that should be unique (e.g., a homepage hero). |

**Special field for parent blocks:** If your block restricts which blocks can be inserted as children, you can add an `allowedBlocks` key at the top level of `block.json` (not inside `supports`). The Buttons block does this:

```json
{
    "name": "acf/buttons",
    "title": "Buttons (VDI)",
    "description": "A button or group of buttons.",
    "allowedBlocks": ["acf/button"],
    "category": "vdi-blocks",
    ...
}
```

### The PHP Template -- Rendering the Block

The PHP template is responsible for outputting the block's HTML. Every template follows the same structure:

```php
<?php
/**
 * Block Name: Boilerplate
 *
 * This is the template for building your own custom blocks.
 *
 * @package BasicWP
 */

namespace BasicWP;

$classes = 'boilerplate';

/**
 * NOTE: DO NOT remove this function call - it is required to avoid editor issues.
 * $is_preview is a WordPress global when in the editor.
 */
$wrapper = blockWrapperAttributes( $classes, $is_preview );
?>

<section <?php echo wp_kses_post( $wrapper ); ?>>
    <!-- Your block code will go here -->
</section>
```

**Key elements explained:**

1. **`namespace BasicWP;`** -- Every block template must declare this namespace. It gives you access to the theme's helper functions (`blockWrapperAttributes`, `getFieldValue`, etc.) without needing fully-qualified class names.

2. **`$is_preview`** -- This is a WordPress global variable that is `true` when the block is being rendered inside the block editor, and `false` on the frontend. You can use it to conditionally show editor-only content or adjust markup for the editor.

3. **`blockWrapperAttributes()`** -- This helper function (defined in `lib/helpers.php`) generates the wrapper attributes for the block's root element. It handles the difference between editor preview mode and the frontend:
   - In preview mode (`$is_preview` is `true`): returns a simple `class="my-class"` string, which avoids rendering issues in the editor.
   - On the frontend (`$is_preview` is `false`): returns the full `get_block_wrapper_attributes()` output, which includes WordPress-generated classes and attributes for alignment, anchor, custom class names, etc.

   **Always use `blockWrapperAttributes()` instead of calling `get_block_wrapper_attributes()` directly.** The direct call can cause rendering problems in the editor.

4. **`wp_kses_post()`** -- Always wrap the wrapper attributes output with `wp_kses_post()` for security. This sanitizes the output while preserving the HTML attributes that `blockWrapperAttributes()` generates.

5. **Semantic HTML wrapper** -- Use a semantic element like `<section>`, `<article>`, `<aside>`, or `<div>` as the outermost element. The block's wrapper attributes (classes, anchor, alignment) must go on this outermost element.

### The CSS File -- Scoped Styles

Each block has its own CSS file that is loaded automatically by WordPress when the block is present on the page. This means styles are only loaded when needed, keeping page weight minimal.

The CSS filename must match the block slug and be referenced in `block.json` using the `file:./` prefix:

```json
"style": ["file:./testimonial.css"]
```

You can use Tailwind utility classes directly in your PHP templates (e.g., `class="flex gap-4 p-6"`), and they will work as long as the Tailwind build process can detect them. For complex or block-specific styles that are not expressible as utility classes, write them in the block's CSS file using the BEM-like naming convention:

```css
/* testimonial.css */

.testimonial {
    /* Block-level styles */
}

.testimonial__quote {
    /* Element styles */
}

.testimonial__quote--large {
    /* Modifier styles */
}
```

The file can be empty initially and filled in as needed.

---

## Helper Functions

The theme provides several helper functions in `lib/helpers.php`, all under the `BasicWP` namespace. Because every block template declares `namespace BasicWP;`, you can call these functions directly without any prefix.

### blockWrapperAttributes()

```php
function blockWrapperAttributes( $classes, $is_preview )
```

**Purpose:** Generates the HTML attributes string for a block's root element, handling the difference between the editor and the frontend.

**Parameters:**
- `$classes` (string) -- A space-separated list of CSS class names to apply to the block wrapper.
- `$is_preview` (bool) -- Whether the block is being rendered in the editor. Always pass the global `$is_preview` variable.

**Returns:** A string of HTML attributes ready to echo inside an HTML tag.

**How it works:**
- When `$is_preview` is `true` (in the editor), it returns `class="my-class"`. This is a simplified output that avoids rendering issues caused by WordPress's `get_block_wrapper_attributes()` in the editor context.
- When `$is_preview` is `false` (on the frontend), it calls WordPress's `get_block_wrapper_attributes()` with your classes merged in, producing the full set of attributes including alignment classes, anchor IDs, custom class names from the editor, and more.

**Usage pattern:**

```php
$classes = 'my-block some-tailwind-class';
$wrapper = blockWrapperAttributes( $classes, $is_preview );
?>
<section <?php echo wp_kses_post( $wrapper ); ?>>
    <!-- block content -->
</section>
```

**Important:** Never call `get_block_wrapper_attributes()` directly. Always use `blockWrapperAttributes()` instead. Direct calls can cause the block to render incorrectly in the editor.

### getFieldValue()

```php
function getFieldValue( $field_path )
```

**Purpose:** Retrieves nested values from ACF option fields (Global Fields) using dot notation.

**Parameters:**
- `$field_path` (string) -- A dot-notated path to the value. For example, `'contact_info.phone'` retrieves the `phone` subfield from the `contact_info` options page field.

**Returns:** The value at the specified path, or an empty string if the path does not exist.

**How it works:** The function splits the path on `.`, calls `get_field()` with the first segment and `'option'` as the second parameter (which tells ACF to look in the options table), then traverses the remaining segments through the nested array.

**Usage example:**

```php
// Instead of:
$phone = get_field( 'contact_info', 'option' )['phone'];

// You can write:
$phone = getFieldValue( 'contact_info.phone' );
```

This is cleaner and avoids "undefined index" errors when subfields are missing.

### escEmbeds()

```php
function escEmbeds()
```

**Purpose:** Returns an array of allowed HTML elements and attributes for safely outputting embed content (like YouTube or Vimeo iframes). Use this with `wp_kses()` when rendering embed blocks.

**Usage example:**

```php
echo wp_kses( $video_embed_html, escEmbeds() );
```

---

## ACF Field Groups

After creating your block's three files, you need to create an ACF field group in the WordPress admin. This defines the fields that editors fill in when editing the block.

### Creating a Field Group

1. In WordPress admin, go to **Custom Fields > Add New**.
2. Give the field group a descriptive name (e.g., "Testimonial Fields").
3. Add your fields using the ACF interface. Common field types include:
   - **Text** -- Single-line text input (for headings, names, etc.)
   - **Textarea** -- Multi-line text (for body copy, quotes, etc.)
   - **Image** -- Image selector (returns an array with `url`, `alt`, `sizes`, etc.)
   - **Select** -- Dropdown menu for predefined options
   - **True/False** -- Checkbox toggle (useful for conditional display logic)
   - **Repeater** -- Repeatable groups of fields (for lists, slides, etc.)
   - **Link** -- URL + title + target picker
   - **WYSIWYG** -- Rich text editor
4. Set the **location rule** to: **Block > is equal to > [Your Block Name]**. This tells ACF to show these fields when editing your block.
5. Click **Save** or **Publish**.

### JSON Sync

The theme's `ACF` class (in `lib/class-acf.php`) configures custom save and load paths for ACF JSON:

```php
class ACF {
    public $path;

    public function __construct() {
        $this->path = get_stylesheet_directory() . '/acf';
        add_filter( 'acf/settings/load_json', array( $this, 'loadJson' ) );
        add_filter( 'acf/settings/save_json', array( $this, 'saveJson' ) );
    }

    public function saveJson( $path ) {
        return $this->path;
    }

    public function loadJson( $paths ) {
        return array( $this->path );
    }
}
```

This means:
- When you save a field group in the admin, ACF writes a JSON file to the `acf/` directory in the theme root.
- When ACF loads field groups, it reads from the same `acf/` directory.
- These JSON files are version-controlled, so field group configurations travel with the codebase and sync across environments.

**Important:** After saving a field group, you will see a new JSON file appear in the `acf/` directory. Commit this file to version control so other environments receive the field group definition.

---

## Parent-Child Block Patterns (InnerBlocks)

Some blocks act as containers that hold other blocks. WordPress provides `<InnerBlocks />` for this purpose, and ACF blocks can use it too.

### Basic InnerBlocks

To allow any block to be inserted inside your block, simply add `<InnerBlocks />` to your template:

```php
<section <?php echo wp_kses_post( $wrapper ); ?>>
    <InnerBlocks />
</section>
```

This is what the Section block does -- it wraps its children in a container div:

```php
<section <?php echo wp_kses_post( $wrapper ); ?> style="<?php echo esc_attr( $styles ); ?>">
    <?php if ( $contentWidth === 'full' ) : ?>
        <InnerBlocks />
    <?php else : ?>
        <div class="container content-wrapper">
            <InnerBlocks />
        </div>
    <?php endif; ?>
</section>
```

### Restricted InnerBlocks

You can restrict which blocks are allowed inside your block using the `allowedBlocks` prop on `<InnerBlocks />`. This creates a parent-child relationship where only specific block types can be inserted.

The Buttons block only allows Button blocks:

```php
<div id="<?php echo esc_attr( $block['id'] ); ?>" <?php echo esc_attr( $wrapper ); ?>>
    <InnerBlocks className="<?php echo esc_attr( $ibClasses ); ?>" />
</div>
```

And its `block.json` enforces this at the registration level:

```json
{
    "allowedBlocks": ["acf/button"],
    ...
}
```

The Grid block restricts children to Grid Cell blocks:

```php
$allowedBlocks = array( 'acf/grid-cell' );
```

### Enabling InnerBlocks in block.json

For InnerBlocks to work, you must enable JSX support in your block's `supports` configuration:

```json
"supports": {
    "jsx": true,
    ...
}
```

Without `"jsx": true`, the block editor will not render the InnerBlocks area.

### Adding Classes to InnerBlocks

You can pass a `className` prop to `<InnerBlocks />` to style the inner block container:

```php
<InnerBlocks className="<?php echo esc_attr( $ibClasses ); ?>" />
```

Or with allowedBlocks:

```php
<InnerBlocks allowedBlocks={['acf/button']} className="flex gap-4" />
```

---

## Tailwind CSS in Blocks

This theme uses Tailwind CSS v4 with the `@tailwindcss/cli` package. The configuration is handled entirely through CSS, not through a `tailwind.config.js` file.

### How Tailwind is Set Up

The entry point is `styles/theme.css`, which imports Tailwind and all the theme's stylesheets:

```css
/* Tailwind setup */
@import "tailwindcss";

/* Base styles */
@import "./base/index.css";
@import "./navigation/index.css";

/* ... more imports ... */

/* Blocks */
@import "./blocks/index.css";

/* Import Tailwind typography plugin */
@plugin "@tailwindcss/typography";
```

### Using Tailwind Classes in Blocks

You can use Tailwind utility classes directly in your block PHP templates. The build process scans PHP files for class names and includes the corresponding CSS.

For example, the Homepage Hero block uses Tailwind classes extensively:

```php
$classes = 'homepage-hero mx-break-out bg-black bg-cover bg-no-repeat text-light py-12 lg:py-16 overflow-hidden';
```

And the Buttons block:

```php
$ibClasses = 'flex flex-wrap gap-4 w-full justify-center sm:justify-start';
```

### Whitelisting Editor-Only Classes

Some Tailwind classes are used only in the WordPress block editor (for example, classes applied through the editor's UI that do not appear anywhere in the theme's PHP or CSS source files). Because Tailwind's content scanning only finds classes in source files, these editor-applied classes would be purged from the final CSS.

To prevent this, add editor-only classes to `whitelist.php`. This file contains HTML `<span>` elements with the classes that Tailwind should always include:

```php
<!-- whitelist.php -->
<span class="grid"></span>
<span class="grid-cols-1"></span>
<span class="grid-cols-2"></span>
<!-- ... more classes ... -->
```

The whitelist is primarily used for grid and layout classes that the Grid block applies dynamically through ACF field values (since those class names are generated at runtime, not hardcoded in templates).

### Block-Specific CSS Files

Each block's CSS file (referenced in `block.json` via `"style": ["file:./block-name.css"]`) is loaded automatically by WordPress only when that block is present on the page. This keeps the CSS payload minimal. You can write both custom CSS and use Tailwind's `@apply` directive in these files.

Additionally, some blocks share styles that are imported globally. The `styles/blocks/index.css` file imports styles for blocks that need to be available more broadly (such as button styles that apply across multiple blocks).

---

## Step-by-Step: Creating a New Block

This walkthrough demonstrates creating a "Testimonial" block from scratch.

### 1. Create the Block Directory

Create a new folder under `views/blocks/` using a lowercase, hyphenated slug:

```
views/blocks/testimonial/
```

The directory name becomes the block's slug and must match the filenames of the PHP and CSS files inside it.

### 2. Create block.json

Create `views/blocks/testimonial/block.json`:

```json
{
    "name": "acf/testimonial",
    "title": "Testimonial",
    "description": "A customer testimonial with quote, name, and role.",
    "style": ["file:./testimonial.css"],
    "category": "vdi-blocks",
    "icon": "format-quote",
    "keywords": ["testimonial", "quote", "review"],
    "acf": {
        "mode": "preview",
        "renderTemplate": "testimonial.php"
    },
    "supports": {
        "align": true,
        "anchor": true,
        "color": true,
        "html": false,
        "jsx": false,
        "mode": true,
        "multiple": true
    }
}
```

**Checklist for `block.json`:**
- `name` starts with `acf/`
- `category` is set to `vdi-blocks`
- `style` references the CSS file with the `file:./` prefix
- `acf.renderTemplate` matches the PHP filename exactly
- `supports.html` is `false` (ACF blocks should not support HTML editing)
- `supports.jsx` is `false` unless the block uses InnerBlocks

### 3. Create the PHP Template

Create `views/blocks/testimonial/testimonial.php`:

```php
<?php
/**
 * Block Name: Testimonial
 *
 * A customer testimonial with quote, name, and role.
 *
 * @package BasicWP
 */

namespace BasicWP;

$classes = 'testimonial';
$wrapper = blockWrapperAttributes( $classes, $is_preview );

// Retrieve ACF fields
$quote = get_field( 'quote' );
$name  = get_field( 'name' );
$role  = get_field( 'role' );
$image = get_field( 'image' );
?>

<section <?php echo wp_kses_post( $wrapper ); ?>>
    <blockquote class="testimonial__quote">
        <?php echo wp_kses_post( $quote ); ?>
    </blockquote>

    <div class="testimonial__author">
        <?php if ( $image ) : ?>
            <img
                src="<?php echo esc_url( $image['url'] ); ?>"
                alt="<?php echo esc_attr( $image['alt'] ); ?>"
                class="testimonial__image"
            >
        <?php endif; ?>

        <div class="testimonial__info">
            <cite class="testimonial__name"><?php echo esc_html( $name ); ?></cite>
            <?php if ( $role ) : ?>
                <span class="testimonial__role"><?php echo esc_html( $role ); ?></span>
            <?php endif; ?>
        </div>
    </div>
</section>
```

**Checklist for the PHP template:**
- Always start with `namespace BasicWP;`
- Always call `blockWrapperAttributes( $classes, $is_preview )` and assign it to `$wrapper`
- Always echo `$wrapper` inside the root element with `wp_kses_post()`
- Use `get_field()` to retrieve ACF field values
- Use `getFieldValue()` for nested option fields
- Escape all output: `wp_kses_post()` for HTML content, `esc_html()` for plain text, `esc_url()` for URLs, `esc_attr()` for HTML attributes
- Use semantic HTML elements (`<section>`, `<blockquote>`, `<cite>`, etc.)
- Follow BEM-like naming for CSS classes: `.block`, `.block__element`, `.block__element--modifier`

### 4. Create the CSS File

Create `views/blocks/testimonial/testimonial.css`. It can start empty or with basic structure:

```css
/* Testimonial block styles */

.testimonial {
    /* Block-level styles */
}

.testimonial__quote {
    /* Quote styles */
}

.testimonial__author {
    /* Author layout */
}

.testimonial__image {
    /* Avatar styles */
}

.testimonial__info {
    /* Author info layout */
}

.testimonial__name {
    /* Name styles */
}

.testimonial__role {
    /* Role styles */
}
```

If you are using Tailwind utility classes in the PHP template, you may not need much custom CSS. The file still needs to exist and be referenced in `block.json` so WordPress can load it.

### 5. Create ACF Field Groups in WordPress Admin

1. Log in to the WordPress admin dashboard.
2. Go to **Custom Fields > Add New**.
3. Enter a title: "Testimonial Fields".
4. Add the following fields:

   | Field Label | Field Name | Field Type | Notes |
   |---|---|---|---|
   | Quote | `quote` | Textarea | The testimonial text |
   | Name | `name` | Text | The customer's name |
   | Role | `role` | Text | The customer's role or title (optional) |
   | Image | `image` | Image | The customer's photo (optional) |

5. Under **Location**, set the rule: **Block is equal to Testimonial**. ACF will auto-detect the block name from your `block.json`.
6. Click **Save** or **Publish**.

After saving, ACF will write a JSON file to the `acf/` directory in the theme root. This file should be committed to version control.

### 6. Build and Verify

If you used Tailwind utility classes in your block template, run the build process:

```bash
npm run build
```

Then verify the block appears in the editor:

1. Edit a page in the WordPress block editor.
2. Open the inserter and look under **VDI Custom Blocks**.
3. You should see "Testimonial" with the quote icon.
4. Insert the block and fill in the fields.
5. Save and preview the page on the frontend to confirm rendering works correctly.

---

## Real-World Examples from This Theme

### Simple Block: Homepage Hero

The Homepage Hero is a straightforward block that retrieves ACF fields and renders them with Tailwind classes. It does not use InnerBlocks.

**Key patterns:**
- Retrieves multiple ACF fields with `get_field()`
- Conditionally renders sections only when fields have values (`! empty( $heading )`)
- Uses Tailwind classes extensively for layout and styling
- Handles editor vs. frontend differences for link URLs

```php
// Retrieve ACF fields
$heading = get_field( 'heading' );
$intro   = get_field( 'intro' );
$ctas    = get_field( 'calls_to_action' );

$classes = 'homepage-hero mx-break-out bg-black bg-cover bg-no-repeat text-light py-12 lg:py-16 overflow-hidden';
$wrapper = blockWrapperAttributes( $classes, $is_preview );
?>

<section <?php echo wp_kses_post( $wrapper ); ?>>
    <div class="container content-wrapper">
        <div class="max-w-lg sm:text-center lg:text-left lg:items-center ml-0">
            <?php if ( ! empty( $heading ) ) : ?>
            <h1 class="text-4xl lg:text-5xl font-bold leading-tight mb-4">
                <?php echo esc_html( $heading ); ?>
            </h1>
            <?php endif; ?>
            <!-- ... more content ... -->
        </div>
    </div>
</section>
```

### Parent Block with InnerBlocks: Section

The Section block is a container that wraps its child blocks with optional background styling. It demonstrates conditional rendering based on ACF fields.

**Key patterns:**
- Builds CSS class strings dynamically based on field values
- Builds inline `style` strings from field values
- Conditionally renders an overlay div
- Conditionally wraps InnerBlocks in a container div based on the `content_width` field
- Uses `blockWrapperAttributes()` with dynamic classes

```php
// Retrieve ACF fields
$contentWidth = get_field( 'content_width' );
$isDark       = get_field( 'is_dark' );
$bgColor      = get_field( 'background_color' );
$bgImage      = get_field( 'background_image' );

// Build classes dynamically
$classes = 'section';

if ( $contentWidth === 'full' ) {
    $classes .= ' mx-break-out';
}
if ( $isDark ) {
    $classes .= ' dark text-light';
}
if ( $bgColor || $bgImage ) {
    $classes .= ' has-background bg-no-repeat';
}

// Build inline styles
$styles = '';
if ( $bgColor ) {
    $styles .= "background-color: $bgColor;";
}
if ( $bgImage ) {
    $styles .= ' background-image: url(' . esc_url( $bgImage['url'] ) . ');';
}

$wrapper = blockWrapperAttributes( $classes, $is_preview );
?>

<section <?php echo wp_kses_post( $wrapper ); ?> style="<?php echo esc_attr( $styles ); ?>">
    <?php if ( $ovlColor || $ovlImage ) : ?>
        <div aria-hidden="true" class="section-overlay absolute inset-0" style="<?php echo esc_attr( $overlayStyles ); ?>"></div>
    <?php endif; ?>

    <?php if ( $contentWidth === 'full' ) : ?>
        <InnerBlocks />
    <?php else : ?>
        <div class="container content-wrapper">
            <InnerBlocks />
        </div>
    <?php endif; ?>
</section>
```

### Restricted Parent Block: Buttons

The Buttons block is a container that only allows Button blocks as children. It enforces this restriction through both `block.json` and the template.

**Key patterns:**
- Uses `allowedBlocks` in `block.json` to restrict children to `acf/button`
- Sets `"jsx": true` in `supports` to enable InnerBlocks
- Passes Tailwind classes to InnerBlocks via the `className` prop
- Sets `"align": false` and `"color": false` since styling comes from child Button blocks

```json
{
    "name": "acf/buttons",
    "allowedBlocks": ["acf/button"],
    "supports": {
        "align": false,
        "jsx": true
    }
}
```

```php
$ibClasses = 'flex flex-wrap gap-4 w-full justify-center sm:justify-start';
$classes   = 'align-with-content my-[1.2em]';
$wrapper   = blockWrapperAttributes( $classes, $is_preview );
?>

<div id="<?php echo esc_attr( $block['id'] ); ?>" <?php echo esc_attr( $wrapper ); ?>>
    <InnerBlocks className="<?php echo esc_attr( $ibClasses ); ?>" />
</div>
```

Note: The Buttons block uses `esc_attr()` instead of `wp_kses_post()` for the wrapper because the `<div>` tag is not inside a `<section>` -- both approaches are valid, but `wp_kses_post()` is preferred for the main block wrapper.

### Dynamic Parent Block: Grid

The Grid block builds CSS classes dynamically from ACF field values (columns, breakpoints, gaps). This is a case where runtime-generated class names need to be whitelisted.

**Key patterns:**
- Dynamically constructs Tailwind class names from field values (e.g., `'grid-cols-' . get_field( 'columns' )`)
- Uses `$block['anchor']` and `$block['className']` for editor-set attributes
- These dynamic class names are added to `whitelist.php` so Tailwind includes them in the build

```php
$allowedBlocks = array( 'acf/grid-cell' );
$gridClasses   = 'grid grid-cols-' . get_field( 'columns' );

// Add breakpoint-specific column classes
if ( $colBPs ) {
    foreach ( $colBPs as $bp ) {
        $gridClasses .= ' ' . $bp . ':grid-cols-' . get_field( 'columns_' . $bp );
    }
}

// Add gap classes
if ( $gapX ) {
    $gridClasses .= ' gap-x-' . $gapX;
}
if ( $gapY ) {
    $gridClasses .= ' gap-y-' . $gapY;
}

$classes = trim( $className . ' ' . $gridClasses );
?>

<div id="<?php echo esc_attr( $anchor ); ?>">
    <InnerBlocks className="<?php echo esc_attr( $classes ); ?>" />
</div>
```

Because the class names like `grid-cols-3` and `md:grid-cols-4` are generated at runtime from field values (not hardcoded in PHP templates), Tailwind cannot detect them through content scanning. That is why `whitelist.php` explicitly lists all possible grid column and gap classes.

### Block Using Global Fields: Contact Info

The Contact Info block demonstrates how to access ACF options page data (Global Fields) using `getFieldValue()`.

**Key patterns:**
- Uses `get_field( 'contact_info', 'option' )` to retrieve the options page field group, then accesses sub-fields with array syntax
- Alternatively, could use `getFieldValue( 'contact_info.phone' )` for the same result
- Combines static content from Global Fields with dynamic InnerBlocks content (a contact form)

```php
namespace BasicWP;

$classes = 'contact-info';
$wrapper = blockWrapperAttributes( $classes, $is_preview );
?>

<section <?php echo esc_attr( $wrapper ); ?>>
    <div class="flex flex-col lg:flex-row">
        <div class="w-full lg:w-1/2 p-6">
            <h2 class="text-2xl font-bold mb-4">Contact Information</h2>
            <p><?php echo wp_kses_post( get_field( 'contact_info', 'option' )['address'] ); ?></p>
            <p><a href="mailto:<?php echo esc_html( get_field( 'contact_info', 'option' )['email'] ); ?>">
                <?php echo esc_html( get_field( 'contact_info', 'option' )['email'] ); ?>
            </a></p>
            <p><a href="tel:<?php echo esc_html( get_field( 'contact_info', 'option' )['phone'] ); ?>">
                <?php echo esc_html( get_field( 'contact_info', 'option' )['phone'] ); ?>
            </a></p>
        </div>

        <div class="w-full lg:w-1/2 p-6">
            <InnerBlocks />
        </div>
    </div>
</section>
```

---

## Common Pitfalls and Best Practices

### Do

- **Always use `namespace BasicWP;`** at the top of every block PHP template. Without it, helper functions like `blockWrapperAttributes()` and `getFieldValue()` will not be available.
- **Always use `blockWrapperAttributes()`** for the root element's attributes. Never call `get_block_wrapper_attributes()` directly.
- **Always escape output.** Use `wp_kses_post()` for HTML content, `esc_html()` for plain text, `esc_url()` for URLs, and `esc_attr()` for HTML attribute values.
- **Always set `category` to `vdi-blocks`** in `block.json` so your block appears under "VDI Custom Blocks" in the editor.
- **Always prefix `name` with `acf/`** in `block.json` (e.g., `"acf/testimonial"`, not just `"testimonial"`).
- **Always set `supports.html` to `false`** in `block.json` for ACF blocks. ACF blocks use PHP templates, not HTML editing.
- **Always set `supports.jsx` to `true`** if your block uses `<InnerBlocks />`. Without this, the InnerBlocks area will not render.
- **Commit ACF JSON files** from the `acf/` directory to version control after creating field groups.
- **Use semantic HTML elements** as block wrappers (`<section>`, `<article>`, `<aside>`, `<nav>`, etc.) instead of generic `<div>` elements where appropriate.
- **Use BEM-like naming** for custom CSS classes: `.block-name`, `.block-name__element`, `.block-name__element--modifier`.
- **Add dynamic Tailwind classes to `whitelist.php`** if they are generated from ACF field values rather than hardcoded in templates.

### Do Not

- **Do not call `get_block_wrapper_attributes()` directly.** Always use `blockWrapperAttributes()` instead. Direct calls cause rendering issues in the editor.
- **Do not edit the `boilerplate` directory.** It is excluded from registration and serves as a reference template. Copy its files to a new directory instead.
- **Do not forget to create the CSS file** referenced in `block.json`. Even if the file is empty, WordPress needs it to exist. If the file is missing, WordPress may throw an error when loading the block.
- **Do not use `'option'` directly with `get_field()` for nested values without null checking.** Prefer `getFieldValue()` which handles missing values gracefully.
- **Do not set `supports.multiple` to `false`** unless the block truly must be unique per page (like a homepage hero). Most blocks should allow multiple instances.
- **Do not hardcode editor-only Tailwind classes in PHP templates** without adding them to `whitelist.php`. If a class only appears in the editor's UI (like grid column classes set via ACF fields), Tailwind will not include it in the build.
- **Do not use the `style` attribute on the block's root element alongside `blockWrapperAttributes()` for background colors** unless the block specifically needs it. WordPress's built-in color supports (enabled via `supports.color`) handle this automatically.

### Debugging Tips

- If a block does not appear in the editor, check that `block.json` is valid JSON and that the `name` field starts with `acf/`.
- If ACF fields do not show up when editing a block, verify the field group's location rule is set to "Block is equal to [Your Block Name]".
- If styles are not loading, confirm the `style` array in `block.json` uses the `file:./` prefix and the CSS filename matches exactly.
- If Tailwind classes are not applying on the frontend, run `npm run build` and check that the classes are either in your templates or in `whitelist.php`.
- If InnerBlocks are not rendering, confirm `"jsx": true` is set in the block's `supports` configuration.