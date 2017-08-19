# WordPress Plugin

A foundation for building standardized, organized, object-oriented Wordpress plugins by relying on the official guidelines and APIs.

## Features

* Based on the official Wordpress [Plugin API](http://codex.wordpress.org/Plugin_API), [Coding Standards](http://codex.wordpress.org/WordPress_Coding_Standards), and [Documentation Standards](https://make.wordpress.org/core/handbook/best-practices/inline-documentation-standards/php/).
* Documented classes, functions and variables for easy developer reference.
* Strict file organization scheme that facilitates fast development and a scalable approach.
* Included translation file as a starting point for internationalization.
* Settings page with various types of fields.
* A registered custom post type, taxonomy and their templates.
* A meta box and sample fields nativelly via the API.
* An AJAX call boilerplate for the front-end of the site.
* An example action link to the plugin settings available on the Plugins table.

## Contents

The "WordPress Plugin" includes the following files:

* `.gitignore`. Used to exclude certain files from the repository.
* `CHANGELOG.md`. The list of project historical changes.
* `README.md`. The file that you’re currently reading.
* `plugin`. The directory that contains the source code.

## Installation

You can install the Plugin through the Wordpress admin interface or by copying the `plugin` directory to `wp-content/plugins`. Also, you must activate it from the Plugins page.

## License

The WordPress Plugin Boilerplate is licensed under the GPL v2 or later.

> This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation.

> This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

> You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

A copy of the license is included in the root of the plugin’s directory. The file is named `LICENSE`.

## Important Notes

### Licensing

The WordPress Plugin Boilerplate is licensed under the GPL v2 or later; however, if you opt to use third-party code that is not compatible with v2, then you may need to switch to using code that is GPL v3 compatible.

For reference, [here's a discussion](http://make.wordpress.org/themes/2013/03/04/licensing-note-apache-and-gpl/) that covers the Apache 2.0 License used by [Bootstrap](http://twitter.github.io/bootstrap/).

# Credits

The WordPress Plugin Boilerplate was started in 2011 by [Tom McFarlin](http://twitter.com/tommcfarlin/) and has since included a number of great contributions. In March of 2015 the project was handed over by Tom to Devin Vinson. The next version of the Boilerplate was developed in conjunction with [Josh Eaton](https://twitter.com/jjeaton), [Ulrich Pogson](https://twitter.com/grapplerulrich), and [Brad Vincent](https://twitter.com/themergency).

Further, this forked version of the Boilerplate is developed and maintained by [Alexandru Doda](https://alexandru.co).
