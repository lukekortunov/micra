<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\RockPaperScissors;
use Psr\Http\Message\ServerRequestInterface;

final class RockPaperScissorsController
{
    public function __construct(private readonly RockPaperScissors $rockPaperScissors)
    {
    }

    public function __invoke(ServerRequestInterface $request): array
    {
        return [
            'result' => $this->rockPaperScissors->play()
        ];
    }
}
