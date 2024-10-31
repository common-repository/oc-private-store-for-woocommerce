<?php
/**
* Plugin Name: OC Private Store For Woocommerce
* Description: This plugin allows create OC Private Store For Woocommerce plugin.
* Version: 1.0
* Copyright: 2020
* Text Domain: oc-private-store-for-woocommerce
* Domain Path: /languages 
*/


if (!defined('ABSPATH')) {
	exit('-1');
}
if (!defined('PSFW_PLUGIN_NAME')) {
  define('PSFW_PLUGIN_NAME', 'OC Private Store For Woocommerce');
}
if (!defined('PSFW_PLUGIN_VERSION')) {
  define('PSFW_PLUGIN_VERSION', '1.0.0');
}
if (!defined('PSFW_PLUGIN_FILE')) {
  define('PSFW_PLUGIN_FILE', __FILE__);
}
if (!defined('PSFW_PLUGIN_DIR')) {
  define('PSFW_PLUGIN_DIR',plugins_url('', __FILE__));
}
if (!defined('PSFW_BASE_NAME')) {
    define('PSFW_BASE_NAME', plugin_basename(PSFW_PLUGIN_FILE));
}
if (!defined('PSFW_DOMAIN')) {
  define('PSFW_DOMAIN', 'oc-private-store-for-woocommerce');
}

if (!class_exists('PSFW')) {

  	class PSFW {

    	protected static $PSFW_instance;

      public static function load_plugin() {

        $args = array('orderby' => 'ID');
        $wp_user_query = new WP_User_Query($args);
        $users = $wp_user_query->results;
        foreach ($users as $value) {
          if($value->roles != 'administrator'){
            update_user_meta($value->ID, 'approval_confirmation', 'confirm_approve');
          }
        }
      }

    	public static function PSFW_instance() {
	      	if (!isset(self::$PSFW_instance)) {
	        	self::$PSFW_instance = new self();
	        	self::$PSFW_instance->init();
	        	self::$PSFW_instance->includes();
	      	}
	      	return self::$PSFW_instance;
	    }

	    function __construct() {
        	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        	add_action('admin_init', array($this, 'psfw_check_plugin_state'));
      }


    	function init() {	      	
    		add_action( 'admin_notices', array($this, 'psfw_show_notice'));
      	add_action( 'admin_enqueue_scripts', array($this, 'PSFW_load_admin_script_style'));
      	add_action( 'wp_enqueue_scripts',  array($this, 'PSFW_load_script_style'));
      	add_filter( 'plugin_row_meta', array( $this, 'psfw_plugin_row_meta' ), 10, 2 );
	    }

	    //Load all includes files
	    function includes() {
	      	 include_once('includes/psfw-common.php');
	      	 include_once('includes/psfw-backend.php');
	      	 include_once('includes/psfw-kit.php');
	      	 include_once('includes/psfw-frontend.php');
	        if ( ! class_exists( 'WP_List_Table' ) ) {
      			 require_once( PSFW_PLUGIN_FILE . 'wp-admin/includes/class-wp-list-table.php' );
      		}
	    }

	     function PSFW_load_admin_script_style() {
	      	wp_enqueue_style( 'psfw-backend-css', PSFW_PLUGIN_DIR.'/assets/css/psfw-backend.css', false, '1.0' );
	      	wp_enqueue_style( 'woocommerce_admin_styles-css', WP_PLUGIN_URL. '/woocommerce/assets/css/admin.css',false,'1.0',"all");
	      	wp_enqueue_script( 'psfw-backend-js', PSFW_PLUGIN_DIR . '/assets/js/psfw-backend-js.js', array( 'jquery', 'select2'));
      		wp_localize_script( 'ajaxloadpost', 'ajax_postajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
      		wp_enqueue_style('wp-color-picker' );
          wp_enqueue_script('wp-color-picker-alpha', PSFW_PLUGIN_DIR . '/assets/js/wp-color-picker-alpha.js', array( 'wp-color-picker' ), '1.0.0', true );
          $color_picker_strings = array(
              'clear'            => __( 'Clear', 'textdomain' ),
              'clearAriaLabel'   => __( 'Clear color', 'textdomain' ),
              'defaultString'    => __( 'Default', 'textdomain' ),
              'defaultAriaLabel' => __( 'Select default color', 'textdomain' ),
              'pick'             => __( 'Select Color', 'textdomain' ),
              'defaultLabel'     => __( 'Color value', 'textdomain' ),
          );
          wp_localize_script( 'wp-color-picker-alpha', 'wpColorPickerL10n', $color_picker_strings );
          wp_enqueue_script( 'wp-color-picker-alpha' );
	    }

	    function PSFW_load_script_style() {
	    	wp_enqueue_script('jquery', false, array(), false, false);
	    }

	    function psfw_show_notice() {
        	if ( get_transient( get_current_user_id() . 'wfcerror' ) ) {

          		deactivate_plugins( plugin_basename( __FILE__ ) );

          		delete_transient( get_current_user_id() . 'wfcerror' );

          		echo '<div class="error"><p> This plugin is deactivated because it require <a href="plugin-install.php?tab=search&s=woocommerce">WooCommerce</a> plugin installed and activated.</p></div>';
        	}
    	}

    	function psfw_plugin_row_meta( $links, $file ) {
            if ( PSFW_BASE_NAME === $file ) {
                $row_meta = array(
                    'rating'    =>  '<a href="https://www.xthemeshop.com/oc-private-store-for-woocommerce/" target="_blank">Documentation</a> | <a href="https://xthemeshop.com/contact/" target="_blank">Support</a> | <a href="https://wordpress.org/support/plugin/oc-private-store-for-woocommerce/reviews/?filter=5" target="_blank"><img src="'.PSFW_PLUGIN_DIR.'/images/star.png" class="psfw_rating_div"></a>'
                );
                return array_merge( $links, $row_meta );
            }
            return (array) $links;
        }

    	function psfw_check_plugin_state(){
      		if ( ! ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) ) {
        		set_transient( get_current_user_id() . 'wfcerror', 'message' );
      		}
    	}	

	}

  	add_action('plugins_loaded', array('PSFW', 'PSFW_instance'));
    
    register_activation_hook( __FILE__, array('PSFW', 'load_plugin' ));
}


add_action( 'plugins_loaded', 'PSFW_load_textdomain' );
function PSFW_load_textdomain() {
    load_plugin_textdomain( 'oc-private-store-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}
function PSFW_load_my_own_textdomain( $mofile, $domain ) {
    if ( 'oc-private-store-for-woocommerce' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
        $locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
        $mofile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/languages/' . $domain . '-' . $locale . '.mo';
    }
    return $mofile;
}
add_filter( 'load_textdomain_mofile', 'PSFW_load_my_own_textdomain', 10, 2 );
?>