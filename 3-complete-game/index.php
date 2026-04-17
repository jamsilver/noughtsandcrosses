<?php

// Noughts and Crosses game.
$board = [
    [' ', ' ', ' '],
    [' ', ' ', ' '],
    [' ', ' ', ' '],
];

// Hydrate the board from storage.
$moves = apcu_fetch('moves');

if ($moves === false || !is_array($moves)) {
    $moves = [];
}

foreach ($moves as [$x, $y, $symbol]) {
    $board[$x][$y] = $symbol;
}

$lastMove = end($moves);
$lastSymbol = $lastMove === false ? null : $lastMove[2];
$currentSymbol = match($lastSymbol) {
    'X', 'x' => 'O',
    default => 'X',
};
$nextSymbol = $currentSymbol;

// Process User Input.
$move = trim((string) ($_SERVER['QUERY_STRING'] ?? ''));

if (!empty($move) && strlen($move) === 2) {
    $x = match ($move[0]) {
        "A" => 0,
        "B" => 1,
        "C" => 2,
        default => -1,
    };

    $y = (int) $move[1] - 1;

    if (!isset($board[$x][$y])) {
        print "ERROR: Invalid input. Use coordinates.\n\n";
    }

    elseif ($board[$x][$y] !== ' ') {
        print "ERROR: Square $move is already filled, pick a blank square.\n\n";
    }

    else {
        $moves[] = [$x, $y, $currentSymbol];
        apcu_store('moves', $moves);
        $board[$x][$y] = $currentSymbol;
        $nextSymbol = $lastSymbol ?? 'O';
    }
}


// Render current board state.
header('Content-Type: text/plain; charset=UTF-8');

print "1    {$board[0][0]} | {$board[1][0]} | {$board[2][0]} \n";
print "    -----------\n";
print "2    {$board[0][1]} | {$board[1][1]} | {$board[2][1]} \n";
print "    -----------\n";
print "3    {$board[0][2]} | {$board[1][2]} | {$board[2][2]} \n\n";
print "     A | B | C";
print "\n\n";

$threeInARow = ($board[0][0] !== ' ' && $board[0][0] === $board[0][1] && $board[0][0] === $board[0][2]) ||
    ($board[1][0] !== ' ' && $board[1][0] === $board[1][1] && $board[1][0] === $board[1][2]) ||
    ($board[2][0] !== ' ' && $board[2][0] === $board[2][1] && $board[2][0] === $board[2][2]) ||
    ($board[0][0] !== ' ' && $board[0][0] === $board[1][0] && $board[0][0] === $board[2][0]) ||
    ($board[0][1] !== ' ' && $board[0][1] === $board[1][1] && $board[0][1] === $board[2][1]) ||
    ($board[0][2] !== ' ' && $board[0][2] === $board[1][2] && $board[0][2] === $board[2][2]) ||
    ($board[0][0] !== ' ' && $board[0][0] === $board[1][1] && $board[0][0] === $board[2][2]) ||
    ($board[2][0] !== ' ' && $board[2][0] === $board[1][1] && $board[2][0] === $board[0][2]);

if ($threeInARow) {
    print "$currentSymbol IS THE WINNER! GeeGees!";
}
else {
    print "> $nextSymbol to play next.";
}
