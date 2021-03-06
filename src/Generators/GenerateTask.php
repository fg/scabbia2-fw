<?php
/**
 * Scabbia2 PHP Framework Code
 * http://www.scabbiafw.com/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link        http://github.com/scabbiafw/scabbia2-fw for the canonical source repository
 * @copyright   2010-2014 Scabbia Framework Organization. (http://www.scabbiafw.com/)
 * @license     http://www.apache.org/licenses/LICENSE-2.0 - Apache License, Version 2.0
 */

namespace Scabbia\Generators;

use Scabbia\Code\AnnotationScanner;
use Scabbia\Code\TokenStream;
use Scabbia\Config\Config;
use Scabbia\Generators\GeneratorRegistry;
use Scabbia\Framework\ApplicationBase;
use Scabbia\Framework\Core;
use Scabbia\Loader\Loader;
use Scabbia\Helpers\FileSystem;
use Scabbia\Objects\CommandInterpreter;
use Scabbia\Tasks\TaskBase;
use RuntimeException;

/**
 * Task class for "php scabbia generate"
 *
 * @package     Scabbia\Generators
 * @author      Eser Ozvataf <eser@sent.com>
 * @since       2.0.0
 *
 * @todo only pass annotations requested by generator
 */
class GenerateTask extends TaskBase
{
    /** @type array                   $generators         set of generators */
    public $generators = [];
    /** @type AnnotationScanner|null  $annotationScanner  annotation scanner */
    public $annotationScanner = null;


    /**
     * Registers the tasks itself to a command interpreter instance
     *
     * @param CommandInterpreter $uCommandInterpreter interpreter to be registered at
     *
     * @return void
     */
    public static function registerToCommandInterpreter(CommandInterpreter $uCommandInterpreter)
    {
        $uCommandInterpreter->addCommand(
            "generate",
            "Calls all generators registered to your project",
            [
                // type, name, description
                [Console::OPTION_FLAG, "--clean", ""]
            ]
        );
    }

    /**
     * Initializes the generate task
     *
     * @param mixed      $uConfig    configuration
     * @param IInterface $uInterface interface class
     *
     * @return GenerateTask
     */
    public function __construct($uConfig, $uInterface)
    {
        parent::__construct($uConfig, $uInterface);
    }

    /**
     * Executes the task
     *
     * @param array $uParameters parameters
     *
     * @throws RuntimeException if configuration is invalid
     * @return int exit code
     */
    public function executeTask(array $uParameters)
    {
        /*
        if (count($uParameters) === 0) {
            $tProjectFile = "etc/project.yml";
            $tApplicationKey = "default";
        } else {
            $tExploded = explode("/", $uParameters[0], 2);
            if (count($tExploded) === 1) {
                $tProjectFile = "etc/project.yml";
                $tApplicationKey = $tExploded[0];
            } else {
                $tProjectFile = $tExploded[0];
                $tApplicationKey = $tExploded[1];
            }
        }

        $tProjectFile = FileSystem::combinePaths(Core::$loader->basepath, Core::translateVariables($tProjectFile));
        $uApplicationConfig = Config::load($tProjectFile)->get();

        if (!isset($uApplicationConfig[$tApplicationKey])) {
            throw new RuntimeException(sprintf("invalid configuration - %s::%s", $tProjectFile, $tApplicationKey));
        }

        // TODO is sanitizing $tApplicationKey needed for paths?
        $tApplicationWritablePath = Core::$loader->basepath . "/var/generated/app.{$tApplicationKey}";

        if (!file_exists($tApplicationWritablePath)) {
            mkdir($tApplicationWritablePath, 0777, true);
        }

        // initialize annotation scanner
        $this->annotationScanner = new AnnotationScanner();

        // initialize generators read from configuration
        if (isset($this->config["generators"])) {
            foreach ($this->config["generators"] as $tTaskGeneratorClass) {
                $tInstance = new $tTaskGeneratorClass (
                    $uApplicationConfig[$tApplicationKey],
                    $tApplicationWritablePath
                );

                foreach ($tInstance->annotations as $tAnnotationKey => $tAnnotation) {
                    $this->annotationScanner->annotations[$tAnnotationKey] = $tAnnotation;
                }

                $this->generators[$tTaskGeneratorClass] = $tInstance;
            }
        }

        // -- scan composer maps
        Core::pushSourcePaths($uApplicationConfig[$tApplicationKey]);
        $tFolders = iterator_to_array(Core::$loader->getComposerFolders(), false);

        $this->interface->writeColor("green", "Composer Maps:");
        foreach ($tFolders as $tFolder) {
            $this->interface->writeColor("white", sprintf("- [%s] \\%s => %s", $tFolder[2], $tFolder[0], $tFolder[1]));
        }

        foreach ($this->generators as $tGenerator) {
            $tGenerator->initialize();
        }

        // -- process files
        foreach ($tFolders as $tPath) {
            if (file_exists($tPath[1])) {
                FileSystem::getFilesWalk(
                    $tPath[1],
                    "*.*",
                    true,
                    [$this, "processFile"],
                    $tPath[0]
                );
            }
        }

        foreach ($this->generators as $tGenerator) {
            $tGenerator->processAnnotations($this->annotationScanner->result);
        }

        foreach ($this->generators as $tGenerator) {
            $tGenerator->finalize();
        }

        foreach ($this->generators as $tGenerator) {
            $tGenerator->dump();
        }

        Core::popSourcePaths();
        */

        $tApplication = ApplicationBase::$current;
        $tGeneratorRegistry = new GeneratorRegistry($tApplication->config, $tApplication->writablePath);
        $tGeneratorRegistry->execute();

        $this->interface->writeColor("yellow", "done.");

        return 0;
    }

    /**
     * Processes given file to search for classes
     *
     * @param string $uFile             file
     * @param string $uNamespacePrefix  namespace prefix
     *
     * @return void
     */
    /*
    public function processFile($uFile, $uNamespacePrefix)
    {
        $tFileContents = FileSystem::read($uFile);
        $tTokenStream = TokenStream::fromString($tFileContents);

        foreach ($this->generators as $tGenerator) {
            $tGenerator->processFile($uFile, $tFileContents, $tTokenStream);
        }

        if (substr($uFile, -4) !== ".php") {
            return;
        }

        $this->annotationScanner->process($tTokenStream, $uNamespacePrefix);
    }
    */
}
