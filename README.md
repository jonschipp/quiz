quiz
====

Goal is to create quizzes on various topics for retrieval practice in CSV format to be read from `quiz`.
Put them in a text file where the first field is the question, the second the correct answer, and any fields after a list of other possible choices.

```
What format should the quizzes be in?,csv,colon-delimited,json
The quizzes should be in CSV format?,true,false
```

Each folder should be a different topic which the quizzes are then placed into.

```
mkdir FreeBSD
mkdir MakeItStick-TheScienceOfSuccessfulLearning
mkdir PythonForDummies
```

Read from the quizzes:
```
quiz.php -f FreeBSD/ports.txt -n -d "."

-f	- Specifies the quiz file.
-n	- (optional) No choices. Changes the quiz type to "fill in the blank".
-d	- (optional) Set the delimiter character. If not specified, the delimiter is ",".
-c	- (optional) Runs quiz in "Flash Card" mode.
-q	- (optional) Specifies the number of questions.
-s	- (optional) Sorts questions in sequential (-s seq) or reverse (-s rev) order.
```

[More info](http://www.amazon.com/Make-It-Stick-Successful-Learning/dp/0674729013)
