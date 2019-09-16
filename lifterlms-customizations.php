<?php
/**
* Plugin Name: LifterLMS Customizations
* Plugin URI: https://lifterlms.com/
* Description: Add custom functions to LifterLMS or LifterLMS LaunchPad and preserve them when updating!
* Version: 1.0.0
* Author: codeBOX
* Author URI: http://gocodebox.com
* Text Domain: lifterlms-customizations
* Domain Path: /languages
* License:     GPLv2
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Requires at least: 4.0
* Tested up to: 4.6
*
* @package 		LifterLMS
* @category 	Core
* @author 		codeBOX
*/

if ( ! defined( 'ABSPATH' ) ) { exit; } // restrict direct access

/***********************************************************************
 *
 * Add custom functions below this comment
 *
 ***********************************************************************/
/**
 * 
 * Create Company Industry as a custom field upon registration or checkout
 * 
 * @param $fields - fields already being registered
 * @param $screen - either checkout or registration screen
 * @return $fields - array with added field
 */
function add_company_industry_lifterlms ( $fields , $screen ) {
	if( strcmp( $screen , 'checkout' ) == 0 ||
		strcmp( $screen , 'registration' ) == 0) {
		$company_industries = array(
			'industry_1' => 'Industry 1',
			'industry_2' => 'Industry 2',
			'industry_3' => 'Industry 3',
			'industry_4' => 'Industry 4',
			'industry_5' => 'Industry 5',
			'other' => 'Other'
		);
		$company_industry = array(
			'columns' => 12,
			'id' => 'llms_company_industry',
			'default' => 'other',
			'label' => __('Company Industry', 'lifterlms'),
			'last_column' => false,
			'options' => $company_industries,
			'required' => false,
			'type' => 'select',
		);
		array_push($fields, $company_industry);
	}
	return $fields;
}
add_filter( 'lifterlms_get_person_fields', 'add_company_industry_lifterlms', 10, 2);
/**
 * 
 * Create Company name as a custom field upon registration or checkout
 * 
 * @param $fields - fields already being registered
 * @param $screen - either checkout or registration screen
 * @return $fields - array with added field
 */
function add_company_name_lifterlms ( $fields , $screen ) {
	if( strcmp( $screen , 'checkout' ) == 0 ||
		strcmp( $screen , 'registration' ) == 0) {
		$company_name = array(
			'columns' => 12,
			'id' => 'llms_company_name',
			'label' => __('Company Name', 'lifterlms'),
			'last_column' => false,
			'required' => true,
			'type' => 'text'
		);
		array_push($fields, $company_name);
	}
	return $fields;
}
add_filter( 'lifterlms_get_person_fields', 'add_company_name_lifterlms', 10, 2);
/**
 *
 * Validate Company Name
 *
 * Company name should be at least 2 characters long
 *
 * @param $validated - current validation status
 * @param $data -  data being passed for validation
 * @param $screen - $screen should be registration or checkout
 * @return $validated - whether or not the company is valid
 */
function validate_company_name( $validated , $data, $screen ){
	if( strcmp( $screen , 'checkout' ) == 0 ||
	    strcmp( $screen , 'registration' ) == 0){
		// Make sure company name is at least characters long
		if( strlen( $data[ 'llms_company_name' ] ) < 2  ){
			return new WP_Error( 'error-code', 'Company Name needs to be at least 2 characters', 'my-text-domain' );
		}
	}
	return $validated;
}
add_filter( 'lifterlms_user_registration_data' , 'validate_company_name', 10 , 3 );
add_filter( 'lifterlms_user_update_data' , 'validate_company_name', 10 , 3 );
/**
 * 
 * Save company name to usermeta table
 * 
 * @param $person_id - id of user registering or checking out
 * @param $data -  data being passed through to be saved
 * @param $screen -  screen is either registration or checkout
 */
function save_custom_company_name( $person_id, $data , $screen ){
	update_user_meta( $person_id, 'llms_company_name', $data['llms_company_name'], true);
}
add_action( 'lifterlms_user_registered', 'save_custom_company_name', 10, 3);
add_action( 'lifterlms_user_updated', 'save_custom_company_name', 10, 3);
/**
 * 
 * Save company industry field to usermeta table
 * 
 * @param $person_id - id of user registering or checking out
 * @param $data -  data being passed through to be saved
 * @param $screen -  screen is either registration or checkout
 */
function save_custom_company_industry( $person_id, $data , $screen ){
	update_user_meta( $person_id, 'llms_company_industry', $data['llms_company_industry'], true);
}
add_action( 'lifterlms_user_registered', 'save_custom_company_industry', 10, 3);
add_action( 'lifterlms_user_updated', 'save_custom_company_industry', 10, 3);










/***********************************************************************
 *
 * Add custom functions above this comment
 *
 ***********************************************************************/

/**
 * Add this plugin's "templates" directory to the list of available override directories
 *
 * If you plan on including template overrides in this plugin, please uncomment the line immediately
 * below this function. It is commented out by default to prevent unnecessary lookups
 * for people who don't intend to include any template overrides in this plugin
 *
 * @param  array $dirs    Array of paths to directories to load LifterLMS templates from
 * @return array
 */
function llms_customizations_overrides_directory( $dirs ) {
	array_unshift( $dirs, plugin_dir_path( __FILE__ ) . '/templates' );
	return $dirs;
}
// add_filter( 'lifterlms_theme_override_directories', 'llms_customizations_overrides_directory', 10, 1 );
