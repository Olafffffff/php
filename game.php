<?php
session_start();

//słowa

$easyWords = array("kot", "pies", "dom", "drzewo", "kwiat", "dach" , "piwo" , "keczup","suknia" , "buty" , "wino" ,"krew","praca","opona","biurko" , "magia", "taczka" );
$hardWords = array("trudneslowo", "odpowiedzialnosc", "internet", "niepodleglosc" , "nosorozec" , "moralnosc","rzeczownik","przewodnik","musztarda", "hamburger" , "weterynarz","zapalniczka" );

// Wybór trudności
$difficulty = isset($_SESSION['difficulty']) ? $_SESSION['difficulty'] : 'easy';
$wordList = ($difficulty === 'easy') ? $easyWords : $hardWords;

// rozpoczecie gry
if (!isset($_SESSION['word']) || isset($_POST['reset'])) {
    $randomIndex = array_rand($wordList);
    $_SESSION['word'] = $wordList[$randomIndex];
    $_SESSION['guessed_letters'] = array();
    $_SESSION['attempts_left'] = ($difficulty === 'easy') ? 10 : 4;
    $_SESSION['hint_count'] = ($difficulty === 'easy') ? 1 : 3;
    $_SESSION['word_guessed'] = false;
}

// Sprawdzenie, czy gra trwa
$gameWon = isset($_SESSION['word']) && $_SESSION['word'] === implode('', array_intersect(str_split($_SESSION['word']), $_SESSION['guessed_letters']));

// Sprawdzenie, czy skończyły się próby
$gameLost = isset($_SESSION['attempts_left']) && $_SESSION['attempts_left'] === 1;

// domyślnych wartości sesji
if (!isset($_SESSION['wins'])) {
    $_SESSION['wins'] = 0;
}
if (!isset($_SESSION['losses'])) {
    $_SESSION['losses'] = 0;
}
if (!isset($_SESSION['game_won'])) {
    $_SESSION['game_won'] = 0;
}
if (!isset($_SESSION['game_lost'])) {
    $_SESSION['game_lost'] = 0;
}

// zwiększania liczników wygranych i przegranych rund
if ($gameWon && !$gameLost) {
    $_SESSION['game_won']++;
    $_SESSION['wins']++;
} elseif (!$gameWon && $gameLost) {
    $_SESSION['game_lost']++;
    $_SESSION['losses']++;
}

// Obsługa podpowiedzi
if (isset($_POST['hint']) && $_SESSION['hint_count'] > 0 && !$gameWon && !$gameLost && !$_SESSION['word_guessed']) {
    $hintIndices = array_diff(range(0, strlen($_SESSION['word']) - 1), array_keys($_SESSION['guessed_letters']));
    $randomHintIndex = array_rand($hintIndices);
    $randomHintLetterIndex = $hintIndices[$randomHintIndex];
    $_SESSION['guessed_letters'][] = $_SESSION['word'][$randomHintLetterIndex];
    $_SESSION['hint_count']--;
}

// Obsługa zgadywania litery
if (isset($_POST['letter'])) {
    $letter = strtolower($_POST['letter']);
    if (ctype_alpha($letter)) {
        if (!in_array($letter, $_SESSION['guessed_letters'])) {
            $_SESSION['guessed_letters'][] = $letter;
            if (!strpos($_SESSION['word'], $letter)) {
                $_SESSION['attempts_left']--;
            }
        }
    }
}

// Sprawdzenie czy wszystkie litery zostały odgadnięte
if (array_diff(str_split($_SESSION['word']), $_SESSION['guessed_letters']) === []){
    $_SESSION['word_guessed'] =true;
}

?>
<!DOCTYPE html>
<html>
<head>
    <p><center>
            <style>
                body #grad1 {
                    width: 1890px;
                    height: 1000px;
                    background-image: linear-gradient(white, lightcoral);
                }
            </style>

            <title>Gra Wisielec</title>
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

            </head>
<body>
<h1> </h1>



<?php

// Wyświetlanie słowa z odkrytymi i zakrytymi literami
echo '<p>';
for ($i = 0; $i < strlen($_SESSION['word']); $i++) {
    $letter = $_SESSION['word'][$i];
    if (in_array($letter, $_SESSION['guessed_letters'])) {
        echo '<span class="visible-letter">' . $letter . '</span>';
    } else {
        echo '<span class="hidden-letter">_</span>';
    }
}
echo '</p>';

