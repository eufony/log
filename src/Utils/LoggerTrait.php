<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

namespace Eufony\Log\Utils;

use Exception;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;
use ReflectionClass;
use Stringable;

/**
 * Provides common functionality for implementing the PSR-3 standards.
 *
 * All PSR-3 methods are prefixed with `psr3_` to avoid naming collisions.
 */
trait LoggerTrait
{
    /**
     * Provides validation for PSR-3 log levels.
     *
     * Ensures that the log level passed to the various logging methods is valid
     * according to the PSR-3 standards.
     *
     * Casts log levels that are instances of `Stringable` to strings.
     * Returns the typecast log level for easy processing.
     *
     * Throws a `\Psr\Log\InvalidArgumentException` if the log level is invalid.
     *
     * Example usage:
     * ```
     * $level = $this->psr3_validateLevel($level);
     * ```
     *
     * @param string|\Stringable $level
     * @return string
     */
    protected function psr3_validateLevel($level): string
    {
        // Ensure log level can be typecast to string
        if (!is_string($level) && !($level instanceof Stringable)) {
            throw new InvalidArgumentException("Log level must be a string");
        }

        // Ensure valid log level is passed
        // Grab valid log levels from constants defined in the LogLevel class
        $levels = (new ReflectionClass(LogLevel::class))->getConstants();

        if (!in_array($level, $levels)) {
            throw new InvalidArgumentException("Invalid log level '$level'");
        }

        // Ensure objects are cast to strings
        /** @var string $level */
        $level = "$level";

        // Return result
        return $level;
    }

    /**
     * Provides validation for PSR-3 method parameters.
     *
     * Ensures that the log level, message, and context array passed to the various
     * logging methods are all valid according to the PSR-3 standards.
     *
     * Casts log levels and messages that are instances of `Stringable` to strings.
     * Returns the typecast parameters for easy processing.
     *
     * Throws a `\Psr\Log\InvalidArgumentException` if any of the parameters are
     * invalid.
     *
     * Example usage:
     * ```
     * [$level, $message, $context] = $this->psr3_validateParams($level, $message, $context);
     * ```
     *
     * @param string|\Stringable $level
     * @param string|\Stringable $message
     * @param mixed[] $context
     * @return mixed[]
     */
    protected function psr3_validateParams($level, $message, array $context = []): array
    {
        // Ensure log message can be typecast to string
        if (!is_string($message) && !($message instanceof Stringable)) {
            throw new InvalidArgumentException("Log message must be a string");
        }

        // If "exception" key exists, ensure it is an instance of Exception
        if (array_key_exists("exception", $context) && !($context['exception'] instanceof Exception)) {
            throw new InvalidArgumentException("'exception' key in context array must be an instance of \Exception");
        }

        // Ensure objects are cast to strings
        /** @var string $message */
        $message = "$message";

        // Validate log level
        $level = $this->psr3_validateLevel($level);

        // Return result
        return [$level, $message, $context];
    }

    /**
     * Provides log message interpolation using the given context array according
     * to the PSR-3 standards.
     *
     * Returns the interpolated message for easy processing.
     *
     * Throws a `\Psr\Log\InvalidArgumentException` if the context array is
     * invalid.
     *
     * Example usage:
     * ```
     * $message = $this->psr3_interpolateMessage($message, $context);
     * ```
     *
     * @param string $message
     * @param mixed[] $context
     * @return string
     */
    protected function psr3_interpolateMessage(string $message, array $context = []): string
    {
        // Build a replacement array with braces around the context keys
        $replace = [];

        foreach ($context as $key => $value) {
            // Ensure log value can be typecast to string
            if (!is_string($value) && !($value instanceof Stringable)) {
                throw new InvalidArgumentException("Value in context array must be a string");
            }

            // Ensure objects are cast to strings
            /** @var string $value */
            $value = "$value";

            // Add key-value pair to replacement array
            $replace['{' . $key . '}'] = $value;
        }

        // Interpolate replacement values into the message
        $message = strtr($message, $replace);

        // Return result
        return $message;
    }
}
