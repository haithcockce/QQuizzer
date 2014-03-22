<?php
   if ($_POST['login'] === 'true') {
   	  echo login($_POST['email']);
   } 
   else if($_POST['login'] === 'false'){
   	  logout();
   }
   else { //Protects from people attempting to execute this
   	  header("Location: http://student.cs.appstate.edu/haithcockce/QQuizzer/php/login.php");
   }

   function login($email) {
   	$database = new mysqli('student.cs.appstate.edu', 'haithcockce', '900431409', 'QQuizzer');
   
      $scrubbedEmail = mysqli_real_escape_string($database, $email);
      $result = mysqli_query($database, 'SELECT Name FROM Professors WHERE Email="'.$scrubbedEmail.'"');
      $resultArray = mysqli_fetch_all($result);
      if (count($resultArray) > 0) {
         session_start();

         $_SESSION['Name'] = $resultArray[0][0];
         echo 'success';
      }
      else {
         echo 'failure';
      }
   }
   
   function logout() {
   	session_start();
      unset($_SESSION['Name']);
      echo 'success';
   }

    
?>
