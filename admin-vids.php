<?php

/*
Plugin Name: WP Admin Vids
Version: 0.1
Plugin URI: http://dennishughes.ca/wp-admin-vids/
Description: Creates a "WP Admin Vids" Menu Item and Page in wp-admin with tools to add Youtube videos within your admin panel for quick access.
Author: Dennis Hughes
Author URI: http://dennishughes.ca
*/

// setup constant table var
define("DD_VIDS_TABLE", "devden_av_vids");

// loads Class
require 'vendor/autoload.php';

// Define Class Namespace
use Madcoda\Youtube;

// set the API Key Variable
$dd_yt_api_key = get_option( 'deven_yt_api_key' );

// Create the YT OBJ Defined by your API key var
$youtube = new Youtube(array('key' => $dd_yt_api_key));

/**
 * load custom scripts and styles
 */
add_action( 'admin_enqueue_scripts', 'devden_enqueue' );
function devden_enqueue() 
{
	wp_enqueue_script( 'bootstrap-js', '//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js', array('jquery'), true); // all the bootstrap javascript goodnes
    wp_enqueue_script( 'bootstrap-js-smooth', '//code.jquery.com/ui/1.11.4/jquery-ui.js', array('jquery'), true);
    wp_enqueue_script( 'devden_custom_script', plugin_dir_url( __FILE__ ) . 'js/ajax.js?randid='.rand().'' );
}

