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
define( 'DB_NAME', 'nameless-sheep_test' );

/** MySQL database username */
define( 'DB_USER', 'nameless-sheep' );

/** MySQL database password */
define( 'DB_PASSWORD', '2d6wKvexeszUYs9j' );

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
define( 'AUTH_KEY',         '+=_eAXm$Uv_.c#ehPMu-GikY8S_Nn-UFDY19PT+jV9O}Op5E>nq!{Hp88P=RF/tX' );
define( 'SECURE_AUTH_KEY',  ' PM;Z/;Zcl0-[(4au%fa!86eK3b[RR;W|geBXUa%&EE:N-t]^9^I2E_5tLXG8Tmf' );
define( 'LOGGED_IN_KEY',    '<8`O^KFwG}EwL!N8>i*#l%9v(,oatEd*! F!$q<&z[sWgdUurECLm?G*ikbzhX4@' );
define( 'NONCE_KEY',        'wo_AN)Q>>h7X7 Zjk,N/Z)lD-a.RztvzL$6K!*Dg|ELp~:jtL5y]7aiO;}T{79WW' );
define( 'AUTH_SALT',        'M.Ri=Q@Pc:a+>XM&{Wyg$]=dSp4udEgs-@YOs)]/#VN5|g?Ycj9*sgH[r=U`jWAc' );
define( 'SECURE_AUTH_SALT', '6%DQ|Qw42z1[9Q4|c_nvR5(J;>eRX:u8-Xq}B8!ggLe KxV(p9BO@3.k%Iqqy3dJ' );
define( 'LOGGED_IN_SALT',   '3nK|&!L(85;BS}WTr$=/6U]+`jk*&8WMR%6IOzbTLs0eE(wC wsERx<P|[F@,R8)' );
define( 'NONCE_SALT',       '~@G>vc/5,5qTas6 7nz/Z}cy((Itz]0q|d``a&<AMQ07w~b$DI!.xs5)3l*F,lOK' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'ns_';

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
