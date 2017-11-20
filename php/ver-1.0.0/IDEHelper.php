<?php
declare(strict_types=1);

namespace {
    exit("This file should not be included, only analyzed by your IDE");

    /**
     * Function main.
     * @return \MediasMedia\App
     */
    function main()
    {
    }
}

namespace MediasMedia {

    use ha\App\AppDefault;
    use ha\Component\Container\IoC\IoCContainerFromConfigArray;
    use ha\Middleware\Cache\APCu\APCu;
    use ha\Middleware\NoSQL\Elasticsearch\Elasticsearch;
    use ha\Middleware\RDBMS\MySQLi\MySQLi;
    use ha\Middleware\Render\Twig\TwigBasedHTMLRenderer;
    use ha\Middleware\Session\Session;

    /**
     * Class App.
     * Implementation of App interface for console.
     * @property-read float $scriptStartTime
     * @property-read string $environmentName
     * @property-read \ha\Component\Configuration\Configuration $appConfiguration
     * @property-read array $supportedCharsets
     * @property-read \MediasMedia\ConsoleMiddlewareContainer|\MediasMedia\WebMiddlewareContainer $middleware
     * @property-read \MediasMedia\WebModuleContainer $modules
     */
    class App extends AppDefault
    {
    }

    /**
     * MiddlewareContainer instance for console.
     * @property APCu $APCu
     * @property MySQLi $SQL001
     * @property TwigBasedHTMLRenderer $twig
     * @property Elasticsearch $ES001
     * @property Session $session
     */
    class ConsoleMiddlewareContainer extends IoCContainerFromConfigArray
    {
    }

    /**
     * ModuleContainer for console.
     * @property \MM\Module\Magazine\MagazineModule $magazine
     * @property \hac\ACL\ACL $ACL
     */
    class ConsoleModuleContainer extends IoCContainerFromConfigArray
    {
    }

    /**
     * MiddlewareContainer instance for web access.
     * @property APCu $APCu
     * @property MySQLi $SQL001
     * @property TwigBasedHTMLRenderer $twig
     * @property Elasticsearch $ES001
     * @property Session $session
     */
    class WebMiddlewareContainer extends IoCContainerFromConfigArray
    {
    }

    /**
     * ModuleContainer instance for web access.
     * @property \MM\Module\Magazine\MagazineModule $magazine
     * @property \hac\ACL\ACL $ACL
     */
    class WebModuleContainer extends IoCContainerFromConfigArray
    {
    }

}