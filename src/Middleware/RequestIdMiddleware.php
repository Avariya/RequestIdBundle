<?php

namespace Avariya\RequestIdBundle\Middleware;

use Avariya\RequestIdBundle\Exception\RequestIdNotFoundException;
use Psr\Http\Message\RequestInterface;
use Qandidate\Stack\RequestIdGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class RequestIdMiddleware
{
    /**
     * @var RequestIdGenerator
     */
    private $requestIdGenerator;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    private $header;

    /**
     * RequestIdMiddleware constructor.
     * @param RequestIdGenerator $requestIdGenerator
     * @param RequestStack $requestStack
     * @param string $header
     */
    public function __construct(
        RequestIdGenerator $requestIdGenerator,
        RequestStack $requestStack,
        string $header
    ) {
        $this->requestIdGenerator = $requestIdGenerator;
        $this->request = $requestStack->getCurrentRequest();
        $this->header = $header;
    }

    /**
     * @param callable $handler
     * @return \Closure
     */
    public function __invoke(callable $handler): \Closure
    {
        return function ($request, array $options) use ($handler) {
            $request = $this->beforeCall($request);

            return $handler($request, $options);
        };
    }

    /**
     * @return string
     * @throws RequestIdNotFoundException
     */
    private function getRequestId(): string
    {
        if (!$this->request->headers->has($this->header)) {
            throw new RequestIdNotFoundException();
        }

        return $this->request->headers->get($this->header);
    }

    /**
     * @param RequestInterface $request
     * @return RequestInterface
     */
    private function beforeCall(RequestInterface $request): RequestInterface
    {
        if ($request->hasHeader($this->header)) {
            return $request;
        }

        try {
            $requestId = $this->getRequestId();
        } catch (RequestIdNotFoundException $exception) {
            $requestId = $this->requestIdGenerator->generate();
        }

        return $request->withHeader(
            $this->header,
            $requestId
        );
    }
}
