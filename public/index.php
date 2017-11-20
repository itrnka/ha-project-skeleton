<?php
declare(strict_types=1);

########################################################################################################################
# Bootstrap
########################################################################################################################

$scriptStartTime = microtime(true);

// setup php flags and settings
ini_set("display_errors", "1");
error_reporting(E_ALL);

// detect environment name or use default
$environmentName = getenv('HA_APP_ENV');
if (!is_string($environmentName) || empty($env)) {
    $environmentName = 'web';
}

// load main runner
include(dirname(__DIR__) . '/php/main.php');

// setup locale for numbers
if (!setlocale(LC_ALL, 'C')) {
    throw new Error('Could not setup locale in app bootstrap');
}

// set real start of script (patch for bootstrap delay)
main()->setScriptStartTime($scriptStartTime);


########################################################################################################################
# Run routing (build HTTP response by HTTP request)
########################################################################################################################

try {
    $routerClassName = main()->cfg('web.router.builder');
    /** @var \ha\Access\HTTP\Router\Builder\HTTPRouterBuilder $routerBuilder */
    $routerBuilder = new $routerClassName();
    $router = $routerBuilder->buildRouter();
    $router->handleInputRequest();
} catch (\Exception $e) {
    throw new Error("Web router is defined incorrectly", 0, $e);
}