=== Awesome Support - WordPress Support Plugin ===

Contributors: themeavenue,julien731,SiamKreative
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KADEESTQ9H3GW
Tags: support,helpdesk,tickets,ticketing,help,support staff,
Requires at least: 3.8
Tested up to: 4.6
Stable tag: 3.3.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The most versatile help desk and support plugin for WordPress. Provide awesome support directly from your WordPress site.

== Description ==

Awesome Support is the most versatile support plugin for WordPress. It’s the result of two years of work, research, and improvement. The features are an answer to user requests for a solid, WordPress-based help desk, and that's what makes it the best!

https://www.youtube.com/watch?v=IdSOWQI3tWU

For support please visit the [official site](http://getawesomesupport.com).

= Seamless Integration =

Awesome Support was built to be compatible with 99% of all existing themes, both free and commercial. It’s "plug & play" - all settings and templates are automatically switched on as soon as you click the "Activate" button, so you can get started with providing support straight away.

= Responsive Design =

Awesome Support provides an optimized viewing experience for easy reading and navigation with minimal resizing, panning, and scrolling. Do your clients regularly submit tickets on the go? Awesome Support is responsive, so they’ll enjoy a seamless experience from the convenience of their mobile phone (provided the theme you use is also responsive).

= Robust Code =

Version 3 of Awesome Support was built with flexibility in mind; the code is clean, well-documented and full of hooks. Customize Awesome Support to do almost anything you want or need.

Awesome Support relies on the [Titan Framework](http://www.titanframework.net/) to handle plugin options. This framework is built by expert WordPress developers and well-maintained on GitHub.

= What does it do? =

Check out the user-friendly features of this awesome plugin:

- **Ticketing**: users can submit tickets from the front-end, and your agents can help them from the WordPress back-end
- **E-mail notifications**: relevant people are notified of certain actions by e-mail, and all e-mails are customizable
- **Restricted access**: all correspondence is private between the client and the agents
- **File upload**: you control when files can be uploaded, how many files, and how large they are
- **Multiple products**: provide support for as many products as you want
- **Custom fields**: easily set up additional custom fields
- **Terms & conditions**: ask users to agree to your your terms and conditions before they open a ticket
- **Old tickets**: identify old tickets quickly with tags

= Extensions =

Awesome Support is already packed with features, but if you want to supercharge your support and make it even MORE awesome, our free and premium add-ons allow you to extend the plugin's functionality even further! All of our add-ons are extremely affordable (they’re a fraction of the cost of a web-based application). To check them out, visit our official site at [getawesomesupport.com](http://getawesomesupport.com?utm_source=wordpress.org&utm_medium=readme&utm_campaign=Extend).

**Popular extensions**

* [E-Mail Piping](http://getawesomesupport.com/addons/email-support/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=Extend) - reply to tickets by e-mail
* [WooCommerce](http://getawesomesupport.com/addons/woocommerce/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=Extend) - the bridge between your WooCommerce store and Awesome Support
* [Canned Responses](http://getawesomesupport.com/addons/canned-responses/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=Extend) - create replies to common questions, and access them in one click

= Roadmap =

Want to know what’s next for Awesome Support? [Check out the roadmap](https://trello.com/b/pHYdtkHc). You can also vote for the ideas you like best!

= Translations =

Awesome Support id available in French, Dutch, Hungarian, Italian, Portuguese, Spanish, Swedish, Persian, Dutch and Polish.

You want to translate Awesome Support in your language? [Head over to the Transifex project](https://www.transifex.com/projects/p/awesome-support/)!

== Installation ==

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'Awesome Support'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Download `awesome-support.zip` from this page
2. Navigate to the 'Add New' in the plugins dashboard
3. Navigate to the 'Upload' area
4. Select `awesome-support.zip` from your computer
5. Click 'Install Now'
6. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `awesome-support.zip` from this page
2. Extract the `awesome-support` directory to your computer
3. Upload the `awesome-support` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard

= Setup =

Whatever the method you used, after you activated the plugin through the plugins dashboard, the setup is done silently in the background.

Two new pages will be added to your site:

- My Tickets
- Submit ticket

Add those two pages to your menu in order to give your users easy access to their support area.

== Frequently Asked Questions ==

= I get a blank page after ticket submission =

This is most likely a permalinks issue. What you need to do is log into your WordPress admin, go to *Settings > Permalinks* and hit the *Save* button. You don't actually need to change anything, just hitting *Save* will refresh your permalinks structure, including the new `ticket` post type.

= The plugin data isn't removed from the database after uninstall =

If you want to delete the plugin and all of its data, you need to go to the *Advanced* tab in the plugin settings and check the *Delete Data* option. Only then the data will be removed from the database during the uninstall process.

= Users get "You do not have the capacity to open a new ticket" =

Normally, when a user registers through the plugin, he is given the role *Support User*. [This role has special capabilities](http://codex.wordpress.org/Roles_and_Capabilities).

If your users get the error message *"You do not have the capacity to open a new ticket"*, it means that they don't have the special capabilities.

Here you have two choices:

1. Change all your users' role to *Support User*
2. Give the role you want to use (eg. *Subscriber*) the special capabilities

If you don't know how to add new capabilities to a role, I suggest you use a plugin like [User Role Manager](https://wordpress.org/plugins/user-role-editor/) and give the desired role the following capabilities:

- `view_ticket`
- `create_ticket`
- `close_ticket`
- `reply_ticket`
- `attach_files`

**Do not give your users more than those 5 capabilities**, otherwise they could get administrative privileges.

= How to disable agent auto-assignment =

If you need to disable the auto-assignment function and hence have all new tickets assigned to the default agent (set in the plugin general settings), you can add this constant in your theme's `functions.php` file:

`
define( 'WPAS_DISABLE_AUTO_ASSIGN', true );
`

= How to set the product field as mandatory? =

If you have enabled multi-products support and want to make the "Product" field in the submission form mandatory, just add the following code snippet to your theme's `functions.php` file: https://gist.github.com/julien731/a519956ce9c81542439c

= How to change the tickets slug? =

By default, all tickets will be accessed through a URL of the type domain.com/ticket/my-ticket-title.

If you wish to change the slug `ticket` to something else, let's say `help`, so that your URLs look like domain.com/help/my-ticket-title, you need to add a constant in your theme's `functions.php` file as follows:

`
define( 'WPAS_SLUG', 'my_new_slug' );
`

= I don't receive e-mail notifications =

There are several factors that can influence e-mail notifications delivery. Please read this article for details: https://getawesomesupport.com/email-notifications-awesome-support-wordpress/

== Screenshots ==

1. Agent's view (tickets list)
2. Agent's view (ticket details)
3. Agent creates a ticket
4. Client view (tickets list)
5. Client view (ticket details)
6. Client creates a ticket
7. Settings page

== Changelog ==

== 3.3.1 - June 20, 2016 ==

* Bugfixes
	* Fix conflict with Ninja Forms
	* Fix fatal error with is_main_query()
	* Fix issue with PHP 5.2
	* Fix issue with file names when downloading attachments (props [IgorCode](https://github.com/IgorCode))
	* Filter user name in the user profile metabox
	* Hide "Terms & Conditions" checkbox when inactive
	* Show Awaiting Reply after a ticket is transferred

= 3.3.0 - May 30, 2016 =

* New
	* Add user profile to ticket details
	* Add logout link on front-end
	* Add a "Department" field
	* Add Ajax search to users list when editing tickets
	* Add support for select2 for dropdowns custom fields
	* Add support for data attributes on dropdowns
	* Add support for column attributes for custom fields
	* New e-mail template for tickets closed by the client
	* Add pagination to front-end tickets list
	* Introduce a `WPAS_Member` class

* Improvements
	* Admin tickets list
	* Move tickets with recent replies to the top in the admin tickets list (thanks <a href="https://github.com/mikeschinkel" target="_blank">Mike Schinkel</a>)
	* Better stakeholders metabok in ticket details
	* Redirect to tickets list after "quick closing" a ticket
	* Front-end tickets list
	* Better pagination in ticket details on front-end
	* Highlight agent replies in the conversation (front-end)
	* Filter ticket attachments out of the media library
	* Products synchronization can be disabled + selective sync
	* Better performance with large users databases
	* Stop using PHP session to avoid issues with site caching
	* Add visible "Lost your password" link on login form
	* Only show auto-assignment status for agents in WP users list
	* Many more small UX improvements

* Bugfixes
	* Redirect non logged-in users even if no tickets list page is set
	* Broken admin tickets list on mobiles
	* Accounts can no longer be created without an e-mail
	* Attachments can't be opened when Wordfence is enabled with the post-hack option
	* Many more bugfixes

= 3.2.9 - November 11, 2015 =

* Improvements
    * Add a link ot close ticket under reply box for agents

* Bugfixes
    * Fix issue with all tickets showing up in admin even though the option wasn't checked
    * Fix issue with client replies not appearing for agents
    * Fix issue with e-mail notifications not sent during Ajax
    * Fix issue with incorrect links to tickets in notifications sent during Ajax

= 3.2.8 - November 10, 2015 =

* Improvements
    * Improve e-commerce products synchronization to avoid errors
    * Remove `make_clickable()` and replace it by `Autolinker.js`
    * Improve caching of `wpas_get_tickets()`
    * Add a new filter for when a ticket is transfered from one agent to another (`wpas_ticket_assignee_changed`)

* Bugfixes
    * Update textdomain in translation files and when loading translations
    * Fix wrong label on login / registration buttons when clicked
    * Fix issue with custom taxonomies filters on ticket list screen (admin)
    * Remove space from filter `wpas_email_notifications_cases_active_option`
    * Prefix settings page name (fixes conflict with WordPress Download Manager)
    * Fix filtering by ticket status by removing the `author` parameter from the `WP_Query` (occured when plugin was set to only show own tickets in admin)
    * Fix wrong agent open tickets count caused by ticket transfer

= 3.2.7 - October 22, 2015 =
* Bugfix: Bug in RabbitVCS prevented unversioned files from being committed

= 3.2.6 - October 22, 2015 =

* New
    * Filter ticket replies controls in admin view
    * New hooks in admin reply submission process
    * New hook after reply submission form buttons

* Improvements
    * Smaller admin bar icon with open tickets count
    * Display correct message when user closes a ticket
    * Update textdomain for compatibility with WordPress language packs

* Bugfixes
    * Fix error with WooCommerce variable products
    * Fix PHP warning on new site acitvation (in multisite environments)
    * Fix issue with `nl_NL` translation

= 3.2.5 - October 2, 2015 =

* New
    * Introduction (very basic) of the singleton pattern
    * Introduce an admin notices class

* Improvements
    * Assign tickets later during ticket creation so that related product is known in the `wpas_find_available_agent` filter
    * Dynamically register user profile fields for more flexibility with addons
    * Make sure `$agent` is a `WP_User` object in the tickets list screen to avoid PHP notices
    * Remove editor background color upon validation
    * Change output markup for system status report for WordPress.org

* Bugfixes
    * Reply content validation in admin text editor
    * Correctly filter `wpas_can_submit_ticket`
    * Correctly display taxonomy label in admin no-edit mode

= 3.2.4 - September 28, 2015 =

* Bugfix
    * Fix issue with options not saving (update Titan Framework to 1.9.1)
    * Fix agents not being able to see tickets
    * Fix "open" status auto-selected in the filters even if it's not the case

= 3.2.3 - September 24, 2015 =

* Improvements
    * Extract string from JS to make it translatable
    * Allow clients to close a ticket without reply 

* Bugfix
    * Fix wpColorPicker by updating to Titan Framework 1.9

= 3.2.2 - September 21, 2015 =

* New
	* Filter taxonomies name
	* Wrapper function to get a user's tickets (`wpas_get_user_tickets()`)
	* Persian, Dutch and Polish translations

* Improvements
	* Make links clickable on both front/back end
	* Better `wpas_can_submit_ticket()` function
	* Better sanitize ticket content and replies in admin
	* About page footer layout
	* Use Grunt to release new versions

* Bugfix
	* Fix translation not working
	* Fix use of deprecated parameter in `wp_new_user_notification()`
	* Remove remaining deprecated `wpas_create_notification()`
	* Do not allow multiple tickets list pages
	* Fix registrations possible even if deactivated when using the correct POST data
	* Fix wrong data type used in system status to list special pages

= 3.2.1 - September 16, 2015 =

* Bugfix
	* All agents automatically set for auto-assignment
	* Switch errored login notifications to the new system


= 3.2.0 - September 16, 2015 =

* New
	* Custom field types: checkbox, date, email, number, password, radio, select, textarea, upload, URL, WYSYWYG
	* Ability to pre-populate submission forms fields
	* Multiple submission forms
	* Compatibility with [WordPress ReCaptcha Integration](https://wordpress.org/plugins/wp-recaptcha-integration/)
	* Add option to manually enable/disable auto-assignment for each agent/admin
	* Show auto-assignment status in users list table
	* Pagination on ticket details page for displaying replies (front-end)
	* Allow for disabling registration notification when disabled
	* Ability to copy system status report for WordPress.org forums directly
	* Add new filters to edit fields markup
	* Add support for a `functions.php` file in themes
	* Introduce a `WPAS_Agent` class for ease of work with agents and assignment
	* Test the plugin against PHP 7


* Improvements
	* Only agents and ticket author can view attachments
	* Complete rewrite of the custom fields system
	* Use custom fields for all front-end forms (so all fields can be customized at once)
	* Add front-end live validation for files upload
	* Send different notifications if ticket is closed by agent or client
	* Make links clickable in tickets and replies
	* Improve error messages management (drop the use of long URL vars)
	* Revamp notifications management (using the new session manager)
	* Improve the use of sessions (using Eric Mann's session manager)
	* Update to the latest version of Titan Framework
	* Add system tool to clean agents metas
	* Remove the use of all `extract()` functions for improved code clarity
	* Load unit test files recursively


* Bugfixes
	* Fix wrong post count in the tickets views (above the tickets list table in admin)
	* Fix conflict with Jetpack Publicize
	* Users dropdown lists not updating immediately after a user is created / deleted / modified
	* Remove duplicate notifications on submission form page
	* Clients can now upload files from any type specified in the plugin settings
	* Fix error loading stylesheets when `home_url` and `site_url` are different
	* Remove internal links from TinyMCE's link builder box on front-end
	* Fix two PHP 5.2 bugs (`T_PAAMAYIM_NEKUDOTAYIM` errors)

= 3.1.12 - July 3, 2015 =

* Features
	* Add new hook to filter who can view a ticket (`wpas_can_view_ticket`)
	* Add new filter to change the allowed file types layout for uploads (`wpas_attachments_filetypes_display`) - props [digitalchild](https://github.com/digitalchild)
	* Add new filter `wpas_before_login_form` - props [Vasik](https://github.com/vasikgreif)

* Bugfixes
	* Remove double notification on ticket submission page
	* Fix issue escaped characters in e-mail notifcations
	* Add support for sites using HTTPS on front-end but not admin
	* Fix issue with custom fields not showing in admin when using custom callback function
	* Style custom fields in admin
	* Fix addons not displaying in the addon page

* Translations
	* Portuguese (Brazil)

= 3.1.11 - June 5, 2015 =

* Features
	* Improve tests coverage
	* Add Portuguese (Brazil) translation

* Bugfixes
	* Error messages on plugin pages always show
	* Only synchronize e-commerce products that are published
	* Show all the synchronized products in the taxonomy screen
	* Remove a couple of PHP notices

= 3.1.10 - May 20, 2015 =

* More performance improvements
* Fixes "Got a packet bigger than ‘max_allowed_packet’ bytes" issue on sites with lots of users

= 3.1.9 - May 20, 2015 =

* Add an option to enable/disable the credit link

= 3.1.8 - May 19, 2015 =

* Features
	* Ticket submission on front-end is now about 50% faster
	* Significant performance improvement in the back-end

* Bugfixes:
	* Fix the PHP warning on ticket submission
	* Hide others tickets in admin if set this way in the settings
	* Fix issue with translations not working on some sites

= 3.1.7 - May 15, 2015 =

* Features:
	* Ticket ID in tickets list table (admin)
	* Direct link to last reply in tickets list table (admin)
	* New system tools - Delete / Resync products synchronized with an e-commerce plugin

* Translations:
	* Polish translation
	* Persian translation
	* Romanian translation

* Bugfixes:
	* Critical XSS vulnerability with custom information messages - props Anton Kulpinov
	* Critical vulnerability with shortcodes allowed in replies - props Anton Kulpinov
	* Call `wpautop()` correctly (there was a typo in the function name)
	* When a ticket is closed, check for user's capability to close it
	* Error messages correctly display on login / registration page - props [rudashi](https://github.com/rudashi)
	* No mor ecalls to deprecated function `update_usermeta()` - props [rudashi](https://github.com/rudashi)
	* Wrong URL to stylesheet when using baclslashes
	* Wrong tickets count in admin, the count doesn't include trashed ticket anymore
	* Correctly hide closed tickets if the option is enabled
	* Compatibility issue with WooCOmmerce Cart Reports

= 3.1.6 - March 19, 2015 =

* Add Croatian translation
* Correctly format the terms & conditions modal box content
* Load custom theme stylesheet if plugin's theme was customized
* Set the default theme
* Fix broken URL redirect after deleting a reply

= 3.1.5 - March 17, 2015 =

* Add new filters
* Add missing textdomains, localize a few forgotten strings, and update translation catalog
* Add a small API to handle admin notices and dismiss them
* Show user display name in users dropdowns and admin tickets list
* Show agent display name in ticket history (back-end)
* Send a confirmation e-mail to user when agent opens a ticket on his/her behalf
* Rewrite of the reply edition function in ticket edit screen (back-end) fixing a couple of bugs
* Fix issue in agent assignment function
* Fix issue with empty email subject when using Mandrill
* Fix the issue of settings page not being translated
* Fix issue with settings page not being reachable when the plugin is translated
* Fix issue with e-commerce products not saving correctly when multiple products is enabled
* Fix dates displayed incorrectly on front-end
* Fix uploads size limit applying on the entire WordPress site
* Fix "ticket closed" saved twice when replying and closing a ticket at the same time
* [More details about this update on our blog](http://getawesomesupport.com/bugfixes-3-1-5/)

= 3.1.4 =

* Fix compatibitily issue with WooCommerce

= 3.1.3 =

* Add Gist oEmbed support
* Add the `WPAS_DISABLE_AUTO_ASSIGN` to disable auto-assignment
* Add URL field for custom fields
* Show login form to non logged-in users on ticket details page
* Fix compatibility issue with WP Members
* Fix random agent assignment when re-assigning an open ticket
* Fix issue with e-mail notifications sent to the wrong agent
* Only show open tickets when filtering by status
* Fix bug preventing ticket with no replies from being deleted
* Don't show the current status when ticket is closed
* Fix bug with tags not filtering correctly in the admin
* Correctly load translations
* Add Select2 for users and tickets dropdowns
* Do not count trashed posts in the admin menu

= 3.1.2 =

* Fix issue with first reply being added twice
* Fix issue with HTML e-mail notifications if multiple notifications are sent by the same instance
* Use the user display name in the stakeholders metabox
* Set the ticket as "In Progress" after first reply in a more reliable way
* Add new hooks

= 3.1.1 =

* Do not override custom templates for the ticket details page
* Send HTML e-mails more reliably
* Bugfixes

= 3.1.0 =

* Add new filters before registering the post type
* Add a logging class that can be used for debugging purposes
* Add support for e-commerce plugins when multi-products is enabled (currently supports WooCommerce, Easy Digital Downloads, WP eCommerce and Jigoshop)
* Ask for a password only once on the registration form
* Add e-mail verification to the registration form (uses MailGun, free account required)
* Hide about page from the menu
* Allow e-mail to be used as the login for clients
* Improve agent assignment function
* Re-written and optimized e-mail notification class (with a wrapper function `wpas_email_notify()`)
* Filter subject and body on e-mail notifications
* Let the user specify a reply-to e-mail for notifications
* Make the display of ticket details more secure (to avoid conflicts with plugins/themes)
* Use users display name instead of user name everywhere on the site
* Don't display agents in the clients list of the stakeholders metabox
* Fix issue with the blank page after login
* Fixed some notices on the ticket single page
* Few bugfixes

= 3.0.1 =

* Display taxonomies drop-downs hierarchically
* Add new hooks in the user registration process ([81a278a](https://github.com/ThemeAvenue/Awesome-Support/commit/81a278a807d3d41bbfc9327908365f3eff07e34a))
* Filter the value returned by `wpas_get_option()`
* Minor bugfixes and improvements

= 3.0.0 =

* The Phoenix reborn. An entirely new version of Awesome Support

== Upgrade Notice ==

Bugfixes for latest version.

== Roadmap ==

Want to know what’s next for Awesome Support? [Check out the roadmap](https://trello.com/b/pHYdtkHc). You can also vote for the ideas you like best!

== Add-Ons ==

Need to extend Awesome Support's features? We have lots of add-ons to help you setup the perfect support site. [Check out our add-ons page](http://getawesomesupport.com/addons/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=Extend).

== Team ==

Even though the plugin has been developed and is maintained by ThemeAvenue, we've had help from various developers around the world. You can see all the contributors on the [team page](http://getawesomesupport.com/team/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=Extend). Many thanks to them all!
