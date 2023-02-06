<?php

declare(strict_types=1);

namespace App\Service;

final class RockPaperScissors
{
    public function play(): string
    {
        return match ($this->decide()) {
            -1  => 'Loose',
            0   => 'Draw',
            1   => 'Won',
        };
    }

    private function decide(): int
    {
        return rand(-1, 1);
    }
}
