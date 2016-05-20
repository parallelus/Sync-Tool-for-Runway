<?php
/*
	Extension Name: Sync Tool
	Extension URI: https://github.com/parallelus/Sync-Tool-for-Runway
	Version: 0.7.2
	Description: Easily manage remote WordPress installs and synchronize development and production servers.
	Author: Parallelus
	Author URI: http://para.llel.us
*/

// Settings
$fields = array(
	'var' => array( 'menu_url', 'menu_name', 'page_name', 'menu_permissions', 'slug', 'function' ),
	'array' => array()
);

$default = array();

$settings = array(
	'name' => __('Sync tool', 'runway'),
	'option_key' => $shortname.'sync-tool',
	'fields' => $fields,
	'default' => $default,
	'parent_menu' => 'settings',
	'menu_permissions' => 'administrator',
	'file' => __FILE__,
	'js' => array(
		FRAMEWORK_URL.'extensions/sync-tool/js/sync-tool-connections.js',
		'jquery-ui-core',
		'jquery-ui-dialog',
	),
	'css' => array(
		FRAMEWORK_URL.'extensions/sync-tool/css/style.css',
	),
);
global $sync_tool_admin, $sync_tool;

include 'object.php';
$sync_tool = new Sync_Tool_Object( $settings );

// Load admin components
if ( is_admin() ) {
	include 'settings-object.php';
	$sync_tool_admin = new Sync_Tool_Admin_Object( $settings );
}

?>
