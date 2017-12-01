<?php

namespace Avariya\Listener;

use Qandidate\Stack\RequestIdGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class ExtendKernelListener
{
    const REQUEST_ID_HEADER = 'X-Request-Id';

    /**
     * @var string
     */
    private $requestId;

    /**
     * @var RequestIdGenerator
     */
    private $requestIdGenerator;

    /**
     * ExtendKernelListener constructor.
     * @param RequestIdGenerator $requestIdGenerator
     */
    public function __construct(RequestIdGenerator $requestIdGenerator)
    {
        $this->requestIdGenerator = $requestIdGenerator;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        /** @var Request $request */
        $request = $event->getRequest();
        if (!$request->headers->has(self::REQUEST_ID_HEADER)) {
            $request->headers->set(self::REQUEST_ID_HEADER, $this->requestIdGenerator->generate());
        }

        $this->requestId = $request->headers->get(self::REQUEST_ID_HEADER);
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
