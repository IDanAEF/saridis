<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'saridis' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'mysql-8.2' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'DAH%8s[5?xSM)_x YVw!.Dl4AottSGj*F6=w!C{wlJaV$7`Ww*7B|9rIV+EQmtFr' );
define( 'SECURE_AUTH_KEY',  'vu8K3Y;.DCRtIset2uAZ>2kA/?xrCx38oVv0nC];X|BGXp2ZBX&a3q7Ql!GeiMIS' );
define( 'LOGGED_IN_KEY',    'a44kmEYa e87tS@tJMC5tSY#0m_#a_x,a+mej6~:]!uA]Mz+$G^p{Y|2p=yq9:6`' );
define( 'NONCE_KEY',        'D|W&&62jZXO_]Z!go.^q?$X8h4m+)~!FN)ece_?D%=Ym^dlOs7/OB0dSzZ:fPs$^' );
define( 'AUTH_SALT',        '^$cdM<P?.Yx2TyE4c=u)j&st2NP=f8#5sc}S_B(4pB0NtX)[{eQGe`J>ln_+=qXK' );
define( 'SECURE_AUTH_SALT', '@|CASd8T<wht$%dW@b;X}W7HK=S]eAT&uk ihdp!6.-}$v=fxCD!NOpW80tRK_Do' );
define( 'LOGGED_IN_SALT',   'cJE&K}Jo#N{bL=<73S9K#^V)l]y`h<3UhcA]j++K6%(n$5^Ux$kbUll?-$TDiM)9' );
define( 'NONCE_SALT',       'H:l:iMvY$@x,lz8btWu(7~M:I)j+UcwO1@$hMff*^KoTFBzjq<_75LKv>?NDwQ[.' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
