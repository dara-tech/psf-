@extends('layouts.app')

@section('content')


<script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default" >
                <div class="panel-heading">Dashboard</div>
               
				
				<div id="canvas-holder" >
						<canvas id="chart-area"></canvas>
				</div>
            </div>
        </div>
	</div>
	

<script>
  
  var randomScalingFactor = function() {
			return Math.round(Math.random() * 100);
		};

		var config = {
			type: 'pie',
			data: {
				datasets: [{
					data: [
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
					],
					backgroundColor: [
						'#4dc9f6',
						'#f67019',
						'#f53794',
						'#537bc4',
						'#acc236',
						'#166a8f',
						'#00a950',
						'#58595b',
						'#8549ba'
					],
					label: 'Dataset 1',
					borderColor: 'rgba(0, 0, 0, 0)',
					showLine:false
				}],
				labels: [
					'Red',
					'Orange',
					'Yellow',
					'Green',
					'Blue'
				]
			},
			options: {
				responsive: true,
				legend: {
					display: true,
					labels: {
						fontColor: '#fff',
						
					}
				}
			}
		};

		window.onload = function() {
			var ctx = document.getElementById('chart-area').getContext('2d');
			window.myPie = new Chart(ctx, config);
		};
</script>
@endsection
