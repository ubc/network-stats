NetStatsUsers = {
	draw: function() {

		d3.json(json_url, function(error, data) {
			jQuery('#users-total').append('<strong>'+_.keys(data).length+'</strong>');
			console.log(data);
			var tbody = jQuery('#data-table tbody');
			var i = 0;
			data.forEach(function(d) {
				i++;
				var alt = ( i % 2 == 1 ? 'class="alternate"' : '');

				tbody.append(	'<tr ' + alt + '><td>' + d.user_id + '</td><td>' + d.name + '</td><td>' + d.user_email + '</td><td>role goes here</td><td>' + d.registered + '</td><td>yolo sites</td></tr>');
			});

			// draw the role bar graph
			role_bar_graph(data);

			// draw the users per time graphs
			users_per_time(data);

			// draw the sites per user bar graph
			user_number_sites(data);
		});
	}
}

// div#user-activity

// div#user-pie-chart
function user_pie_chart(data) {

}
// div#faculties-bar-graph

// div#role-bar-graph
function role_bar_graph(data) {

	var role_string_array = new Array();

	for (var i = data.length - 1; i >= 0; i--) {
		if(data[i]["role"].length > 0)
			role_string_array.push(data[i]["role"][0]);
		else
			role_string_array.push("other");
	};
	role_string_array.sort();

	var temp = _.countBy(role_string_array, function(value) {
		return value;
	});
	
	console.log(temp);
	var role = _.keys(temp);		// user roles
	var count = _.values(temp);		// count of users per role

	var margin = {top: 20, right: 20, bottom: 30, left: 75},
		width = 960 - margin.left - margin.right,
		bar_height = 20,
		height = 400 - margin.top - margin.bottom;

	// define the scaling for the x axis
	var x = d3.scale.linear()
		.range([0, width]);

	var y = d3.scale.ordinal()
		.rangeRoundBands([0, height], .625);

	var x_axis = d3.svg.axis()
		.scale(x)
		.orient("bottom");

	var y_axis = d3.svg.axis()
		.scale(y)
		.orient("left");

	var svg = d3.select("#role-bar-graph").append("svg")
		.attr("width", width + margin.left + margin.right)
		.attr("height", height + margin.top + margin.bottom)
	  .append("g")
		.attr("transform", "translate(" + margin.left + "," + margin.top + ")");

	x.domain([0, d3.max(count)]);
	y.domain(role);

	svg.append("g")
		.attr("class", "x axis")
		.attr("transform", "translate(0," + height + ")")
		.call(x_axis)
	  .append("text")
		.attr("x", width)
		.attr("dy", "-0.71em")
		.style("text-anchor", "end")
		.text("Number of Users");

	svg.append("g")
		.attr("class", "y axis")
		.call(y_axis)
	  .append("text")
		.attr("transform", "rotate(-90)")
		.attr("y", 6)
		.attr("dy", ".71em")
		.style("text-anchor", "end")
		.text("Roles");

	svg.selectAll("rect")
		.data(count)
	  .enter().append("rect")
		.attr("x", 0)
		.attr("width", x)
		.attr("y", function(d, i){ return y(i); })
		.attr("height", bar_height);

}

