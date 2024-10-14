<?php
/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Causal\Extractor\Service\Tika;

use Causal\Extractor\Service\ServiceInterface;
use TYPO3\CMS\Core\Resource\File;

/**
 * A common interface for the different ways of accessing Tika,
 * e.g., standalone application or server.
 *
 * @author      Ingo Renner <ingo@typo3.org>
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
interface TikaServiceInterface extends ServiceInterface
{
    /**
     * Returns the version of Tika.
     *
     * @return string
     */
    public function getTikaVersion(): string;

    /**
     * Takes a file reference and detects its content's language.
     *
     * @param \TYPO3\CMS\Core\Resource\File $file
     * @return string Language ISO code
     */
    public function detectLanguage(File $file): string;

    /**
     * Takes a file reference and detects its content's language.
     *
     * @param string $file Path to the file
     * @return string Language ISO code
     */
    public function detectLanguageFromLocalFile(string $file): string;
}
