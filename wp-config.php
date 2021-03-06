<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link    https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */
// ** MySQL settings - You can get this info from your web host ** //
define('DB_NAME', 'wp_coolpad');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key
 * service} You can change these at any point in time to invalidate all existing cookies. This will force all users to
 * have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'jjryGi|i(1bvQ,YpISJ`y&-^5Ir$3Gk6&H3#mXVL0|q7o)@u?P{%0s~NJn^{WX2W');
define('SECURE_AUTH_KEY', '5?G13Sqr2D04i;kK 3(Jk+i@ZNExwRrsMER)Ep7Zqc P}Zl3SIrhOk6tLkp![oIF');
define('LOGGED_IN_KEY', '6#+7O .V^QPAR13;O+Fi1^Rzv%5}BMHQ#q v+?65,m>v5n?)kTR J:4q!pd/~Ow4');
define('NONCE_KEY', 'kqS,6vf&6Z(-0=@WXtO|I5c^j|q#X*$P6-L~z/j3~?ZchfAKd?bNp0.,N:+U$O!]');
define('AUTH_SALT', '_=UY #gJk,*@L,Gp|.LpUeNvY}F48+WSwpEEUEUpb/xSarr5/?iP~LK&L +a[xA4');
define('SECURE_AUTH_SALT', '#Kk:Z5CcSRxbczF7pEAT< |@ScH_o#h8EC_m1@#z~Y]7c7)}]2[}xr$v[u|,+p7/');
define('LOGGED_IN_SALT', 'm3i{#>+;@S@g[sfh$J3J:bY7YTp=ynX/]Y5+rZR0l0ytz}TmrF@;fdoVB:04-!!!');
define('NONCE_SALT', 'q3,/Vc7Xa=QT&&N{7pKP}dV!quQG9lAdu-BD4)Oa;)A`c4(q{bKJ}ixmE$@IQ!4C');
/**#@-*/
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';
/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', FALSE);

/* Multisite */
define('WP_ALLOW_MULTISITE', TRUE);
define('MULTISITE', TRUE);
define('SUBDOMAIN_INSTALL', FALSE);
define('DOMAIN_CURRENT_SITE', 'localhost');
define('PATH_CURRENT_SITE', '/coolpad/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

/* That's all, stop editing! Happy blogging. */
/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
	define('ABSPATH', dirname(__FILE__) . '/');
}
/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