// div#users-per-time
function users_per_time(data) {

	var margin = {top: 10, right: 10, bottom: 100, left: 40},
	    margin2 = {top: 430, right: 10, bottom: 20, left: 40},
	    width = 960 - margin.left - margin.right,
	    height = 500 - margin.top - margin.bottom,
	    height2 = 500 - margin2.top - margin2.bottom;

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
	    .interpolate("step-before")
	    .x(function(d) { return x(d.registered); })
	    .y0(height)
	    .y1(function(d) { return y(d.user_id); });

	var area2 = d3.svg.area()
	    .interpolate("step-before")
	    .x(function(d) { return x2(d.registered); })
	    .y0(height2)
	    .y1(function(d) { return y2(d.user_id); });

	var svg = d3.select("#users-per-time").append("svg")
	    .attr("width", width + margin.left + margin.right)
	    .attr("height", height + margin.top + margin.bottom);

	svg.append("defs").append("clipPath")
	    .attr("id", "clip")
	  .append("rect")
	    .attr("width", width)
	    .attr("height", height);

	var focus = svg.append("g")
	    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

	var context = svg.append("g")
	    .attr("transform", "translate(" + margin2.left + "," + margin2.top + ")");

	  data.forEach(function(d) {
	    d.registered = parseDate(d.registered);
	    d.user_id = +d.user_id;
	  });

	  x.domain(d3.extent(data.map(function(d) { return d.registered; })));
	  y.domain([0, d3.max(data.map(function(d) { return d.user_id; }))]);
	  x2.domain(x.domain());
	  y2.domain(y.domain());

	  focus.append("path")
	      .datum(data)
	      .attr("clip-path", "url(#clip)")
	      .attr("d", area);

	  focus.append("g")
	      .attr("class", "x axis")
	      .attr("transform", "translate(0," + height + ")")
	      .call(xAxis);

	  focus.append("g")
	      .attr("class", "y axis")
	      .call(yAxis);

	  context.append("path")
	      .datum(data)
	      .attr("d", area2);

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

	function brushed() {
	  x.domain(brush.empty() ? x2.domain() : brush.extent());
	  focus.select("path").attr("d", area);
	  focus.select(".x.axis").call(xAxis);
	}

	// set the dimensions
	/*var margin = {top: 10, right: 10, bottom: 100, left: 40},
		margin2 = {top: 430, right: 10, bottom: 20, left: 40},
		width = 960 - margin.left - margin.right,
		height = 500 - margin.top - margin.bottom,
		height2 = 500 - margin2.top - margin2.bottom;

	// set the date stuff into 1970-01-01 00:00:00
	var parse_date = d3.time.format("%Y-%m-%d %H:%M:%S").parse;

	var x = d3.time.scale().range([0, width]),
		x2 = d3.time.scale().range([0, width]),
		y = d3.scale.linear().range([height, 0]),
		y2 = d3.scale.linear().range([height2, 0]);

	var x_axis = d3.svg.axis().scale(x).orient("bottom"),
		x_axis2 = d3.svg.axis().scale(x2).orient("bottom"),
		y_axis = d3.svg.axis().scale(y).orient("left");

	var brush = d3.svg.brush()
		.x(x2)
		.on("brush", brushed);

	var area = d3.svg.area()
		.interpolate("monotone")
		.x(function(d) { return x(d.registered); })
		.y0(height)
		.y1(function(d) { return y(d.user_id); });

	var area2 = d3.svg.area()
		.interpolate("monotone")
		.x(function(d) { return x2(d.registered); })
		.y0(height)
		.y1(function(d) { return y2(d.user_id); });

	/*var line = d3.svg.line()
		.x(function(d) { return x(d.registered); })
		.y(function(d) { return y(d.user_id); });*/

	/*var svg = d3.select("#users-per-time").append("svg")
		.attr("width", width + margin.left + margin.right)
		.attr("height", height + margin.top + margin.bottom)

	svg.append("defs").append("clipPath")
		.attr("id", "clip")
	  .append("rect")
		.attr("width", width)
		.attr("height", height);

	var focus = svg.append("g")
		.attr("transform", "translate(" + margin.left + "," + margin.top + ")");

	var context = svg.append("g")
		.attr("transform", "translate(" + margin2.left + "," + margin2.top + ")");

	data.forEach(function(d) {
		d.registered = parse_date(d.registered);
		d.user_id = +d.user_id;
	});

	x.domain(d3.extent(data.map(function(d) { return d.registered; })));
	y.domain([0, d3.max(data.map(function(d) { return d.user_id; }))]);
	x2.domain(x.domain());
	y2.domain(y.domain());

	focus.append("path")
		.datum(data)
		.attr("clip-path", "url(#clip)")
		.attr("d", area);

	focus.append("g")
		.attr("class", "x axis")
		.attr("transform", "translate(0," + height + ")")
		.call(x_axis);

	svg.append("g")
		.attr("class", "y axis")
		.call(y_axis);

	context.append("path")
		.datum(data)
		.attr("d", area2);

	context.append("g")
		.attr("class", "x axis")
		.attr("transform", "translate(0," + height2 + ")")
		.call(x_axis2);

	context.append("g")
		.attr("class", "y axis")
		.call(brush)
	  .selectAll("rect")
		.attr("y", -6)
		.attr("height", height2 + 7);

	  /*.append("text")
		.attr("transform", "rotate(-90)")
		.attr("y", 6)
		.attr("dy", ".71em")
		.style("text-anchor", "end")
		.text("Num Users");

	svg.append("path")
		.datum(data)
		.attr("class", "line")
		.attr("d", line);*/

	/*function brushed() {
		x.domain(brush.empty() ? x2.domain() : brush.extent());
		focus.select("path").attr("d", area);
		focus.select(".x.axis").call(x_axis);
	}*/
}

