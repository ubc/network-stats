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

			// draw the bar graph
			role_bar_graph(data);
		});
	}
}

// div#user-activity

// div#user-pie-chart

// div#faculties-bar-graph

// div#role-bar-graph
function role_bar_graph(data) {

	var array_choose = ["administrator", "editor", "author", "contributor", "subscriber"];
	var input_array = new Array();

	function random_array_value(array) {
		return array[Math.floor(Math.random() * (array.length - 1 - 0 + 1) )];
	}

	for (var i =  1000; i > 0; i--) {
		input_array.push(random_array_value(array_choose));
	}

	input_array.sort();
	//var dict = new Array();
	var temp = _.countBy(input_array, function(value) {
		return value;
	});
	
	/*_.each(temp, function(value, key) {
		dict.push({role: key, count: value});
	});*/
	
	var role = _.keys(temp);		// user roles
	var count = _.values(temp);		// count of users per role

	/* graphing code based on https://gist.github.com/ghiden/3046929 */
	var bar_height = 20,
		left_width = 100,
		width = 400,
		gap = 2,					// gap between the bars
		height = bar_height * role.length;

	// define the scaling for the x axis
	var x = d3.scale.linear()
				.domain([0, d3.max(count)])
				.range([0, width]);

	// account for values 
	// (do not use ordinal if you expect data to have duplicate values)
	var y_rangeband = bar_height + 2 * gap;
	console.log(y_rangeband);
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

}
// div#registration-per-time
