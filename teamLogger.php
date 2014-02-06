<!DOCTYPE html>
<HTML>
<head>
   <title>Success Page</title>
</head>

<body>
   <?
      /*setcookie('team', $_POST['teamName']*/
      echo '<p>Congradulations, '.$_POST['myName'].', '.
      'you have registered with the team, '.$_POST['teamName'].'</p>';
   ?>
</body>
</HTML>
