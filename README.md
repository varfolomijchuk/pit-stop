# Spacehills — WordPress Starter Theme

A custom WordPress starter theme built with **Webpack 5**, **Sass**, **Babel**, and **Tailwind CSS**. Designed as a clean foundation for building bespoke WordPress sites.

---

## Requirements

| Tool | Minimum version |
|------|----------------|
| WordPress | 6.0+ |
| PHP | 8.0+ |
| Node.js | 18+ |
| Composer | 2.0+ |

---

## Quick Start

**1. Install Node dependencies**
```bash
npm install
```

**2. Install Composer dependencies**
```bash
composer install
```

**3. Configure BrowserSync**

Open `webpack.config.js` and update the proxy to match your local WordPress URL:
```js
proxy: 'http://example.local'
```

**4. Start development**
```bash
npm run dev
```

---

## NPM Scripts

| Command | Description |
|---------|-------------|
| `npm run dev` | Watch mode — compiles assets and rebuilds on change |
| `npm run start` | Webpack dev server with BrowserSync live reload |
| `npm run build` | Production build — minified, content-hashed output |

---

## Directory Structure

```
spacehills/
├── src/
│   ├── js/
│   │   ├── app.js                  # Main front-end JavaScript entry point
│   │   ├── admin-api-fetch.js      # WP REST API helpers for admin screens
│   │   └── quick-edit-tab.js       # Quick edit UI enhancements
│   └── scss/
│       ├── app.scss                # Main stylesheet entry point
│       ├── editor.scss             # Gutenberg editor styles
│       ├── home-page-editor.scss   # Editor overrides for the home page
│       └── parts/
│           ├── _global.scss        # Global base styles
│           └── _casino_table.scss  # Casino table component styles
├── dist/                           # Compiled output (git-ignored)
│   ├── js/
│   ├── css/
│   └── manifest.json               # Asset manifest for cache-busting
├── app/
│   ├── helpers.php                 # Utility functions (SVG upload support, etc.)
│   ├── filters.php                 # WordPress filter hooks
│   └── blocks.php                  # Gutenberg block registration
├── template-part/                  # Reusable template parts
├── blocks/                         # Custom block templates
├── cpt/                            # Custom post type definitions
├── classes/                        # PHP classes
├── header.php                      # Site header, opens <main>
├── footer.php                      # Closes <main>, site footer
├── index.php                       # Fallback template (blog loop)
├── 404.php                         # Not found template
├── functions.php                   # Theme setup, enqueue, menu registration
├── style.css                       # Theme metadata (WordPress header comment)
├── webpack.config.js
├── package.json
└── composer.json
```

---

## Build System

Webpack 5 compiles assets from `src/` into `dist/` with content-hash filenames for cache-busting. The manifest file at `dist/manifest.json` maps logical asset names to their hashed filenames, resolved at runtime via the `ascent_asset()` helper in `functions.php`.

**Webpack entry points:**

| Entry | Source | Output |
|-------|--------|--------|
| `app` | `src/js/app.js` | `dist/js/app.[hash].js` + `dist/css/app.[hash].css` |
| `editor` | `src/scss/editor.scss` | `dist/css/editor.[hash].css` |

**Loaders included:**
- `babel-loader` — transpiles modern JS via `@babel/preset-env`
- `sass-loader` + `css-loader` + `MiniCssExtractPlugin` — compiles SCSS to separate CSS files
- `postcss-loader` with Autoprefixer — adds vendor prefixes automatically
- `file-loader` — handles images (`dist/images/`) and fonts (`dist/fonts/`)
