<?php
    if ($_POST['requesting'] == 'get new question')
    	  getNewQuestion();
    else if ($_POST['requesting'] == 'get old question')
        getOldQuestion();
    else if ($_POST['requesting'] == 'answer a question')
        recordAnswer();
    else
    	  header('Location: http://student.cs.appstate.edu/haithcockce/QQuizzer/questions/index.php');

    function getNewQuestion() {
        $QUESTION_NUMBER = 0;
        $QUESTION_CLAUSE = 1;
        $QUESTION_CORRECT_ANSWER_INDEX = 2;
        $QUESTION_ANSWER_CHOICES = 3;
        $DELIMITER = '|$|';

    	  $dbConnection = new mysqli('student.cs.appstate.edu', 'haithcockce', '900431409', 'QQuizzer');
    	  $randomQuestionIndexQuery = 'SELECT `QuestionNumber` 
    	                                   FROM `BoothQuestions` 
    	                                   WHERE `BoothName`="'.$_POST['booth'].'" 
    	                                   ORDER BY RAND() 
    	                                   LIMIT 1';
    	  $questionNumber = mysqli_fetch_row(mysqli_query($dbConnection, $randomQuestionIndexQuery));
    	  $questionQuery = 'SELECT * 
                              FROM `Questions` 
                              WHERE `QuestionNumber`="'.$questionNumber[0].'"';
        $question = mysqli_fetch_row(mysqli_query($dbConnection, $questionQuery));
        //split answer choices into an array
        $question[$QUESTION_ANSWER_CHOICES] = explode($DELIMITER, $question[$QUESTION_ANSWER_CHOICES]);
        $teamNameQuery = 'SELECT `TeamName` 
                              FROM `Teams` 
                              WHERE `Key`="'.$_POST['key'].'"';
        $teamName = mysqli_fetch_row(mysqli_query($dbConnection, $teamNameQuery));
        $updateTeamAnswerQuery = 'INSERT INTO `TeamAnswers` (
                                     `TeamName`, 
                                     `Key`, 
                                     `Question`, 
                                     `Answer`, 
                                     `Correct`
                                  ) VALUES (
                                     "'.$teamName[0].'", 
                                     "'.$_POST['key'].'", 
                                     "'.$question[$QUESTION_CLAUSE].'", 
                                     "INCOMPLETE", 
                                     "INCOMPLETE"
                                  )';
        $result = mysqli_query($dbConnection, $updateTeamAnswerQuery);
        echo json_encode($question);
    }
    
    function getOldQuestion() {
        $QUESTION_ANSWER_CHOICES = 3;
        $DELIMITER = '|$|';

    	  $dbConnection = new mysqli('student.cs.appstate.edu', 'haithcockce', '900431409', 'QQuizzer');
    	  $getEarlierQuestionQuery = 'SELECT `Question` 
    	                                  FROM `TeamAnswers` 
    	                                  WHERE `Key`="'.$_POST['key'].'"';
    	  $questionClause = mysqli_fetch_row(mysqli_query($dbConnection, $getEarlierQuestionQuery));
        $fullQuestionQuery = 'SELECT *  
                                  FROM `Questions` 
                                  WHERE `Question`="'.$questionClause[0].'"';
        $question = mysqli_fetch_row(mysqli_query($dbConnection, $fullQuestionQuery));
    	  $question[$QUESTION_ANSWER_CHOICES] = explode($DELIMITER, $question[$QUESTION_ANSWER_CHOICES]);
    	  echo json_encode($question);
    }

    function recordAnswer() {
        $dbConnection = new mysqli('student.cs.appstate.edu', 'haithcockce', '900431409', 'QQuizzer');
        if ($_POST['correct'] == 'true')
            $correct = 1;
        else
            $correct = 0;
        $updateTeamAnswerQuery = 'UPDATE `TeamAnswers` 
                                      SET `Answer`="'.$_POST['answerChoice'].'", 
                                          `Correct`="'.$correct.'" 
                                      WHERE `Key`="'.$_POST['key'].'" 
                                          AND `Question`="'.$_POST['question'].'"';
        $result = mysqli_query($dbConnection, $updateTeamAnswerQuery);
        if ($correct == 1) {
            $scoreUpdateQuery = 'UPDATE `Teams` 
                                     SET `Score`=`Score`+1 
                                     WHERE `Key`="'.$_POST['key'].'"';
            $updateScoreResult = mysqli_query($dbConnection, $scoreUpdateQuery);
        }
        echo $result;
    }
?>