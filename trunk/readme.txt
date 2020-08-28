=== Jellyfish Invaders ===
Contributors: toxicToad
Author URI: http://strawberryjellyfish.com/
Donate link: http://strawberryjellyfish.com/donate/
Plugin URI: http://strawberryjellyfish.com/wordpress-plugins/jellyfish-invaders/
Tags: retro, space invaders, 8bit, animation, visual effect, animated aliens, sci fi, gaming,
Requires at least: 3.0
Tested up to: 5.4
Stable tag: 0.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add pixelated pets to your site in the form of funky animated retro
space invaders.

== Description ==

Your new pixelated pals will randomly fly around, and if you choose, home in
on any mouse clicks. The glowing retro 8bit scan-line effect space invaders
work on most backgrounds but obviously are more suited to a dark theme. It's
easy to configure the number, size, behaviour and where the invaders appear
through the comprehensive admin control panel.

Demo and more information at the plugin homepage:

http://strawberryjellyfish.com/wordpress-plugins/jellyfish-invaders

= Configuring your Invaders =

A new settings page will be added to your Admin Appearance section, where you
can change the look and behaviour of your new binary buddies. Here's an
overview of the available settings:

* **Enable Invaders** Turn the invaders off and on. A quick way to
temporarily disable the invaders without deactivating the plugin.

* **Where to show** you can choose either everywhere OR only on individual
posts or pages. You can use this second option to just show them on your home
page or a single post for example.

* **The invaders will look out for a custom field called "jellyfish_invaders"
on any post or page. If they find one and it has a value of 'true' or 'on'
they will populate the page.

* **Number of Invaders** how many individual space invaders you want, keep
this number quite low to avoid slowing down your page too much

* **Invader Size** size of the invaders

* **Fly Time** how long the invaders fly around before pausing in
milliseconds (1000 = 1 second)

* **Pause Time** How long the invaders Pause (or wiggle see below) for
between flying cycles.

* **Random** adds some variation to the time settings so each invader
acts a little differently.

* **Wiggle** Instead of pausing the invaders will do a missile evasive
wiggle.

* **Attack Mode** None, One or All of the invaders will home in on any
mouse clicks.

By default the invaders will be free to roam randomly around the entire page,
but there are a couple of methods to confine them to specific areas if you wish.

* **Containing Element** the invaders will only roam within the element set
here, normally that's body (the whole page) but you may enter the id of an
element on the page to confine them to a specific area. You can see this in
effect right here where the invaders have been limited to the section at the
top of the page.

* **Z-Index** If you'd like the invaders to hide behind certain layers on
your page you can set an appropriate z-index here, obviously this setting it
very dependant on the structure of your WordPress theme.

* **Use Electric Fence** Check this option and use the top, left, right
and bottom options to define a virtual fence that will contain the invaders.
The values you enter represent pixels position on the entire document. Note,
this option will override any custom setting for Containing Element.

== Installation ==

Either install and activate the plugin via your WordPress Admin

Or

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from
Plugins page.

When first installed the Jellyfish Invaders plugin will show some invaders on
every page of your blog. You can easily change this to your liking by visiting
the setting page, you will find a link to the settings page in the Appearance
menu of your WordPress admin.

== Frequently Asked Questions ==


== Screenshots ==


== Changelog ==

= 0.9 =
* Tested up to WordPress 4.0
* Added Containing Element option: Confine invaders to a specific page element
* Added Z-index support: Configure invaders z-index for layering in document
* Updated Admin screen
* Updated Spritely version

= 0.8.1 =
* changed the way scripts are queued to ensure JavaScript is only included when invaders are actually needed.

= 0.6 =
* Initial release


== Upgrade Notice ==

There should be no issues upgrading from a previous version. Visit the plugin
settings page in Admin > Appearance > jellyfish Invaders to configure new
options.
