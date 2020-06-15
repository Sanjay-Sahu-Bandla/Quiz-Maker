<?php

$quiz_id = $_GET['quiz_id'];

$post_data = array();

foreach ($_POST as $key => $value) {

	$post_data[] = $key;

}

// print_r($post_data);

if(count($post_data) != count(array_filter($post_data))){
	echo 'some fields are empty';
}

else {

	$quiz_data = file_get_contents("JSON/".$quiz_id.".json");
	$array = json_decode($quiz_data,true);

	$user_data = array();

	foreach ($_POST as $key => $value) {

		$user_data[] = $value;

	}

	$time = $user_data[0];

	// echo $time.'<br><br>';

	array_splice($user_data, 0, 1);

	$user_answers = $user_data;

	// echo count($user_answers);

	$ans_count;
	$correct_ans = array();
	$total_questions;
	$answerred = array();
	$attempted = array();

	for($i=0;$i<count($user_answers); $i++) {
		// echo $user_answers[$i].'<br>';

		if($user_answers[$i] != '') {

			$attempted[] = $user_answers[$i];

		}

		$answerred[] = $user_answers[$i];
		// else {


		// }

	}

	for($i=0; $i<count($array); $i++) {

		$total_questions = count($array[$i]['answers']);

		for($j=0; $j<count($array[$i]['answers']); $j++) {

			// echo $array[$i]['answers'][$j].'<br>';

			if($array[$i]['answers'][$j] === $user_answers[$j]) {

				// echo "<br>";

				$correct_ans[] = $array[$i]['answers'][$j];
			}
		}

	}

	// save data into result.json //

	$JSON_data = file_get_contents("JSON/result.json");
	$array1 = json_decode($JSON_data,true);

	date_default_timezone_set("Asia/Kolkata");

	$timestamp =  date('d-m-Y h:i:s');
	$id = uniqid();

	$array1[] = array(

		'id' => $id,
		'quiz_id' => $quiz_id,
		'total_questions' => $total_questions,
		'total_answerred' => count($answerred),
		'total_correct_ans' => count($correct_ans),
		'answerred' => $answerred,
		'correct_ans' => $correct_ans,
		'timestamp' => date('d-m-Y h:i:s')
	);

	array_push($array1);

	if(!file_put_contents("JSON/result.json",json_encode($array1,JSON_PRETTY_PRINT))) {

		echo "Something went wrong !";
	}
	
}

?>


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

	<!-- <script src="Assets/dist/script.js" type="text/javascript"></script> -->

	<title>Quiz Maker</title>

</head>
<body class="container">

	<h1 class="text-primary text-center mt-3 ">Quiz Result</h1><br>

	<div class="container shadow table-responsive">

		<table class="table">
			<thead class="text-center">
				<tr>
					<th scope="col">No of Questions</th>
					<th scope="col">Answerred</th>
					<th scope="col">Correct</th>
				</tr>
			</thead>
			<tbody>
				<tr class="text-center">
					<td><?php echo $total_questions; ?></td>
					<td><?php echo count($attempted); ?></td>
					<td><?php echo count($correct_ans); ?></td>
				</tr>
			</tbody>
		</table>

	</div><br><br>

	<div id="result" class="rounded shadow m-3 p-3">

		<?php 

		for($i=0; $i<count($array); $i++) {

			for($j=0; $j<count($array[$i]["questions"]); $j++) {

				echo '<div class="card bg-light mb-3">
				<div class="card-body">
				<h5 class="card-title"><span class="badge badge-pill badge-primary mr-2">Q</span> ';

				echo $array[$i]["questions"][$j];


				echo '</h5>
				<p class="card-text"><span class="badge badge-pill badge-success mr-2" style="font-size: 16px;">A</span> ';

				if($answerred[$j] == null) {
					$answerred[$j] = '<span class="text-secondary">'.$answerred[$j].'Not Given</span>';
				}

				else if(in_array($answerred[$j], $correct_ans)) {
					$answerred[$j] = '<span class="text-success">'.$answerred[$j].'</span>';
				}

				else if(!in_array($answerred[$j], $correct_ans)) {
					$answerred[$j] = '<span class="text-danger">'.$answerred[$j].'</span>';
				}

				// if($answerred[$j] == $correct_ans[$j]) {

				// 	$answerred[$j] = '<span class="text-success">'.$answerred[$j].'</span>';
				// }


				// else if($answerred[$j] != $correct_ans[$j]) {
				// 	$answerred[$j] = '<span class="text-danger">'.$answerred[$j].'</span>';
				// }

				// else if($answerred[$j] == '') {
				// 	$answerred[$j] = '<span class="text-secondary">'.$answerred[$j].'Not Given</span>';
				// }

				echo $answerred[$j];

				echo '</p>
				</div>
				</div>';

			}

		}

		?>

	</div>

</body>
</html>
