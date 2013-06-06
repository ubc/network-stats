jQuery( function($) { 
	NetStats = {
	 update_data : function( page ) {
	
		var post = {
			action: 'network_stats',
			do: 'update_'+network_stats_page,
			page: page
		};
				
		jQuery.post( ajaxurl, post, function( response ) {
			if(  response.next_page % 1 === 0 ) {
				NetStats.update_data( response.next_page );
					
			} else{
				NetStats.draw();
			}
			
		}, "json");
		
	},
	start: function() {
		if( network_stats_refresh_data ) {
			NetStats.update_data( 1 );
		} else{
			NetStats.draw();
		}
	},
	draw: function() {
		
		switch(network_stats_page)	{
			case 'sites':
				NetStatsSites.draw();
				
			break;
			case 'plugins':
				NetStatsPlugins.draw();
				
			break;

			case 'users':
				NetStatsUsers.draw();
				
			break;
		}
	 }
	
	}
	
	
	
	
	NetStats.start();
	
});