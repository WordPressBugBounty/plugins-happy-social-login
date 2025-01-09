=== Happy Social Login ===
Contributors: wpfolk
Tags: social login, google login, facebook login, linkedin login, github login
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.5.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Enables user authentication through various social media accounts. Login through Google, Facebook, LinkedIn, GitHub and more.

== Changelog ==

= 1.5.0 - 2025-01-09 =
* Release free version to Wordpress

= 1.4.9 - 2024-12-12 =
* Updated Freemius SDK, php/jwt
* Fix svg sanitization

= 1.4.8 - 2024-11-26 =
* Added nonce verification to is_being_deactivate and fix debug.php
* Fix readme to include 3rd party services privacy policy
* Used Freemius uninstaller
* Fix translation issue
* Fix inline script sanitization
* Fix svg sanitization

= 1.4.7 - 2024-11-04 =
* Fixed: Uploaded SVG on Elementor Social Login Widget not working
* Fixed: Rewrite rules get cleared on deactivation of other plugins resulting /sso/google to 404 not found
* Fixed: Z-index issue on social media logos
* New: Added a id attribute to each button so that users can target a particular provider and change its style
* Fixed: Responsive control issue

= 1.4.6 - 2024-11-03 =
* Fixed Apple Logo missing from Elementor's Social Login widget
* Fixed Google Logo missing from Elementor's Social Login widget
* Added responsiveness to Elementor's social login widget
* Fixed Facebook provider: Renamed client id to app id and app secret

= 1.4.5 - 2024-11-02 =
* Added Signin with Apple

= 1.4.4 - 2024-09-05 =
* Fixed Wordpress Plugin Code standard issue
* Compatibilty tested with latest veriosn of Wordpress, Elementor

= 1.4.3 - 2024-05-16 =
* Added Facebook, Google, Github, LInkedin Login option
* Added support for Elementor

== Description ==
Let your users signup and login to your WordPress website using their favorite social media accounts Facebook, Google, LinkedIn, Github and 42+ more. Happy Social Login is a free, easy-to-use WordPress plugin that makes registration and login a breeze. With just its social profiles (like Facebook, Google, or X (formerly Twitter)), your visitors can quickly sign up and log in to your site. No lengthy forms, no waiting for validation emails, and no more forgotten passwords. It's simple, fast, and user-friendly!

###🔗 Useful Links
[Official Page](https://wpfolk.com/plugins/happy-social-login) || [Demo](https://playground.wordpress.net/?plugin=happy-social-login) || [Documentation](https://wpfolk.com/docs/happy-social-login)

==Key Features:==
1. Quick registration and login via Facebook, Google, LinkedIn, and Github
2. Easy integration with WordPress user accounts
3. Customizable redirect URLs after registration and login
4. Display social profile pictures as avatars
5. Simple setup and user-friendly interface
6. Helpful support for any questions or issues
7. Additional Features in the Pro Version:
8. Compatibility with WooCommerce, BuddyPress, UserPro, and more
9. Access to additional providers like Amazon, PayPal, and more
10. Control over email and username collection during registration
11. Different login layouts and button styles
12. Role-based access control for social logins
13. Automatic assignment of user roles based on social login provider

== Installation ==
1. Search for Happy Social Login in the WordPress plugin directory.
2. Click Install Now and then Activate.
3. Go to Settings > Happy Social Login to configure your providers.
4. Test and enable your desired providers.

== Disclaimer ==
Happy Social Login is an independent plugin and is not affiliated with or endorsed by
any of the third-party services mentioned in this documentation, including but not limited 
to Facebook, Google, Twitter, LinkedIn, GitHub, and others. All trademarks, service marks, 
and company names are the property of their respective owners. We do not hold any copyright 
over the APIs or services provided by these third parties. Any use of these services is subject 
to their respective terms of use and privacy policies. Users are responsible for complying with 
the terms of the third-party services they choose to enable through this plugin.

Happy Social Login relies on third-party services for authentication. When a user logs in using a 
social media account, their data is sent to the respective third-party service for authentication. 
Below is a list of the services used, along with their respective links to privacy policies:

###🔗 Privacy Policy Links
[X](https://twitter.com/en/privacy) || [Google](https://policies.google.com/privacy) || [Facebook](https://www.facebook.com/policy.php)

== Frequently Asked Questions ==
= Is Happy Social Login GDPR compliant? =
Yes, it provides tools to help make your site GDPR compliant.

= Where are the social login buttons displayed? =
They are shown on the WordPress login page and other forms created with a special action. You can also use widgets or shortcodes to place them anywhere you want.

= How can I get email addresses from Twitter users? =
Refer to our docs for instructions on configuring your app settings.

= Why are random usernames generated? =
Sometimes usernames can not be created from social media names, so the plugin generates unique ones instead.

= What should I do if I have issues? =
Contact our support team for help!

= Can I translate the plugin? =
Yes, check our docs for details on translation.

= How can I suggest a feature? =
Just reach out to our support team with your ideas!

= Does Happy Social Login work with BuddyPress? =
While the free version doesn\'t have specific BuddyPress settings, users can still log in via the WordPress login page. The Pro version allows customization for the BuddyPress register form.