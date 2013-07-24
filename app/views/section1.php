<canvas id="canvas" height="345" width="634"></canvas>
	<script>

		var lineChartData = {
			labels : ["January","February","March","April","May","June","July"],
			datasets : [
				{
					fillColor : "rgba(220,220,220,0.5)",
					strokeColor : "rgba(220,220,220,1)",
					pointColor : "rgba(220,220,220,1)",
					pointStrokeColor : "#fff",
					data : [<?=$a ?>]
				},
				{
					fillColor : "rgba(151,187,205,0.5)",
					strokeColor : "rgba(151,187,205,1)",
					pointColor : "rgba(151,187,205,1)",
					pointStrokeColor : "#fff",
					data : [<?=$b ?>]
				}
			]
			
		}

	var myLine = new Chart(document.getElementById("canvas").getContext("2d")).Line(lineChartData,{animation : false});
	
	</script>