add_action('admin_enqueue_scripts', 'enqueue_devden_styles');
function enqueue_devden_styles() {
	wp_register_style( 'bootstrap', '//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css' );
	wp_enqueue_style( 'bootstrap');
	wp_register_style( 'bootstrap_smooth', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css' );
	wp_enqueue_style( 'bootstrap_smooth');
	wp_register_style( 'devdev-style', plugins_url('css/devden-style.css?randid='.rand().'', __FILE__) );
	wp_enqueue_style( 'devdev-style' );
}

/**
 * AJAX functions
 */


/**
 * Ajax callback function
 * Adds the YT API Key to the wordpress options table so the plugin can function
 */
add_action( 'wp_ajax_add_key', 'add_key_callback' );
function add_key_callback() {

	if (isset($_POST['yt_key'])) 
	{
		$key = $_POST['yt_key'];

		update_option( 'deven_yt_api_key', $key );
		$dd_api_key = "***************" . substr(get_option( 'deven_yt_api_key' ), 14, 14) . "***************";
		echo('Key: '.$dd_api_key);

	}else{
		echo('error no API Key was passed');
	}
	wp_die();
}

/**
 * Ajax callback function
 * Adds the YT code to the DB from the data entered into one of the forms 
 * Reloads the updated content in the selected div
 */
add_action( 'wp_ajax_add_code', 'add_code_callback' );
function add_code_callback() {
	global $wpdb; // Globalize the WP database object $wpdb

	$playlist_table = $wpdb->prefix .  DD_VIDS_TABLE;  // {$table}

	if (isset($_POST['yt_code']) && isset($_POST['yt_code_type'])) 
	{
		$code = $_POST['yt_code'];
		$code_type = $_POST['yt_code_type'];

		if ($code != "" && $code_type != "") 
		{
			$dd_insert_vid_playlist = $wpdb->insert( $playlist_table, array('code'=>$code,'code_type'=>$code_type),array('%s') );
		}

		switch ($code_type) {
			case 'playlist':
				devden_playlists($code_type);
				//echo "playlist update";
				break;
			case 'channel':
				devden_channels($code_type);
				break;
			case 'video':
				devden_videos($code_type);
				break;
		}

	}else{
		echo('error no playlist id was passed');
	}
	//return $return;
	wp_die();
}

add_action( 'wp_ajax_delete_vids', 'delete_vids_callback' );
function delete_vids_callback() {
	global $wpdb; // setup access to the database

	$link_table = $wpdb->prefix . DD_VIDS_TABLE;  // {$table}
	
	if (isset($_POST['delete_id'])) 
	{
		$delete_id = $_POST['delete_id'];
		$code_type = $_POST['delete_type'];

		if ($delete_id != "") 
		{
			$wpdb->delete( $link_table, array( 'id' => $delete_id ), array( '%d' ) );
		}

		switch ($code_type) {
			case 'playlist':
				devden_playlists($code_type);
				//echo "playlist update";
				break;
			case 'channel':
				devden_channels($code_type);
				break;
			case 'video':
				devden_videos($code_type);
				break;
		}

	}else{
		echo('error');
	}

	wp_die();
}


/**
 * [devden_playlists]
 * @param  SET $code_type ['channel','video','playlist']
 */
function devden_playlists($code_type) {
	
	global $wpdb; // setup access to the database

	$table = $wpdb->prefix . DD_VIDS_TABLE;  // {$table}

	$dd_playlist = $wpdb->get_results( "SELECT * FROM {$table} WHERE code_type = '".$code_type."'" );

	if ($dd_playlist) 
	{
		
		foreach ($dd_playlist as $k => $value) 
		{
			display_playlist($value->id,$value->code,$value->code_type);
		}

	}
	else
	{
		echo("No Playlists yet.");
	}

}

/**
 * [devden_channels]
 * @param  SET $code_type ['channel','video','channel']
 */
function devden_channels($code_type) {
	
	global $wpdb; // setup access to the database

	$table = $wpdb->prefix . DD_VIDS_TABLE;  // {$table}

	$dd_channel = $wpdb->get_results( "SELECT * FROM {$table} WHERE code_type = '".$code_type."'" );

	if ($dd_channel) 
	{
		
		foreach ($dd_channel as $k => $value) 
		{
			display_playlist($value->id,$value->code,$value->code_type);
		}

	}
	else
	{
		echo("No Channels yet.");
	}

}

/**
 * Outputs all Youtube vidoes by type
 * @param  SET $code_type ['channel','video','channel']
 */
function devden_videos($code_type) {
	
	global $wpdb; // setup access to the database
	global $youtube;

	$table = $wpdb->prefix . DD_VIDS_TABLE;  // {$table}

	$dd_video = $wpdb->get_results( "SELECT * FROM {$table} WHERE code_type = '".$code_type."'" );

	if ($dd_video) 
	{
		// define how many colums
		$columns = 6;
		$i = 0;
		$count = $dd_video;
		if ($count > 0) 
		{
			echo("<div class=\"row sort\">");
			foreach ($dd_video as $k => $value) 
			{
				$playlist = $youtube->getVideoInfo($value->code);
				//display_playlist($value->id,$value->code,$value->code_type);
				$trimmed_description = wp_trim_words( $playlist->snippet->description, $num_words = 25, $more = null );
				?>
				<!-- 
				Make sure the col-md-'*' width span * $columns == 12 
				$columns = 3 would require col-md-4, $columns = 6 would require col-md-2 ...
				-->
				<div class="col-md-2">
					<img src="<?php echo($playlist->snippet->thumbnails->medium->url) ?>" class="img-responsive img-thumbnail">
					<h4 style="word-wrap: break-word;"><?php echo($playlist->snippet->title) ?></h4>
					<p style="word-wrap: break-word;"><?php echo($trimmed_description) ?></p>
					<p><div class="btn-group btn-group-xs pull-right" role="group" aria-label="...">
						  <button type="button" class="btn btn-danger delete_id" id="<?php echo($value->id) ?>" value="<?php echo($value->code_type) ?>"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></button>
						<a class="btn btn-default" href="https://www.youtube.com/watch?v=<?php echo($playlist->id) ?>" target="_blank" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> View</a>
						</div>
					</p>
				</div>
				<?php 
				$i++;
				if ($i%$columns == 0) echo '</div><div class="row">';
			}
		}
	}
	else
	{
		echo("No Videos yet.");
	}

}

/**
 * Outputs a Youtube playlist or channel info along with all its associated vidoes
 * @param  [int] 	$id            	[Primary Key 'id' from table DD_VIDS_TABLE]
 * @param  [string] $playlist_id   	['code' from table DD_VIDS_TABLE]
 * @param  [string] $code_type 		['code_type' from table DD_VIDS_TABLE, SET 'channel','video','playlist']
 * @return [html]                	[Outputs bootstrapped HTML]
 */
function display_playlist($id, $playlist_id, $playlist_type){
	
	global $wpdb; // setup access to the database
	global $youtube; // setup the youtube object

	if ($playlist_type == "playlist") 
	{
		// Return a std PHP object
		$playlist = $youtube->getPlaylistById($playlist_id);
		// Return an array of PHP objects
		$playlistItems = $youtube->getPlaylistItemsByPlaylistId($playlist_id);
	}
	else if($playlist_type == "channel")
	{
		// Return a std PHP object
		$playlist = $youtube->getChannelById($playlist_id);
		// Return an array of PHP objects
		$playlistItems = $youtube->getPlaylistsByChannelId($playlist_id);
	}
	?>
	<!-- <span class="glyphicon glyphicon-plus pull-right text-muted" aria-hidden="true"> -->
	<div class="panel panel-default success">
		<div class="panel-heading danger">
			<h4 class="panel-title">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#<?php echo $playlist_type; ?>s_accordion" href="#collapse<?php echo $id; ?>">
					<?php echo $playlist->snippet->title; ?> <?php //echo($url) ?></a>
				<div class="btn-group btn-group-xs pull-right" role="group" aria-label="..." id="channel-nav">
				  <button type="button" class="btn btn-danger delete_id" id="<?php echo($id) ?>" value="<?php echo($playlist_type) ?>"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></button>
				  <button value="<?php echo($playlist_id) ?>" type="button" class="btn btn-default accordion-toggle" id="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $id; ?>"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> View</button>
				</div>
			</h4>
		</div>
		<div id="collapse<?php echo $id; ?>" class="panel-collapse collapse <?php echo $first_feed; ?>">
			<div class="panel-body">
				

			    <!-- Main jumbotron for a primary marketing message or call to action -->
			    <div class="jumbotron">
			      <div class="container">
			        <p class="visible-lg"><img src="<?php echo $playlist->snippet->thumbnails->medium->url; ?>" class="pull-right img-thumbnail img-playlist"></p>
			        <h1><?php echo $playlist->snippet->title; ?></h1>
			        <p><?php echo $playlist->snippet->description; ?>
			      </div>
			    </div>
			    <div class="container">
					<!-- Rows of columns with video content -->
					<div class="row sort">
					<?php 
					// define how many colums
					$columns = 4;
					$i = 0;
					$count = $playlistItems;

					
					if ($count > 0) 
					{
						foreach ($playlistItems as $k => $newPlaylist) {
							$trimmed_description = wp_trim_words( $newPlaylist->snippet->description, $num_words = 25, $more = null );
						?>
						<!-- 
						Make sure the col-md-'*' width span * $columns == 12 
						$columns = 3 would require col-md-4, $columns = 6 would require col-md-2 ...
						-->
						<div class="col-md-3">
							<img src="<?php echo($newPlaylist->snippet->thumbnails->medium->url) ?>" class="img-responsive img-thumbnail">
							<h4 style="word-wrap: break-word;"><?php echo($newPlaylist->snippet->title) ?></h4>
							<p style="word-wrap: break-word;"><?php echo($trimmed_description) ?></p>
							<p><a class="btn btn-default" href="https://www.youtube.com/watch?v=<?php echo($newPlaylist->contentDetails->videoId) ?>" target="_blank" role="button">View details &raquo;</a></p>
						</div>
						<?php 
							$i++;
							if ($i%$columns == 0) echo '</div><div class="row">';
						}
					}
					?>
					</div>
			    </div>
			</div>
		</div>
	</div>
	<?php
}


/**
 * Plugin activation function 
 * Create table and set options on plugin activate 
 */
function devden_activate()
{
	global $wpdb;
	
	$table_name = $wpdb->prefix . DD_VIDS_TABLE;

	if ( $wpdb->get_var('SHOW TABLES LIKE ' . $table_name) != $table_name ) {
		
		$sql = 'CREATE TABLE `' . $table_name . '` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `code` varchar(255) NOT NULL,
				  `code_type` set(\'channel\',\'video\',\'playlist\') DEFAULT NULL,
				  `label` varchar(100) NOT NULL,
				  `parent_id` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;';

		$wpdb->query($sql);

		
		// just some sample data for insert into the database
		$sql = "INSERT INTO `ddwp_devden_av_vids` (`code`, `code_type`, `label`, `parent_id`) VALUES
				('uGM0gZNdflw', 'video', '', 0),
				('PLonJJ3BVjZW6_Z5c2SYZK6jdV__gIsHcG', 'playlist', '', 0),
				('UCI-vEugj8uNGB_ZFuutlMYw', 'channel', '', 0),
				('O2K46phqYhk', 'video', '', 0),
				('ufmzc2sDmhs', 'video', '', 0),
				('UCyU5wkjgQYGRB0hIHMwm2Sg', 'channel', '', 0),
				('PLLnpHn493BHF6utwkwpo7RN-GPg1sZhvK', 'playlist', '', 0),
				('yRpvw6zSWa4', 'video', '', 0),
				('MaWcS-10NIw', 'video', '', 0),
				('PLKlA1QwYBcmdWMz5fg7q5qlMbvCg5Ug0_', 'playlist', '', 0),
				('UCsnXly_QQUVHUb3EEzm69Mg', 'channel', '', 0),
				('RTjd1nwvlj4', 'video', '', 0);";

		//$wpdb->query($sql);

	}

	// required for database version controll
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	// setup Devden option vars
	add_option( 'deven_db_version', '1.0' );
	add_option( 'deven_db_activated', true );
	add_option( 'deven_yt_api_key', 'none' );
}

register_activation_hook( __FILE__, 'devden_activate');

/**
 * Plugin Deactivation function 
 * Delete table and options on plugin deactivate 
 */
function devden_deactivate()
{
	//drop db table
	global $wpdb;
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}".DD_VIDS_TABLE."");
	delete_option('deven_db_version');
	delete_option('deven_db_activated');
	delete_option('deven_yt_api_key');
}

register_deactivation_hook( __FILE__, 'devden_deactivate');


// Insert the dd_add_pages() hook list for 'admin_menu'
add_action('admin_menu', 'dd_add_pages');

// dd_add_pages() is the function for the 'admin_menu' hook
function dd_add_pages() 
{
    //Add a new top-level menu
    add_menu_page('YouTube WP Admin Videos', 'WP Admin Vids','5','wp-admin-vids', 'dd_toplevel_page', plugins_url('admin-vids/images/devden-logo-22x22-gray.png'), 1 );
	// Add Submenus
	add_submenu_page('wp-admin-vids', 'Settings', 'Settings','5', 'dd-settings','dd_submenu_page_settings' );
}

/**
 * Main Plugin Page - includes and displays the page content for the Toplevel WP-Admin Vids menu item
 * @return [html] [Outputs bootstrapped HTML]
 */
function dd_toplevel_page() 
{
	require_once 'includes/toplevel_page.php';	 
}

// Displays the submenu content
function dd_submenu_page_settings() 
{
	require_once 'includes/submenu_page_settings.php';	 	  
}

// Change the default footer text
add_filter('admin_footer_text', 'left_admin_footer_text_output'); // footer text left side
function left_admin_footer_text_output($text) 
{
    $text = 'Web development <a href="http://devden.ca">devden.ca</a>';
    return $text;
}
 
add_filter('update_footer', 'right_admin_footer_text_output', 11); // footer text right side
function right_admin_footer_text_output($text) 
{
    $text = 'Save all of your favorite Youtube videos in on place with <a href="http://devden.ca/devden-vids/">DevDen WP-Admin Vids</a>.';
    return $text;
}


