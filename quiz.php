#!/usr/bin/php
<?php
	/* TODO:
	 *   Add command line options for controlling:
	 *     1) Number of questions
	 *     2) Order of questions
	 * 		 3) Set delimiter
	 * 		 4) Option to omit multiple choice answers
   */

	$delimiter = ",";

	if ($argc < 2) {
		die("Usage: {$argv[0]} path/to/quiz.txt\n");
	}

	if (($file = @fopen($argv[1], "r")) === FALSE) {
		die("Could not open {$argv[1]} for reading\n");
	}

	$cnt = 0;

	while (($data = fgetcsv($file, 1000, $delimiter)) !== FALSE) {
		$quiz_data[$cnt]['question'] 	= $data[0];
		$quiz_data[$cnt]['correct']		= $data[1];

		for ($i=2; $i<count($data); $i++) {
			$quiz_data[$cnt]['answer_' . $i] = $data[$i];
		}

		$cnt++;
	}

	$correct_answers = 0;

	system("clear");

	foreach ($quiz_data as $quiz_datum) {
		foreach ($quiz_datum as $key => $value) {
			if ($key == 'question') {
				continue;
			}

			$temp[] = $value;
                }
		shuffle($temp);
		echo $quiz_datum['question'] . "\n";

		for ($i=0; $i<count($temp); $i++) {
			$n = $i+1;
			echo "$n) " . $temp[$i] . "\n";
		}	

		$answer = trim(fgets(STDIN));

		if ($temp[$answer-1] == $quiz_datum['correct']) {
			$correct_answers++;
		}

		// clear out the temp array
		$temp = array();

		system("clear");
	}

	echo "You answered $correct_answers questions correctly\n";

	fclose($file);
