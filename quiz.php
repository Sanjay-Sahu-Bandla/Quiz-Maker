<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">

	<meta name="viewport" content="width=device-width,initial-scale=1">

	<!-- JQuery -->
	<script src="Assets/plugins/jquery/jquery.slim.min.js"></script>

	<!-- bootstrap -->
	<link href="Assets/plugins/bootstrap-4.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="Assets/plugins/bootstrap-4.4.1/dist/js/bootstrap.min.js"></script>

	<!-- fontawesome -->
	<link href="Assets/plugins/fontawesome-free/css/all.min.css" rel="stylesheet">

	<!-- customizations -->
	<link rel="stylesheet" type="text/css" href="Assets/dist/style.css">

	<script src="Assets/dist/script.js" type="text/javascript"></script>
	<!-- Ajax -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<title>Quiz Maker</title>

</head>
<body class="container d-flex justify-content-center align-items-center"style="min-height: 100vh;">

	<div class="container shadow table-responsive rounded">

		<div id="header" class="d-flex justify-content-between m-3">
			<h5 class="text-secondary">Question No <span id="question_count">--</span>/
				<?php

				$quiz_data = file_get_contents("JSON/".$_GET['quiz_id'].".json");
				$array = json_decode($quiz_data,true);

				$user_data = array();

				for($i=0;$i<count($array); $i++) {

					$que_count = count($array[$i]['questions']);

				}

				echo $que_count;

				?>
			</h5>
			<div id="time_count" class="badge badge-pill badge-success d-flex align-items-center px-3 shadow">-- : --</div>
		</div>

		<hr>

		<div id="question_window" class="mx-3 mb-3">
			<div id="question" class="mb-2">--- ?</div>

			<div id="options">
				<div>
					<input type="radio" name="option" id="option1" class="radio" value="0000"><label for="option1" class="ml-2">--</label>
				</div>
				<div>
					<input type="radio" name="option" id="option2" class="radio" value="1111"><label for="option2" class="ml-2">--</label>
				</div>
				<div>
					<input type="radio" name="option" id="option3" class="radio" value="2222"><label for="option3" class="ml-2">--</label>
				</div>
				<div>
					<input type="radio" name="option" id="option4" class="radio" value="3333"><label for="option4" class="ml-2">--</label>
				</div>
			</div>
		</div>

		<div id="answerred">
			<form action="result.php?quiz_id=<?php echo $_GET['quiz_id']; ?>" method="POST">
				<input type="hidden" id="time" name="time" value="">

				<?php

				for($i=0;$i<count($array); $i++) {

					for($j=0;$j<count($array[$i]['questions']); $j++) {

						echo '<input type="hidden" id="ans'.$j.'" name="ans'.($j+1).'" value="">';
					}
				}

				?>

				<!-- <input type="hidden" id="ans0" name="ans1" value="">
				<input type="hidden" id="ans1" name="ans2" value="">
				<input type="hidden" id="ans2" name="ans3" value="">
				<input type="hidden" id="ans3" name="ans4" value="">
				<input type="hidden" id="ans4" name="ans5" value="">
				<input type="hidden" id="ans5" name="ans6" value="">
				<input type="hidden" id="ans6" name="ans7" value="">
				<input type="hidden" id="ans7" name="ans8" value="">
				<input type="hidden" id="ans8" name="ans9" value="">
				<input type="hidden" id="ans9" name="ans10" value=""> -->

				<div id="next_button" class="text-center my-4">
					<button class="btn btn-primary shadow px-5" type="button" disabled="" value="Next">Next</button>
				</div>
			</form>
		</div>

	</div>

	<script type="text/javascript">
		
		$(document).ready(function() {

			// get quiz_id from the url

			function param(name) {
				return (location.search.split(name + '=')[1] || '').split('&')[0];
			}

			if(param("quiz_id") !== '') {
				var quiz_id = param("quiz_id");
			}

			else {
				alert('Something went wrong !');
				javascript:history.go(-1);
			}

			var que = 0;
			var timer2;

			// get time from the quiz file

			$.getJSON( "JSON/"+quiz_id+".json", function(obj) {

				$.each(obj, function(key, value) {

					timer2 = value['time'];

				});
			});

			var interval = setInterval(function() {

				var timer = timer2.split(':');
				var minutes = parseInt(timer[0], 10);
				var seconds = parseInt(timer[1], 10);

				if(timer2 == "0:00") {

					alert('Time is up !');
					$("#time").val(timer2);
					$("#next_button button").remove();
					$("#next_button").append('<button class="btn btn-success shadow px-5" id="submit" type="submit" value="Submit">Submit</button>');
					$("#submit").click();
				}

				else {
					--seconds;
					minutes = (seconds < 0) ? --minutes : minutes;
					if (minutes < 0) clearInterval(interval);
					seconds = (seconds < 0) ? 59 : seconds;
					seconds = (seconds < 10) ? '0' + seconds : seconds;
					$('#time_count').html(minutes + ':' + seconds);
					timer2 = minutes + ':' + seconds;
				}

			}, 1000);

			$.getJSON( "JSON/"+quiz_id+".json", function(obj) {

				$.each(obj, function(key, value) {

					if(que != value['questions'].length) {

						$('#question_count').html(que+1);

						$('#question').html(value['questions'][que]);

						$('#options div label').eq(0).html(value['options'][que][0]);
						$('#options div label').eq(1).html(value['options'][que][1]);
						$('#options div label').eq(2).html(value['options'][que][2]);
						$('#options div label').eq(3).html(value['options'][que][3]);

						$('#options div input').eq(0).val(value['options'][que][0]);
						$('#options div input').eq(1).val(value['options'][que][1]);
						$('#options div input').eq(2).val(value['options'][que][2]);
						$('#options div input').eq(3).val(value['options'][que][3]);
					}

				});
			});

			$('.radio').click(function() {

				$("#next_button button").prop('disabled',false);

				$('#answerred #ans'+que).val(this.value);

			});

			$("#next_button button").click(function() {

				$('.radio').prop('checked',false);

				$(this).prop('disabled',true);

				$.getJSON( "JSON/"+quiz_id+".json", function(obj) {

					$.each(obj, function(key, value) {

						var que_count = que+2;

						if(que_count <= value['questions'].length) {

							que++;

							$('#question_count').html(que_count);
						}

						if(que-1 == value['questions'].length-2){

							$("#next_button button").remove();
							$("#next_button").append('<button class="btn btn-success shadow px-5" id="submit" type="submit" value="Submit">Submit</button>');

						}

						$('#question').html(value['questions'][que]);

						$('#options div label').eq(0).html(value['options'][que][0]);
						$('#options div label').eq(1).html(value['options'][que][1]);
						$('#options div label').eq(2).html(value['options'][que][2]);
						$('#options div label').eq(3).html(value['options'][que][3]);

						$('#options div input').eq(0).val(value['options'][que][0]);
						$('#options div input').eq(1).val(value['options'][que][1]);
						$('#options div input').eq(2).val(value['options'][que][2]);
						$('#options div input').eq(3).val(value['options'][que][3]);

					});
				});

			});

			$('form').on('submit',function(event) {

				alert('Your Quiz has been completed !\nClick ok to see the result.');

				timer2 = timer2.replace(':', '_');

				$('#time').val(timer2);
				
				return true;
			});
		});

	</script>

</body>
</html>
