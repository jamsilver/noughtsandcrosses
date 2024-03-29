<?php

include 'src/Board.php';
include 'src/Coordinate.php';
include 'src/Move.php';
include 'src/MoveBomb.php';
include 'src/MoveClaimSquare.php';
include 'src/MoveList.php';
include 'src/PlayerSymbol.php';

$board = new Board();

$moveList = !empty($_GET['reset']) ?
    (new MoveList())->store() :
    MoveList::createFromStorage();

$moveList->applyToBoard($board);

$lastSymbol = $moveList->getLastMove()?->getPlayerSymbol();
$currentSymbol = $lastSymbol?->flip() ?? PlayerSymbol::X;
$nextSymbol = $currentSymbol;

$input = trim((string) ($_GET['move'] ?? ''));

$errorMessage = '';
if (!empty(trim($input))) {
    try {
        $move = Move::createFromNotation($input, $currentSymbol);
        $move->validateForBoard($board);
        $move->applyToBoard($board);
        $nextSymbol = $currentSymbol->flip();
        $moveList->push($move)->store();
    }
    catch (UnexpectedValueException $e) {
        $errorMessage = 'ERROR: ' . $e->getMessage();
    }
}

header('Content-Type: text/html; charset=UTF-8');

$playMessage = $board->hasWinner() ?
    $currentSymbol->value . ' IS THE WINNER! GeeGees!' :
    '> ' . $nextSymbol->value . ' to play next.';

print <<<HTML
<html>
    <head>
        <title>Noughts &amp; Crosses</title>
    </head>
    <body>
        <h1>Noughts &amp; Crosses</h1>
        <p>$errorMessage</p>
        <pre><code>$board</code></pre>
        <p>$playMessage</p>
        <form method="get" action="/">
            <label for="input">{$nextSymbol->value}'s move:</label>
            <input id="input" type="text" name="move">
            <input type="submit" value="Go" />
            <input type="submit" value="Reset" name="reset" />
        </form>
        <p>Moves:</p>
        <ul>
            <li>💥: Play bomb (single use)</li>
        </ul>
    </body>
</html>
HTML;
