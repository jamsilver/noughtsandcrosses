<?php

include 'src/Board.php';
include 'src/Coordinate.php';
include 'src/Move.php';
include 'src/MoveList.php';
include 'src/Symbol.php';

$board = new Board();

$moveList = MoveList::createFromStorage();
$board->applyMoveList($moveList);

$lastSymbol = $moveList->getLastMove()?->getSymbol();
$currentSymbol = $lastSymbol?->flip() ?? Symbol::X;
$nextSymbol = $currentSymbol;

$input = trim((string) ($_GET['move'] ?? ''));

$errorMessage = '';
if (!empty($input)) {
    try {
        $userCoordinate = Coordinate::createFromNotation($input);
        $board->validateCoordinateIsAvailable($userCoordinate);

        $move = new Move($userCoordinate, $currentSymbol);
        $moveList->push($move)->store();

        $board->applyMove($move);

        $nextSymbol = $currentSymbol->flip();
    }
    catch (UnexpectedValueException $e) {
        $errorMessage = 'ERROR: ' . $e->getMessage();
    }
}

header('Content-Type: text/html; charset=UTF-8');

$playMessage = $board->hasThreeInARow() ?
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
        </form>
    </body>
</html>
HTML;
