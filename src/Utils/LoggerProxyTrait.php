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

use Psr\Log\LoggerInterface;

/**
 * Provides common functionality for wrapping a PSR-3 logger in a proxy class.
 *
 * Inheriting classes must set the `$logger` field in the object constructor.
 */
trait LoggerProxyTrait
{
    /**
     * The PSR-3 logger used internally to provide the real logging implementation.
     *
     * @var \Psr\Log\LoggerInterface $logger
     */
    protected LoggerInterface $logger;

    /**
     * Returns the internal PSR-3 logger.
     *
     * @return \Psr\Log\LoggerInterface
     */
    public function logger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @inheritDoc
     */
    public function log($level, $message, array $context = []): void
    {
        $this->logger->log($level, $message, $context);
    }
}
