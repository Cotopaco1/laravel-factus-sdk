<?php

namespace Cotopaco\Factus\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Cotopaco\Factus\Factus
 */
class Factus extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Cotopaco\Factus\Factus::class;
    }
}
