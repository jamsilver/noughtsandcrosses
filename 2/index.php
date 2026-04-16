<?php

// Noughts and Crosses game.
$board = [
    [' ', ' ', ' '],
    [' ', ' ', ' '],
    [' ', ' ', ' '],
];

$move = trim((string) ($_SERVER['QUERY_STRING'] ?? ''));

if (!empty($move) && strlen($move) === 2) {
    $x = match ($move[0]) {
        "A" => 0,
        "B" => 1,
        "C" => 2,
    };

    $y = (int) $move[1] - 1;

    if (isset($board[$x][$y])) {
        $board[$x][$y] = 'X';
    }
}

header('Content-Type: text/plain; charset=UTF-8');

print "1    {$board[0][0]} | {$board[1][0]} | {$board[2][0]} \n";
print "    -----------\n";
print "2    {$board[0][1]} | {$board[1][1]} | {$board[2][1]} \n";
print "    -----------\n";
print "3    {$board[0][2]} | {$board[1][2]} | {$board[2][2]} \n\n";
print "     A | B | C";
