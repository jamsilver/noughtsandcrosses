<?php

include 'src/Board.php';
include 'src/Board1D.php';
include 'src/Board2D.php';
include 'src/Board3D.php';
include 'src/Coordinate.php';
include 'src/Coordinate1D.php';
include 'src/Coordinate2D.php';
include 'src/Coordinate3D.php';
include 'src/Move.php';
include 'src/MoveBomb.php';
include 'src/MoveClaimSquare.php';
include 'src/MoveList.php';
include 'src/MoveSmartBomb.php';
include 'src/MoveTableFlip.php';
include 'src/PlayerSymbol.php';

$board = new Board2D();

$moveList = !empty($_GET['reset']) ?
    (new MoveList())->store() :
    MoveList::createFromStorage();

if (!empty($_GET['undo'])) {
    $moveList->removeLastMove()->store();
}

$moveList->applyToBoard($board);

$lastSymbol = $moveList->getLastMove()?->getPlayerSymbol();
$currentSymbol = $lastSymbol?->flip() ?? PlayerSymbol::X;
$nextSymbol = $currentSymbol;

$input = trim((string) ($_GET['move'] ?? ''));

$errorMessage = '';
if (!empty(trim($input))) {
    try {
        $move = Move::createFromNotation($input, $currentSymbol, $board);
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
        $board
        <p>$playMessage</p>
        <form method="get" action="/">
            <label for="input">{$nextSymbol->value}'s move:</label>
            <input id="input" type="text" name="move">
            <input type="submit" value="Go" />
            <br />
            <br />
            <input type="submit" value="Undo" name="undo" />
            <input type="submit" value="Reset" name="reset" />
        </form>
        <p>Moves:</p>
        <ul>
            <li>💥: Play bomb (single use, only one of 💥 and 💣 may be played)</li>
            <li>💣: Play smart bomb (single use, only one of 💥 and 💣 may be played)</li>
            <li>(╯°□°）╯︵ ┻━┻: Flip the board (single use)</li>
        </ul>
    </body>
</html>
HTML;
