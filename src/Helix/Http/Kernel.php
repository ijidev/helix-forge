<?php

namespace Helix\Http;

use Helix\Container\Container;
use Helix\Http\Middleware\MiddlewareInterface;
use Helix\Routing\Router;

class Kernel
{
    private array $middleware = [];

    public function __construct(
        private readonly Container $container,
        private readonly Router $router
    ) {}

    public function pushMiddleware(MiddlewareInterface|string $middleware): void
    {
        $this->middleware[] = $middleware;
    }

    public function handle(Request $request): Response
    {
        try {
            $core = function (Request $request) {
                return $this->router->dispatch($request);
            };

            $pipeline = $this->buildPipeline($core, $this->middleware);

            return $pipeline($request);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    private function buildPipeline(callable $core, array $middleware): callable
    {
        $pipeline = $core;

        foreach (array_reverse($middleware) as $mw) {
            $pipeline = function (Request $request) use ($mw, $pipeline) {
                if (is_string($mw)) {
                    $mw = $this->container->get($mw);
                }
                return $mw->handle($request, $pipeline);
            };
        }

        return $pipeline;
    }

    private function handleException(\Throwable $e): Response
    {
        $status = 500;
        $message = 'Internal Server Error';

        if ($e instanceof \Helix\Routing\RouteNotFoundException) {
            $status = 404;
            $message = 'Not Found';
        }

        if ($e instanceof \Helix\Validation\ValidationException) {
            $status = 422;
            $message = 'Validation Failed';
        }

        return new JsonResponse([
            'error' => true,
            'message' => $message,
            'detail' => $e->getMessage(),
        ], $status);
    }
}
