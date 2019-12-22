<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian.feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\Condition\FileChanged;

use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Condition\FileChanged;
use SebastianFeldmann\Git\Repository;

/**
 * Class All
 *
 * The FileChange condition is applicable for `post-merge` and `post-checkout` hooks.
 * It checks if all configured files are updated within the last change set.
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhookphp/captainhook
 * @since   Class available since Release 4.2.0
 */
class All extends FileChanged
{
    /**
     * Evaluates the condition
     *
     * @param  \CaptainHook\App\Console\IO       $io
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @return bool
     */
    public function isTrue(IO $io, Repository $repository): bool
    {
        return $this->didAllFilesChange($this->getChangedFiles($io, $repository));
    }

    /**
     * Check if all of the configured files where changed within the applied change set
     *
     * Important: If no files are configured this condition is always true.
     *
     * @param  array<string> $changedFiles
     * @return bool
     */
    private function didAllFilesChange(array $changedFiles): bool
    {
        foreach ($this->filesToWatch as $filePattern) {
            $foundPattern = false;
            foreach($changedFiles as $changedFile) {
                if (fnmatch($filePattern, $changedFile)) {
                    $foundPattern = true;
                    break;
                }
            }

            if(!$foundPattern) {
                return false;
            }
        }
        return true;
    }
}
