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

namespace Scabbia\Helpers;

/**
 * A bunch of utility methods for runtime hacks
 *
 * @package     Scabbia\Helpers
 * @author      Eser Ozvataf <eser@sent.com>
 * @since       2.0.0
 */
class Runtime
{
    /**
     * Default variables for runtime hacks
     *
     * @type array $defaults array of default variables
     */
    public static $defaults = [
    ];


    /**
     * Sets the default variables
     *
     * @param array $uDefaults variables to be set
     *
     * @return void
     */
    public static function setDefaults($uDefaults)
    {
        self::$defaults = $uDefaults + self::$defaults;
    }

    /**
     * Allows on-the-fly construction of classes
     *
     * @param mixed    $uCallback  callback
     *
     * @return mixed   callback
     */
    public static function callbacks($uCallback)
    {
        if (is_string($uCallback) && ($tPos = strrpos($uCallback, "@")) !== false) {
            $tClassName = substr($uCallback, 0, $tPos);
            $uCallback = [new $tClassName (), substr($uCallback, $tPos + 1)];
        }

        return $uCallback;
    }
}