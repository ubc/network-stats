

NetStatsSites = {
	draw: function(){
		
		var margin = {top: 10, right: 10, bottom: 100, left: 40},
   		margin2 = {top: 430, right: 10, bottom: 20, left: 40},
    	width = jQuery('#data').width() - margin.left - margin.right,
    	height = 500 - margin.top - margin.bottom,
    	height2 = 500 - margin2.top - margin2.bottom;
    	
		// 2012-08-31 17:01:43
		var parseDate = d3.time.format("%Y-%m-%d %H:%M:%S").parse;
		
		var x = d3.time.scale().range([0, width]),
			x2 = d3.time.scale().range([0, width]),
		    y = d3.scale.linear().range([height, 0]),
		    y2 = d3.scale.linear().range([height2, 0]);
		
		var xAxis = d3.svg.axis().scale(x).orient("bottom"),
		    xAxis2 = d3.svg.axis().scale(x2).orient("bottom"),
		    yAxis = d3.svg.axis().scale(y).orient("left");
		
		var brush = d3.svg.brush()
		    .x(x2)
		    .on("brush", brushed);
		
		var area = d3.svg.area()
		    .interpolate("monotone")
		    .x(function(d) { return x( d.registered  ); })
		    .y0( height )
		    .y1(function(d) { return y( d.site_id ); });
		
		var area2 = d3.svg.area()
		    .interpolate("monotone")
		    .x(function(d) { return x2( d.registered ); })
		    .y0(height2)
		    .y1(function(d) { return y2( d.site_id ); });
		
		var svg = d3.select("body").selectAll("#data").append("svg")
		    .attr("width", width + margin.left + margin.right)
		    .attr("height", height + margin.top + margin.bottom);
		
		svg.append("defs").append("clipPath")
		    .attr("id", "clip")
		  .append("rect")
		    .attr("width", width)
		    .attr("height", height);
		
		// focus us the bigger graph
		var focus = svg.append("g")
		    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
		
		var context = svg.append("g")
		    .attr("transform", "translate(" + margin2.left + "," + margin2.top + ")");
		
		d3.json(json_url, function(error, data) {
		  
		  data.forEach(function(d) {
		     console.log(d.registered)
		     d.registered = parseDate(d.registered);
		     d.site_id = +d.site_id;
		     console.log(d.site_id, d.registered);
		  });
		  
		  x.domain(d3.extent(data.map(function(d) { return d.registered; })));
		  y.domain([0, d3.max(data.map(function(d) { return d.site_id; }))]);
		  x2.domain(x.domain());
		  y2.domain(y.domain());
		  
		  /*
		  svg.selectAll(".bar")
		      .data(data)
		      .enter().append("rect")
		      .attr("class", "bar")
		      .attr("x", function(d) { return d.registered; })
		      .attr("width", 1)
		      .attr("y", function(d) { return y(d.site_id); })
		      .attr("height", function(d) { return height - y(d.site_id); });
		  */
		 
		  focus.append("path")
		      .datum(data)
		      .attr("clip-path", "url(#clip)")
		      .attr("d", area)
		      .attr("class", "line")
		  
		  focus.append("g")
		      .attr("class", "x axis")
		      .attr("transform", "translate(0," + height + ")")
		      .call(xAxis);
		
		  focus.append("g")
		      .attr("class", "y axis")
		      .call(yAxis);
		 
		  context.append("path")
		      .datum(data)
		      .attr("d", area2)
		      .attr("class", "line")
      		
		  context.append("g")
		      .attr("class", "x axis")
		      .attr("transform", "translate(0," + height2 + ")")
		      .call(xAxis2);
		
		  context.append("g")
		      .attr("class", "x brush")
		      .call(brush)
		      .selectAll("rect")
		      .attr("y", -6)
		      .attr("height", height2 + 7);
		});
		  
		 function brushed() {
			x.domain(brush.empty() ? x2.domain() : brush.extent());
			focus.select("path").attr("d", area);
			focus.select(".x.axis").call(xAxis);
		}
	}
}