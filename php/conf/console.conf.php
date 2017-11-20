<?php
include_once(__DIR__ . '/_shared.php');

########################################################################################################################
# Override shared config values or add special values for this configuration
########################################################################################################################



########################################################################################################################
# Define console configuration
########################################################################################################################

// console app name
$cfg['console.app.name'] = 'HA console application';

// console app version
$cfg['console.app.version'] = '1.0.0';

// Add your commands here ($cfg['console.commands'] is list of commands classes)
$cfg['console.commands'] = [
    Examples\ConsoleAccess\HelloWorldCommand::class,
];


########################################################################################################################
# Add specific middleware and modules instances for console
########################################################################################################################

// session is not accessible from shell, so null object is used for session (see null object design pattern)
$cfg['middleware'][] = [
    ha\Middleware\Session\SessionDisabled::class,
    [
        'name' => 'session',
    ]
];

