<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface;

final class IndexController
{
    public function __invoke(ServerRequestInterface $request): array
    {
        return [
            'index' => true
        ];
    }
}
