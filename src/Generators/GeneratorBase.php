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

use Scabbia\Code\AnnotationManager;
use Scabbia\Code\TokenStream;
use Scabbia\Generators\GeneratorRegistry;

/**
 * Default methods needed for implementation of a generator
 *
 * @package     Scabbia\Generators
 * @author      Eser Ozvataf <eser@sent.com>
 * @since       2.0.0
 */
abstract class GeneratorBase
{
    /** @type GeneratorRegistry         $generatorRegistry        generator registry */
    public $generatorRegistry;


    /**
     * Initializes a generator
     *
     * @param GeneratorRegistry  $uGeneratorRegistry   generator registry
     *
     * @return GeneratorBase
     */
    public function __construct(GeneratorRegistry $uGeneratorRegistry)
    {
        $this->generatorRegistry = $uGeneratorRegistry;
    }

    /**
     * Processes a file
     *
     * @param string      $uPath         file path
     * @param string      $uFileContents contents of file
     * @param TokenStream $uTokenStream  extracted tokens wrapped with tokenstream
     *
     * @return void
     */
    public function processFile($uPath, $uFileContents, TokenStream $uTokenStream)
    {
    }

    /**
     * Processes set of annotations
     *
     * @return void
     */
    public function processAnnotations()
    {
    }

    /**
     * Finalizes generator process
     *
     * @return void
     */
    public function finalize()
    {
    }
}
