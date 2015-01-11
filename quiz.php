#!/usr/bin/php
<?php
	/* TODO:
	 *   Add command line options for controlling:
	 *     1) Number of questions
	 *     2) Order of questions
         */


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
	$quiz_data = getData($file, $delimiter);

	// if -c is not set (flash card mode) run in default.
	if (!isset($options['c'])) {	
		quizMode($quiz_data, $options);
	// if -c is set, run in flash card mode	
	} elseif (isset($options['c'])) {
		flashMode($quiz_data);
	}

	fclose($file);

	// functions
	function getData($file, $delimiter) {
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
		return $quiz_data;
	}	


	function showAnswers($temp) {
		for ($i=0; $i<count($temp); $i++) {
			$n = $i+1;
			echo "$n) " . $temp[$i] . "\n";
		}
	}

	function checkAnswer($options, $temp, $answer, $quiz_datum, $correct_answers) {
		if (!isset($options['n'])) {
			if ($temp[$answer-1] == $quiz_datum['correct']) {
				$correct_answers++;
			}
		} elseif (strcasecmp($answer, $quiz_datum['correct']) == 0) {
				$correct_answers++;
			}
		return $correct_answers;
	}	


	function quizMode($quiz_data, $options) {
		system("clear");
		$correct_answers = 0;
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
				showAnswers($temp);
			}	
			// store user's answer
			$answer = trim(fgets(STDIN));
			// check if answer is correct
			$correct_answers = checkAnswer($options, $temp, $answer, $quiz_datum, $correct_answers);
			$temp = array();
			system("clear");
		}
		echo "You answered $correct_answers of " . count($quiz_data) . " questions correctly\n";
	}

	function flashMode($quiz_data) {
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
