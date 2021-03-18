=== Falang multilanguage for WordPress ===
Contributors: sbouey
Donate link: www.faboba.com/falangw/
Tags: multilingual, translation, translate, bilingual, qTranslate
Requires at least: 4.7
Tested up to: 5.6
Requires PHP: 5.6
Stable tag: 1.3.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Falang is the easiest multilanguage plugin you can use to translate a WordPress site.

== Description ==

Falang is a multilanguage plugin for WordPress. It allows you to translate an existing WordPress site to other languages. Falang natively supports WooCommerce (product, variation, category, tag, attribute, etc.)

= Concept =

* Easy setup
* Supports all languages supported by WordPress (RTL and LTR)
* When you add a language in Falang, WP language packages are automatically downloaded and updated
* Easy to use: Translate Posts, Pages, Menus, Categories from the plugin or linked from the WP interface
* Translate Posts and Terms permalinks
* Translate additional plugins like WooCommerce, Yoast SEO, etc.
* You can use Azure,Yandex,Lingvanex to help you with the translation (Google and DeepPL services may be included in later versions)
* Displays the default language if the content is not yet translated
* The Language Switcher widget is configurable to display flags and/or language names
* Language Switcher can be put in Menu, Header, Footer, Sidebars
* Image captions, alt text and other media text translation without duplicating the media files
* Language Code directly in the URL
* No extra database tables created, no content duplication
* Very good website speed performance (low impact)
* Contains translations for IT, FR, DE, ES, NL
* Falang is not meant for WordPress multisite installations!

= Falang's goal is to let you translate everything on your page =

* Taxonomies
* Menu items
* Theme and plugin strings
* Custom fields
* Page builder content
* Widgets
* Shortcode outputs
* URL slugs
* WooCommerce products
* Page title and description
* Image alt text and captions

= Also available =

* Falang Q-Importer [qTranslateX to Falang](https://wordpress.org/plugins/falang-q-importer/)
* Falang for Divi [Falang for Divi](https://www.faboba.com/en/wordpress/falang-divi/presentation.html)
* Falang for Elementor [Falang for Elementor](https://wordpress.org/plugins/falang-for-elementor-lite/)

== Installation ==

1. Upload the entire Falang folder to the /wp-content/plugins/ directory or install from WP Plugins
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add languages
4. Set the default language (main language of your site)
5. Add the  Language Switcher to your site
6. Translate your post / pages / menus etc. to the other languages

== Frequently Asked Questions ==

* Is Falang Translate free?
Yes, but without on-site support.

* Where to find help?
  First time users should read the [Falang - Documentation](https://www.faboba.com/falangw/), which explains the basics (with screenshots - in English).

== Screenshots ==

1. The Post Listing translation panel
2. The Post translation panel
3. The Language Listing panel

== Changelog ==

= 1.3.8 (15/02/2021) =
* Add attachment translation directly in media popup
* Add search on translated language
* Add Rank math suport
* Add Yoast Seo Filter (for new Yoast vesrion 1.5)
* fix woocommerce save settings making 404
* change the_content priority from 9 to 8 for display work type plugin working

== Known issues ==
* Elementor preview needs to have “Show slug for main language” disabled in Falang Settings
* When a post/page is restricted to one language, the Blog posts
overview hides the post in the other languages. But in the single post
view on the frontend you can still choose the other language(s) through the language switcher, resulting in a 404. This will be fixed in the
next version.

== Upgrade Notice ==
* No notice yet
