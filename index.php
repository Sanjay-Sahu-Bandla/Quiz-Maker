<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">

	<meta name="viewport" content="width=device-width,initial-scale=1">

	<!-- JQuery -->
	<script src="Assets/plugins/jquery/jquery.slim.min.js"></script>

	<!-- Ajax -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- bootstrap -->
	<link href="Assets/plugins/bootstrap-4.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="Assets/plugins/bootstrap-4.4.1/dist/js/bootstrap.min.js"></script>

	<!-- fontawesome -->
	<link href="Assets/plugins/fontawesome-free/css/all.min.css" rel="stylesheet">

	<!-- customizations -->
	<link rel="stylesheet" type="text/css" href="Assets/dist/style.css">

	<script src="Assets/dist/create_quiz.js" type="text/javascript"></script>

	<title>Quiz Maker</title>

	<style type="text/css">
		body {
			/*background: gray;*/
		}
	</style>

</head>
<body class="container">

	<h1 class="text-primary text-center mt-3 ">Quiz Maker</h1><br>

	<div class="container shadow table-responsive rounded">

		<table class="table text-center">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Quiz Name</th>
					<th scope="col">No of Questions</th>
					<th scope="col">Time Limit</th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody>

				<?php

				$quiz_data = file_get_contents("JSON/quiz_entries.json");
				$array = json_decode($quiz_data,true);

				for($i=0; $i<count($array); $i++) {

					echo '<tr>
					<th scope="row">'.($i+1).'</th>
					<td>'.$array[$i]["quiz_name"].'</td>
					<td>'.count($array[$i]["questions"]).'</td>
					<td>'.$array[$i]["time"].' Minutes</td>
					<td class="mx-0 px-0">
					<a href="quiz.php?quiz_id='.$array[$i]["quiz_id"].'" class="btn btn-outline-primary">Start <i class="ml-1 fa fa-arrow-right"></i></a>
					</td>
					</tr>';

				}

				?>

			</tbody>
		</table>

	</div>

	<div id="create_quiz_btn" class="text-center m-5">
		<button class="btn btn-lg px-4 btn-primary" onclick="$('#entry_quiz_modal').modal('show');">Create Your Own <i class="ml-2 fa fa-arrow-right"></i></button>
	</div><br><br>

	<form id="questions_set_form" method="post" action="">

		<div id="questions_set">
		</div>
		<div id="alert"></div>

		<div class="d-none">
			<input type="hidden" name="quiz_name_val" id="quiz_name_val">
			<input type="hidden" name="no_of_quests_val" id="no_of_quests_val">
			<input type="hidden" name="time_limit_val" id="time_limit_val">
			<input type="hidden" name="quiz_id" id="quiz_id" value="<?php echo uniqid(); ?>">
		</div>

	</form>

	<!-- modal for creating new quizes -->

	<div class="modal fade" id="entry_quiz_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLongTitle">Create Your Own</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					<form action="" method="post" onsubmit="event.preventDefault();create_quiz();">
						<div class="form-group">
							<label for="quiz_name">Quiz Name</label>
							<input type="text" name="quiz_name" class="form-control" id="quiz_name" required="">
						</div>
						<div class="row">
							<div class="col-6">
								<div class="form-group mb-0">
									<label for="no_of_quests">No of Questions</label>
									<input type="number" name="no_of_quests" class="form-control" id="no_of_quests" required="">
								</div>
							</div>
							<div class="col-6">
								<div class="form-group mb-0">
									<label for="time_limit">Time Limit <span class="text-secondary">(In minutes)</span></label>
									<input type="number" name="time_limit" class="form-control" id="time_limit" required="">
								</div>
							</div>
						</div>
						<br>
						
						<button type="submit" class=" mt-2 btn btn-primary">Submit</button>
						<div class="form-group text-right mb-0">
							<button type="button" class=" btn btn-light bg-light text-danger" data-dismiss="modal" aria-label="Close">
								<small>Cancel</small>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">

		
	</script>


</body>
</html>
