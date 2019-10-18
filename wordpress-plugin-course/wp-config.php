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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wpc' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '|2n(Xhn=<~`NZ8u+Q|e UDx{jGFa}_.*=$D`a#xhEFYE-5m5?Lbxl(~qg]t6QR=|' );
define( 'SECURE_AUTH_KEY',  'FC+DhkAt%2lpZ Z_y3qW)@n]>a`E^0}Am3Xg<Clcf9$=f:E<VZz$ T_=aaaB}DA`' );
define( 'LOGGED_IN_KEY',    '3ScEc(%of7<Le7)hC`zFwIDDZ,B5yojGMY5EY}i~Z9<Hwsp)N41h_mQ2OFc`@;j~' );
define( 'NONCE_KEY',        'lTD_ +uRwwT+y4:HDC-D&F?]sn[D2OYdH,|e8hX:?O)uM9}L%2xCj/SCqG&PNPT5' );
define( 'AUTH_SALT',        '^:?h6K.+t#2|4@&yMn}13?NpCM>~dj]k=z Oe0M/ACx=arVA(erCBfT.N$8r4Hld' );
define( 'SECURE_AUTH_SALT', 'apM^KIhq[qTnjCkDzEwM,d[M-623YmlSpS#&|au{QQIFCpUO+BO4;VYo<|aV~:OM' );
define( 'LOGGED_IN_SALT',   'J.R<ybu$WYYW|zH2FRy;8am3TS>qk_VlC;J/5j?X~?z]KTj*8j$8XRlk)YS9p39M' );
define( 'NONCE_SALT',       '4%|3MZb>ojS4Fy+XbMt&aiIL:8(T|oFx8I[0pvc8zrmy HQK]+}s}KJb,zO#{?`B' );

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
