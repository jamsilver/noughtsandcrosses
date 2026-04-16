<?php

final class Board3D extends Board
{
    public function newCoordinate(...$points): Coordinate
    {
        return new Coordinate3D(...$points);
    }

    public function newCoordinateFromNotation(string $value): Coordinate
    {
        return Coordinate3D::createFromNotation($value);
    }

    public function forEachCell(callable $callback): self
    {
        for ($y = 0; $y < static::SIZE; $y++) {
            for ($x = 0; $x < static::SIZE; $x++) {
                for ($z = 0; $z < static::SIZE; $z++) {
                    $coordinate = $this->newCoordinate($x, $y, $z);
                    $callback($coordinate, $this->getCell($coordinate));
                }
            }
        }
        return $this;
    }

    public function forEachCellAround(Coordinate $epicentre, int $radius, bool $includeEpiCentre, callable $callback): self
    {
        for ($x = max(0, $epicentre->getX() - $radius); $x <= min(static::SIZE - 1, $epicentre->getX() + $radius); $x++) {
            for ($y = max(0, $epicentre->getY() - $radius); $y <= min(static::SIZE - 1, $epicentre->getY() + $radius); $y++) {
                for ($z = max(0, $epicentre->getZ() - $radius); $z <= min(static::SIZE - 1, $epicentre->getZ() + $radius); $z++) {
                    $coordinate = $this->newCoordinate($x, $y, $z);
                    if (!$includeEpiCentre && $coordinate == $epicentre) {
                        continue;
                    }
                    $callback($coordinate, $this->getCell($coordinate));
                }
            }
        }
        return $this;
    }

    public function hasWinner(): bool
    {
        // @TODO.
        return false;
    }

    public function __toString(): string
    {
        $cellSize = 30;
        $gridSize = static::SIZE * $cellSize * 1.5;
        $perspective = static::SIZE * 150;

        $output = [];
        $output[] = <<<HTML
        <style>
            .grid {
                position: relative;
                height: {$gridSize}px;
                width: {$gridSize}px;
            }
            @property --angle {
              syntax: '<angle>';
              initial-value: 0deg;
              inherits: false;
            }
            @keyframes rotateAngle {
              0% {--angle: 280deg}
              50% {--angle: 420deg}
              100% {--angle: 280deg}
            }
            .threed-cell {
              width: 30px;
              height: 30px;
              text-align: center;
              line-height: 30px;
              border: 1px solid #ccc;
              position: absolute;
              left: 150px;
              top: 0px;
              animation: 10s linear rotateAngle infinite;
              transform: perspective({$perspective}px) rotate3d(0.5, 3, 0.5, var(--angle)) translate3d(calc(var(--x) * {$cellSize}px), calc(var(--y) * {$cellSize}px), calc(var(--z) * {$cellSize}px));
            }
            @keyframes rotateAnimation {
                from {transform: rotateY(45deg);}
                to {transform: rotateY(225deg);}
            }
            sub {
                font-size: 6px;
                color: red;
                position: absolute;
                left: 0;
                bottom: 0;
                line-height: 1.2em;
            }
        </style>
        <div class="grid">
        HTML;
        $this->forEachCell(function(Coordinate3D $coordinate, ?PlayerSymbol $cell) use (&$output) {
            $output[] = <<<HTML
            <span class="threed-cell"
                style="--x: {$coordinate->getX()}; --y: {$coordinate->getY()}; --z: {$coordinate->getZ()}"
            >
                {$cell?->value}
                <sub>{$coordinate->toNotation()}</sub>
            </span>
            HTML;
        });
        $output[] = '</div>';

        return implode("\n", $output);
    }
}
