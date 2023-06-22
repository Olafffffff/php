<?php
session_start();
if (isset($_POST['difficulty'])) 
        {
    		$_SESSION['difficulty'] = $_POST['difficulty'];
    	}
if (isset($_POST['reset'])) 
        {
    		$_SESSION['difficulty'] = false;
    		$_SESSION['word'] = false;
    	}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
                .hidden-letter {
                    display: inline-block;
                    border-bottom: 1px solid #000;
                    margin: 0 5px;
                    padding: 0 5px;
                }

                .visible-letter {
                    display: inline-block;
                    margin: 0 5px;
                    padding: 0 5px;
                }
    </style>
    <title>Gra Wisielec</title>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md max-w-md w-full relative">
        <h1 class="text-3xl font-bold mb-6 text-center">Gra Wisielec</h1>
        <?php
        if ($_SESSION['difficulty']) {    
            require_once 'hangman.php';
            include 'game.php';
        } else {
            include 'menu.php';
        }
        ?>
    </div>
    <script>
        function submitForm() {
            document.getElementById("close").submit();
        }
    </script>
</body>


</html>

