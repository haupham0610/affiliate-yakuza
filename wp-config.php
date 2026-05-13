<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'K5pfsT7pFwt=Y62Y7w%OWBb4OVUu):~C=7Ztybcjo7srHl>Q(}]Pe4ulGv$tEFyP' );
define( 'SECURE_AUTH_KEY',   'kFwGRfMkNP?_)$Ey^WVnA1,>@WDIi=e@iI+3Y>?hrs+l?oTE/kP.]dwo_=Q!?yY>' );
define( 'LOGGED_IN_KEY',     ';`cU hxc0V>c-m$!Jg/v*i]8u|3?*J~YY@]N5B+}L05|Se~8X3N3BUsg->,rzPdl' );
define( 'NONCE_KEY',         'b5Vc-XB|Y*hN?}jlg?~}DdujS~DvQ}tUw1`&M?JOY&Y^**b)[7%( {];f8BPf gq' );
define( 'AUTH_SALT',         '+aH52!vV&9z6Zss)v?(vP2_;@>bY?704roeeMmA:%hO/,L4tCRnQ||@U*Rc__5Lp' );
define( 'SECURE_AUTH_SALT',  '1YievuR`,}u.!dN#A{dN)>76qjpJNllp5gTfG1r5X{pgo%-1 ghH>!1g1ys2&UH(' );
define( 'LOGGED_IN_SALT',    'tLw/ZaHkj(m$wo.n:$H{Xs~oQ8([!cV|=R,fqO{STI[v`>mSg^](x>T@=!;{>xWs' );
define( 'NONCE_SALT',        '`P_Kz[&pdEU!]A4(WM)|*_?y1i:sXrc,2C?VLx>eqYKhT&Eb*3Y9oe^HB)!z8O7X' );
define( 'WP_CACHE_KEY_SALT', '!_,8k??IRe;%|#@<Se!^ZINZ_#YDaxi|sFggEEnA(ecUPgo/4+d90 Z8P(,%&5=S' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
// if ( ! defined( 'WP_DEBUG' ) ) {
// 	define( 'WP_DEBUG', false );
// }

define( 'WP_DEBUG', false );
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', '0' );

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
