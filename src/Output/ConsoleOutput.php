<?php
/**
 * Scabbia2 PHP Framework
 * http://www.scabbiafw.com/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link        http://github.com/scabbiafw/scabbia2 for the canonical source repository
 * @copyright   2010-2013 Scabbia Framework Organization. (http://www.scabbiafw.com/)
 * @license     http://www.apache.org/licenses/LICENSE-2.0 - Apache License, Version 2.0
 */

namespace Scabbia\Output;

use Scabbia\Output\IOutput;

/**
 * Implementation of output to console.
 *
 * @package     Scabbia\Output
 * @author      Eser Ozvataf <eser@sent.com>
 * @since       2.0.0
 */
class ConsoleOutput implements IOutput
{
    /**
     * Writes given message.
     *
     * @param int    $uHeading size
     * @param string $uMessage message
     *
     * @return void
     */
    public function writeHeader($uHeading, $uMessage)
    {
        if ($uHeading === 1) {
            $tChar = "=";
        } else {
            $tChar = "-";
        }

        echo "$uMessage\r\n", str_repeat($tChar, strlen($uMessage)), "\r\n";

        if ($uHeading === 1) {
            echo "\r\n";
        }
    }

    /**
     * Outputs the array to console.
     *
     * @param array $uArray Target array will be printed
     *
     * @return void
     */
    public function writeArray(array $uArray)
    {
        print_r($uArray);
    }
}