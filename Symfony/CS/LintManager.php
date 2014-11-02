<?php

/*
 * This file is part of the PHP CS utility.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\CS;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessUtils;

/**
 * Handle PHP code linting process.
 *
 * @author Dariusz Rumiński <dariusz.ruminski@gmail.com>
 */
class LintManager
{
    /**
     * Temporary file for code linting.
     *
     * @var string|null
     */
    private $temporaryFile;

    public function __destruct()
    {
        if ($this->temporaryFile) {
            unlink($this->temporaryFile);
        }
    }

    /**
     * Create process that lint PHP file.
     *
     * @param string $path path to file
     *
     * @return Process
     */
    public function createProcessForFile($path)
    {
        $process = new Process('php -l '.ProcessUtils::escapeArgument($path));
        $process->setTimeout(null);
        $process->run();

        return $process;
    }

    /**
     * Create process that lint PHP code.
     *
     * @param string $source code
     *
     * @return Process
     */
    public function createProcessForSource($source)
    {
        if (!$this->temporaryFile) {
            $this->temporaryFile = tempnam('.', 'tmp');
        }

        file_put_contents($this->temporaryFile, $source);
        $process = $this->createProcessForFile($this->temporaryFile);

        return $process;
    }
}