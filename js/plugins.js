
NetStatsPlugins = {
	draw: function(){
	
		
		
		d3.json(json_url, function(error, data) {
			
			var all_plugins = _.chain(data).map( function(d){ return _.flatten( d.plugins ) }).flatten().uniq().value();
			console.log(all_plugins);
			var plugins = [];
			
			all_plugins.forEach( function(d){
				
			});
			data.forEach(function(d) {
				
				//var index = _.indexOf(d.plugins, )
				//if( d.plugins
					
				// jQuery('#myTable').append('<tr><td>'+d.name+'</td><td>more data</td></tr>');
			});
			
		});
	}
}