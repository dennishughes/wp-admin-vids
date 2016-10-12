<div class='wrap sort'>
	<nav class="navbar navbar-inverse">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><img src="<?php echo plugins_url( '../images/deven-logo-new-blue.png', __FILE__ ); ?>" class="logo" alt="">DevDen WP-Admin Vids</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
		<?php 
		if ( get_option( 'deven_yt_api_key' ) !== 'none' ) {
		?>
			<form method="post" id="add-video-form" class="navbar-form navbar-right" action="">
				<div class="form-group">
					<input type="text" class="form-control" id="yt_video_id" name="yt_video_id" placeholder="Youtube Video ID">
				</div>
				<button type="submit" class="btn btn-danger" id="add_yt_video" value="video">Add Video</button>
			</form>
			<form method="post" id="add_channel_form" class="navbar-form navbar-right" action="">
				<div class="form-group">
					<input type="text" class="form-control" id="yt_channel_id" name="yt_channel_id" placeholder="Youtube Channel ID">
				</div>
				<button type="submit" class="btn btn-success" id="add_yt_channel" value="channel">Add Channel</button>
			</form>
			<form method="post" id="add_playlist_form" class="navbar-form navbar-right" action="">
				<div class="form-group">
					<input type="text" class="form-control" id="yt_playlist_id" name="yt_playlist_id" placeholder="Youtube Playlist ID">
				</div>
				<button type="submit" class="btn btn-info" id="add_yt_playlist" value="playlist">Add Playlist</button>
			</form>:<?php echo(get_option( 'deven_yt_api_key' )) ?>
		<?php 
		}else{
		?>
			<form method="post" id="add_playlist_form" class="navbar-form navbar-right" action="">
				<button type="submit" class="btn btn-danger" id="go_dd_settings" value="admin.php?page=dd_settings">Add Your YouTube API Key</button>
			</form>:<?php echo(get_option( 'deven_yt_api_key' )) ?>
		<?php 
		}
		?>
        </div><!--/.navbar-collapse -->
    </nav>
	<div class="row">
		<div class="col-md-12">
		<div class="row sort">
			
		<div class="col-md-6">
			<div class="panel panel-info">
			  	<div class="panel-heading">
			    	<h3 class="panel-title">Youtube Playlists</h3>
			  	</div>
			  	<div class="panel-body" id="devden_playlists">	
					<div class="panel-group" id="playlists_accordion">
						<?php 
						devden_playlists('playlist');
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6" id="left-col">
			<div class="panel panel-success">
			  	<div class="panel-heading">
			    	<h3 class="panel-title">Youtube Channels</h3>
			  	</div>
			  	<div class="panel-body" id="devden_channels">	
					<div class="panel-group" id="channels_accordion">
						<?php 
						devden_channels('channel');
						?>
					</div>
				</div>
			</div>
		</div>
			</div>
		</div>
	</div>	
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-danger">
			  	<div class="panel-heading">
			    	<h3 class="panel-title">Youtube Videos</h3>
			  	</div>
			  	<div class="panel-body" id="devden_videos">	
					<div class="panel-group" id="videos_accordion">
						<?php 
						devden_videos('video');
						?>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div><!--/.wrap -->
