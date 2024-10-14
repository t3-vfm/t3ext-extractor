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

namespace Causal\Extractor\Service\Extraction;

use Causal\Extractor\Service\Php\PhpService;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * A service to extract metadata from files using PHP's built-in methods.
 *
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class PhpMetadataExtraction extends AbstractExtractionService
{
    /**
     * @var integer
     */
    protected $priority = 50;

    /**
     * @var string
     */
    protected $serviceName = 'Php';

    /**
     * PhpMetadataExtraction constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $phpService = $this->getPhpService();
        $this->supportedFileExtensions = $phpService->getSupportedFileExtensions();
    }

    /**
     * Checks if the given file can be processed by this extractor.
     *
     * @param File $file
     * @return boolean
     */
    protected function _canProcess(File $file): bool
    {
        $fileExtension = strtolower($file->getProperty('extension'));
        return in_array($fileExtension, $this->supportedFileExtensions);
    }

    /**
     * The actual processing task.
     *
     * Should return an array with database properties for sys_file_metadata to write.
     *
     * @param File $file
     * @param array $previousExtractedData optional, contains the array of already extracted data
     * @return array
     */
    public function extractMetaData(File $file, array $previousExtractedData = []): array
    {
        $metadata = [];

        $extractedMetadata = $this->getPhpService()->extractMetadata($file);
        if (!empty($extractedMetadata)) {
            $dataMapping = $this->getDataMapping($file);
            $metadata = $this->remapServiceOutput($extractedMetadata, $dataMapping);
            $this->processCategories($file, $metadata);
        }

        return $metadata;
    }

    /**
     * Returns a PHP service.
     *
     * @return \Causal\Extractor\Service\Php\PhpService
     */
    protected function getPhpService(): PhpService
    {
        /** @var \Causal\Extractor\Service\Php\PhpService $phpService */
        static $phpService = null;

        if ($phpService === null) {
            $phpService = GeneralUtility::makeInstance(\Causal\Extractor\Service\Php\PhpService::class);
        }

        return $phpService;
    }

    /**
     * Returns a logger.
     *
     * @return \TYPO3\CMS\Core\Log\Logger
     */
    protected static function getLogger()
    {
        /** @var \TYPO3\CMS\Core\Log\Logger $logger */
        $logger = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Log\LogManager::class)->getLogger(__CLASS__);
        return $logger;
    }
}
