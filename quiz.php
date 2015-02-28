#!/usr/bin/php
<?php

	// get command line options
	$options = getopt("cnf:d:q:s:");
	$quiz = $options['f'];

	// check if delimiter is set. Default to ","
	if (isset($options['d'])) {
		$delimiter = $options['d'];
	} else {
		$delimiter = ",";
	}

	// end with usage statement if file is not specified
	if ($argc < 3) {
		die("Usage: {$argv[0]} -f path/to/quiz.txt [-n]\n");
	}

	// end if file cannot be opened
	if (($file = @fopen($quiz, "r")) === FALSE) {
		die("Could not open $quiz for reading\n");
	}

	// run quiz
	$quiz_data = getData($file, $delimiter, $options);

	// if -c is not set (flash card mode) run in default.
	if (!isset($options['c'])) {
		quizMode($quiz_data, $options);
	// if -c is set, run in flash card mode
	} elseif (isset($options['c'])) {
		flashMode($quiz_data);
	}

	fclose($file);

	// functions
	function getData($file, $delimiter, $options) {
		$cnt = 0;
		while (($data = fgetcsv($file, 1000, $delimiter)) !== FALSE) {
			$quiz_data[$cnt]['question'] = $data[0];
			$quiz_data[$cnt]['correct'] = $data[1];

			for ($i=2; $i<count($data); $i++) {
				$quiz_data[$cnt]['answer_' . $i] = $data[$i];
			}
			$cnt++;
		}
		if (isset($options['s'])) {
			if ($options['s'] == "seq") {
			} elseif ($options['s'] == "rev") {
				$quiz_data=array_reverse($quiz_data);
			}
		} else {
			shuffle($quiz_data);
		}
		if (isset($options['q'])) {
			array_splice($quiz_data, $options['q']);
		}
		return $quiz_data;
	}


	function showAnswers($temp) {
		for ($i=0; $i<count($temp); $i++) {
			$n = $i+1;
			echo "\033[36m$n) $temp[$i]\033[0m\n";
		}
	}

	function checkAnswer($options, $temp, $answer, $quiz_datum, $correct_answers) {
		if (!isset($options['n'])) {
			if ($temp[$answer-1] == $quiz_datum['correct']) {
				$correct_answers++;
				echo "\n\033[32mCorrect!\033[0m";
				fgets(STDIN);
			} else {
				echo "\n\033[31mIncorrect. The correct answer is: \"" . $quiz_datum['correct'] . "\"\033[0m";
				fgets(STDIN);
			}
		} elseif (strcasecmp($answer, $quiz_datum['correct']) == 0) {
				$correct_answers++;
				echo "\nCorrect!";
				fgets(STDIN);
		} else {
			echo "\nIncorrect. The correct answer is: \"" . $quiz_datum['correct'] . "\"";
			fgets(STDIN);
		}
		return $correct_answers;
	}


	function quizMode($quiz_data, $options) {
		system("clear");
		$correct_answers = 0;
		$q = 1;
		$percentage = 0;
		foreach ($quiz_data as $quiz_datum) {
			foreach ($quiz_datum as $key => $value) {
				if ($key == 'question') {
					continue;
				}

				$temp[] = $value;
			}
			shuffle($temp);

			$total = count($quiz_data);
			// display question count
			echo "\033[33mQuestion $q of $total \033[0m \t\t\t\033[33mCorrect: $percentage%\033[0m\n\n";
			// display qustion
			echo "\033[35m" . $quiz_datum['question'] . "\033[0m\n";
			// display possible answers if -n option is not used
			if (!isset($options['n'])) {
				showAnswers($temp);
			}
                        // store user's answer
                        echo "\n\033[35mA: ";
			$answer = trim(fgets(STDIN));
			// check if answer is correct
			$correct_answers = checkAnswer($options, $temp, $answer, $quiz_datum, $correct_answers);
			$count1 = ($correct_answers / $total) * 100;
			$percentage = number_format($count1, 0);
			$q++;
			$temp = array();
			system("clear");
		}
		echo "\033[36mYou answered $correct_answers of " . count($quiz_data) . " questions correctly\033[0m   \033[33m($percentage%)\033[0m\n\n";
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
	
