Cutie - Framework
=================

Framework for thin and light website

- Must be placed within 'Website' directory
	
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
-----------------------------
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
