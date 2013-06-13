NetStatsUsers = {
	draw: function() {

		d3.json(json_url, function(error, data) {
			jQuery('#users-total').append('<strong>'+data.length+'</strong>');
			var tbody = jQuery('#data-table tbody');
			var i = 0;
			data.forEach(function(d) {
				i++;
				var alt = ( i % 2 == 1 ? 'class="alternate"' : '');

				tbody.append(	'<tr ' + alt + '><td>' + d.user_id + '</td><td>' + d.name + '</td><td>' + d.user_email + '</td><td>role goes here</td><td>' + d.registered + '</td><td>yolo sites</td></tr>');
			});

			// draw the role bar graph
			role_bar_graph(data);

			// draw the sites per user bar graph
			user_number_sites(data);
		});
	}
}

// div#user-activity

// div#user-pie-chart

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
	
	var role = _.keys(temp);		// user roles
	var count = _.values(temp);		// count of users per role

	/* graphing code based on https://gist.github.com/ghiden/3046929 */
	var bar_height = 20,
		left_width = 100,
		width = 400,
		gap = 4,					// gap between the bars
		height = bar_height * role.length;

	// define the scaling for the x axis
	var x = d3.scale.linear()
				.domain([0, d3.max(count)])
				.range([0, width]);

	// account for values 
	// (do not use ordinal if you expect data to have duplicate values)
	var y_rangeband = bar_height + 2 * gap;
	var y = function(i) { return y_rangeband * i; };

	// create chart context here
	var chart = d3.select("#role-bar-graph").append("svg")
					.attr("class", "chart")
					.attr("width", width + left_width + 40)
					.attr("height", (bar_height + gap * 2) * role.length + 30)
				  .append("g")
					.attr("transform", "translate(10, 20)");

	// add scaling for the chart axes
	/*var x = d3.scale.linear()
				.domain([0, d3.max(dict, function(d) { return d.count; })])
				.range([0, width]);*/

	// draw the rules
	chart.selectAll(".rule")
		.data(x.ticks(10))
	  .enter().append("text")
		.attr("x", function(d) { return x(d) + left_width; })
		.attr("y", 0)
		.attr("dy", -6)
		.attr("text-anchor", "middle")
		/*.attr("width", x)
		.attr("height", bar_height)*/
		.text(String);

	// draw the ticks
	chart.selectAll("line")
		.data(x.ticks(10))
	  .enter().append("line")
		.attr("x1", function(d) { return x(d) + left_width; })
		.attr("x2", function(d) { return x(d) + left_width; })
		.attr("y1", 0)
		.attr("y2", (bar_height + gap * 2) * role.length);

	// draw the bars themselves
	chart.selectAll("rect")
		.data(count)
	  .enter().append("rect")
	  	.attr("x", left_width)		// start the bars at the value for left_width
	  	.attr("y", function(d, i) { return y(i) + gap; })		// ensures that arrays with duplicate values will be drawn
	  	.attr("width", x)
	  	.attr("height", bar_height);

	// add numbers to the end of each bar
	chart.selectAll("text.bar-num")
		.data(count)
	  .enter().append("text")
		.attr("x", function(d) { return x(d) + left_width; })
		.attr("y", function(d, i) { return y(i) + y_rangeband / 2; })
		.attr("dx", -5)
		.attr("dy", ".36em")
		.attr("text-anchor", "end")
		.attr("class", "bar-num")
		.text(String);

	// add name labels to the left of chart
	chart.selectAll("text.name")
		.data(role)
	  .enter().append("text")
		.attr("x", left_width / 2)
		.attr("y", function(d, i) { return y(i) + y_rangeband / 2; })
		.attr("dy", ".36em")
		.attr("text-anchor", "middle")
		.attr("class", "name")
		.text(String);

	// add a y-axis
	chart.append("line")
		.attr("x1", left_width - 0.5)
		.attr("x2", left_width - 0.5)
		.attr("y1", 0)
		.attr("y2", (bar_height + gap * 2) * role.length)
		.style("stroke", "#000");

	// add an x-axis
	chart.append("line")
		.attr("x1", left_width)
		.attr("x2", left_width + width)
		.attr("y1", (bar_height + gap * 2) * role.length - 0.5)
		.attr("y2", (bar_height + gap * 2) * role.length - 0.5)
		.style("stroke", "#000");

}
// div#registration-per-time

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

	console.log(user_id_array);
	console.log(num_site_array);
	console.log(user_count);
	console.log("max: " + max.num_site);
	console.log("min: " + min.num_site);
	console.log("average: " + average);
	console.log(Math.max.apply(Math, _.values(user_count)));


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
