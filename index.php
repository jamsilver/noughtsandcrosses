<?php

function loadMoves(): array
{
    $moves = apcu_fetch('moves');

    if ($moves === false || !is_array($moves)) {
        $moves = [];
    }

    return $moves;
}

function applyMovesToBoard(array $board, array $moves): array
{
    foreach ($moves as [$x, $y, $symbol]) {
        $board[$x][$y] = $symbol;
    }

    return $board;
}

function getLastMoveSymbol(array $moves): ?string
{
    $lastMove = end($moves);
    return $lastMove === false ? null : $lastMove[2];
}

function flipSymbol(string $symbol): string
{
    return match($symbol) {
        'X' => 'O',
        default => 'X',
    };
}

function loadXYFromUserInput(string $queryString): array
{
    $move = trim($queryString);

    if (empty($move) || strlen($move) !== 2) {
        return [null, null];
    }

    $x = match ($move[0]) {
        "A" => 0,
        "B" => 1,
        "C" => 2,
    };

    $y = (int) $move[1] - 1;

    return [$x, $y];
}

function validateXYForBoard(array $board, int $x, int $y): bool
{
    if (!isset($board[$x][$y])) {
        print "ERROR: Invalid input. Use coordinates.\n\n";
        return false;
    }

    if ($board[$x][$y] !== ' ') {
        print "ERROR: Square is already filled, pick a blank square.\n\n";
        return false;
    }

    return true;
}

function printBoard(array $board): void
{
    print "1    {$board[0][0]} | {$board[1][0]} | {$board[2][0]} \n";
    print "    -----------\n";
    print "2    {$board[0][1]} | {$board[1][1]} | {$board[2][1]} \n";
    print "    -----------\n";
    print "3    {$board[0][2]} | {$board[1][2]} | {$board[2][2]} \n\n";
    print "     A | B | C";
    print "\n\n";
}

function boardHasThreeInARow(array $board): bool
{
    return ($board[0][0] !== ' ' && $board[0][0] === $board[0][1] && $board[0][0] === $board[0][2]) ||
    ($board[1][0] !== ' ' && $board[1][0] === $board[1][1] && $board[1][0] === $board[1][2]) ||
    ($board[2][0] !== ' ' && $board[2][0] === $board[2][1] && $board[2][0] === $board[2][2]) ||
    ($board[0][0] !== ' ' && $board[0][0] === $board[1][0] && $board[0][0] === $board[2][0]) ||
    ($board[0][1] !== ' ' && $board[0][1] === $board[1][1] && $board[0][1] === $board[2][1]) ||
    ($board[0][2] !== ' ' && $board[0][2] === $board[1][2] && $board[0][2] === $board[2][2]) ||
    ($board[0][0] !== ' ' && $board[0][0] === $board[1][1] && $board[0][0] === $board[2][2]) ||
    ($board[2][0] !== ' ' && $board[2][0] === $board[1][1] && $board[2][0] === $board[0][2]);
}

// Noughts and Crosses game.
$board = [
    [' ', ' ', ' '],
    [' ', ' ', ' '],
    [' ', ' ', ' '],
];

$moves = loadMoves();
$board = applyMovesToBoard($board, $moves);

$lastSymbol = getLastMoveSymbol($moves);
$currentSymbol = flipSymbol($lastSymbol ?? 'O');
$nextSymbol = $currentSymbol;

[$x, $y] = loadXYFromUserInput((string) $_SERVER['QUERY_STRING']);

if (isset($x) && isset($y) && validateXYForBoard($board, $x, $y)) {
    $moves[] = [$x, $y, $currentSymbol];
    apcu_store('moves', $moves);
    $board[$x][$y] = $currentSymbol;
    $nextSymbol = flipSymbol($currentSymbol);
}


header('Content-Type: text/plain; charset=UTF-8');

printBoard($board);

if (boardHasThreeInARow($board)) {
    print "$currentSymbol IS THE WINNER! GeeGees!";
}
else {
    print "> $nextSymbol to play next.";
}
