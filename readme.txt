=== Jellyfish Invaders ===
Contributors: toxicToad
Author URI: http://strawberryjellyfish.com/
Donate link: http://strawberryjellyfish.com/donate/
Plugin URI: http://strawberryjellyfish.com/wordpress-plugin-jellyfish-invaders/
Tags: retro, space invaders, 8bit, animation, visual effect, animated aliens, sci fi, gaming,
Requires at least: 3.0
Tested up to: 3.8
Stable tag: 0.8.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds animated flying retro space invaders to your blog. You can configure, 
number, size, behaviour and where they appear.

== Description ==

This plugin for WordPress adds a number of retro space invaders to your blog pages
that will randomly fly around, and if you choose home in on any mouse clicks.

You can choose the number, size, speed, and behaviour of the space invaders as 
well as limit them to a specific area of your pages, a single post, or the entire
blog.

Great glowing retro 8bit scanline effect space invaders work on most backgrounds
but obviously are more suited to a dark theme.

Demo

You can see them in action on the plugin page at
http://strawberryjellyfish.com/wordpress-plugin-jellyfish-invaders/

This plugin uses the excellent Spritely library for jQuery. Read more about 
Spritely at http://www.spritely.net/


==Usage==

The plugin will show some invaders on every page of your blog when first installed.
To change this to your liking you will find a setting page in the Appearance menu 
of your WordPress admin, Here you can configure your invaders. 

General Settings

* Enable Invaders - If you want to turn Invaders on or off use this

* Where to show	- you can choose either everywhere OR only on individual posts 
or pages that contain the custom field "jellyfish_invaders". You can use this 
second option to just show them on your home page or a single post for example.
Simply add the custom field "jellyfish_invaders" with a value of "true" or "on" 
to any post you want them to appear.

Boundries

By default the invaders will be free to roam randomly around the entire document.
If you want to restrict them to an area for example a header, sidebar or blank
area on your page to avoid distracting from the main content simply uncheck the
"Ignore Boundries" checkbox and fill in the top, left, right and bottom pixel
values of your invisible invader electric fence.



== Installation ==

Extract the zip file and just drop the contents in the wp-content/plugins/ 
directory of your WordPress installation and then activate the Plugin from 
Plugins page. Visit your blog and marvel at the spectacle, then go to the 
Jellyfish Invaders settings page in the Appearance menu of your WordPress 
admin to play with the settings.

== Frequently Asked Questions == 

None yet

== Changelog ==

* 0.8.1 tweaked admin for WordPress 3.8
* 0.75  changed the way scripts are queued to ensure javascript is only included 
when invaders are actually needed.        
* 0.6   initial release

== Upgrade Notice ==

== Screenshots ==
