quiz
====

Goal is to create quizzes on various topics for retrieval practice in CSV format to be read from `quiz` (not written yet).
Put them in a text file where the first field is the question, the second the answer, and any fields after a list of other possible choices.

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
quiz -f FreeBSD/ports.txt
```

[More info](http://www.amazon.com/Make-It-Stick-Successful-Learning/dp/0674729013)
