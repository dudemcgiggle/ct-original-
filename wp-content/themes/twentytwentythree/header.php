<?php
/**
 * header.php for Twenty Twenty-Three (overrides block-based header)
 *
 * This file “loads” the original block‐template header.html and outputs it,
 * so your site still looks exactly like TT3’s default header. We use
 * apply_filters('the_content', file_get_contents(...)) to render the blocks.
 *
 * IMPORTANT: Since TT3 updates WILL overwrite this file, keep a backup locally.
 */

// Debug marker: uncomment if you want to verify this PHP is loaded
// echo "<!-- TT3 header.php loaded -->";
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
// Hook for plugins or other code to inject immediately after <body>
wp_body_open();

// 2) Now “include” the block‐template header:
$header_file = get_template_directory() . '/block-templates/header.html';
if ( file_exists( $header_file ) ) {
    // apply 'the_content' filter so all inner blocks are properly rendered
    echo apply_filters( 'the_content', file_get_contents( $header_file ) );
}
// If for some reason header.html is missing, you could fall back to a simpler header:
// else {
//     echo '<header><a href="'.esc_url(home_url('/')).'">'.get_bloginfo('name').'</a></header>';
// }
?>