// Wyświetlanie liczby niewykorzystanych prób
echo '<p>Liczba prób: ' . $_SESSION['attempts_left'] . '</p>';

// Wyświetlanie listy użytych liter
echo '<p>Użyte litery: ' . implode(', ', $_SESSION['guessed_letters']) . '</p>';

// Wyświetlanie licznika wygranych i przegranych rund
echo '<p>Wygrane: ' . $_SESSION['wins'] . '</p>';
echo '<p>Przegrane: ' . $_SESSION['losses'] . '</p>';

// Obliczanie wskaźnika skuteczności wygrywania
$totalGames = $_SESSION['game_won'] + $_SESSION['game_lost'];
$accuracyRate = ($totalGames > 0) ? ($_SESSION['game_won'] / $totalGames) * 100 : 0;
echo '<p>Procentowy wskaźnik skuteczności: ' . $accuracyRate . '%</p>';

// Sprawdzenie, czy gra trwa
if (!$gameWon && !$gameLost && !$_SESSION['word_guessed']) {
    echo '
        <form method="post" action="">
            <label for="letter">Wprowadź literę:</label>
            <input type="text" id="letter" name="letter" maxlength="1" pattern="[a-zA-Z]" autocomplete="off">
            <input type="submit" value="Sprawdź">
        </form>
        ';
} elseif ($_SESSION['word_guessed']) {
    echo '<p>Odgadnięte słowo: ' . $_SESSION['word'] . '</p>';
    echo '
        <form method="post" action="">
            <input type="hidden" name="reset" value="1">
            <input type="submit" value="Zagraj od nowa">
        </form>
        ';
} else {
    echo '<p>Przegrana! Wykorzystano wszystkie próby.</p>';
    echo '<p>Prawidłowe słowo: ' . $_SESSION['word'] . '</p>';
    echo '
        <form method="post" action="">
            <input type="hidden" name="reset" value="1">
            <input type="submit" value="Zagraj od nowa">
        </form>
        ';
}

//  obrazy wisielca
$attemptsLeft = $_SESSION['attempts_left'];
// Obrazy dla łatwego trybu
$hangmanImagesEasy = array(
    ' 
    
    
       
    
    
=========',

    ' 
      |
      |
      |
      |
      |
=========',

    ' +----+
      |
      |
      |
      |
      |
=========',

    ' +----+
  |   |
      |
      |
      |
      |
=========',

    ' +----+
  |   |
  O   |
      |
      |
      |
=========',

    ' +----+
  |   |
  O   |
  |   |
      |
      |
=========',

    ' +----+
  |   |
  O   |
 /|   |
      |
      |
=========',

    ' +----+
  |   |
  O   |
 /|\\  |
      |
      |
=========',

    ' +----+
  |   |
  O   |
 /|\\  |
 /   |
      |
=========',

    ' +----+
  |   |
  O   |
 /|\\  |
 / \\  |
      |
========='
);

// Obrazy dla trudnego trybu
$hangmanImagesHard = array(
    ' 
    
    
       
    
    
=========',

    ' 
      |
      |
      |
      |
      |
=========',

    ' +----+
  |   |
  O   |
 /|\\  |
      |
      |
=========',

    ' +----+
  |   |
  O   |
 /|\\  |
 / \\  |
      |
========='
);

// Wybór odpowiednich obrazów na podstawie trudności
$hangmanImages = ($difficulty === 'easy') ? $hangmanImagesEasy : $hangmanImagesHard;

// Obliczenie indeksu obrazu na podstawie liczby dostępnych prób
$hangmanImageIndex = count($hangmanImages) - $_SESSION['attempts_left'];
if ($hangmanImageIndex < 0) {
    $hangmanImageIndex = 0;
} elseif ($hangmanImageIndex >= count($hangmanImages)) {
    $hangmanImageIndex = count($hangmanImages) - 2;
}

// Wyświetlanie obrazu wisielca
echo '<pre>' . $hangmanImages[$hangmanImageIndex] . '</pre>';



// przycisk podpowiedzi
if (!$gameWon && !$gameLost && !$_SESSION['word_guessed'] && $_SESSION['hint_count'] > 0) {
    echo '
        <form method="post" action="">
            <input type="submit" name="hint" value="Podpowiedź">
        </form>
        ';
}

// przycisk powrotu do menu
echo '
    <form method="post" action="menu.php">
        <input type="submit" value="Powrót do menu">
    </form>
    ';
?>
</center></p>
<div id="grad1"></div>
</body>
</html>