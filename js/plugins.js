
NetStatsPlugins = {
	draw: function(){
	
		
		
		d3.json(json_url, function(error, data) {
			jQuery('#plugins-total').append('<strong>'+data.length+'</strong>')
			var tbody = jQuery('#data-table tbody');
			var i = 0;
			data.forEach(function(d) {
				i++;
				//var index = _.indexOf(d.plugins, )
				//if( d.plugins
				var alt = ( i%2 == 1 ? 'class="alternate"' : '');
				tbody.append('<tr '+alt+'><td>'+d.name+'</td><td>'+d.num_sites+'</td><td>'+d.user_network+'</td><td>hello</td></tr>');
			});
			
		});
	}
}