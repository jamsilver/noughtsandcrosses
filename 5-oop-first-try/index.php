<?php

include 'src/Board.php';
include 'src/Coordinate.php';
include 'src/Move.php';
include 'src/MoveList.php';
include 'src/PlayerSymbol.php';

$board = new Board();

$moveList = MoveList::createFromStorage();
$board->applyMoveList($moveList);

$lastSymbol = $moveList->getLastMove()?->getSymbol();
$currentSymbol = $lastSymbol?->flip() ?? PlayerSymbol::X;
$nextSymbol = $currentSymbol;

$input = trim((string) ($_SERVER['QUERY_STRING'] ?? ''));

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
        print 'ERROR: ' . $e->getMessage() . "\n\n";
    }
}

header('Content-Type: text/plain; charset=UTF-8');

print $board->toString();

print "\n";

if ($board->hasThreeInARow()) {
    print $currentSymbol->value . ' IS THE WINNER! GeeGees!';
}
else {
    print '> ' . $nextSymbol->value . ' to play next.';
}
