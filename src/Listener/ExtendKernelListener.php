<?php

namespace Avariya\RequestIdBundle\Listener;

use Qandidate\Stack\RequestIdGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

final class ExtendKernelListener
{
    /**
     * @var string
     */
    private $requestId;

    /**
     * @var RequestIdGenerator
     */
    private $requestIdGenerator;

    /**
     * @var string
     */
    private $header;

    /**
     * ExtendKernelListener constructor.
     * @param RequestIdGenerator $requestIdGenerator
     * @param string $header
     */
    public function __construct(RequestIdGenerator $requestIdGenerator, string $header)
    {
        $this->requestIdGenerator = $requestIdGenerator;
        $this->header = $header;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        /** @var Request $request */
        $request = $event->getRequest();
        if (!$request->headers->has($this->header)) {
            $request->headers->set($this->header, $this->requestIdGenerator->generate());
        }

        $this->requestId = $request->headers->get($this->header);
    }

    /**
     * @param array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        if ($this->requestId) {
            $record['extra']['request_id'] = $this->requestId;
        }

        return $record;
    }
}
