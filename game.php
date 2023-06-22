<?php

// Sprawdzenie czy wszystkie litery zostały odgadnięte
if (array_diff(str_split($_SESSION['word']), $_SESSION['guessed_letters']) === []){
    $_SESSION['word_guessed'] =true;
}

// Sprawdzenie, czy gra trwa
$gameWon = isset($_SESSION['word']) && $_SESSION['word'] === implode('', array_intersect(str_split($_SESSION['word']), $_SESSION['guessed_letters']));

// Sprawdzenie, czy skończyły się próby
$gameLost = isset($_SESSION['attempts_left']) && $_SESSION['attempts_left'] === 0;

// Przycisk zamkniecia gry
echo '<form id="close" action="index.php" method="post">
            <input type="hidden" name="reset" value="1">
		<button class="absolute top-2 left-2 bg-red-500 hover:bg-red-600 rounded-full w-10 h-10 flex items-center justify-center focus:outline-none transition duration-300 ease-in-out transform hover:scale-110">
    		<svg class="w-6 h-6 fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
        	<path d="M5.293 4.293a1 1 0 011.414 0L10 8.586l3.293-3.293a1 1 0 111.414 1.414L11.414 10l3.293 3.293a1 1 0 01-1.414 1.414L10 11.414l-3.293 3.293a1 1 0 01-1.414-1.414L8.586 10 5.293 6.707a1 1 0 010-1.414z" />
    		</svg>
		</button>
		</form>
		<p>';


// Sprawdzenie, czy gra trwa i wyswietlanie klawiatury
if (!$gameWon && !$gameLost && !$_SESSION['word_guessed']) {

for ($i = 0; $i < strlen($_SESSION['word']); $i++) {
    $letter = $_SESSION['word'][$i];
    if (in_array($letter, $_SESSION['guessed_letters'])) {
        echo '<span class="visible-letter">' . $letter . '</span>';
    } else {
        echo '<span class="hidden-letter">_</span>';
    }
}
echo '</p><HR class="my-4">';

// Wyświetlanie listy użytych liter
$allLetters = range('a', 'z');

echo '<form method="post" action="index.php">';
echo '<div class="flex flex-wrap gap-2">';
foreach ($allLetters as $letter) {
    $keyClass = (in_array($letter, $_SESSION['guessed_letters'])) ? 'bg-gray-200' : 'bg-green-500 hover:bg-green-600 cursor-pointer';
    echo '<div class="flex items-center justify-center w-12 h-12 rounded-md ' . $keyClass . '">';
    echo '<button type="submit" id="letter" name="letter" value="' . $letter . '" class="text-gray-800">' . $letter . '</button>';
    echo '</div>';
}
echo '</div>';
echo '</form>';

// przycisk podpowiedzi
if (!$gameWon && !$gameLost && !$_SESSION['word_guessed'] && $_SESSION['hint_count'] > 0) {
    echo '<form method="post" action="index.php">
    <div class="flex items-center justify-center w-12 h-12 rounded-md bg-yellow-500 hover:bg-green-600 cursor-pointer">
            <button type="submit" id="hint" name="hint" value="hint" class="text-gray-800">?</button>
        </div></form><HR class="my-4">
        ';
}
}


// Sprawdzenie, czy gra trwa
if (!$gameWon && !$gameLost && !$_SESSION['word_guessed']) {   
} elseif ($_SESSION['word_guessed']) {
    $_SESSION['game_won']++;
    $_SESSION['wins']++;
    echo '<div class="flex justify-center">Odgadnięte słowo: ' . $_SESSION['word'] . '</div><HR class="my-4">';
    echo '<div class="flex justify-center">
        <form method="post" action="index.php">
            <input type="hidden" name="reset" value="1">
            <input type="submit" value="Zagraj od nowa" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
        </form></div><HR class="my-4">
        ';
} else {
    $_SESSION['game_lost']++;
    $_SESSION['losses']++;
    echo '<div class="flex justify-center"><p>Przegrana! Wykorzystano wszystkie próby.</div><HR class="my-4">';
    echo '<div class="flex justify-center">Prawidłowe słowo: ' . $_SESSION['word'] . '</div><HR class="my-4">';
    echo '<div class="flex justify-center">
        <form method="post" action="index.php">
            <input type="hidden" name="reset" value="1">
            <input type="submit" value="Zagraj od nowa" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
        </form></div><HR class="my-4">
        ';
}

//  obrazy wisielca
$attemptsLeft = $_SESSION['attempts_left'];
// Obrazy dla łatwego trybu

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
echo '<div class="flex justify-center"><pre>' . $hangmanImages[$hangmanImageIndex] . '</pre></div><HR class="my-4">';


// Wyświetlanie liczby niewykorzystanych prób
if ($_SESSION['difficulty'] === 'easy')
	{
	$progress = ($_SESSION['attempts_left']/10)*100;
	} else {
	$progress = ($_SESSION['attempts_left']/4)*100;
	}
echo '<p>Liczba prób: ' . $_SESSION['attempts_left'] . '</p>
	<div class="h-4 bg-gray-200 rounded-full">
  	<div class="h-full bg-green-500 rounded-full" style="width: ' . $progress . '%;">
  	</div>
	</div><HR class="my-4">';


// Wyświetlanie licznika wygranych i przegranych rund
echo '<div class="flex justify-center">
	<div class="flex items-center">
  	<span class="mr-2">
    	<span class="px-2 py-1 bg-green-500 text-white rounded-full">Win: ' . $_SESSION['wins'] . '</span>
  	</span>
  	<span>
    	<span class="px-2 py-1 bg-red-500 text-white rounded-full">Loss: ' . $_SESSION['losses'] . '</span>
  	</span>
	</div></div><HR class="my-4">';

// Obliczanie wskaźnika skuteczności wygrywania
$totalGames = $_SESSION['game_won'] + $_SESSION['game_lost'];
$accuracyRate = ($totalGames > 0) ? round(($_SESSION['game_won'] / $totalGames) * 100) : 0;
echo '<p>Wskaźnik skuteczności: ' . $accuracyRate . '%</p>
<div class="h-4 bg-gray-200 rounded-full">
  	<div class="h-full bg-green-500 rounded-full" style="width: ' . $accuracyRate . '%;">
  	</div>
	</div><HR class="my-4">';

?>
