# isprithul theme

The WordPress theme for [isprithul.me](https://isprithul.me): a hardware engineer’s homepage drawn
like a KiCad schematic, with the SCH (light) and PCB (dark) colour themes, a clickable probe, project
cards with a photo viewer and a “Start a project” form.

It needs the [isprithul core](https://github.com/Prithul0218/isprithul-core) plugin, which holds the
projects and every homepage text (Site content in the admin). Requires WordPress 6.5+, PHP 8.0+.

## Install

With **Git Updater** (Settings › Git Updater › Install Theme):

| Field | Value |
|---|---|
| Theme URI | `https://github.com/Prithul0218/isprithul-theme` |
| Repository Branch | `main` |
| Remote Repository Host | GitHub |

Then Appearance › Themes › isprithul › **Live Preview** to check it, and **Activate**.
To go back, activate the previous theme (Astra) again; nothing is deleted.

## How it works

- `front-page.php` renders the homepage on the server from the plugin’s data (good for search
  engines and fast first paint). The scripts in `assets/js/` add the schematic probe, filters, photo
  viewer, “Is Prithul ___?” and the form on top.
- The homepage always uses this template, even though the old Home page was built with Elementor.
  It loads none of Elementor’s or its add-ons’ files and no Google Fonts. Other Elementor pages
  keep working inside this theme’s header and footer.
- Ultimate Addons for Elementor no longer swaps in its own header and footer
  (`add_theme_support( 'header-footer-elementor' )`).
- Fonts are served from the theme (Bricolage Grotesque, Atkinson Hyperlegible, JetBrains Mono under
  the SIL Open Font License; osifont under LGPL-3.0 with font exception).

## Files

| Path | What it holds |
|---|---|
| `front-page.php`, `parts/home/*.php` | the homepage sections |
| `header.php`, `footer.php`, `parts/site-*.php`, `parts/dialogs.php` | header, footer, photo viewer, form |
| `page.php`, `single.php`, `index.php`, `404.php`, `comments.php` | everything else |
| `inc/` | setup, assets, plugin compatibility, template helpers |
| `assets/css/tokens.css` | every colour (KiCad Schematic + PCB themes), fonts and sizes |
| `assets/css/*.css` | base, components, schematic, homepage, WordPress pages |
| `assets/js/*.js` | probe, filters, viewer, theme switch, form |
| `assets/fonts/` | self-hosted fonts and their licences |

Change colours only in the semantic block of `assets/css/tokens.css` (`--bg`, `--ink`, …).
Bump `Version` in `style.css` (and `ISP_THEME_VERSION` in `functions.php`) with each change so
Git Updater offers the update and browsers fetch the new files.
