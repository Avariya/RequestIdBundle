<?php

namespace Avariya\RequestIdBundle\Middleware;

use Avariya\RequestIdBundle\Exception\RequestIdNotFoundException;
use Psr\Http\Message\RequestInterface;
use Qandidate\Stack\RequestIdGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class RequestIdMiddleware
{
    const REQUEST_ID_HEADER = 'X-Request-Id';

    /**
     * @var RequestIdGenerator
     */
    private $requestIdGenerator;

    /**
     * @var Request
     */
    private $request;

    /**
     * RequestIdMiddleware constructor.
     * @param RequestIdGenerator $requestIdGenerator
     * @param RequestStack $requestStack
     */
    public function __construct(
        RequestIdGenerator $requestIdGenerator,
        RequestStack $requestStack
    ) {
        $this->requestIdGenerator = $requestIdGenerator;
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @param callable $handler
     * @return \Closure
     */
    public function __invoke(callable $handler): \Closure
    {
        try {
            $requestId = $this->getRequestId();
        } catch (RequestIdNotFoundException $exception) {
            $requestId = $this->requestIdGenerator->generate();
        }

        return function ($request, array $options) use ($handler, $requestId) {
            $request = $this->beforeCall($request, $requestId);

            return $handler($request, $options);
        };
    }

    /**
     * @return string
     * @throws RequestIdNotFoundException
     */
    private function getRequestId(): string
    {
        if (!$this->request->headers->has(self::REQUEST_ID_HEADER)) {
            throw new RequestIdNotFoundException();
        }

        return $this->request->headers->get(self::REQUEST_ID_HEADER);
    }

    /**
     * @param RequestInterface $request
     * @param string $requestId
     * @return RequestInterface
     */
    private function beforeCall(RequestInterface $request, string $requestId): RequestInterface
    {
        if ($request->hasHeader(self::REQUEST_ID_HEADER)) {
            return $request;
        }

        return $request->withHeader(
            self::REQUEST_ID_HEADER,
            $requestId
        );
    }
}
