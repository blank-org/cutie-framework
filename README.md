Cutie - Framework
=================

Framework for thin and light website

- Must be placed within 'Website' directory

Component text fields
---------------------

Component records distinguish between three kinds of text:

- `Label` is the short, visible name used for the page heading, navigation
  tiles, breadcrumbs, and AJAX navigation.
- `Title` is a concise URL-sharing title. It is intended for Open Graph,
  Twitter, messaging previews, and similar metadata, and should generally be
  shorter than the description.
- `Description` is the longer summary of the component. A site template may
  also use it when constructing the browser document title.

Do not use `Title` as the visible page heading or navigation label. Article
navigation passes the neighboring component's `Label`.

`Type` is optional for compatibility with existing component indexes. When it
is present, article navigation includes only same-level rows whose type is
`article`. When it is absent, same-level component rows are treated as
articles.


Directory structure
-------------------
	
	Framework
	|
	├───API
	|
	├───CSS
	|   ├───Base
	|   └───Fragment
	|
	├───Files
	|
	├───HTML
	|   └───Fragment
	|
	└───JS
	    ├───Base
	    └───Fragment
	
File structure
--------------
	Framework									The standard framework part (Usually not to be modified)
	|
	│   README.md
	│
	├───API
	│       API.php
	│       ComponentDetails.php
	│       Config.php
	│       IncludeDir.php
	│       IncludeSVG.php
	│       Pre.php
	│
	├───CSS
	│   │   Style.php
	│   │
	│   ├───Base
	│   │   │   Core.css
	│   │   │   Core_narrow.css
	│   │   │   Font.css
	│   │   │   Item.css
	│   │   │   Item_narrow.css
	│   │   │   Media.css
	│   │   │   Print.css
	│   │   │   Shadow.css
	│   │   │   Structure.css
	│   │   │   Wait_loader.css
	│   │   │
	│   │   ├───Body
	│   │   │   │   Body.css
	│   │   │   │   Body_narrow.css
	│   │   │   │   FB.css
	│   │   │   │   Nav_list.css
	│   │   │   │   Path.css
	│   │   │   │   Separator.css
	│   │   │   │   Sub_list.css
	│   │   │   │   Switch.css
	│   │   │   │   Title.css
	│   │   │   │   Translate_element.css
	│   │   │   │   Updated.css
	│   │   │   │
	│   │   │   └───Content
	│   │   │           Content.css
	│   │   │           Content_image.css
	│   │   │           Cover_image.css
	│   │   │           Cover_image_narrow.css
	│   │   │           FB_narrow.css
	│   │   │           Indent.css
	│   │   │           Indent_narrow.css
	│   │   │           List.css
	│   │   │           Spacing.css
	│   │   │
	│   │   ├───Component
	│   │   │       License.css
	│   │   │       Logo.css
	│   │   │       Me_table.css
	│   │   │       Me_table_narrow.css
	│   │   │       Timeline.css
	│   │   │
	│   │   ├───Footer
	│   │   │       Footer.css
	│   │   │       Social.css
	│   │   │
	│   │   ├───Header
	│   │   │       Header.css
	│   │   │       Menu_button.css
	│   │   │       Right_buttons.css
	│   │   │       Search_button.css
	│   │   │       Translate_button.css
	│   │   │
	│   │   └───Menu
	│   │           Menu.css
	│   │           Switch.css
	│   │
	│   └───Fragment
	│           CSS.php
	│
	├───Files
	│       Manifest.json.php
	│
	├───HTML
	│   │   404.php
	│   │   Component.php
	│   │   Page.php
	│   │
	│   └───Fragment
	│           Component_bottom.php
	│           Component_bottom_nav.php
	│           Component_cover.php
	│           Component_FB_buttons.php
	│           Component_FB_comments.php
	│           Component_image.php
	│           FB_meta.php
	│           GCSE.php
	│           Google_Plus_meta.php
	│           Item.php
	│           Item_image.php
	│           Item_text.php
	│           Link.php
	│           NavList.php
	│           OG_meta.php
	│           Path.php
	│           SubList.php
	│           Twitter_meta.php
	│
	└───JS
	    │   Activate.js
	    │   AJAXLoad.js
	    │   API.js
	    │   Canvas.js
	    │   History.js
	    │   Init.js
	    │   InitPage.js
	    │   Script.js
	    │   Script.php
	    │   XURL.js
	    │
	    ├───Base
	    │       Script.js
	    │
	    └───Fragment
	            Adsense_auto.php
	            BodyBegin_FB.php
	            GA_headScript.php
	            GA_track.js
	            GCSE.php
	            GTranslate.php
	            JS.php
	            Project_title.php
	            Sentry_exec.php
	            Sentry_version.php
Layout & skin (opt-in)
----------------------

Shell appearance is selected per site via `Config/Vars.tsv`. Defaults keep the classic article look for existing sites (cutie.com, samples, etc.).

| Key | Values | Effect |
|-----|--------|--------|
| `layout` | `classic` (default), `wide` | Shell width / gutters. `wide` unlocks `.layout-rail`, `.layout-split` helpers. |
| `skin` | `none` (default), `glass` | Shared translucent panel tokens/utilities (`.glass`, `.glass-panel`, `.glass-soft`). |

Emit the attributes from the site template (Cutie templates are site-owned):

```html
<html … data-layout="<?php echo htmlspecialchars(getLayoutMode()); ?>" data-skin="<?php echo htmlspecialchars(getSkinMode()); ?>">
```

Framework CSS is gated on those attributes (`Layout_wide.css`, `Skin_glass.css`), so other sites stay unchanged until they set the keys.

Site adapters
-------------

Brand-specific composition that is not useful across sites belongs in the site tree, not the framework:

- Prefer `CSS/Base/adapter.css` (or similarly named Base CSS) that styles chrome using `[data-layout]` / `[data-skin]` and the shared utilities.
- Keep marketing/landing markup and copy in site `HTML/Component/`.
- Override site `HTML/Fragment/{Header,Menu,Footer}.php` for shell redesigns that should not affect other Cutie sites.
- Prefer site `HTML/Fragment/GCSE.php` + `JS/Fragment/GCSE.php` when replacing Google CSE with a custom search UI (template should prefer site fragments when present).
- Mark self-contained landing/case pages with class `no-auto-cover` so SPA cover injection is skipped without brand-specific framework checks.
- Keep fonts, default body chrome, and CSE dark-mode rules in the framework classic defaults; override typography/colors in the site adapter.
- Do not fork `Structure.css` / `Body.css` / `Font.css` for one brand; extend via attributes + adapter.

**Branch vs disjoint framework repo:** prefer a long-lived branch on `cutie-framework` (e.g. `shell/wide`) when temporary framework API divergence is required. Point the site submodule at that branch, periodically rebase/merge from `main`, and open PRs upstream for reusable pieces. Avoid a disjoint framework repo — it makes pulling updates and sending features back much harder. Prefer site-owned chrome for radical redesigns whenever possible.

This keeps the framework selectable and reusable while allowing long-lived per-site skins.

Overrides
---------

#### CSS  
place *css* overrides in project `css` dir

#### Debug:
to debug local rewrite path
in .htaccess change

RewriteRule ^(.*?)\.jpg$ Resource/$1/index.jpg [L]
to
RewriteRule ^(.*?)\.jpg$ Resource/$1/index.jpg [R=302,L]
