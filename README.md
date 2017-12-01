## Installation

### ***Change composer.json***

Require bundle:

    composer require avariya/request-id-bundle

Add bundle to AppKernel:

    new \Avariya\RequestIdBundle\AvariyaRequestIdBundle(),
    
### ***Add configuration***_(optional)_
Default values:

    avariya_request_id:
        monolog_support: true
        kernel_subscriber: true
        header: X-Request-Id
        guzzle_middleware:
            guzzle_tag: csa_guzzle.middleware
        
or:

    avariya_request_id:
        monolog_support: false
        kernel_subscriber: false
        header: X-Request-Id
        guzzle_middleware: false