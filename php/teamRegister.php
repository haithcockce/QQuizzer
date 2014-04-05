<?php
    if($_POST['requesting'] == 'team registration') {
        register();
    }
    else {
        header('Location: http://student.cs.appstate.edu/haithcockce/QQuizzer/php/teamLogger.php');
    }

    function register() {
        $dbConnection = new mysqli('student.cs.appstate.edu', 'haithcockce', '900431409', 'QQuizzer');
        
        $teamName = mysqli_real_escape_string($dbConnection, $_POST['teamName']);
        $schoolName = mysqli_real_escape_string($dbConnection, $_POST['schoolName']);
        $teacherName = mysqli_real_escape_string($dbConnection, $_POST['teacherName']);
        $queryStr = 'INSERT INTO Teams (
                        `TeamName`,
                        `Score`,
                        `School`,
                        `Key`,
                        `TeacherName`
                    ) VALUES (
                        "'.$teamName.'", 
                        "0", 
                        "'.$schoolName.'", 
                        "'.$_POST['deviceID'].'", 
                        "'.$teacherName.'")';
        $result = mysqli_query($dbConnection, $queryStr);
        session_start();
        
        if ($result == 0)
            echo 'failure';
        else {
            $_SESSION['deviceID'] = $_POST['deviceID'];
            echo 'success';
        }
    }
?>