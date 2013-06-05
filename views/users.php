<?php

$users_data_object = new Network_Stats_Users_Data();
$url = $users_data_object->get_url();
//$users_data_object->fetch_latest_data();

if( empty( $url ) ) {

	//$users_data_object->fetch_latest_data();
	?>
	<script type="text/javascript">
		var data = {
			action: 'network_stats_users_data'
		};
	</script>
	<?php

}

?>

<div id="role-bar-graph"></div>
<style>
	.chart rect {
		fill: steelblue;
		stroke: white;
	}
	.chart text {
		fill: white;
		z-index: 5;
	}
	text.rule {
		fill: black;
	}
	
</style>
<script type="text/javascript">
	var array_choose = ["administrator", "editor", "author", "contributor", "subscriber"];
	var input_array = new Array();

	function random_array_value(array) {
		return array[Math.floor(Math.random() * (array.length - 1 - 0 + 1) )];
	}

	for (var i =  1000; i > 0; i--) {
		input_array.push(random_array_value(array_choose));
	}
	var dict = {
		"administrator": 0,
		"editor": 0,
		"author": 0,
		"contributor": 0,
		"subscriber": 0
	};

	for (var i = input_array.length - 1; i >= 0; i--) {
		// key = administrator, editor, author, contributor or subscriber
		dict[input_array[i]]++;
		//dict.num[dict.role.indexOf(input_array[i])]++;
	}


	// test code from http://stackoverflow.com/questions/126100/how-to-efficiently-count-the-number-of-keys-properties-of-an-object-in-javascrip
	var count = Object.keys(dict).length;	// get the number of elements in the Object
	var max = Math.max.apply(null, Object.keys(dict).map(function(e) { return dict[e]; }));	// get the max value in the Object
	var min = Math.min.apply(null, Object.keys(dict).map(function(e) { return dict[e]; }));	// get the min value in the Object

	var chart = d3.select("#role-bar-graph").append("svg")
			.attr("class", "chart")
			.attr("width", 500)
			.attr("height", 1000)
		.append("g")
			.attr("transform", "translate(10, 15)");

	var x = d3.scale.linear()
		.domain([0, d3.max(d3.values(dict))])
		.range([0, 450]);

	var y = d3.scale.ordinal()
		.domain(d3.values(dict))
		.rangeBands([0, 20 * count]);

	chart.selectAll("rect")
			.data(d3.values(dict))
		.enter().append("rect")
			.attr("y", y)
			.attr("width", x)
			.attr("height", y.rangeBand());

	chart.selectAll("text")
			.data(d3.values(dict))
		.enter().append("text")
			.attr("x", x)
			.attr("y", function(d) { return y(d) + y.rangeBand() / 2; })
			.attr("dx", -3) // padding-right
			.attr("dy", ".35em") // vertical-align: middle
			.attr("text-anchor", "end") // text-align: right
			.text(String);

	chart.selectAll("line")
			.data(x.ticks(10))
		.enter().append("line")
			.attr("x1", x)
			.attr("x2", x)
			.attr("y1", 0)
			.attr("y2", 20 * count)
			.style("stroke", "#ccc");

	chart.selectAll(".rule")
			.data(x.ticks(10))
		.enter().append("text")
			.attr("class", "rule")
			.attr("x", x)
			.attr("y", 0)
			.attr("dy", -3)
			.attr("text-anchor", "middle")
			.text(String)

	chart.append("line")
		.attr("y1", 0)
		.attr("y2", 20 * count)
		.style("stroke", "#000");

	/*chart.selectAll(".margin")
			.data(d3.keys(dict))
		.enter().append("text")
			.attr("class", "margin")
			.attr("x", 120)
			.attr("y", function(d) { return y(d) + y.rangeBand() / 2; })
			.attr("dx", 3)	// padding-left
			.attr("dy", ".35em")	// vertical-align: middle
			.attr("text-anchor", "end")
			.text(String);*/


</script>
<script>
	var container;
	jQuery.ajax({
		dataType: "json",
		url: "<?php echo $url; ?>",
		success: function(data) {
			//console.log(data);
			//container = data;
		}
	});
</script>
<p>Last updated <em><?php echo $users_data_object->updated_since(); ?></em></p>
