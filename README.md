## What is _Wordboot_?

Wordboot is a starter bundle used to develop standardized, organized, documented, object-oriented WordPress plugins. By following the official guidelines and APIs, the starter plugin helps you save a considerable amount time and build better WordPresss software.

## Features

* Following the official Wordpress [Plugin API](http://codex.wordpress.org/Plugin_API), [Coding Standards](http://codex.wordpress.org/WordPress_Coding_Standards), and [Documentation Standards](https://make.wordpress.org/core/handbook/best-practices/inline-documentation-standards/php/).
* Inline documented classes, functions and variables for easy developer reference.
* Strict file organization scheme that facilitates fast development and a scalable approach.
* Sample custom post type, taxonomy and their matching frontend templates.
* Custom Meta Boxes using the lightweight [Metabun](https://github.com/AlexandruDoda/Metabun) class.
* Settings page with various types of fields (including repeater).
* AJAX call boilerplate for the front-end of the site.
* Example action link to the plugin settings available on the Plugins table.
* Included translation file as a starting point for internationalization.

Learn more about the Plugin features by visiting [the official wiki](https://github.com/AlexandruDoda/Wordboot/wiki).

## Installation

You can install the Plugin through the Wordpress admin interface or by copying the `wordboot` directory to `wp-content/plugins`. Also, you must activate it from the Plugins page.

## Development

The best thing about WordBoot is that you can brand it as your own plugin within less than a minute. Simply run a *case-sensitive search and replace* on your project files using your own names:

* [ ] `wordboot` -> `plugin`
* [ ] `Wordboot` -> `Plugin`,
* [ ] `Alexandru Doda` -> `Author Name`
* [ ] `https://alexandru.co` -> `Author URL`

Also, you must rename `wordboot` to `plugin` in the file names (e.g. `includes/class-wordboot.php` would become `includes/class-plugin.php`).

# Credits

Developed and maintained by [Alexandru Doda](https://alexandru.co), Wordboot is inspired by the [Plugin Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate) developed by [Devin Vinson](https://github.com/DevinVinson).
