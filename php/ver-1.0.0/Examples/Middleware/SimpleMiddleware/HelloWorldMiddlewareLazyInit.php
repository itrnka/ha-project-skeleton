<?php
declare(strict_types=1);

namespace Examples\Middleware\SimpleMiddleware;

use ha\Middleware\MiddlewareDefaultAbstract;

class HelloWorldMiddlewareLazyInit extends MiddlewareDefaultAbstract
{
    /** @var \mysqli */
    private $driver;

    /**
     * Example access to some driver or vendor package class. This driver is initialized after first call.
     * @return \mysqli
     */
    public function driver()
    {
        if (isset($this->driver)) {
            $this->driver = new \mysqli($this->cfg('host'), $this->cfg('user'), $this->cfg('pwd'), $this->cfg('db'));
            // Note: used cfg values on previous line must be defined in configuration file
        }
        return $this->driver;
    }
}