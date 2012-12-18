<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('WP_CACHE', true); //Added by WP-Cache Manager
define( 'WPCACHEHOME', '/srv/www/kube-dev/wordpress/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'kubemain');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'panther786');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '=2z_F8&oVvMn^XdoB:QJEH%?y/jh^_u+cpPx%[R]9zU=0T,+(r+we LbmtVU>WiP');
define('SECURE_AUTH_KEY',  '9*0O#!-{:CJ]673_oBSIzO(*+PmA8KKPiZ4G!Ezt:G^Q~B~(o;jU0j;Wae2u$Vhm');
define('LOGGED_IN_KEY',    'd?OoGV9.j?9OdT/xD2; _z%fZ|&NUEAYmiOH%c//q~q94J3o5jdVn4wI.h1/Smk6');
define('NONCE_KEY',        ' ac+oP/;WPE5OT+MD<6u7vu#cyDw_WtPak#k,aaJYvk $I]V@KwVZGNbEM<p$1 }');
define('AUTH_SALT',        '5enBVNev3PY~rkfp8@2Dg?US70-tx%gl5*d_05iii/%`)}r9=S*-TBU`,TC=uz!b');
define('SECURE_AUTH_SALT', 'upN(f^b2-MF)CH%YV}fLl5?abc2[{YC2DF:jJch6R.wGtvEdSQ6[Id1YR?0b.s&]');
define('LOGGED_IN_SALT',   '2?_ikgYDrq3k9OPU::n!**Y1sCWj)G:/5|-`J$[b6>y<ov_U3mT+HX#mdVx<1I!C');
define('NONCE_SALT',       'QS6~jC3$fvJ``gxH|&6R}Ksi,l_LHtgd^zeh:nHHJ_-_~KnyP:$gft2*{[jb&md;');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_kubemain_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
