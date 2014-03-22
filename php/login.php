<?php
   session_start();
   if(isset($_SESSION['Name'])) {
      header('Location: http://student.cs.appstate.edu/haithcockce/QQuizzer/php/MasterEditor.php');
   }

?>

<!doctype html>
<html class="no-js" lang="en">
<head>
   <meta charset="utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Quession Submission</title>

   <!--Foundation Stuff-->
   <link rel="stylesheet" href="../css/foundation.css" />
   <script src="../js/vendor/jquery.js"></script>
   <script src="../js/foundation.min.js"></script>
   <script>$(document).foundation();</script>
   <script src="../js/vendor/modernizr.js"></script>

   <!--Custom Stuff-->
   <link rel="stylesheet" href="../css/custom.css" />
   <script>
      $(document).ready(function() {
         $('#login-info-submit-button').on('click', function() {
            var $inputs = $(this).find('input, a');
            $inputs.prop('disabled', true);
            $JSONdata = {'login': 'true', 'email': $('input').val()};
            $.post('LoginLogout.php', $JSONdata, function(response) {
               if (response === 'success') {
                  window.location.replace('http://student.cs.appstate.edu/haithcockce/QQuizzer/php/MasterEditor.php');
               }
               else {
                  if (!($('.error').length > 0)) {
                     $('#email-login-container').append('<small class="error">The email provided does not seem to match our records</small>');
                  }
               }
            });
            $inputs.prop('disabled', false);
            
         });
      });
   </script>
</head>

<body>
   <div class='row global-container'>
      <form>
         <fieldset>
            <h3>Question and Booth Editor Login</h3>
            <div class='small-12 medium-6 large-6 columns' id='email-login-container'>
               <label>Email
                  <input id='login-email' name='email' type='text' />
               </label>
            </div>
            <div class='small-12 medium-6 large-6 columns'>
               <a href='#' id='login-info-submit-button' class='button expand'>Submit</a>
            </div>
         </fieldset>
      </form>
   </div>
</body>
</html>
