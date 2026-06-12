<?php

namespace MKrawczyk\FunQuery;

use Iterator;
use IteratorAggregate;
use MKrawczyk\FunQuery\Exceptions\FunQueryException;
use Traversable;

/**
 * @template T
 */
abstract class FQ implements IteratorAggregate, \JsonSerializable
{
    /**
     * @param string $name
     * @param array<int,mixed> $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        if (!method_exists(FunQuery::class, $name)) {
            throw new \BadMethodCallException(sprintf('Method %s::%s() does not exist.', self::class, $name));
        }

        $method = new \ReflectionMethod(FunQuery::class, $name);

        if (!$method->isPublic()) {
            throw new \BadMethodCallException(sprintf('Method %s::%s() is not public.', FQ::class, $name));
        }

        if ($method->isStatic()) {
            return FunQuery::$name(...$arguments);
        }

        if (empty($arguments)) {
            throw new \BadMethodCallException(sprintf('Method %s::%s() requires the source argument.', self::class, $name));
        }

        $init = array_pop($arguments);

        return FunQuery::create($init)->$name(...$arguments);
    }

}
