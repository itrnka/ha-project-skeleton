<?php
// create configuration variable (here is cinfiguration stored)
$cfg = []; // do not remove this line, $cfg variable is very important and required for bootstrap

########################################################################################################################
# APP
########################################################################################################################

/** @var string $cfg ['app.environmentName'] Store env name recieved from bootstrap (make it accessible from app container) */
$cfg['app.environmentName'] = $environmentName;

/** @var string $cfg ['app.builder.className'] Name of a class, which implements \ha\App\Builder\AppBuilder */
$cfg['app.builder.className'] = ha\App\Builder\AppBuilderDefault::class;

/** @var string $cfg ['app.uniqueName'] Unique name of app - define unique app name in app "cluster" */
$cfg['app.uniqueName'] = md5(__DIR__); // please use concrete name

/** @var string Project root directory. */
$cfg['app.rootDir'] = dirname(__DIR__);

/** @var string Public root directory for HTTP access. */
$cfg['app.publicDirHTTP'] = $cfg['app.rootDir'] . '/public';

########################################################################################################################
# Class autoload by PSR-4 standard
########################################################################################################################

/** @var string $cfg ['app.class.version'] Project files root dir for class autoloading */
$cfg['app.class.version'] = 'ver-1.0.0';

/** @var array $cfg ['app.class.namespaceRoots'] string[] Array with your custom namespace roots used in your logic */
$cfg['app.class.namespaceRoots'] = ['Examples'];

########################################################################################################################
# Middleware
########################################################################################################################

$cfg['middleware'] = [// TODO define shared middleware
];

########################################################################################################################
# Modules
########################################################################################################################

$cfg['modules'] = [// TODO define shared modules
];

########################################################################################################################
# Access configuration (routing/commands, based on access type)
########################################################################################################################

// TODO define router builder (for HTTP access) or commands (for console)

########################################################################################################################
# Other (pseudo global variables)
########################################################################################################################

#$cfg['my.example'] = 123;
