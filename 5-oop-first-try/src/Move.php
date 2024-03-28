<?php

class Move
{
    public function __construct(
        private readonly Coordinate $coordinate,
        private readonly Symbol $symbol,
    ) {}

    public function getCoordinate(): Coordinate
    {
        return $this->coordinate;
    }

    public function getSymbol(): Symbol
    {
        return $this->symbol;
    }
}
