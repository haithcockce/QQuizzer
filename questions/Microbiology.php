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
   <script src='../js/questionHandler.js'></script>
   <style type="text/css" media="screen">
      input {
         width: 2em;
         height: 2em;
      }
   </style>
   <script>
      var BOOTH = 'Microbiology';
      $(document).ready(function() {
         if (localStorage.getItem('deviceID') === null)
            window.location = '../php/teamLogger.php';
         else if (localStorage.getItem(BOOTH) === null) 
            localStorage.setItem(BOOTH, 'start');
         getQuestion(BOOTH, localStorage.getItem(BOOTH));
      });
   </script>
</head>

<body>
   <div class='row global-container'>
      <form>
         <fieldset>
            <div class='row question-clause-container'>
               <div class='small-12 medium-12 large-12 column'>
                  <h2></h2>
               </div>
            </div>
            <div class='row'>
               <div class='small-12 medium-12 large-12 column answer-choices-container'>
               </div>
            </div>
            <div class='row'>
               <div class='small-12 medium-12 large-12 column submit-button-container'>
                  <a href='#' class='button expand' onclick='checkAnswer("Microbiology")'>Submit</a>
               </div>
            </div>
         </fieldset>
      </form>
   </div>
   <p class='watermark'>Produced by Charles Haithcock and powered by Appalachian State University's CS Department.</p>
</body>
</html>
