<?php

declare(strict_types=1);

namespace Grafema\Env;

/**
 * AbstractProcessor
 *
 * Defines the structure of a processor, which processes a value
 * and determines if it can be processed, as well as executes the processing logic.
 */
abstract class AbstractProcessor
{
    /**
     * Constructor to initialize the processor with a value.
     *
     * @param string $value The value to be processed.
     */
    public function __construct(protected string $value)
    {
    }

    /**
     * Determines if the value can be processed.
     *
     * @return bool True if the value can be processed, false otherwise.
     */
    abstract public function canBeProcessed(): bool;

    /**
     * Executes the processing logic.
     *
     * @return mixed The result of the processing logic.
     */
    abstract public function execute(): mixed;
}
