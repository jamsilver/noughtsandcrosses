<?php

class MoveList implements IteratorAggregate
{
    private array $moves = [];

    public static function createFromStorage(): MoveList
    {
        $instance = new MoveList();
        $moves = apcu_fetch('moves');

        if (is_array($moves)) {
            $instance->moves = $moves;
        }

        return $instance;
    }

    public function getLastMove(): ?Move
    {
        $lastMove = end($this->moves);
        return $lastMove === false ? null : $lastMove;
    }

    public function push(Move $move): self
    {
        $this->moves[] = $move;
        return $this;
    }

    public function store(): self
    {
        apcu_store('moves', $this->moves);
        return $this;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->moves);
    }

    public function applyToBoard(Board $board): self
    {
        foreach ($this->moves as $move) {
            $move->applyToBoard($board);
        }
        return $this;
    }
}
