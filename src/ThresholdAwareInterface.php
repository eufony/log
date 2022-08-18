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

namespace Eufony\Log;

/**
 * Provides a common interface for minimum and maximum log level thresholds.
 */
interface ThresholdAwareInterface
{
    /**
     * Combined getter / setter for the minimum log level.
     *
     * Returns the current minimum log level.
     * If `$level` is set, sets the new minimum and returns the previous value.
     *
     * @param string|null $level
     * @return string
     */
    public function minLevel(?string $level = null): string;

    /**
     * Combined getter / setter for the maximum log level.
     *
     * Returns the current maximum log level.
     * If `$level` is set, sets the new maximum and returns the previous value.
     *
     * @param string|null $level
     * @return string
     */
    public function maxLevel(?string $level = null): string;
}
