
<!doctype html>
<html class="no-js" lang="en">
<head>
   <meta charset="utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Team Logger</title>

   <!--Foundation Stuff-->
   <link rel="stylesheet" href="../css/foundation.css" />
   <script src="../js/vendor/jquery.js"></script>
   <script src="../js/foundation.min.js"></script>
   <script>$(document).foundation();</script>
   <script src="../js/vendor/modernizr.js"></script>

   <!--Custom Stuff-->
   <link rel="stylesheet" href="../css/custom.css" />
   <script type="text/javascript">
       $(document).ready(function() {
           if (localStorage.getItem('deviceID') !== null) {
               window.location = 'doh.php';
               return;
           }
           $('.success-container').hide();
       });
       function register() {
           if (localStorage.getItem('deviceID') !== null) {
               window.location = 'doh.php';
               return;
           }
           var teamName = $('.team-info input').eq(0).val();
           if (teamName == "") {
               if ($('[class="error 1"]').length == 0) {
                   $('.team-info label').eq(0)
                     .append('<small class="error 1">Required</small>');
               }
           }
           //remove the alert if a booth is selected
           else {
               if ($('[class="error 1"]').length > 0) 
                   $('[class="error 1"]').remove();
           }

           var schoolName = $('.team-info input').eq(1).val();
           if (schoolName == "") {
               if ($('[class="error 2"]').length == 0) {
                   $('.team-info label').eq(1)
                     .append('<small class="error 2">Required</small>');
               }
           }
           //remove the alert if a booth is selected
           else {
               if ($('[class="error 2"]').length > 0) 
                   $('[class="error 2"]').remove();
           }

           var teacherName = $('.team-info input').eq(2).val();
           if (teacherName == "") {
               if ($('[class="error 3"]').length == 0) {
                   $('.team-info label').eq(2)
                     .append('<small class="error 3">Required</small>');
               }
           }
           //remove the alert if a booth is selected
           else {
               if ($('[class="error 3"]').length > 0) 
                   $('[class="error 3"]').remove();
           }
           
           if (!(teamName == "" || schoolName == "" || teacherName == "")) {
               var deviceID = Math.random().toFixed(12);
               var formData = {
                   'requesting': 'team registration',
                   'teamName': teamName,
                   'schoolName': schoolName,
                   'teacherName': teacherName,
                   'deviceID': deviceID
               };

               localStorage.setItem('deviceID', deviceID);

               $.post('teamRegister.php', formData, function(response) {
                   if (response === 'success') {
                       $('input').prop('disabled', true);
                       $('a').attr('disabled', 'disabled').removeAttr('onclick').unbind('click');
                       $('.success-container').slideDown('fast');
                       localStorage.setItem('teamName', teamName);
                   }
                   else 
                       alert('Please try again!');
               });
           }
       }
   </script> 
</head>

<body>
   <script type='javascript'>
      if (localStorage.getItem('deviceID') !== null) {
               window.location = 'doh.php';
               return;
           }
   </script>
   <div class='row global-container'>
      <form>
         <fieldset>
            <div class='row title'>
               <div class='small-12 medium-12 large-12 column'>
                  <h3>Welcome to QQuizzer!</h3>
               </div>
            </div>
            <div class='row team-info'>
               <div class='small-12 medium-4 large-4 column'>
                  <label>Provide the team name
                     <input type='text' placeholder="Charlie's Angels" />
                  </label>
               </div>
               <div class='small-12 medium-4 large-4 column'>
                  <label>Provide your school
                     <input type='text' placeholder='Orange High School' />
                  </label>
               </div>
               <div class='small-12 medium-4 large-4 column'>
                  <label>Provide your teacher's name
                     <input type='text' placeholder='Mr. Awesomeface' />
                  </label>
               </div>
            </div>
            <hr />
            <div class='row submit-and-warning'>
               <div class='small-12 medium-6 large-4 column'>
                  <p>
                     Make sure everything is correct when you submit it!
                     If not, you can't be awarded if you win the contest!
                  </p>
               </div>
               <div class='small-12 medium-6 large-6 column'>
                  <a href='#' class='button expand' onclick='register()'>Register! :D</a>
               </div>
            </div>
            <div class='row success-container'>
               <div class='small-12 medium-12 large-12 column'>
                  <h3>
                     You successfully registered!
                  </h3>
               </div>
            </div>
         </fieldset>
      </form>
   </div>
   <p class='watermark'>Produced by Charles Haithcock and powered by Appalachian State University's CS Department.</p>
</body>
</html>