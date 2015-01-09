#!/usr/bin/php
<?php
	/* TODO:
	 *   Add command line options for controlling:
	 *     1) Number of questions
	 *     2) Order of questions
   */


// functions

	function getData() {
		global $quiz_data;
		global $file;
		global $delimiter;
		$cnt = 0;
		while (($data = fgetcsv($file, 1000, $delimiter)) !== FALSE) {
			$quiz_data[$cnt]['question'] = $data[0];
			$quiz_data[$cnt]['correct'] = $data[1];

			for ($i=2; $i<count($data); $i++) {
				$quiz_data[$cnt]['answer_' . $i] = $data[$i];
			}	
			$cnt++;
		}
		shuffle($quiz_data);
	}	


	function showAnswers() {
		global $temp;
		for ($i=0; $i<count($temp); $i++) {
			$n = $i+1;
			echo "$n) " . $temp[$i] . "\n";
		}
	}

	function checkAnswer() {
		global $options;
		global $temp;
		global $correct_answers;
		global $quiz_data;
		global $answer;
		global $quiz_datum;
		if (!isset($options['n'])) {
			if ($temp[$answer-1] == $quiz_datum['correct']) {
				$correct_answers++;
			}
		} else {
			if (strcasecmp($answer, $quiz_datum['correct']) == 0) {
				$correct_answers++;
			}
		}
	}


	function quizMode() {
		global $quiz_data;
		global $options;
		global $temp;
		global $quiz_datum;
		global $answer;
		global $correct_answers;
		system("clear");
		$q = 1;
		foreach ($quiz_data as $quiz_datum) {
			foreach ($quiz_datum as $key => $value) {
				if ($key == 'question') {
					continue;
				}

				$temp[] = $value;
			}
			shuffle($temp);

			// display question count
			echo "Question " . $q . " of " . count($quiz_data) . "\n\n";
			$q++;
			// display qustion
			echo $quiz_datum['question'] . "\n";
			// display possible answers if -n option is not used
			if (!isset($options['n'])) {
				showAnswers();
			}	
			// store user's answer
			$answer = trim(fgets(STDIN));
			// check if answer is correct
			checkAnswer();
			$temp = array();
			system("clear");
		}
		echo "You answered $correct_answers of " . count($quiz_data) . " questions correctly\n";
	}

	function flashMode() {
		global $quiz_data;
		global $quiz_datum;
		system("clear");
		$q = 1;
		foreach ($quiz_data as $quiz_datum) {
			// display question count
			echo "Flash Card " . $q . " of " . count($quiz_data) . "\n\n";
			$q++;
			// display qustion
			echo "Q: " . $quiz_datum['question'] . "\n";
			fgets(STDIN);
			echo "A: " . $quiz_datum['correct'] . "\n";
			fgets(STDIN);
			system("clear");
		}
	}



// set correct_answers
$correct_answers = 0;


// get command line options
	
	$options = getopt("cnf:d:");
	$quiz = $options['f'];	

// check if delimiter is set. Default to ","

	if (isset($options['d'])) {
		$delimiter = $options['d'];
	} else {
		$delimiter = ",";
	}

// end with usage statement if file is not specified

	if ($argc < 3) {
		die("Usage: {$argv[0]} -f path/to/quiz.txt {-n}\n");
	}

// end if file cannot be opened

	if (($file = @fopen($quiz, "r")) === FALSE) {
		die("Could not open $quiz for reading\n");
	}




// run quiz
	getData();

	// if -c is not set (flash card mode) run in default.
	if (!isset($options['c'])) {	
		quizMode();

	// if -c is set, run in flash card mode	
	} elseif (isset($options['c'])) {

		flashMode();
		}

	fclose($file);
