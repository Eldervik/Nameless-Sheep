<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


if ( ! class_exists( 'AWS_Admin' ) ) :

/**
 * Class for plugin admin panel
 */
class AWS_Admin {

    /*
     * Name of the plugin settings page
     */
    var $page_name = 'aws-options';

    /**
     * @var AWS_Admin The single instance of the class
     */
    protected static $_instance = null;


    /**
     * Main AWS_Admin Instance
     *
     * Ensures only one instance of AWS_Admin is loaded or can be loaded.
     *
     * @static
     * @return AWS_Admin - Main instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /*
    * Constructor
    */
    public function __construct() {

        add_action( 'admin_menu', array( &$this, 'add_admin_page' ) );
        add_action( 'admin_init', array( &$this, 'register_settings' ) );

        if ( ! AWS_Admin_Options::get_settings() ) {
            $default_settings = AWS_Admin_Options::get_default_settings();
            update_option( 'aws_settings', $default_settings );
        }

        add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts' ) );

    }

    /**
     * Add options page
     */
    public function add_admin_page() {
        add_menu_page( esc_html__( 'Adv. Woo Search', 'advanced-woo-search' ), esc_html__( 'Adv. Woo Search', 'advanced-woo-search' ), 'manage_options', 'aws-options', array( &$this, 'display_admin_page' ), 'dashicons-search' );
    }

