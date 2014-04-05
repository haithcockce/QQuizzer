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
   
   <link rel="stylesheet" href="../css/custom.css" />
   <style>
      .global-container {
          background-color: hsla(0, 5%, 90%, .85);
      }

   </style>
   <script type="text/javascript">
      $(document).ready(function() {
         if (localStorage.getItem('deviceID') !== null) {
            $('h3').append(localStorage.getItem('teamName') + '!');
         }
      });
   </script>

</head>

<body>
   <div class='row global-container'>
      <div class='small-12 medium-12 large-12'>
          <h3>D'OH! It looks like you've already registered as team </h3>
      </div>
      <div class='small-12 medium-12 large-12'>
         <img src='../img/homer-doh.png' alt='homer simpson saying DOH!'>
      </div>
   </div>
</body>
</html>