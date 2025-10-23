<?php
/**
 * Template Name: Elementor Full Width
 * Description: Loads header → content → footer with no extra TT3 block wrappers.
 * Template Post Type: page
 *
 * After saving, edit any Page in WP Admin and choose “Elementor Full Width”
 * from the “Template” dropdown. Elementor will then see the_content() here.
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

<?php get_footer(); ?>
