<?php
/**
 * single.php for Twenty Twenty-Three
 *
 * Because block templates are disabled, WP uses this
 * for all single posts. Elementor sees the_content() and works.
 *
 * ⚠️ Will be overwritten on TT3 updates—keep a backup.
 */

get_header();
?>

<div id="primary" class="content-area">
  <main id="main" class="site-main">
    <?php
    if ( have_posts() ) {
      while ( have_posts() ) {
        the_post();
        the_content(); // ← Elementor hooks in here
      }
    }
    ?>
  </main>
</div>

<?php
get_footer();
