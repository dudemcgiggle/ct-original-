<?php
/**
 * Twenty Twenty-Three override/functions.php
 *
 * Disables TT3’s block-template support so WordPress uses page.php / single.php.
 * Also enqueues Adobe Fonts (Sofia Pro Variable) and Google Fonts (Poppins).
 * Adds support for uploading SVG, WebP, and HEIC image types.
 * Prevents image downscaling and forces GD over Imagick for reliability.
 *
 * ⚠️ ANY TT3 UPDATE WILL OVERWRITE THIS FILE ⚠️
 * Keep a local copy so you can reapply after updating TT3.
 */

/**
 * 1) Disable TT3’s block-template support (so WP will use page.php / single.php).
 */
add_action( 'after_setup_theme', function() {
    remove_theme_support( 'block-templates' );
}, 11 );

/**
 * 2) Enqueue Adobe Fonts Kit (Sofia Pro Variable) via Typekit.
 *
 *    Replace 'dcl5phc' with your actual Typekit Kit ID if different.
 */
add_action( 'wp_enqueue_scripts', function() {
    $typekit_kit_id = 'dcl5phc';  // ← your Typekit “Kit ID”
    $typekit_url    = "https://use.typekit.net/{$typekit_kit_id}.css";

    wp_enqueue_style(
        'adobe-sofia-pro',        // unique handle
        esc_url( $typekit_url ),  // URL to your Typekit CSS
        array(),                  // no dependencies
        null                      // no version (so browsers can cache)
    );
} );

/**
 * 3) (Optional) Enqueue Google Fonts (Poppins).
 *    Remove this block entirely if you do not need Poppins.
 */
add_action( 'wp_enqueue_scripts', function() {
    $poppins_url = 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap';

    wp_enqueue_style(
        'google-fonts-poppins',
        esc_url( $poppins_url ),
        array(),
        null
    );
} );

/**
 * 4) Allow uploads of additional media types (SVG, WebP, HEIC).
 */
add_filter( 'upload_mimes', function( $mimes ) {
    $mimes['svg']  = 'image/svg+xml';
    $mimes['webp'] = 'image/webp';
    $mimes['heic'] = 'image/heic';
    $mimes['heif'] = 'image/heif';
    return $mimes;
} );

/**
 * 5) (Optional) Temporarily allow unfiltered uploads for unsupported types.
 *    ⚠️ Only enable this if absolutely needed and disable afterward.
 */
// define('ALLOW_UNFILTERED_UPLOADS', true);

/**
 * 6) Disable WordPress's big image downscaling (default 2560px cap).
 */
add_filter( 'big_image_size_threshold', '__return_false' );

/**
 * 7) Prefer GD over Imagick if Imagick causes resource/memory errors.
 */
add_filter( 'wp_image_editors', function( $editors ) {
    return array( 'WP_Image_Editor_GD', 'WP_Image_Editor_Imagick' );
} );
