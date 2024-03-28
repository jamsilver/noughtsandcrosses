<?php

// Noughts and Crosses game.
$board = [
    [' ', ' ', ' '],
    [' ', ' ', ' '],
    [' ', ' ', ' '],
];

header('Content-Type: text/plain; charset=UTF-8');

print " {$board[0][0]} | {$board[1][0]} | {$board[2][0]} \n";
print "-----------\n";
print " {$board[0][1]} | {$board[1][1]} | {$board[2][1]} \n";
print "-----------\n";
print " {$board[0][2]} | {$board[1][2]} | {$board[2][2]} \n";
