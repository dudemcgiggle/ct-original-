<?php
/**
 * Plugin Name: Cache Purge Tool
 * Description: Adds an admin page for purging the WordPress cache.
 * Version: 1.0.0
 * Author: OpenAI Assistant
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Cache_Purge_Tool {
    const NONCE_ACTION      = 'cache_purge_tool_purge';
    const ACTION            = 'cache_purge_tool';
    const MENU_SLUG         = 'cache-purge-tool';
    const CONTEXT_FIELD     = 'cache_purge_tool_context';
    const CONTEXT_SITE      = 'site';
    const CONTEXT_NETWORK   = 'network';
    const RESULT_QUERY_ARG  = 'cache-purged';
    const RESULT_SUCCESS    = '1';
    const RESULT_FAILURE    = '0';

    public static function init() {
        add_action( 'admin_menu', array( __CLASS__, 'register_admin_menu' ) );
        add_action( 'network_admin_menu', array( __CLASS__, 'register_network_admin_menu' ) );
        add_action( 'admin_post_' . self::ACTION, array( __CLASS__, 'handle_purge_request' ) );
        add_action( 'admin_notices', array( __CLASS__, 'maybe_render_admin_notice' ) );
        add_action( 'network_admin_notices', array( __CLASS__, 'maybe_render_admin_notice' ) );
    }

    public static function register_admin_menu() {
        self::register_menu( self::CONTEXT_SITE );
    }

    public static function register_network_admin_menu() {
        self::register_menu( self::CONTEXT_NETWORK );
    }

    public static function render_admin_page() {
        $context     = self::get_current_context();
        $capability  = self::get_capability_for_context( $context );

        if ( ! current_user_can( $capability ) ) {
            wp_die( esc_html__( 'You do not have permission to access this page.', 'cache-purge-tool' ) );
        }

        $action_url = self::CONTEXT_NETWORK === $context ? network_admin_url( 'admin-post.php' ) : admin_url( 'admin-post.php' );
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Cache Purge', 'cache-purge-tool' ); ?></h1>
            <p><?php esc_html_e( 'Use the button below to manually purge the WordPress object cache.', 'cache-purge-tool' ); ?></p>
            <form method="post" action="<?php echo esc_url( $action_url ); ?>">
                <?php wp_nonce_field( self::NONCE_ACTION ); ?>
                <input type="hidden" name="action" value="<?php echo esc_attr( self::ACTION ); ?>" />
                <input type="hidden" name="<?php echo esc_attr( self::CONTEXT_FIELD ); ?>" value="<?php echo esc_attr( $context ); ?>" />
                <?php submit_button( __( 'Purge Cache', 'cache-purge-tool' ), 'primary', 'submit', false ); ?>
            </form>
        </div>
        <?php
    }

    public static function handle_purge_request() {
        $context    = self::get_submitted_context();
        $capability = self::get_capability_for_context( $context );

        if ( ! current_user_can( $capability ) ) {
            wp_die( esc_html__( 'You do not have permission to perform this action.', 'cache-purge-tool' ) );
        }

        check_admin_referer( self::NONCE_ACTION );

        $purged = self::purge_cache();

        $redirect_url = add_query_arg(
            array(
                'page'                      => self::MENU_SLUG,
                self::RESULT_QUERY_ARG      => $purged ? self::RESULT_SUCCESS : self::RESULT_FAILURE,
            ),
            self::get_admin_base_url_for_context( $context )
        );

        wp_safe_redirect( $redirect_url );
        exit;
    }

    public static function maybe_render_admin_notice() {
        $page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';

        if ( self::MENU_SLUG !== $page ) {
            return;
        }

        $status = isset( $_GET[ self::RESULT_QUERY_ARG ] ) ? sanitize_text_field( wp_unslash( $_GET[ self::RESULT_QUERY_ARG ] ) ) : '';

        if ( self::RESULT_SUCCESS === $status ) {
            printf(
                '<div class="notice notice-success is-dismissible"><p>%s</p></div>',
                esc_html__( 'Cache purged successfully.', 'cache-purge-tool' )
            );
        } elseif ( self::RESULT_FAILURE === $status ) {
            printf(
                '<div class="notice notice-error"><p>%s</p></div>',
                esc_html__( 'Unable to purge the cache. Please try again or contact an administrator.', 'cache-purge-tool' )
            );
        }
    }

    private static function register_menu( $context ) {
        $capability = self::get_capability_for_context( $context );

        add_menu_page(
            __( 'Cache Purge', 'cache-purge-tool' ),
            __( 'Cache Purge', 'cache-purge-tool' ),
            $capability,
            self::MENU_SLUG,
            array( __CLASS__, 'render_admin_page' ),
            'dashicons-update',
            60
        );
    }

    private static function get_current_context() {
        return is_network_admin() ? self::CONTEXT_NETWORK : self::CONTEXT_SITE;
    }

    private static function get_capability_for_context( $context ) {
        return self::CONTEXT_NETWORK === $context ? 'manage_network_options' : 'manage_options';
    }

    private static function get_submitted_context() {
        if ( ! isset( $_POST[ self::CONTEXT_FIELD ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
            return self::CONTEXT_SITE;
        }

        $context = sanitize_key( wp_unslash( $_POST[ self::CONTEXT_FIELD ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

        return self::CONTEXT_NETWORK === $context ? self::CONTEXT_NETWORK : self::CONTEXT_SITE;
    }

    private static function get_admin_base_url_for_context( $context ) {
        return self::CONTEXT_NETWORK === $context ? network_admin_url( 'admin.php' ) : admin_url( 'admin.php' );
    }

    private static function purge_cache() {
        if ( ! function_exists( 'wp_cache_flush' ) ) {
            return false;
        }

        $flushed = wp_cache_flush();

        return false !== $flushed;
    }
}

Cache_Purge_Tool::init();
