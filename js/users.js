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

	var dict = new Array();
	var temp = _.countBy(input_array, function(value) {
		return value;
	})
	
	_.each(temp, function(value, key) {
		dict.push({role: key, count: value});
	});

	var bar_height = 20,
		width = 600,
		left_width = 100,	// offset for the left of the chart
		height = bar_height * dict.length;

	var gap = 2;	// defining a gap for padding between the bars

	// create chart context here
	var chart = d3.select("#role-bar-graph").append("svg")
					.attr("class", "chart")
					.attr("width", width + left_width + 40)
					.attr("height", (bar_height + gap * 2) * dict.length + 30)
				  .append("g")
					.attr("transform", "translate(10, 20)");

	// add scaling for the chart axes
	var x = d3.scale.linear()
				.domain([0, d3.max(dict, function(d) { return d.count; })])
				.range([0, width]);

	var y = d3.scale.ordinal()
				.domain(dict.map(function(d) { return d.role; }))
				.rangeBands([0, (bar_height + 2 * gap) * dict.length]);

	// draw the bars themselves
	chart.selectAll("rect")
		.data(dict.map(function(d) { return d.count; }))
	  .enter().append("rect")
	  	.attr("x", left_width)		// start the bars at the value for left_width
	  	.attr("y", function(d, i) { return i * y.rangeBand(); })		// ensures that arrays with duplicate values will be drawn
	  	.attr("width", x)
	  	.attr("height", y.rangeBand());

	// add numbers to the end of each bar
	chart.selectAll("text.bar-num")
		.data(dict.map(function(d) {return d.count; }))
	  .enter().append("text")
		.attr("x", function(d) { return x(d) + left_width; })
		.attr("y", function(d, i) { return i * y.rangeBand() + y.rangeBand()/2; })
		.attr("dx", -5)
		.attr("dy", ".36em")
		.attr("text-anchor", "end")
		.attr("class", "bar-num")
		.text(String);

	// add name labels to the left of chart
	chart.selectAll("text.name")
		.data(dict.map(function(d) { return d.role; }))
	  .enter().append("text")
		.attr("x", left_width / 2)
		.attr("y", function(d) { return y(d) + y.rangeBand() / 2; })
		.attr("dy", ".36em")
		.attr("text-anchor", "middle")
		.attr("class", "name")
		.text(String);

}
// div#registration-per-time