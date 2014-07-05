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

namespace Scabbia\Framework;

use Scabbia\Events\Events;

/**
 * Default methods needed for implementation of an application
 *
 * @package     Scabbia\Framework
 * @author      Eser Ozvataf <eser@sent.com>
 * @since       2.0.0
 */
abstract class ApplicationBase
{
    /** @type ApplicationBase $current current application instance */
    public static $current = null;
    /** @type mixed           $config application configuration */
    public $config;
    /** @type Events          $events events */
    public $events;
    /** @type array           $paths paths include source files */
    public $paths;
    /** @type string          $writablePath writable output folder */
    public $writablePath;
    /** @type bool            $development the development flag of application is on or off */
    public $development;


    /**
     * Initializes an application
     *
     * @param mixed  $uConfig        application config
     * @param array  $uPaths         paths include source files
     * @param string $uWritablePath  writable output folder
     *
     * @return ApplicationBase
     */
    public function __construct($uConfig, $uPaths, $uWritablePath)
    {
        $this->paths = $uPaths;
        $this->writablePath = $uWritablePath;

        $this->config = $uConfig;
        $this->development = $uConfig["development"];

        $this->events = new Events();
        $this->events->events = require "{$this->writablePath}/events.php";

        // TODO initialize the proper environment
        if ($this->development) {
            error_reporting(-1);
        } else {
            error_reporting(0);
        }

        // TODO set exception handler
        // TODO instantiate application with variables (environment and its config [development, disableCaches])
        // TODO load modules
        // TODO execute autoexecs
    }

    /**
     * Gets request method
     *
     * @return array
     */
    abstract public function getRequestMethod();

    /**
     * Gets request path info
     *
     * @return array
     */
    abstract public function getRequestPathInfo();

    /**
     * Gets query parameters
     *
     * @return array
     */
    abstract public function getQueryParameters();

    /**
     * Gets post parameters
     *
     * @return array
     */
    abstract public function getPostParameters();

    /**
     * Generates request
     *
     * @param string $uMethod          method
     * @param string $uPathInfo        pathinfo
     * @param array  $uQueryParameters query parameters
     * @param array  $uPostParameters  post parameters
     *
     * @return void
     */
    abstract public function generateRequest($uMethod, $uPathInfo, array $uQueryParameters, array $uPostParameters);

    /**
     * Generates request from globals
     *
     * @return void
     */
    abstract public function generateRequestFromGlobals();
}
