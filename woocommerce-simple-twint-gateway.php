<?php
/**
 * Plugin Name:       		WooCommerce Simple Twint Gateway
 * Plugin URI:        		https://1daywebsite.ch
 * Description:       		A simple way to add Twint to the checkout
 * Version:           		1.0.0
 * Author:            		AFB
 * Author URI:        		https://1daywebsite.ch
 * Tested up to:		6.5.5 
 * WC requires at least:	3.0
 * WC tested up to:		9.0.2
 * Text Domain:       		woocommerce-simple-twint-gateway
 * Domain Path: 		/languages
 * License:           		GPL-2.0+
 * License URI:       		http://www.gnu.org/licenses/gpl-2.0.txt
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$active_plugins = apply_filters('active_plugins', get_option('active_plugins'));

if( simple_twint_gateway_is_woocommerce_active() ){
	/**
	* Add new payment gateway class: WC_Simple_Twint_Gateway
	*/
	add_filter('woocommerce_payment_gateways', 'add_simple_twint_gateway');
	function add_simple_twint_gateway( $gateways ){
		$gateways[] = 'WC_Simple_Twint_Gateway';
		return $gateways; 
	}
	/**
	* Load class file and load plugin text domain
	*/
	add_action('plugins_loaded', 'init_simple_twint_gateway');
	function init_simple_twint_gateway(){
		require 'class-woocommerce-simple-twint-gateway.php';
	}
	add_action( 'plugins_loaded', 'simple_twint_gateway_load_plugin_textdomain' );
	function simple_twint_gateway_load_plugin_textdomain() {
	  load_plugin_textdomain( 'woocommerce-simple-twint-gateway', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
	}
}
/**
* Check if WooCommerce is active, otherwise don't run plugin
* @return bool
*/
function simple_twint_gateway_is_woocommerce_active() {
	$active_plugins = (array) get_option('active_plugins', array());
	if (is_multisite()) {
		$active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
	}
	return in_array('woocommerce/woocommerce.php', $active_plugins) || array_key_exists('woocommerce/woocommerce.php', $active_plugins);
}
