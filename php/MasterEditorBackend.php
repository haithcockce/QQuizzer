<?php
    switch ($_POST['requesting']) {
        case 'get all booths':    
            getBoothNames(); break;
        case 'get booth info':    
            getBoothInfo(); break;
        case 'get all questions':    
            getQuestions(); break;
        case 'get question answers': 
            getAnswers(); break;
        case 'add a question':    
            addQuestion(); break;
        case 'edit a question':
            editQuestion(); break;
        case 'remove a question': 
            removeQuestion(); break;
        case 'move a question':   
            moveQuestion(); break;
        case 'add a booth':    
            addBooth(); break;
        case 'edit a booth':      
            editBooth(); break;
        case 'remove a booth':    
            removeBooth(); break;
        default:
            session_start();
            if(isset($_SESSION['Name'])) {
                header('Location: http://student.cs.appstate.edu/haithcockce/QQuizzer/php/MasterEditor.php');
            }
            else {
                header('Location: http://student.cs.appstate.edu/haithcockce/QQuizzer/php/login.php');
            }
    }

    $HOST = 'student.cs.appstate.edu';
    $USERNAME = 'haithcockce';
    $PASSWORD = '900431409';
    $DATABASE = 'QQuizzer';
   	  

    function getBoothNames() {
        $dbConnection = new mysqli('student.cs.appstate.edu', 'haithcockce', '900431409', 'QQuizzer');
        $result = mysqli_query($dbConnection, 'SELECT BoothName FROM BoothNames');
        echo queryResultToJSON($result);
    }

    function getBoothInfo() {
        $dbConnection = new mysqli('student.cs.appstate.edu', 'haithcockce', '900431409', 'QQuizzer');
        $result = mysqli_query($dbConnection, 'SELECT Organizer FROM BoothNames 
                                               WHERE BoothName="'.$_POST['booth'].'"');
        echo queryResultToJSON($result);
    }
    

    function getQuestions() {
        $dbConnection = new mysqli('student.cs.appstate.edu', 'haithcockce', '900431409', 'QQuizzer');
        $result = mysqli_query($dbConnection, 'SELECT QuestionNumber FROM BoothQuestions
                                               WHERE BoothName="'.$_POST['booth'].'"');
        if (mysqli_num_rows($result) == 0) {
            echo 'no questions';
            exit;
        }
        $queryStr = 'SELECT Question FROM Questions WHERE QuestionNumber IN (';
        $a = array();
        for ($i = 0; $i < mysqli_num_rows($result); $i++) {
            $a = mysqli_fetch_row($result);
            $queryStr .= $a[0];
            if ($i != mysqli_num_rows($result) - 1) {
                $queryStr .= ', ';
            }
        }
        $queryStr .= ')';
        $result = mysqli_query($dbConnection, $queryStr);
        echo queryResultToJSON($result);
    }

    function getAnswers() {
        $dbConnection = new mysqli('student.cs.appstate.edu', 'haithcockce', '900431409', 'QQuizzer');
        $queryStr1 = 'SELECT PossibleAnswers
                      FROM Questions
                      WHERE Question="'.$_POST['question'].'"';
        $queryStr2 = 'SELECT CorrectAnswer
                      FROM Questions
                      WHERE Question="'.$_POST['question'].'"';
        $answersStr = mysqli_fetch_row(mysqli_query($dbConnection, $queryStr1));
        $correctAnswer = mysqli_fetch_row(mysqli_query($dbConnection, $queryStr2));
        $answersArray = explode('|$|', $answersStr[0]);
        array_push($answersArray, $correctAnswer[0]);
        echo json_encode($answersArray);
    }
    
    

    function addQuestion() {
        //INSERT INTO BoothQuestions (BoothName, QuestionNumber) VALUES ('Testest', '2')
        //SELECT QuestionNumber FROM BoothQuestions ORDER BY QuestionNumber DESC LIMIT 1
        $dbConnection = new mysqli('student.cs.appstate.edu', 'haithcockce', '900431409', 'QQuizzer');
        $result = mysqli_query($dbConnection, 'SELECT QuestionNumber FROM BoothQuestions 
                                               ORDER BY QuestionNumber DESC LIMIT 1');
        $questionNumber = (mysqli_num_rows($result) == 0 ? 0 : mysqli_fetch_row($result));
        if ($questionNumber != 0) $questionNumber = $questionNumber[0];
        $questionNumber++;
        $result = mysqli_query($dbConnection, 'INSERT INTO BoothQuestions (BoothName, QuestionNumber) 
                                               VALUES ("'.$_POST['booth'].'", "'.$questionNumber.'")');
        if (!$result) {
            echo 'failed to insert into BoothQuestions';
            exit;
        }
        $fromArray = implode('|$|', $_POST['answers']);
        $result = mysqli_query($dbConnection, 'INSERT INTO Questions 
                                               (QuestionNumber, Question, CorrectAnswer, PossibleAnswers, Shuffle) 
                                               VALUES 
                                               ("'.$questionNumber.'", "'.$_POST['question'].'", "'.$_POST['correct'].'", "'.$fromArray.'", "0")');
        if (!$result) {
            $questionCheck = mysqli_num_rows(mysqli_query($dbConnection, 'SELECT Question FROM Questions
                                                                   WHERE Question="'.$_POST['question'].'"')); 
            if ($questionCheck == 1) echo 'found question';
            else echo 'failed to insert into Questions';
            exit;
        }
        echo 'success';
        
    }

    function editQuestion() {
        $dbConnection = new mysqli('student.cs.appstate.edu', 'haithcockce', '900431409', 'QQuizzer');
        $queryStr1 = 'UPDATE BoothQuestions
                      SET BoothName="'.$_POST['newQuestion'].'"
                      WHERE BoothName="'.$_POST['oldQuestion'].'"';
        $queryStr2 = 'UPDATE Questions
                      SET Question="'.$_POST['newQuestion'].'" , 
                          CorrectAnswer="'.$_POST['correctAnswer'].'" ,
                          PossibleAnswers="'.(implode('|$|', $_POST['answers'])).'" 
                      WHERE Question="'.$_POST['oldQuestion'].'"';
        $result1 = mysqli_query($dbConnection, $queryStr1);
        $result2 = mysqli_query($dbConnection, $queryStr2);
        
        echo json_encode([$result1, $result2]);
    }
    
    function removeQuestion() {
        $dbConnection = new mysqli('student.cs.appstate.edu', 'haithcockce', '900431409', 'QQuizzer');
        $queryStr = 'SELECT QuestionNumber 
                     FROM  Questions
                     WHERE Question="'.$_POST['question'].'"';
        $result = mysqli_fetch_row(mysqli_query($dbConnection, $queryStr));
        $queryStr1 = 'DELETE FROM Questions
                      WHERE Questions.QuestionNumber="'.$result[0].'"';
        $queryStr2 ='DELETE FROM BoothQuestions
                     WHERE BoothQuestions.QuestionNumber="'.$result[0].'"';
        $result1 = mysqli_query($dbConnection, $queryStr1);
        $result2 = mysqli_query($dbConnection, $queryStr2);
        
        if ($result1 == 1 && $result2 == 1) 
            echo 'success';
        else
            echo 'failure';
    }

    function moveQuestion() {
        $dbConnection = new mysqli('student.cs.appstate.edu', 'haithcockce', '900431409', 'QQuizzer');
        $a = mysqli_fetch_row(mysqli_query($dbConnection, 'SELECT QuestionNumber FROM Questions WHERE Question="'.$_POST['question'].'"'));
        $query = 'UPDATE BoothQuestions SET BoothName="'.$_POST['toBooth'].'" WHERE QuestionNumber="'.$a[0].'"';
        $result = mysqli_query($dbConnection, $query);
        echo $result;
    }

   
    function addBooth() {
        $dbConnection = new mysqli('student.cs.appstate.edu', 'haithcockce', '900431409', 'QQuizzer');
   	    $result = mysqli_query($dbConnection, 'INSERT INTO BoothNames (BoothName, Organizer) 
   	                                           VALUES ("'.$_POST['name'].'", "'.$_POST['organizer'].'")');
   	    if ($result == 1) 
            echo 'success';
        else {
      	    $boothNameResult = mysqli_num_rows(mysqli_query($dbConnection, 'SELECT BoothName FROM BoothNames 
      	                                                                    WHERE BoothName="'.$_POST['name'].'"'));
      	    $organizerNameResult = mysqli_num_rows(mysqli_query($dbConnection, 'SELECT Organizer FROM BoothNames 
      	                                                                        WHERE Organizer="'.$_POST['organizer'].'"'));
      	    if ($boothNameResult == 1 && $organizerNameResult == 1) echo 'found both';
      	    else if ($boothNameResult == 1) echo 'found booth';
      	    else echo 'found organizer';
        }
    }

    function editBooth() {
        /**
         * To-do:
         * CHeck for existing records to prevent repeats to fail gracefully.
         * Consolidate code
         */
        
        $dbConnection = new mysqli('student.cs.appstate.edu', 'haithcockce', '900431409', 'QQuizzer');
        if ($_POST['changing'] === 'organizer only') {
            $queryStr = 'UPDATE BoothNames
                            SET Organizer="'.$_POST['organizer'].'"
                            WHERE BoothName="'.$_POST['booth'].'"';
            $result = mysqli_query($dbConnection, $queryStr);
            if ($result == 1) 
                echo 'success';
            else {
                echo 'failure';
            }
        }

        else if ($_POST['changing'] === 'booth name only') {
            $queryStr1 = 'UPDATE BoothNames
                            SET BoothName="'.$_POST['newName'].'"
                            WHERE BoothName="'.$_POST['oldName'].'"';
            $queryStr2 = 'UPDATE BoothQuestions
                            SET BoothName="'.$_POST['newName'].'"
                            WHERE BoothName="'.$_POST['oldName'].'"';
            $result1 = mysqli_query($dbConnection, $queryStr1);
            $result2 = mysqli_query($dbConnection, $queryStr2);
            if ($result1 == 1 && $result2 == 1) {
                echo 'success';
            }
            else {
                echo 'failure';
            }
        }
        else if ($_POST['changing'] === 'both') {
            $queryStr1 = 'UPDATE BoothNames
                            SET BoothName="'.$_POST['newName'].'"
                            WHERE BoothName="'.$_POST['oldName'].'"';
            $queryStr2 = 'UPDATE BoothQuestions
                            SET BoothName="'.$_POST['newName'].'"
                            WHERE BoothName="'.$_POST['oldName'].'"';
            $queryStr3 = 'UPDATE BoothNames
                            SET Organizer="'.$_POST['organizer'].'"
                            WHERE BoothName="'.$_POST['newName'].'"';
            $result1 = mysqli_query($dbConnection, $queryStr1);
            $result2 = mysqli_query($dbConnection, $queryStr2);
            $result3 = mysqli_query($dbConnection, $queryStr3);
            if ($result1 == 1 && $result2 == 1 && $result3 == 1)
                echo 'success';
            else {
                echo 'failure';
            }
        }
    }
    
    function removeBooth() {
        $dbConnection = new mysqli('student.cs.appstate.edu', 'haithcockce', '900431409', 'QQuizzer');
        $result = mysqli_query($dbConnection, 'DELETE FROM BoothNames WHERE BoothName="'.$_POST['booth'].'"');
        $queryStr = 'DELETE FROM BoothQuestions
                     WHERE BoothName="'.$_POST['booth'].'"';
        $result2 = mysqli_query($dbConnection, $queryStr);
        if ($result == 1 && $result2 == 1)
            echo 'success';
        else 
            echo 'failure';
    }

    function queryResultToJSON($result) {
        $a = array();
        for($i = 0; $i < mysqli_num_rows($result); $i++) {
            $a[$i] = mysqli_fetch_row($result);
        }
        return json_encode($a);
    }

?>