// div#user-number-sites
function user_number_sites(data) {

	var data_array = new Array();

	function get_id_and_sites(id, site_object) {
		return {
			user_id: id,
			num_site: _.size(site_object)
		};
	}

	for (var i = data.length - 1; i >= 0; i--) {
		data_array.push(get_id_and_sites(
			data[i].user_id, 
			data[i].sites_array
		));
	};

	/* do user calculations here using the data_array */
	var max = _.max(data_array, function(info) { return info.num_site; });		// most sites that a user has
	var min = _.min(data_array, function(info) { return info.num_site; });		// least sites that a user has
	
	var user_id_array = _.chain(data_array)
							.map(function(user) { return user.user_id; })
							.flatten()
							.value();		// create array of user ids
	var num_site_array = _.chain(data_array)
							.map(function(user) { return user.num_site; })
							.flatten()
							.value();		// create array of num sites
	// the above arrays have indices that correspond to the original array of objects (data_array)

	var user_count = _.countBy(num_site_array, function(count) { return count; });

	var sum = _.chain(num_site_array)
						.reduce(function(memo, num) { return memo + num; }, 0)
						.value();		// get the sum of all sites regardless of duplicate sites

	var average = sum / user_id_array.length;
	average = Math.round(average*Math.pow(10, 3)) / Math.pow(10, 3);	// get precision to 3 decimal places

	/*console.log(user_id_array);
	console.log(num_site_array);
	console.log(user_count);
	console.log("max: " + max.num_site);
	console.log("min: " + min.num_site);
	console.log("average: " + average);
	console.log(Math.max.apply(Math, _.values(user_count)));*/


	// set the variables here
	var bar_width 		= 20,
		bar_height 		= 200,
		bottom_height 	= 50,
		chart_width		= bar_width * _.size(user_count),
		chart_height	= bar_height + bottom_height,
		gap 			= 4;

	// set the x and y scales 
	var x_rangeband = bar_width + 2 * gap;
	var x = function(i) { return x_rangeband * i; };

	var y = d3.scale.linear()
				.domain([0, d3.max(_.values(user_count))])
				.range([0, bar_height]);

	// create chart context here
	var chart = d3.select("#user-number-sites").append("svg")
					.attr("class", "chart")
					.attr("width", (bar_width + gap * 2) * _.size(user_count) + 30)
					.attr("height", bar_height + bottom_height + 40)
				  .append("g")
					.attr("transform", "translate(20, 20)");

	var rule_scale = d3.scale.linear()
						.domain([0, d3.max(_.values(user_count))])
						.range([bar_height, 0]);

	//draw the rules
	chart.selectAll(".rule")
		.data(rule_scale.ticks(10))
	  .enter().append("text")
	  .attr("x", 0)
	  .attr("y", function(d) { return rule_scale(d); })
	  .attr("dx", -6)
	  .attr("text-anchor", "middle")
	  .text(String);

	 // draw the ticks
	chart.selectAll("line")
		.data(y.ticks(10))
	  .enter().append("line")
		.attr("x1", 0)
		.attr("x2", (bar_width + gap * 2) * _.size(user_count))
		.attr("y1", function(d) { return y(d); })
		.attr("y2", function(d) { return y(d); });

	// add the initial bars
	chart.selectAll("rect")
		.data(_.values(user_count))
	  .enter().append("rect")
		.attr("x", function(d, i) { return x(i) - 0.5; })
		.attr("y", function(d) { return bar_height - y(d) - 0.5; })
		.attr("width", bar_width)
		.attr("height", function(d) { return y(d); });

	// add name labels to the chart
	chart.selectAll("text.name")
		.data(_.keys(user_count))
	  .enter().append("text")
		.attr("x", function(d, i) { return x(i) + 10 - 0.5; })
		.attr("y", chart_height - 20)
		.attr("dy", -6)
		.attr("text-anchor", "middle")
		.text(String);

	// add numbers to the end of the bars
	chart.selectAll("text.bar-num")
		.data(_.values(user_count))
	  .enter().append("text")
	  	.attr("x", function(d, i) { return x(i) + 10 - 0.5;})
		.attr("y", function(d) { console.log(bar_height - y(d)); return bar_height - y(d) - 0.5; })
		.attr("dy", 18)
		.attr("text-anchor", "middle")
		.attr("class", "bar-num")
		.text(String);

	// add x-axis to the bars
	chart.append("line")
		.attr("x1", 0)
		.attr("x2", (bar_width + gap * 2) * _.size(user_count))
		.attr("y1", bar_height - 0.5)
		.attr("y2", bar_height - 0.5)
		.style("stroke", "#000");

	// add y-axis to the bars
	chart.append("line")
		.attr("x1", 0 - 0.5)
		.attr("x2", 0 - 0.5)
		.attr("y1", 0)
		.attr("y2", bar_height - 0.5)
		.style("stroke", "#000");

	// add a legend to the y-axis
	chart.append("text")
		.attr("x", ((bar_width + gap * 2) * _.size(user_count)) / 2)
		.attr("y", bar_height + (bottom_height))
		.attr("dy", -6)
		.attr("text-anchor", "middle")
		.text("Number of Sites");
}


// testing functions
function random_roles_generator() {
	var array_choose = ["administrator", "editor", "author", "contributor", "subscriber"];
	var input_array = new Array();

	function random_array_value(array) {
		return array[Math.floor(Math.random() * (array.length - 1 - 0 + 1) )];
	}

	for (var i =  1000; i > 0; i--) {
		input_array.push(random_array_value(array_choose));
	}

	input_array.sort();
}
