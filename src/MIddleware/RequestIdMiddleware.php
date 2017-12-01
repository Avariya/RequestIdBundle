<?php

namespace Avariya\RequestIdBundle\Middleware;

use Psr\Http\Message\RequestInterface;
use Qandidate\Stack\RequestIdGenerator;

final class RequestIdMiddleware
{
    const REQUEST_ID_HEADER = 'X-Request-Id';

    /**
     * @var RequestIdGenerator
     */
    private $requestIdGenerator;

    /**
     * RequestIdMiddleware constructor.
     * @param RequestIdGenerator $requestIdGenerator
     */
    public function __construct(RequestIdGenerator $requestIdGenerator)
    {
        $this->requestIdGenerator = $requestIdGenerator;
    }

    /**
     * @param callable $handler
     * @return \Closure
     */
    public function __invoke(callable $handler): \Closure
    {
        //@todo: check was request id passed to symfony kernel
        return function ($request, array $options) use ($handler) {

            $request = $this->beforeCall($request);

            return $handler($request, $options);
        };
    }

    /**
     * @param RequestInterface $request
     * @return RequestInterface
     */
    public function beforeCall(RequestInterface $request): RequestInterface
    {
        return $request->withHeader(
            self::REQUEST_ID_HEADER,
            $this->requestIdGenerator->generate()
        );
    }
}
