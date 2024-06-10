<?php

namespace Api\Listener;

use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\JsonModel;
use Throwable;

class JsonErrorHandler
{
    public function __invoke(MvcEvent $e)
    {
        $exception = $e->getParam('exception');
        if (!$exception instanceof Throwable) {
            return;
        }

        $response = $e->getResponse();
        $response->setStatusCode(500);

        $model = new JsonModel([
            'error' => true,
            'message' => $exception->getMessage(),
        ]);
        $e->setResult($model);
        $e->setViewModel($model);
    }
}
