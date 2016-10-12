jQuery(document).ready( function($){

	$( "#playlists_accordion" ).collapse().sortable({
	   connectWith: "#channels_accordion"
	});  

	$( "#channels_accordion" ).collapse().sortable({
	   connectWith: "#playlists_accordion"
	}); 

	$( ".sort" ).sortable();

	$("#add_yt_key").click( function(e){
		 e.preventDefault();

		$.ajax({
			type: "POST",
			data: {
				yt_key: $("input#yt_key").val(),
				action: 'add_key'
			},
			url: ajaxurl,
			beforeSend: function(){
				$("#yt_key").val("");
				$("#yt_key").attr("placeholder", "Validating...");
			},
			success: function(data){
				
				$("#yt_key").attr("placeholder", "Success API Key was added");
				$("#api_key").html(data);
				
			},
			error: function(jqXHR, textStatus, errorThrown){
				$("#yt_key").attr("placeholder", "Error!");
				alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
			}
		});
		
	});

	$("#add_yt_playlist").click( function(e){
		 e.preventDefault();

		$.ajax({
			type: "POST",
			data: {
				yt_code: $("input#yt_playlist_id").val(),
				yt_code_type: 'playlist',
				action: 'add_code'
			},
			url: ajaxurl,
			beforeSend: function(){
				$("#yt_playlist_id").val("");
				$("#yt_playlist_id").attr("placeholder", "Validating...");
			},
			success: function(data){
				
				$("#yt_playlist_id").attr("placeholder", "Success!");
				$("#playlists_accordion").html(data);
				
			},
			error: function(jqXHR, textStatus, errorThrown){
				$("#yt_playlist_id").attr("placeholder", "Error!");
				alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
			}
		});
		
	});

	$("#add_yt_channel").click( function(e){
		 e.preventDefault();

		$.ajax({
			type: "POST",
			data: {
				yt_code: $("input#yt_channel_id").val(),
				yt_code_type: 'channel',
				action: 'add_code'
			},
			url: ajaxurl,
			beforeSend: function(){
				$("#yt_channel_id").val("");
				$("#yt_channel_id").attr("placeholder", "Validating...");
			},
			success: function(data){
				
				$("#yt_channel_id").attr("placeholder", "Success!");
				$("#channels_accordion").html(data);
				
			},
			error: function(jqXHR, textStatus, errorThrown){
				$("#yt_channel_id").attr("placeholder", "Error!");
				alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
			}
		});
		
	});

	$("#add_yt_video").click( function(e){
		 e.preventDefault();

		$.ajax({
			type: "POST",
			data: {
				yt_code: $("input#yt_video_id").val(),
				yt_code_type: 'video',
				action: 'add_code'
			},
			url: ajaxurl,
			beforeSend: function(){
				$("#yt_video_id").val("");
				$("#yt_video_id").attr("placeholder", "Validating...");
			},
			success: function(data){
				
				$("#yt_video_id").attr("placeholder", "Success!");
				$("#videos_accordion").html(data);
				
			},
			error: function(jqXHR, textStatus, errorThrown){
				$("#yt_video_id").attr("placeholder", "Error!");
				alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
			}
		});
		
	});

	$('body').delegate('.view_id', 'click', function(e){
	
		e.preventDefault();

		var myUrl = this.value;

		window.open(myUrl, '_blank');
		
	});

	$('body').delegate('#go_dd_settings', 'click', function(e){
	
		e.preventDefault();

		var myUrl = this.value;

		window.open(myUrl, '_self');
		
	});

	$('body').delegate('.delete_id', 'click', function(e){
	
		e.preventDefault();

		var myVal = this.value;

		var deleteOk = confirm("This action will delete this " + this.value +".");
		
		if (deleteOk) {
			$.ajax({
				type: "POST",
				data: {
					delete_id: this.id,
					delete_type: this.value,
					action: 'delete_vids'
				},
				url: ajaxurl,
				beforeSend: function(){
					if (myVal == "playlist") {
						$("#yt_playlist_id").attr("placeholder", "Removing...");
					}else if (myVal == "channel") {
						$("#yt_channel_id").attr("placeholder", "Removing...");
					}else{
						$("#yt_video_id").attr("placeholder", "Removing...");
					}
				},
				success: function(data){

					if (myVal == "playlist") {
						$("#yt_playlist_id").attr("placeholder", "Delete Success!");
						$("#playlists_accordion").html(data);
					}else if (myVal == "channel") {
						$("#yt_channel_id").attr("placeholder", "Delete Success!");
						$("#channels_accordion").html(data);
					}else{
						$("#yt_video_id").attr("placeholder", "Delete Success!");
						$("#videos_accordion").html(data);
					}
				},
				error: function(jqXHR, textStatus, errorThrown){
					alert(jqXHR + " :: " + textStatus + " :: " + errorThrown)
				}
			});
		}

	});

	$('[data-toggle="tooltip"]').tooltip({'placement': 'top'});
	
});	

