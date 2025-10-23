<?php
define( 'WP_CACHE', true );

/**
 * The base configuration for WordPress
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings ** //
define( 'DB_NAME', 'u925346366_bT9wc' );
define( 'DB_USER', 'u925346366_EgoMr' );
define( 'DB_PASSWORD', 'mmFBBnVVxI' );
define( 'DB_HOST', '127.0.0.1' );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

// ** Authentication unique keys and salts ** //
define( 'AUTH_KEY',          'fzNVk0{p-,uZB)B6l_V|wiD,7<>^:pBT_]_oqP>wv%: qWwI>WkYy7vOhP%:BuCZ' );
define( 'SECURE_AUTH_KEY',   ':?75j^xnCnMJbzo`trvKz]`h1(iz=hQ>9G_6!8ebJ2H<//m~5M*FWYG-/nh$[Zwg' );
define( 'LOGGED_IN_KEY',     '>|~KB)#_eeei`gM.+^eEu0)isb4rJ OWa-fI5e!-QRvhtzC`MW6hlRZ>jiFJ|5$:' );
define( 'NONCE_KEY',         '=+RDW} 5!P(UVBjDNLZ{7I1CrtsPz^>b+p(lEMr>uD6;U1u{;LeLmlw%.z)rPdUA' );
define( 'AUTH_SALT',         '.R0}3[=14ncP/Fj2eUPVBtto=}N-WwZij6HO`O8/dhT>3Am{{AtB}pYy)>TO*`lp' );
define( 'SECURE_AUTH_SALT',  'TQJ76kMroWbfxhh&t/*{ujXNG#T~dvi5htu-:@H,%p~&?AxXot+6=bx;aW4|P LV' );
define( 'LOGGED_IN_SALT',    '@fpSKt,tMl@U^KAHy035Z%&I0@pzTNz*[6DOi{&~Bk&20G]~&?&TPSrB _A,6*&s' );
define( 'NONCE_SALT',        '|O<GLjTsO5N[-?_n{spg*&uXK/FAW6!=y(V}FSmH y7]+|3iQ{EQDE|>|+z2i?_T' );
define( 'WP_CACHE_KEY_SALT', 'asmJ]p0|[SRH_Ni|sG=_o3ZFs#qRTT9+<(M=u,Wpu,C_,j=OVkM(k=D7!u{Y0La#' );

// ** Database table prefix ** //
$table_prefix = 'wp_';

// ** PHP memory limits ** //
if ( ! defined( 'WP_MEMORY_LIMIT' ) ) {
	define( 'WP_MEMORY_LIMIT', '512M' );
}
if ( ! defined( 'WP_MAX_MEMORY_LIMIT' ) ) {
	define( 'WP_MAX_MEMORY_LIMIT', '512M' );
}
@ini_set( 'memory_limit', '512M' );

// ** Optional performance tweaks ** //
// define( 'AUTOSAVE_INTERVAL', 300 ); // Increase autosave interval.
// define( 'WP_POST_REVISIONS', 3 );   // Limit post revisions.

// ** Enable additional media uploads (SVG, WebP, HEIC, etc.) ** //
define( 'ALLOW_UNFILTERED_UPLOADS', true );

// ** Debugging and performance ** //
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define( 'FS_METHOD', 'direct' );
define( 'DISABLE_WP_CRON', true );
define( 'COOKIEHASH', '7c1917d01000d5abe18754bd457980fa' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );

/* That's all, stop editing! Happy publishing. */

// ** Absolute path to the WordPress directory. ** //
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

// ** Sets up WordPress vars and included files. ** //
require_once ABSPATH . 'wp-settings.php';
