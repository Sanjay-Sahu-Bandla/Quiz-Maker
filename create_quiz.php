<?php

$questions = array();
$options = array();
$answers = array();

foreach ($_POST as $key => $value) {
	if(strpos($key, 'que_') === 0) {
		$questions[] = $value;
	}
}

foreach ($_POST as $key => $value) {

	if(strpos($key, 'opt_') === 0) {
		$options[] = $value;
	}
}

$split_array = array_chunk($options,4);

foreach ($_POST as $key => $value) {

	if(strpos($key, 'ans_') === 0) {
		$answers[] = $value;
	}
}

// foreach ($_POST as $key => $value) {
// 	echo $key.' : '.$value;
// }

// insert data into quiz_name_val.json

$JSON_data = file_get_contents("JSON/quiz_entries.json");
$array1 = json_decode($JSON_data,true);

date_default_timezone_set("Asia/Kolkata");

$timestamp =  date('d-m-Y h:i:s');

$quiz_id = "Quiz_".$_POST['quiz_id'];

$array1[] = array(

	'quiz_name' => $_POST['quiz_name_val'],
	'quiz_id' => "Quiz_".$_POST['quiz_id'],
	'time' => $_POST['time_limit_val'],
	'questions' => $questions,
	'timestamp' => date('d-m-Y h:i:s')
);

if(!file_put_contents("JSON/quiz_entries.json",json_encode($array1,JSON_PRETTY_PRINT))) {

	echo "json_entries_fail";
}

// create a json file for requested quiz

file_put_contents("JSON/".$quiz_id.".json", '');

$JSON_data = file_get_contents("JSON/".$quiz_id.".json");
$array1 = json_decode($JSON_data,true);

$array1[] = array(

	'quiz_name' => $_POST['quiz_name_val'],
	'quiz_id' => "Quiz_".$_POST['quiz_id'],
	'time' => $_POST['time_limit_val'],
	'questions' => $questions,
	'options' => $split_array,
	'answers' => $answers
);

if(!file_put_contents("JSON/".$quiz_id.".json",json_encode($array1,JSON_PRETTY_PRINT))) {

	echo "json_file_creation_fail";
}

?>