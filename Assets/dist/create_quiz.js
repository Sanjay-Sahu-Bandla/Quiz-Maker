// get input values into select tag

function getOptions(current_sel) {

	$inputs = $(current_sel).closest('#question_set').find('.options input');

	$opt_a = $inputs.eq(0).val();
	$opt_b = $inputs.eq(1).val();
	$opt_c = $inputs.eq(2).val();
	$opt_d = $inputs.eq(3).val();

	// alert($opt_a+$opt_b+$opt_c+$opt_d);

	$(current_sel).children('option').eq(1).val($opt_a);
	$(current_sel).children('option').eq(2).val($opt_b);
	$(current_sel).children('option').eq(3).val($opt_c);
	$(current_sel).children('option').eq(4).val($opt_d);

	$(current_sel).children('option').eq(1).text($opt_a);
	$(current_sel).children('option').eq(2).text($opt_b);
	$(current_sel).children('option').eq(3).text($opt_c);
	$(current_sel).children('option').eq(4).text($opt_d);
}


$(document).ready(function() {

	$('#questions_set_form').on('submit',function(event) {

		event.preventDefault();
		var formData = new FormData(this);
		
		var loader = '<div class="spinner-border text-light ml-3" role="status" style="width: 25px; height: 25px;"><span class="sr-only">Loading...</span></div>';

		var z=1;

		$select = $('select');

		for($s=0; $s<$select.length;) {

			if($select.eq($s).val() == '') {

				$('#questions_set_form #alert').html('<div class="text-center alert alert-info">Plz fill all the answers !</div>');
			}

			else {
				// $('#quiz_submit_btn').prop('disabled','false');
				// $('#quiz_submit_loader').html(loader);
				z=0;

			}

			$s++;
		}

		if(z==0) {

			$('#quiz_submit_loader').html(loader);

			$.ajax({
				type:'POST',
				url:'create_quiz.php',
				data: formData, 
				cache:false,
				contentType: false,
				processData: false,
				success:function(data) {

					$('#quiz_submit_loader').html('');

					if(data.indexOf('json_entries_fail') >= 0) {

						alert('Unable to append data in entries file !');
					}

					else if(data.indexOf('json_file_creation_fail') >= 0) {

						alert('JSON file creation failed !');
					}

					else {
						alert('Quiz Created Successfully !');
						window.location.reload();
					}
				},

				error: function(data){
					$('#quiz_submit_loader').html('');
					alert(data);
				}

			});
		}
	});
});

function create_quiz() {

	$('#create_quiz_btn').hide();
	$('#entry_quiz_modal').modal('hide');

	var quiz_name = $('#quiz_name').val();
	var no_of_quests = $('#no_of_quests').val();
	var time_limit = $('#time_limit').val();

	$('#quiz_name_val').attr('value',quiz_name);
	$('#no_of_quests_val').attr('value',no_of_quests);
	$('#time_limit_val').attr('value',(time_limit+':00'));
	$('#quiz_id').attr('value',Date.now());

	var i;

	for(var j = 0; j<no_of_quests; j++) {

		i = j+1;

		$one = $('#questions_set').append('<div class="card bg-light mb-3" id="que_set_'+i+'">			<div class="card-body" id="question_set">				<div class="form-inline mb-3" id="question_'+i+'">					<div class="d-inline">						<h5 class="d-inline"><span class="badge badge-pill badge-primary mr-2 mb-1 p-2 px-3">Quest. '+i+'</span></h5>						<input type="text" name="que_'+i+'" class="d-inline bg-light form-control que_'+i+'" style="width: ;" placeholder="Questin goes here !" required="">					</div>	<div class="form-inline mt-3 mb-3 options" id="options_'+i+'">					<p class="card-text">						<div class="d-inline badge badge-pill badge-info mr-2 p-2 px-3" style="font-size: 16px;">Options</div>');

		for(var k=1; k<=4; k++) {

			$two = $('#options_'+i).append('<input type="text" name="opt_'+i+'_'+k+'" id="q1_opt_1" class=" bg-light form-control mr-lg-3 opt_'+i+'" style="width: ;" placeholder="Option '+k+'" required=""><br><br>										</p>				</div>');
		}

		$two.after('</div><div class="form-inline" id="answer">					<div class="d-inline">						<h5 class="d-inline"><span class="badge badge-pill badge-success mr-2 mb-1 p-2 px-3">Answer</span></h5><select onclick="getOptions(this);" name="ans_'+i+'" id="q1_sel_1" class="custom-select my-1 mr-sm-2 ans_'+i+'" required="">							<option selected value="">Choose...</option><option id="opt_a" value=" "></option><option id="opt_b" value=" "></option><option id="opt_c" value=" "></option><option id="opt_d" value=" "></option></select>					</div>				</div>			</div>		</div>');
	}


	$('#questions_set').append('<div class="text-center"><button class="px-3 py-2 mb-5 btn btn-primary" type="submit" id="quiz_submit_btn">Create Quiz <span id="quiz_submit_loader"></span></button></div>');
}