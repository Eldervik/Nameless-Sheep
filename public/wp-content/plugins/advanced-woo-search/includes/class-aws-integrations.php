<?php
/**
 * AWS plugin integrations
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_Integrations' ) ) :

    /**
     * Class for main plugin functions
     */
    class AWS_Integrations {

        private $data = array();

        /**
         * @var AWS_Integrations The single instance of the class
         */
        protected static $_instance = null;

        /**
         * Main AWS_Integrations Instance
         *
         * Ensures only one instance of AWS_Integrations is loaded or can be loaded.
         *
         * @static
         * @return AWS_Integrations - Main instance
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Constructor
         */
        public function __construct() {

            //add_action('woocommerce_product_query', array( $this, 'woocommerce_product_query' ) );

            if ( class_exists( 'BM' ) ) {
                add_action( 'aws_search_start', array( $this, 'b2b_set_filter' ) );
            }

            // Protected categories
            if ( class_exists( 'WC_PPC_Util' )
                && method_exists( 'WC_PPC_Util', 'showing_protected_categories' )
                && method_exists( 'WC_PPC_Util', 'to_category_visibilities' )
                && method_exists( 'WC_PPC_Util', 'get_product_categories' )
            ) {
                add_action( 'aws_search_start', array( $this, 'wc_ppc_set_filter' ) );
            }

            if ( function_exists( 'dfrapi_currency_code_to_sign' ) ) {
                add_filter( 'woocommerce_currency_symbol', array( $this, 'dfrapi_set_currency_symbol_filter' ), 10, 2 );
            }

            // WC Marketplace - https://wc-marketplace.com/
            if ( defined( 'WCMp_PLUGIN_VERSION' ) ) {
                add_filter( 'aws_search_data_params', array( $this, 'wc_marketplace_filter' ), 10, 3 );
                add_filter( 'aws_search_pre_filter_products', array( $this, 'wc_marketplace_products_filter' ), 10, 2 );
            }

            // Maya shop theme
            if ( defined( 'YIW_THEME_PATH' ) ) {
                add_action( 'wp_head', array( $this, 'myashop_head_action' ) );
            }

            // Porto theme
            add_filter( 'porto_search_form_content', array( $this, 'porto_search_form_content_filter' ) );

            add_filter( 'aws_terms_exclude_product_cat', array( $this, 'filter_protected_cats_term_exclude' ) );
            add_filter( 'aws_exclude_products', array( $this, 'filter_products_exclude' ) );

            // Seamless integration
            if ( AWS()->get_settings( 'seamless' ) === 'true' ) {

                add_filter( 'aws_js_seamless_selectors', array( $this, 'js_seamless_selectors' ) );

                add_filter( 'et_html_main_header', array( $this, 'et_html_main_header' ) );
                add_filter( 'et_html_slide_header', array( $this, 'et_html_main_header' ) );
                add_filter( 'generate_navigation_search_output', array( $this, 'generate_navigation_search_output' ) );
                add_filter( 'et_pb_search_shortcode_output', array( $this, 'divi_builder_search_module' ) );
                add_filter( 'et_pb_menu_shortcode_output', array( $this, 'divi_builder_search_module' ) );
                add_filter( 'et_pb_fullwidth_menu_shortcode_output', array( $this, 'divi_builder_search_module' ) );
                add_action( 'wp_head', array( $this, 'head_js_integration' ) );

                // Ocean wp theme
                if ( class_exists( 'OCEANWP_Theme_Class' ) ) {
                    add_action( 'wp_head', array( $this, 'oceanwp_head_action' ) );
                }

                // Avada theme
                if ( class_exists( 'Avada' ) ) {
                    add_action( 'wp_head', array( $this, 'avada_head_action' ) );
                }

            }

            // Wholesale plugin hide certain products
            if ( class_exists( 'WooCommerceWholeSalePrices' ) ) {
                add_filter( 'aws_search_results_products', array( $this,'wholesale_hide_products' ) );
            }

            // Search Exclude plugin
            if ( class_exists( 'SearchExclude' ) ) {
                add_filter( 'aws_index_product_ids', array( $this, 'search_exclude_filter' ) );
            }

            // WooCommerce Product Table plugin
            if ( class_exists( 'WC_Product_Table_Plugin' ) ) {
                add_filter( 'wc_product_table_data_config', array( $this, 'wc_product_table_data_config' ) );
                add_filter( 'aws_posts_per_page', array( $this, 'wc_product_table_posts_per_page' ) );
            }

        }

        /*
         * B2B market plugin
         */
        public function b2b_set_filter() {

            $args = array(
                'posts_per_page' => - 1,
                'post_type'      => 'customer_groups',
                'post_status'    => 'publish',
            );

            $posts           = get_posts( $args );
            $customer_groups = array();
            $user_role       = '';

            foreach ( $posts as $customer_group ) {
                $customer_groups[$customer_group->post_name] = $customer_group->ID;
            }

            if ( is_user_logged_in() ) {
                $user = wp_get_current_user();
                $role = ( array ) $user->roles;
                $user_role = $role[0];
            } else {
                $guest_slugs = array( 'Gast', 'Gäste', 'Guest', 'Guests', 'gast', 'gäste', 'guest', 'guests' );
                foreach( $customer_groups as $customer_group_key => $customer_group_id ) {
                    if ( in_array( $customer_group_key, $guest_slugs ) ) {
                        $user_role = $customer_group_key;
                    }
                }
            }

            if ( $user_role ) {

                if ( isset( $customer_groups[$user_role] ) ) {
                    $curret_customer_group_id = $customer_groups[$user_role];

                    $whitelist = get_post_meta( $curret_customer_group_id, 'bm_conditional_all_products', true );

                    if ( $whitelist && $whitelist === 'off' ) {

                        $products_to_exclude = get_post_meta( $curret_customer_group_id, 'bm_conditional_products', false );
                        $cats_to_exclude = get_post_meta( $curret_customer_group_id, 'bm_conditional_categories', false );

                        if ( $products_to_exclude && ! empty( $products_to_exclude ) ) {

                            foreach( $products_to_exclude as $product_to_exclude ) {
                                $this->data['exclude_products'][] = trim( $product_to_exclude, ',' );
                            }

                        }

                        if ( $cats_to_exclude && ! empty( $cats_to_exclude ) ) {

                            foreach( $cats_to_exclude as $cat_to_exclude ) {
                                $this->data['exclude_categories'][] = trim( $cat_to_exclude, ',' );
                            }

                        }

                    }

                }

            }

        }

        /*
         * Protected categories plugin
         */
        public function wc_ppc_set_filter() {

            $hidden_categories = array();
            $show_protected	   = WC_PPC_Util::showing_protected_categories();

            // Get all the product categories, and check which are hidden.
            foreach ( WC_PPC_Util::to_category_visibilities( WC_PPC_Util::get_product_categories() ) as $category ) {
                if ( $category->is_private() || ( ! $show_protected && $category->is_protected() ) ) {
                    $hidden_categories[] = $category->term_id;
                }
            }

            if ( $hidden_categories && ! empty( $hidden_categories ) ) {

                foreach( $hidden_categories as $hidden_category ) {
                    $this->data['exclude_categories'][] = $hidden_category;
                }

                $args = array(
                    'posts_per_page'      => -1,
                    'fields'              => 'ids',
                    'post_type'           => 'product',
                    'post_status'         => 'publish',
                    'ignore_sticky_posts' => true,
                    'suppress_filters'    => true,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field'    => 'id',
                            'terms'    => $hidden_categories
                        )
                    )
                );

                $exclude_products = get_posts( $args );

                if ( $exclude_products && count( $exclude_products ) > 0 ) {

                    foreach( $exclude_products as $exclude_product ) {
                        $this->data['exclude_products'][] = $exclude_product;
                    }

                }

            }

        }

        /*
         * Datafeedr WooCommerce Importer plugin
         */
        public function dfrapi_set_currency_symbol_filter( $currency_symbol, $currency ) {

            global $product;
            if ( ! is_object( $product ) || ! isset( $product ) ) {
                return $currency_symbol;
            }
            $fields = get_post_meta( $product->get_id(), '_dfrps_product', true );
            if ( empty( $fields ) ) {
                return $currency_symbol;
            }
            if ( ! isset( $fields['currency'] ) ) {
                return $currency_symbol;
            }
            $currency_symbol = dfrapi_currency_code_to_sign( $fields['currency'] );
            return $currency_symbol;

        }

        /*
         * WC Marketplace plugin support
         */
        public function wc_marketplace_filter( $data, $post_id, $product ) {

            $wcmp_spmv_map_id = get_post_meta( $post_id, '_wcmp_spmv_map_id', true );

            if ( $wcmp_spmv_map_id ) {

                if ( isset( $data['wcmp_price'] ) && isset( $data['wcmp_price'][$wcmp_spmv_map_id] )  ) {

                    if ( $product->get_price() < $data['wcmp_price'][$wcmp_spmv_map_id] ) {
                        $data['wcmp_price'][$wcmp_spmv_map_id] = $product->get_price();
                        $data['wcmp_lowest_price_id'][$wcmp_spmv_map_id] = $post_id;
                    }

                } else {
                    $data['wcmp_price'][$wcmp_spmv_map_id] = $product->get_price();
                }

                $data['wcmp_spmv_product_id'][$wcmp_spmv_map_id][] = $post_id;

            }

            return $data;

        }

        /*
         * WC Marketplace plugin products filter
         */
        public function wc_marketplace_products_filter( $products_array, $data ) {

            $wcmp_spmv_exclude_ids = array();

            if ( isset( $data['wcmp_spmv_product_id'] ) ) {

                foreach( $data['wcmp_spmv_product_id'] as $wcmp_spmv_map_id => $wcmp_spmv_product_id ) {

                    if ( count( $wcmp_spmv_product_id ) > 1 ) {

                        if ( isset( $data['wcmp_lowest_price_id'] ) && isset( $data['wcmp_lowest_price_id'][$wcmp_spmv_map_id] ) ) {

                            foreach ( $wcmp_spmv_product_id as $wcmp_spmv_product_id_n ) {

                                if ( $wcmp_spmv_product_id_n === $data['wcmp_lowest_price_id'][$wcmp_spmv_map_id] ) {
                                    continue;
                                }

                                $wcmp_spmv_exclude_ids[] = $wcmp_spmv_product_id_n;

                            }

                        } else {

                            foreach ( $wcmp_spmv_product_id as $key => $wcmp_spmv_product_id_n ) {

                                if ( $key === 0 ) {
                                    continue;
                                }

                                $wcmp_spmv_exclude_ids[] = $wcmp_spmv_product_id_n;

                            }

                        }

                    }

                }

            }

            $new_product_array = array();

            foreach( $products_array as $key => $pr_arr ) {

                if ( ! in_array( $pr_arr['id'], $wcmp_spmv_exclude_ids ) ) {
                    $new_product_array[] = $pr_arr;
                }

            }

            return $new_product_array;

        }

        /*
         * Maya shop theme support
         */
        public function myashop_head_action() { ?>

            <style>
                #header .aws-container {
                    margin: 0;
                    position: absolute;
                    right: 0;
                    bottom: 85px;
                }

                @media only screen and (max-width: 960px) {
                    #header .aws-container {
                        bottom: 118px !important;
                        right: 10px !important;
                    }
                }

                @media only screen and (max-width: 600px) {
                    #header .aws-container {
                        position: relative !important;
                        bottom: auto !important;
                        right: auto !important;
                        display: inline-block !important;
                        margin-top: 20px !important;
                        margin-bottom: 20px !important;
                    }
                }
            </style>

        <?php }

        /*
         * Ocean wp theme
         */
        public function oceanwp_head_action() { ?>

            <style>
                .oceanwp-theme #searchform-header-replace .aws-container {
                    padding-right: 45px;
                    padding-top: 15px;
                }
                .oceanwp-theme #searchform-overlay .aws-container {
                    position: absolute;
                    top: 50%;
                    left: 0;
                    margin-top: -33px;
                    width: 100%;
                    text-align: center;
                }
                .oceanwp-theme #searchform-overlay .aws-container form {
                    position: static;
                }

                .oceanwp-theme #searchform-overlay a.search-overlay-close {
                    top: -100px;
                }

            </style>

            <script>

                window.addEventListener('load', function() {

                    window.setTimeout(function(){
                        var formOverlay = document.querySelector("#searchform-overlay form");
                        if ( formOverlay ) {
                            formOverlay.innerHTML += '<a href="#" class="search-overlay-close"><span></span></a>';
                        }
                    }, 300);

                    jQuery(document).on( 'click', 'a.search-overlay-close', function (e) {

                        jQuery( '#searchform-overlay' ).removeClass( 'active' );
                        jQuery( '#searchform-overlay' ).fadeOut( 200 );

                        setTimeout( function() {
                            jQuery( 'html' ).css( 'overflow', 'visible' );
                        }, 400);

                        jQuery( '.aws-search-result' ).hide();

                    } );

                }, false);

            </script>

        <?php }

        /*
         * Avada wp theme
         */
        public function avada_head_action() { ?>

            <style>

                .fusion-flyout-search .aws-container {
                    margin: 0 auto;
                    padding: 0;
                    width: 100%;
                    width: calc(100% - 40px);
                    max-width: 600px;
                    position: absolute;
                    top: 40%;
                    left: 20px;
                    right: 20px;
                }

            </style>

            <script>

                window.addEventListener('load', function() {
                    var awsSearch = document.querySelectorAll(".fusion-menu .fusion-main-menu-search a, .fusion-flyout-menu-icons .fusion-icon-search");
                    if ( awsSearch ) {
                        for (var i = 0; i < awsSearch.length; i++) {
                            awsSearch[i].addEventListener('click', function() {
                                window.setTimeout(function(){
                                    document.querySelector(".fusion-menu .fusion-main-menu-search .aws-search-field, .fusion-flyout-search .aws-search-field").focus();
                                }, 100);
                            }, false);
                        }
                    }

                }, false);

            </script>

        <?php }

        /*
         * Porto theme seamless integration
         */
        public function porto_search_form_content_filter( $markup ) {

            if ( AWS()->get_settings('seamless') === 'true' ) {
                $pattern = '/(<form[\S\s]*?<\/form>)/i';
                $markup = preg_replace( $pattern, aws_get_search_form( false ), $markup );
            }

            return $markup;

        }

        /*
         * Exclude product categories
         */
        public function filter_protected_cats_term_exclude( $exclude ) {
            if ( isset( $this->data['exclude_categories'] ) ) {
                foreach( $this->data['exclude_categories'] as $to_exclude ) {
                    $exclude[] = $to_exclude;
                }
            }
            return $exclude;
        }

        /*
         * Exclude products
         */
        public function filter_products_exclude( $exclude ) {
            if ( isset( $this->data['exclude_products'] ) ) {
                foreach( $this->data['exclude_products'] as $to_exclude ) {
                    $exclude[] = $to_exclude;
                }
            }
            return $exclude;
        }

        public function woocommerce_product_query( $query ) {

            $query_args = array(
                's'                => 'a',
                'post_type'        => 'product',
                'suppress_filters' => true,
                'fields'           => 'ids',
                'posts_per_page'   => 1
            );

            $query = new WP_Query( $query_args );
            $query_vars = $query->query_vars;

            $query_args_options = get_option( 'aws_search_query_args' );

            if ( ! $query_args_options ) {
                $query_args_options = array();
            }

            $user_role = 'non_login';

            if ( is_user_logged_in() ) {
                $user = wp_get_current_user();
                $role = ( array ) $user->roles;
                $user_role = $role[0];
            }

            $query_args_options[$user_role] = array(
                'post__not_in' => $query_vars['post__not_in'],
                'category__not_in' => $query_vars['category__not_in'],
            );

            update_option( 'aws_search_query_args', $query_args_options );

        }

        /*
         * Divi theme seamless integration for header
         */
        public function et_html_main_header( $html ) {
            if ( function_exists( 'aws_get_search_form' ) ) {

                $pattern = '/(<form[\s\S]*?<\/form>)/i';
                $form = aws_get_search_form(false);

                if ( strpos( $html, 'aws-container' ) !== false ) {
                    $pattern = '/(<div class="aws-container"[\s\S]*?<form.*?<\/form><\/div>)/i';
                }

                $html = '<style>.et_search_outer .aws-container { float: right;margin-right: 40px;margin-top: 20px; }</style>' . $html;
                $html = trim(preg_replace('/\s\s+/', ' ', $html));
                $html = preg_replace( $pattern, $form, $html );

            }
            return $html;
        }

        /*
         * Generatepress theme support
         */
        public function generate_navigation_search_output( $html ) {
            if ( function_exists( 'aws_get_search_form' ) ) {
                $html = '<style>.navigation-search .aws-container .aws-search-form{height: 60px;} .navigation-search .aws-container{margin-right: 60px;} .navigation-search .aws-container .search-field{border:none;} </style>';
                $html .= '<script>
                     window.addEventListener("awsShowingResults", function(e) {
                         var links = document.querySelectorAll(".aws_result_link");
                         if ( links ) {
                            for (var i = 0; i < links.length; i++) {
                                links[i].className += " search-item";
                            }
                        }
                     }, false);
                    </script>';
                $html .= '<div class="navigation-search">' . aws_get_search_form( false ) . '</div>';
                $html = str_replace( 'aws-search-field', 'aws-search-field search-field', $html );
            }
            return $html;
        }

        /*
         * Divi builder replace search module
         */
        public function divi_builder_search_module( $output ) {
            if ( function_exists( 'aws_get_search_form' ) && is_string( $output ) ) {

                $pattern = '/(<form[\s\S]*?<\/form>)/i';
                $form = aws_get_search_form(false);

                if ( strpos( $output, 'aws-container' ) !== false ) {
                    $pattern = '/(<div class="aws-container"[\s\S]*?<form.*?<\/form><\/div>)/i';
                }

                $output = trim(preg_replace('/\s\s+/', ' ', $output));
                $output = preg_replace( $pattern, $form, $output );

            }
            return $output;
        }

        /*
         * Selector filter of js seamless
         */
        public function js_seamless_selectors( $selectors ) {

            // shopkeeper theme
            if ( function_exists( 'shopkeeper_theme_setup' ) ) {
                $selectors[] = '.site-search .woocommerce-product-search';
            }

            // ocean wp theme
            if ( class_exists( 'OCEANWP_Theme_Class' ) ) {
                $selectors[] = '#searchform-header-replace form';
                $selectors[] = '#searchform-overlay form';
            }

            return $selectors;

        }

        /*
         * Js seamless integration method
         */
        public function head_js_integration() {

            /**
             * Filter seamless integrations js selectors for forms
             * @since 1.85
             * @param array $forms Array of css selectors
             */
            $forms = apply_filters( 'aws_js_seamless_selectors', array() );

            if ( ! is_array( $forms ) || empty( $forms ) ) {
                return;
            }

            $forms_selector = implode( ',', $forms );

            ?>

            <script>

                window.addEventListener('load', function() {
                    var forms = document.querySelectorAll("<?php echo $forms_selector; ?>");

                    var awsFormHtml = <?php echo json_encode( str_replace( 'aws-container', 'aws-container aws-js-seamless', aws_get_search_form( false ) ) ); ?>;

                    if ( forms ) {

                        for ( var i = 0; i < forms.length; i++ ) {
                            if ( forms[i].parentNode.outerHTML.indexOf('aws-container') === -1 ) {
                                forms[i].outerHTML = awsFormHtml;
                            }
                        }

                        window.setTimeout(function(){
                            jQuery('.aws-js-seamless').each( function() {
                                jQuery(this).aws_search();
                            });
                        }, 1000);

                    }
                }, false);
            </script>

        <?php }

        /*
         * Wholesale plugin hide products
         */
        public function wholesale_hide_products( $products ) {

            $user_role = 'all';
            if ( is_user_logged_in() ) {
                $user = wp_get_current_user();
                $roles = ( array ) $user->roles;
                $user_role = $roles[0];
            }

            $all_registered_wholesale_roles = unserialize( get_option( 'wwp_options_registered_custom_roles' ) );
            if ( ! is_array( $all_registered_wholesale_roles ) ) {
                $all_registered_wholesale_roles = array();
            }

            $new_products_array = array();

            foreach( $products as $product ) {

                $custom_fields = get_post_meta( $product['id'], 'wwpp_product_wholesale_visibility_filter' );
                $custom_price = get_post_meta( $product['id'], 'wholesale_customer_wholesale_price' );

                if ( $custom_fields && ! empty( $custom_fields ) && $custom_fields[0] !== 'all' && $custom_fields[0] !== $user_role ) {
                    continue;
                }

                if ( is_user_logged_in() && !empty( $all_registered_wholesale_roles ) && isset( $all_registered_wholesale_roles[$user_role] )
                    && get_option( 'wwpp_settings_only_show_wholesale_products_to_wholesale_users', false ) === 'yes' && ! $custom_price ) {
                    continue;
                }

                $new_products_array[] = $product;

            }

            return $new_products_array;

        }

        /*
         * Remove products that was excluded with Search Exclude plugin ( https://wordpress.org/plugins/search-exclude/ )
         */
        public function search_exclude_filter( $products ) {

            $excluded = get_option('sep_exclude');

            if ( $excluded && is_array( $excluded ) && ! empty( $excluded ) && $products && is_array( $products ) ) {
                foreach( $products as $key => $product_id ) {
                    if ( false !== array_search( $product_id, $excluded ) ) {
                        unset( $products[$key] );
                    }
                }
            }

            return $products;

        }

        /*
         * Fix WooCommerce Product Table for search page
         */
        public function wc_product_table_data_config( $config ) {
            if ( isset( $_GET['type_aws'] ) && isset( $config['search'] ) ) {
                $config['search']['search'] = '';
            }
            return $config;
        }

        /*
         * WooCommerce Product Table plugin change number of products on page
         */
        public function wc_product_table_posts_per_page( $num ) {
            return 9999;
        }

    }

endif;