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
		stroke: white;
		fill: steelblue;
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
	var data = {
		'administrator': 0,
		'editor': 0,
		'author': 0,
		'contributor': 0,
		'subscriber': 0
	};
	for (var i = input_array.length - 1; i >= 0; i--) {
		// key = administrator, editor, author, contributor or subscriber
		data[input_array[i]]++;
	}

	console.log(Object.keys(data).length);
	// test code from http://stackoverflow.com/questions/126100/how-to-efficiently-count-the-number-of-keys-properties-of-an-object-in-javascrip
	if( !Object.keys ) {
		Object.keys = function( data ) {
			var keys = [],
				k;
			for (k in data) {
				if (Object.prototype.hasOwnProperty.call(data, k)) {
					keys.push(k);
				}
			}
			return keys;
		};
	}
	/*var chart = d3.select("#role-bar-graph").append("svg")
		.attr("class", "chart")
		.attr("width", 420)
		.attr("height", 20 * data.length);

	var x = d3.scale.linear()
		.domain(([0, d3.max(data)]))
		.range([0, 420]);

	chart.selectAll("rect")
			.data(data)
		.enter().append("rect")
			.attr("y", function(d, i) { return i * 20; })
			.attr("width", x)
			.attr("height", 20);

	var y = d3.scale.ordinal()
		.domain(data)
		.rangeBands([0, 120]);*/
</script>
<p>Last updated <em><?php echo $users_data_object->updated_since(); ?></em></p>

<script type="text/javascript">
	jQuery.ajax( {
		dataType: "json",
		url: "<?php echo $url; ?>",
		success: function(data) {
			console.log(data);
		}
	} );
</script>