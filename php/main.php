<?php
declare(strict_types = 1);


class Main
{

    /** @var bool */
    private static $initialized = false;

    /** @var string */
    private static $env;

    /** @var \ha\App\App */
    private static $app;

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
    }

    /**
     * Static
     * Get app (core container) instance
     *
     * @static
     * @return \ha\App\App
     * @throws Error
     */
    public static function getInstance()
    {
        if (!self::$initialized) {
            throw new Error(__CLASS__ . ' not initialized.');
        }
        return self::$app;
    }

    /**
     * Static
     * Static constructor simulation with Singleton concept
     *
     * @param string $env
     *
     * @static
     * @throws Error
     */
    public static function construct(string $env)
    {
        if (self::$initialized) {
            throw new Error(__CLASS__ . ' already initialized.');
        }
        self::registerVendorAutoload();
        self::registerNamespaceAutoload('ha');
        self::setupEnvironment($env);
        self::buildApp();
        self::$initialized = true;
    }

    /**
     * Static
     * Register autoload for vendor classes
     *
     * @static
     */
    private static function registerVendorAutoload()
    {
        include_once(__DIR__ . '/vendor/autoload.php');
    }

    /**
     * Static
     * Register autoload classes in NS root $rootNS
     *
     * @static
     */
    private static function registerNamespaceAutoload($rootNS, $version = null)
    {
        spl_autoload_register(function ($className) use ($rootNS, $version) {
            if (strpos($className, $rootNS . '\\') === 0) {
                $className = str_replace('\\', '/', $className);
                if (is_string($version) && $version !== '') {
                    $path = __DIR__ . "/{$version}/{$className}.php";
                } else {
                    $path = __DIR__ . '/' . $className . '.php';
                }
                $res = @include_once($path);
                if ($res === false) {
                    throw new \Error("Class {$className} could not be initialized - include error, parse error, etc.");
                }
                return;
            }
        }, true, false);
    }

    /**
     * Static
     * Setup environment
     *
     * @param string $env
     *
     * @static
     */
    private static function setupEnvironment(string $env)
    {
        if ($env === '') {
            throw new InvalidArgumentException('$env@' . __CLASS__);
        }
        self::$env = $env;
    }

    /**
     * Static
     * Create App instance as Singleton
     *
     * @static
     */
    private static function buildApp()
    {
        // read configuration file and create Configuration instance
        $configPath = __DIR__ . '/conf/' . self::$env . '.conf.php';
        @include($configPath);
        if (!isSet($cfg) || !is_array($cfg)) {
            throw new Error('$cfg not defined, $cfg not an array or config file "' . $configPath . '" could not be included in main.php', E_USER_ERROR);
        }
        $configuration = new \ha\Component\Configuration\Simple\ConfigurationFromArray($cfg, 'mainConfiguration');

        // extra versioned namespaces
        if (!isSet($cfg['app.class.version']) || !is_string($cfg['app.class.version'])) {
            throw new Error('$cfg[\'app.class.version\'] not found or not a string in ' . $configPath);
        }
        if (!isSet($cfg['app.class.namespaceRoots']) || !is_array($cfg['app.class.namespaceRoots'])) {
            throw new Error('$cfg[\'app.class.namespaceRoots\'] not found or not an array in ' . $configPath);
        }
        $extraNSVersion = $cfg['app.class.version'];
        $extraNSRoots = $cfg['app.class.namespaceRoots'];
        foreach ($extraNSRoots AS $extraNSRoot) {
            self::registerNamespaceAutoload($extraNSRoot, $extraNSVersion);
        }

        // create App instance via builder
        $appBuilderClassName = $configuration->get('app.builder.className');
        self::$app = (new $appBuilderClassName($configuration))->buildApp(self::$env);
    }
}

// environment name must be known here and must be a string in alphanumeric format
if (!isSet($environmentName)) {
    throw new Error('Undefined environment variable $env in main.php', E_USER_ERROR);
}
if (!is_string($environmentName) || !preg_match('/^[a-z0-9]+$/', $environmentName)) {
    throw new Error('Invalid environment variable $env format in main.php', E_USER_ERROR);
}

// run
Main::construct($environmentName);

// load helpers
include(__DIR__ . '/helpers.php');

