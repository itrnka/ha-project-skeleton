<?php
declare(strict_types=1);

namespace Examples\Middleware\SimpleMiddleware;

use ha\Middleware\MiddlewareDefaultAbstract;

class HelloWorldMiddleware extends MiddlewareDefaultAbstract
{

    /**
     * Example method without access to some driver.
     *
     * @param int $number
     *
     * @return int
     */
    public function doSomething(int $number): int
    {
        return ($number * 2);
    }

}