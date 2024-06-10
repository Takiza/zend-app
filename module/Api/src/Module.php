<?php

namespace Api;

use Laminas\Mvc\MvcEvent;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\SharedEventManagerInterface;
use Laminas\Mvc\Application;
use Api\Middleware\ApiKeyMiddleware;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $application = $e->getApplication();
        $eventManager = $application->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();

        $sharedEventManager->attach(__NAMESPACE__, 'route', function (MvcEvent $e) use ($application) {
            $routeMatch = $e->getRouteMatch();
            $routeName = $routeMatch->getMatchedRouteName();

            if (strpos($routeName, 'api-') === 0) {
                $pipeline = $application->getServiceManager()->get('MiddlewarePipeline');
                $pipeline->pipe($application->getServiceManager()->get(ApiKeyMiddleware::class));
            }
        });
    }

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
