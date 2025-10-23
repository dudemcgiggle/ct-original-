<?php
/**
 * footer.php for Twenty Twenty-Three (overrides block-based footer)
 *
 * Reads and outputs the original block‐template footer.html.
 * Keeps your site’s footer exactly as TT3 shipped it.
 *
 * ⚠️ Will be overwritten on TT3 updates—keep a backup.
 */

// Debug marker: uncomment if you want to verify this PHP is loaded
// echo "<!-- TT3 footer.php loaded -->";
?>

<?php
// 1) Output the block-based footer content:
$footer_file = get_template_directory() . '/block-templates/footer.html';
if ( file_exists( $footer_file ) ) {
    echo apply_filters( 'the_content', file_get_contents( $footer_file ) );
}
// 2) WP footer hook (scripts, analytics, etc.)
wp_footer();
?>
</body>
</html>
