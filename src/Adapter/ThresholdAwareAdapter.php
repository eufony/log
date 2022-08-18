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

namespace Eufony\Log\Adapter;

use Eufony\Log\ThresholdAwareInterface;
use Eufony\Log\Utils\LoggerProxyTrait;
use Eufony\Log\Utils\LoggerTrait;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use ReflectionClass;

/**
 * Provides a logging implementation that wraps another implementation and adds
 * functionality for minimum and maximum log level thresholds.
 */
class ThresholdAwareAdapter extends AbstractLogger implements ThresholdAwareInterface
{
    use LoggerProxyTrait;
    use LoggerTrait;

    /**
     * The minimum log level, below which the message will be ignored when logging.
     *
     * Defaults to the lowest level.
     *
     * @var string $minLevel
     */
    protected string $minLevel = LogLevel::DEBUG;

    /**
     * The maximum log level, above which the message will be ignored when logging.
     *
     * Defaults to the highest level.
     *
     * @var string $maxLevel
     */
    protected string $maxLevel = LogLevel::EMERGENCY;

    /**
     * @inheritDoc
     */
    public function minLevel($level = null): string
    {
        $prev = $this->minLevel;
        $this->minLevel = $this->psr3_validateLevel($level ?? $this->minLevel);
        return $prev;
    }

    /**
     * @inheritDoc
     */
    public function maxLevel($level = null): string
    {
        $prev = $this->maxLevel;
        $this->maxLevel = $this->psr3_validateLevel($level ?? $this->maxLevel);
        return $prev;
    }

    /**
     * @inheritDoc
     */
    public function log($level, $message, array $context = []): void
    {
        $level = $this->psr3_validateLevel($level);

        // Determine if the message should be logged given the set minimum and maximum log levels
        $levels = array_values((new ReflectionClass(LogLevel::class))->getConstants());
        $i1 = array_search($this->minLevel, $levels);
        $i2 = array_search($level, $levels);
        $i3 = array_search($this->maxLevel, $levels);

        // Compare index of constants defined in the LogLevel class
        // Lower index means higher priority
        $should_log = $i1 >= $i2 && $i2 >= $i3;

        if (!$should_log) {
            return;
        }

        $this->logger->log($level, $message, $context);
    }
}
