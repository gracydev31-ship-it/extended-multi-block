<?php
/**
 * Plugin Name:       Extended Multi Block
 * Description:       Companion multi-block plugin for RunPartner. Hosts scaffold blocks + future blocks.
 * Version:           0.1.0
 * Requires at least: 6.8
 * Requires PHP:      7.4
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       extended-multi-block
 *
 * @package CreateBlock
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers blocks from the manifest with progressive compatibility.
 */
function register_extended_blocks() {
	$build_dir = __DIR__ . '/build/blocks';
	$manifest  = __DIR__ . '/build/blocks-manifest.php';

	// No blocks built yet — gracefully skip.
	if ( ! file_exists( $manifest ) ) {
		return;
	}

	if ( function_exists( 'wp_register_block_types_from_metadata_collection' ) ) {
		wp_register_block_types_from_metadata_collection( $build_dir, $manifest );
	} elseif ( function_exists( 'wp_register_block_metadata_collection' ) ) {
		wp_register_block_metadata_collection( $build_dir, $manifest );
		$manifest_data = require $manifest;
		foreach ( array_keys( $manifest_data ) as $block_type ) {
			register_block_type_from_metadata( $build_dir . '/' . $block_type );
		}
	} else {
		$manifest_data = require $manifest;
		foreach ( array_keys( $manifest_data ) as $block_type ) {
			register_block_type_from_metadata( $build_dir . '/' . $block_type );
		}
	}
}
add_action( 'init', 'register_extended_blocks' );

/**
 * Enqueues global editor script.
 */
function extended_enqueue_editor_assets() {
	$asset_file = include plugin_dir_path( __FILE__ ) . 'build/editor-script.asset.php';

	wp_enqueue_script(
		'extended-editor-script',
		plugin_dir_url( __FILE__ ) . 'build/editor-script.js',
		$asset_file['dependencies'],
		$asset_file['version'],
		false
	);
}
add_action( 'enqueue_block_editor_assets', 'extended_enqueue_editor_assets' );

/**
 * Enqueues global frontend script.
 */
function extended_enqueue_frontend_assets() {
	$asset_file = include plugin_dir_path( __FILE__ ) . 'build/frontend-script.asset.php';

	wp_enqueue_script(
		'extended-frontend-script',
		plugin_dir_url( __FILE__ ) . 'build/frontend-script.js',
		$asset_file['dependencies'],
		$asset_file['version'],
		true
	);
}
add_action( 'wp_enqueue_scripts', 'extended_enqueue_frontend_assets' );
