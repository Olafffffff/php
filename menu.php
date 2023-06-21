<?php
session_start();

if (isset($_POST['difficulty'])) {
    $_SESSION['difficulty'] = $_POST['difficulty'];
    header('Location: game.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
        #grad1 {
            width: 1890px;
            height: 1000px;
            background-image: linear-gradient(white, lightblue);
        }
    </style>
    <title>Gra Wisielec</title>
</head>
<body>
<p><center>
<h1><p style="font-family:Times New Roman"> Gra Wisielec </p></h1>
<form method="post" action="">
    <label for="difficulty">Wybierz poziom trudności:</label>
   <select id="difficulty" name="difficulty">
        <option value="easy">Łatwy</option>
        <option value="hard">Trudny</option>
    </select>
     <input type="submit" value="Rozpocznij grę">
</form>
    </center></p>
<div id="grad1"></div>
</body>
</html>

