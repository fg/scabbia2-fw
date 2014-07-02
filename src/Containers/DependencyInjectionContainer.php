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

namespace Scabbia\Containers;

/**
 * DependencyInjectionContainer
 *
 * @package     Scabbia\Containers
 * @author      Eser Ozvataf <eser@sent.com>
 * @since       2.0.0
 */
trait DependencyInjectionContainer
{
    /** @type array $parameters parameters */
    public $parameters = [];
    /** @type array $parameters parameters */
    protected $serviceDefinitions = [];
    /** @type array $parameters parameters */
    protected $sharedInstances = [];


    /**
     * Sets a service definition
     *
     * @param string   $uService          name of the service
     * @param callable $uCallback         callback
     * @param bool     $uIsSharedInstance is it a shared instance
     *
     * @return void
     */
    public function setService($uService, /* callable */ $uCallback, $uIsSharedInstance = true)
    {
        $this->serviceDefinitions[$uService] = [$uCallback, $uIsSharedInstance];
    }

    /**
     * Gets a service definition
     *
     * @param string $uService name of the service
     *
     * @return mixed service object
     */
    public function getService($uService)
    {
        return $this->serviceDefinitions[$uService][0];
    }

    /**
     * Checks if a service definition exists
     *
     * @param string $uService name of the service
     *
     * @return bool true if service definition exists
     */
    public function hasService($uService)
    {
        return isset($this->serviceDefinitions[$uService]);
    }

    /**
     * Magic method for dependency injection containers
     *
     * @param string $uName name of the service
     *
     * @return mixed the service instance
     */
    public function __get($uName)
    {
        if (array_key_exists($uName, $this->sharedInstances)) {
            return $this->sharedInstances[$uName];
        }

        $tService = $this->serviceDefinitions[$uName];
        $tReturn = call_user_func($tService[0], $this->parameters);

        if ($tService[1] === true) {
            $this->sharedInstances[$uName] = $tReturn;
        }

        return $tReturn;
    }
}