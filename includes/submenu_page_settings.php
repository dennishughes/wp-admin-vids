<?php
// Set the $dd_api_key var to the deven_yt_api_key option value
// If the key is set then mask it so only part of the key visible
if ( get_option( 'deven_yt_api_key' ) !== 'none' ) {
	$dd_api_key = "***************" . substr(get_option( 'deven_yt_api_key' ), 14, 14) . "***************";
}else{
	$dd_api_key = get_option( 'deven_yt_api_key' );
}
?>
<div class='wrap'>

	<h2>DevDen Admin Vids Settings</h2>

		<h3>Please provide your Google Youtube API key.</h3>

		<p>
			<form method="post" id="add_key_form">
				<div class="form-group">
					<input type="password" class="form-control" id="yt_key" name="yt_key" placeholder="Youtube API Key">
				</div>
				<button type="submit" class="btn btn-danger" id="add_yt_key">Save Key</button>
				<h4 id="api_key">Key: <?php echo($dd_api_key) ?></h4>
				<p>To delete the key and all of its associated data simply deactivate and reactivate the plugin.</p>
			</form>
		</p>

		<p><img src="<?php echo plugins_url( '../images/devden-eye-wide.jpg', __FILE__ ); ?>" class="img-responsive img-thumbnail"></p>

</div>
