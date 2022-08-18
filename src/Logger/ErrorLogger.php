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

namespace Eufony\Log\Logger;

use Eufony\Log\Utils\LoggerTrait;
use Psr\Log\AbstractLogger;

/**
 * Provides a logging implementation for logging to the error log.
 *
 * The messages are directly sent to the SAPI logging handler.
 */
class ErrorLogger extends AbstractLogger
{
    use LoggerTrait;

    /**
     * @inheritDoc
     */
    public function log($level, $message, array $context = []): void
    {
        [$level, $message, $context] = $this->psr3_validateParams($level, $message, $context);
        $message = $this->psr3_interpolateMessage($message, $context);

        // Send log level and message to the SAPI logging handler (the error log)
        error_log("$level: $message", 4);
    }
}
