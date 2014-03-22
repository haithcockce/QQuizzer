<?php
   session_start();
   if(isset($_SESSION['Name'])) {
      header('Location: http://student.cs.appstate.edu/haithcockce/QQuizzer/php/MasterEditor.php');
   }
   else {
    header('Location: http://student.cs.appstate.edu/haithcockce/QQuizzer/php/login.php');
   }

?>