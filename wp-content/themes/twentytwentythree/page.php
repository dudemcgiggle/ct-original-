<?php
/**
 * page.php for Twenty Twenty-Three
 *
 * Because we have disabled block templates, WP will use this
 * file for all “Pages”. Elementor sees the_content() and works.
 *
 * ⚠️ Will be overwritten on TT3 updates—keep a backup.
 */

// 1) Load the header (which itself outputs block-templates/header.html)
get_header();
?>

<div id="primary" class="content-area">
  <main id="main" class="site-main">
    <?php
    if ( have_posts() ) {
      while ( have_posts() ) {
        the_post();
        // ← THIS is why Elementor now works—this call gives it a “content area”
        the_content();
      }
    }
    ?>
  </main>
</div>

<?php
// 2) Load the footer (which outputs block-templates/footer.html)
get_footer();
