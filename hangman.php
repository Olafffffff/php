<?php
$gameWon = false;
$gameLost = false;
$easyWords = array("kot", "pies", "dom", "drzewo", "kwiat", "dach" , "piwo" , "keczup","suknia" , "buty" , "wino" ,"krew","praca","opona","biurko" , "magia", "taczka" );
$hardWords = array("trudneslowo", "odpowiedzialnosc", "internet", "niepodleglosc" , "nosorozec" , "moralnosc","rzeczownik","przewodnik","musztarda", "hamburger" , "weterynarz","zapalniczka" );


// Wybór trudności
$difficulty = isset($_SESSION['difficulty']) ? $_SESSION['difficulty'] : 'easy';
$wordList = ($difficulty === 'easy') ? $easyWords : $hardWords;

// rozpoczecie gry
if (!isset($_SESSION['word']) || (isset($_POST['start']) && $_POST['start'] == '1')) {
    $randomIndex = array_rand($wordList);
    $_SESSION['word'] = $wordList[$randomIndex];
    $_SESSION['guessed_letters'] = array();
    $_SESSION['attempts_left'] = ($difficulty === 'easy') ? 10 : 4;
    $_SESSION['hint_count'] = ($difficulty === 'easy') ? 1 : 3;
    $_SESSION['word_guessed'] = false;
}


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
?>
