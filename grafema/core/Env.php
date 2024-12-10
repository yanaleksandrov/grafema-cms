<?php

namespace Grafema;

use Grafema\Env\AbstractProcessor;
use Grafema\Env\BooleanProcessor;
use Grafema\Env\NullProcessor;
use Grafema\Env\NumberProcessor;
use Grafema\Env\QuotedProcessor;

class Env
{
	/**
	 * Configure the options on which the parsed will act
	 *
	 * @var string[]
	 */
	protected array $processors = [];

	/**
	 *
	 *
	 * @var string $path The directory where the .env file can be located.
	 */
	public function __construct(
		protected string $path,
		?array $processors = null
	) {
		if (!is_file($this->path) || !is_readable($this->path)) {
			throw new \InvalidArgumentException("File '{$this->path}' does not exist or is not readable");
		}

		$this->setProcessors($processors);
	}

	/**
	 * Loads the configuration data from the specified file path.
	 * Parses the values into $_SERVER and $_ENV arrays, skipping empty and commented lines.
	 */
	public function load(): void
	{
		$lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		foreach ($lines as $line) {

			if (strpos(trim($line), '#') === 0) {
				continue;
			}

			list($name, $value) = explode('=', $line, 2);
			$name = trim($name);
			$value = $this->processValue($value);

			if (!isset($_SERVER[$name], $_ENV[$name])) {
				putenv(sprintf('%s=%s', $name, $value));
				$_ENV[$name] = $value;
				$_SERVER[$name] = $value;
			}
		}
	}

	private function setProcessors(?array $processors = null): void
	{
		// Fill with default processors
		if ($processors === null) {
			$this->processors = [
				NullProcessor::class,
				BooleanProcessor::class,
				NumberProcessor::class,
				QuotedProcessor::class
			];
			return;
		}

		foreach ($processors as $processor) {
			if (is_subclass_of($processor, AbstractProcessor::class)) {
				$this->processors[] = $processor;
			}
		}
	}

	/**
	 * Process the value with the configured processors
	 *
	 * @param string $value The value to process
	 * @return mixed
	 */
	private function processValue(string $value)
	{
		// first trim spaces and quotes if configured
		$trimmedValue = trim($value);

		foreach ($this->processors as $processor) {
			/** @var AbstractProcessor $processorInstance */
			$processorInstance = new $processor($trimmedValue);

			if ($processorInstance->canBeProcessed()) {
				return $processorInstance->execute();
			}
		}

		// does not match any processor options, return as is
		return $trimmedValue;
	}
}