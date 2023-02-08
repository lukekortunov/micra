<?php

declare(strict_types=1);

namespace App\Middleware;

use Laminas\Diactoros\Response;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\JwtFacade;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha512;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Throwable;
use Exception;

final class AuthenticationMiddleware implements MiddlewareInterface
{
    public const HEADER = 'Authorization';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $token = $this->getBearerTokenFromRequest($request);
        } catch (Throwable $e) {
            return new Response\EmptyResponse(401);
        }

        try {
            $this->validateJwtToken($token);
        } catch (Throwable $e) {
            return new Response\EmptyResponse(403);
        }

        return $handler->handle($request);
    }

    /**
     * @throws Exception
     */
    private function getBearerTokenFromRequest(ServerRequestInterface $request): string
    {
        if (!$request->hasHeader(self::HEADER)) {
            throw new Exception('No Authorization header found');
        }

        $token = $request->getHeader(self::HEADER)[0] ?? '';
        $token = str_replace('Bearer', '', $token);
        $token = trim($token);

        return $token;
    }

    /**
     * @throws RequiredConstraintsViolated|Exception
     */
    private function validateJwtToken(string $token): void
    {
        $key = InMemory::file(__DIR__ . '/../../var/rsa/public.pem');

        (new JwtFacade())->parse(
            jwt: $token,
            signedWith: new SignedWith(new Sha512(), $key),
            validAt: new LooseValidAt(new SystemClock(new \DateTimeZone('UTC'))),
        );
    }
}
