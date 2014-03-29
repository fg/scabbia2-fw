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

namespace Scabbia\Config;

use Scabbia\Framework\ApplicationBase;
use Scabbia\Framework\Io;
use Scabbia\Yaml\Parser;

/**
 * Config
 *
 * @package     Scabbia\Config
 * @author      Eser Ozvataf <eser@sent.com>
 * @since       1.0.0
 */
class Config
{
    /** @type int NONE      no flag */
    const NONE = 0;
    /** @type int OVERWRITE overwrite existing nodes by default */
    const OVERWRITE = 1;
    /** @type int FLATTEN   flatten nodes by default */
    const FLATTEN = 2;


    /** @type array configuration content */
    public $content = [];


    /**
     * Loads a configuration file
     *
     * @param string $uPath path of configuration file to be loaded
     *
     * @return mixed
     */
    public static function load($uPath)
    {
        $tInstance = new static();
        $tInstance->add($uPath);

        return $tInstance;
    }

    /**
     * Adds a file into configuration compilation
     *
     * @param string $uPath   path of configuration file
     * @param int    $uFlags  loading flags
     *
     * @return void
     */
    public function add($uPath, $uFlags = self::NONE)
    {
        $tConfigContent = Io::readFromCache(
            $uPath,
            function () use ($uPath) {
                $tParser = new Parser();
                return $tParser->parse(Io::read($uPath));
            },
            [
                "ttl" => 60 * 60
            ]
        );

        $this->process($this->content, $tConfigContent, $uFlags);
    }

    /**
     * Processes the configuration file in order to simplify its accessibility
     *
     * @param mixed $uTarget  target reference
     * @param mixed $uNode    source object
     * @param int   $uFlags   loading flags
     *
     * @return void
     */
    public function process(&$uTarget, $uNode, $uFlags)
    {
        $tQueue = [
            [[], $uNode, $uFlags, &$uTarget]
        ];

        while (count($tQueue) > 0) {
            $tItem = array_pop($tQueue);

            if (is_scalar($tItem[1]) || $tItem[1] === null) {
                $tItem[3] = $tItem[1];
                continue;
            }

            $tFlags = $tItem[2];
            $tItem[3] = []; // initialize as an empty array

            foreach ($tItem[1] as $tKey => $tSubnode) {
                $tNodeParts = explode("|", $tKey);
                $tNodeKey = array_shift($tNodeParts);

                foreach ($tNodeParts as $tNodePart) {
                    if ($tNodePart === "disabled") {
                        continue 2;
                    } elseif ($tNodePart === "development") {
                        if (ApplicationBase::$current === null || !ApplicationBase::$current->development) {
                            continue 2;
                        }
                    } elseif ($tNodePart === "important") {
                        $tFlags |= self::OVERWRITE;
                    } elseif ($tNodePart === "flat") {
                        $tFlags |= self::FLATTEN;
                    }
                }

                $tNewNodeKey = $tItem[0];
                $tNewNodeKey[] = $tNodeKey;

                $tQueue[] = [$tNewNodeKey, $tSubnode, $tFlags, &$tItem[3][$tNodeKey]];
            }
        }
    }
}
