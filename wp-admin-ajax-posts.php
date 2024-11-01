<?php
/**
 * Plugin Name: Wordpress Admin Ajax Posts
 * Description: A plugin for fast post management on backend area. Works with all types, products included.
 * Plugin URI: http://wpaap.dezatec.es
 * Author: Pablo Luaces <pablo@dezatec.es>
 * Author URI: https://dezatec.es
 * Version: 1.0
 * Text Domain: wp-admin-ajax-posts
 * License: GPL2
 */

define( 'WPAAP_VERSION', '1.0' );
define( 'WPAAP_PATH', dirname( __FILE__ ) );
define( 'WPAAP_PATH_INCLUDES', dirname( __FILE__ ) . '/inc' );
define( 'WPAAP_FOLDER', basename( WPAAP_PATH ) );
define( 'WPAAP_URL', plugins_url() . '/' . WPAAP_FOLDER );
define( 'WPAAP_URL_INCLUDES', WPAAP_URL . '/inc' );

class Wordpress_Admin_Ajax_Posts_Plugin_Base {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'WPAAP_admin_pages_callback' ));
		add_action( 'admin_enqueue_scripts', array( $this, 'WPAAP_add_admin_JS' ));
		add_action( 'admin_enqueue_scripts', array( $this, 'WPAAP_add_admin_CSS' ));
		add_action( 'plugins_loaded', array( $this, 'WPAAP_add_textdomain' ));
		add_action( 'admin_init', array( $this, 'WPAAP_register_settings' ), 5 );
		add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'WPAAP_plugins_page_links' ));
		add_action( 'post_submitbox_misc_actions', array( $this, 'WPAAP_publish_post_widget' ), 0);
	}

	public function WPAAP_publish_post_widget() {
		?>
			<div class="misc-pub-section misc-pub-post-ajax hide-if-no-js">
				<span><?php _e( "Ajax Save Enabled!", 'wp-admin-ajax-posts' ) ?></span> 
				<a href="<?php echo get_site_url(null, '/wp-admin/admin.php?page=wpaap_home'); ?>" role="button" target="_blank" class="wpaap_ajax_link"><span aria-hidden="true"><?php _e( 'About', 'wp-admin-ajax-posts' ) ?></span></a>
			</div>
		<?php
	}

	public function WPAAP_admin_pages_callback() {
		add_menu_page(__( "Wordpress Ajax Posts", 'wp-admin-ajax-posts' ), __( "Wordpress Ajax Posts", 'wp-admin-ajax-posts' ), 'edit_themes', 'wpaap_home', array( $this, 'WPAAP_plugin_about' ));		
	}

	public function WPAAP_plugins_page_links( $links ) {
	   $links[] = '<a href="' . get_site_url(null, '/wp-admin/admin.php?page=wpaap_home') . '">' . __( 'About', 'wp-admin-ajax-posts' ) . '</a>';
	   return $links;
	}
	
	public function WPAAP_add_admin_JS( $hook ) {
		wp_enqueue_script( 'jquery' );
		wp_register_script( 'wpaap-js-main-admin', plugins_url( '/js/wpaap-admin.min.js' , __FILE__ ), array('jquery'), '1.0', true );
		wp_enqueue_script( 'wpaap-js-main-admin' );
		wp_localize_script( 'wpaap-js-main-admin', 'wpaap_lang_vars', array(
			'close' => __('Hide', 'wp-admin-ajax-posts')
		));
	}
	
	public function WPAAP_add_admin_CSS( $hook ) {
		wp_register_style( 'wpaap-css-main-admin', plugins_url( '/css/wpaap-admin.min.css', __FILE__ ), array(), '1.0', 'screen' );
		wp_enqueue_style( 'wpaap-css-main-admin' );

		wp_register_style( 'wpaap-css-animate-admin', plugins_url( '/css/animate.min.css', __FILE__ ), array(), '1.0', 'screen' );
		wp_enqueue_style( 'wpaap-css-animate-admin' );
		
		if( 'toplevel_page_wpaap_home' === $hook ) {
			wp_register_style( 'wpaap-css-about-page',  plugins_url( '/css/about-page.min.css', __FILE__ ) );
			wp_enqueue_style( 'wpaap-css-about-page' );
		}
	}
	
	
	public function WPAAP_plugin_about() {
		include_once( WPAAP_PATH . '/inc/about-page.php' );
	}

	public function WPAAP_register_settings() {
		require_once( WPAAP_PATH . '/WPAAP-plugin-settings.class.php' );
		new Wordpress_Admin_Ajax_Posts_Plugin_Settings();
	}
	
	public function WPAAP_add_textdomain() {
		load_plugin_textdomain( 'wp-admin-ajax-posts', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}
}

// Initialize everything
$WPAAP_plugin_base = new Wordpress_Admin_Ajax_Posts_Plugin_Base();
