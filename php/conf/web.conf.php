<?php
include_once(__DIR__ . '/_shared.php');

########################################################################################################################
# Override shared config values or add special values for this configuration
########################################################################################################################


########################################################################################################################
# Define HTTP access configuration
########################################################################################################################

// Define your web router class, this class must implement interface ha\Access\HTTP\Router\Builder\HTTPRouterBuilder.
$cfg['web.router.builder'] = ha\Access\HTTP\Router\Builder\HTTPRouterBuilderExample::class; // example implementation


########################################################################################################################
# Add specific middleware and modules instances for console
########################################################################################################################

// session
$cfg['middleware'][] = [
    ha\Middleware\Session\SessionDefault::class,
    [
        'name' => 'session',
        'session.name' => 'ha_session',
    ]
];