    /**
     * Generate and display options page
     */
    public function display_admin_page() {

        $nonce = wp_create_nonce( 'plugin-settings' );

        $tabs = array(
            'general' => esc_html__( 'General', 'advanced-woo-search' ),
            'form'    => esc_html__( 'Search Form', 'advanced-woo-search' ),
            'results' => esc_html__( 'Search Results', 'advanced-woo-search' )
        );

        $current_tab = empty( $_GET['tab'] ) ? 'general' : sanitize_text_field( $_GET['tab'] );

        $tabs_html = '';

        foreach ( $tabs as $name => $label ) {
            $tabs_html .= '<a href="' . admin_url( 'admin.php?page=aws-options&tab=' . $name ) . '" class="nav-tab ' . ( $current_tab == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';

        }

        $tabs_html .= '<a href="https://advanced-woo-search.com/?utm_source=plugin&utm_medium=settings-tab&utm_campaign=aws-pro-plugin" class="nav-tab premium-tab" target="_blank">' . esc_html__( 'Get Premium', 'advanced-woo-search' ) . '</a>';

        $tabs_html = '<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">'.$tabs_html.'</h2>';

        if ( isset( $_POST["Submit"] ) && current_user_can( 'manage_options' ) && isset( $_POST["_wpnonce"] ) && wp_verify_nonce( $_POST["_wpnonce"], 'plugin-settings' ) ) {
            AWS_Admin_Options::update_settings();
        }

        echo '<div class="wrap">';

        echo '<h1></h1>';

        echo '<h1 class="aws-instance-name">Advanced Woo Search</h1>';

        echo $tabs_html;

        echo '<form action="" name="aws_form" id="aws_form" method="post">';

        switch ($current_tab) {
            case('form'):
                new AWS_Admin_Fields( 'form' );
                break;
            case('results'):
                new AWS_Admin_Fields( 'results' );
                break;
            default:
                $this->update_table();
                new AWS_Admin_Fields( 'general' );
        }

        echo '<input type="hidden" name="_wpnonce" value="' . esc_attr( $nonce ) . '">';

        echo '</form>';

        echo '</div>';

    }

    /*
     * Reindex table
     */
    private function update_table() {

        echo '<table class="form-table">';
        echo '<tbody>';

        echo '<tr>';

            echo '<th>' . esc_html__( 'Activation', 'advanced-woo-search' ) . '</th>';
            echo '<td>';
                echo '<div class="description activation">';
                    echo esc_html__( 'In case you need to add plugin search form on your website, you can do it in several ways:', 'advanced-woo-search' ) . '<br>';
                    echo '<div class="list">';
                        echo '1. ' . esc_html__( 'Enable a "Seamless integration" option ( may not work with some themes )', 'advanced-woo-search' ) . '<br>';
                        echo '2. ' . sprintf( esc_html__( 'Add search form using shortcode %s', 'advanced-woo-search' ), "<code>[aws_search_form]</code>" ) . '<br>';
                        echo '3. ' . esc_html__( 'Add search form as widget for one of your theme widget areas. Go to Appearance -> Widgets and drag&drop AWS Widget to one of your widget areas', 'advanced-woo-search' ) . '<br>';
                        echo '4. ' . sprintf( esc_html__( 'Add PHP code to the necessary files of your theme: %s', 'advanced-woo-search' ), "<code>&lt;?php if ( function_exists( 'aws_get_search_form' ) ) { aws_get_search_form(); } ?&gt;</code>" ) . '<br>';
                    echo '</div>';
                echo '</div>';
            echo '</td>';

        echo '</tr>';

        echo '<tr>';

            echo '<th>' . esc_html__( 'Reindex table', 'advanced-woo-search' ) . '</th>';
            echo '<td>';
                echo '<div id="aws-reindex"><input class="button" type="button" value="' . esc_attr__( 'Reindex table', 'advanced-woo-search' ) . '"><span class="loader"></span><span class="reindex-progress">0%</span></div><br><br>';
                echo '<span class="description">' .
                    sprintf( esc_html__( 'This action only need for %s one time %s - after you activate this plugin. After this all products changes will be re-indexed automatically.', 'advanced-woo-search' ), '<strong>', '</strong>' ) . '<br>' .
                    __( 'Update all data in plugins index table. Index table - table with products data where plugin is searching all typed terms.<br>Use this button if you think that plugin not shows last actual data in its search results.<br><strong>CAUTION:</strong> this can take large amount of time.', 'advanced-woo-search' ) . '<br><br>' .
                    esc_html__( 'Products in index:', 'advanced-woo-search' ) . '<span id="aws-reindex-count"> <strong>' . AWS_Helpers::get_indexed_products_count() . '</strong></span>';
                echo '</span>';
            echo '</td>';

        echo '</tr>';


        echo '<tr>';

            echo '<th>' . esc_html__( 'Clear cache', 'advanced-woo-search' ) . '</th>';
            echo '<td>';
                echo '<div id="aws-clear-cache"><input class="button" type="button" value="' . esc_attr__( 'Clear cache', 'advanced-woo-search' ) . '"><span class="loader"></span></div><br>';
                echo '<span class="description">' . esc_html__( 'Clear cache for all search results.', 'advanced-woo-search' ) . '</span>';
            echo '</td>';

        echo '</tr>';

        echo '</tbody>';
        echo '</table>';

    }

    /*
	 * Register plugin settings
	 */
    public function register_settings() {
        register_setting( 'aws_settings', 'aws_settings' );
    }

    /*
	 * Get plugin settings
	 */
    public function get_settings() {
        $plugin_options = get_option( 'aws_settings' );
        return $plugin_options;
    }

    /*
     * Enqueue admin scripts and styles
     */
    public function admin_enqueue_scripts() {

        if ( isset( $_GET['page'] ) && $_GET['page'] == 'aws-options' ) {
            wp_enqueue_style( 'plugin-admin-style', AWS_URL . '/assets/css/admin.css', array(), AWS_VERSION );
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'jquery-ui-sortable' );
            wp_enqueue_script( 'plugin-admin-scripts', AWS_URL . '/assets/js/admin.js', array('jquery'), AWS_VERSION );
            wp_localize_script( 'plugin-admin-scripts', 'aws_vars', array(
                'ajaxurl' => admin_url( 'admin-ajax.php', 'relative' ),
                'ajax_nonce' => wp_create_nonce( 'aws_admin_ajax_nonce' ),
            ) );
        }

    }

}

endif;


add_action( 'init', 'AWS_Admin::instance' );