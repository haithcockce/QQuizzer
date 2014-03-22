<!doctype html>
<html class="no-js" lang="en">
<head>
   <meta charset="utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Logging</title>
   <link rel="stylesheet" href="css/foundation.css" />
   <link rel="stylesheet" href="css/custom.css" />
   <script src="js/vendor/modernizr.js"></script>
</head>
<body>

<body>
   <div class='row' id='global-container'>
      <div class='small-12 column'>
         <?
            //Connect to MySQL
            $databaseLink = mysql_connect("student.cs.appstate.edu", "haithcockce", "900431409") or die("Connection Failed");

            //Connect to QQuizzer database
            $qdb = mysql_select_db("QQuizzer", $databaseLink) or die("Failed to connect to QQuizzer Database");

            //YOU NEED TO HANDLE VALUES WITH APOSTROPHES AND QUOTATIONS!!!!!!!!!!!
            $result = mysql_query("INSERT INTO Teams (Name, Members, BoothsVisited) VALUES ('".$_POST["teamName"]."', '".$_POST["myName"]."', '0')");
            if (!$result) {
               die('Invalid query: '.mysql_error());
            }
            echo 'Inserted values';
         ?>
      </div>
   </div>   
</body>
</HTML>
