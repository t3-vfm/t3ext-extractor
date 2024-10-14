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

use Causal\Extractor\Service\Tika\TikaServiceInterface;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * A service to extract metadata from files using Apache Tika.
 *
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class TikaMetadataExtraction extends AbstractExtractionService
{
    /**
     * @var integer
     */
    protected $priority = 80;

    /**
     * @var string
     */
    protected $serviceName = 'Tika';

    /**
     * @var bool
     */
    protected $tikaAvailable;

    /**
     * TikaMetadataExtraction constructor.
     */
    public function __construct()
    {
        parent::__construct();

        /** @var \TYPO3\CMS\Core\Registry $registry */
        $registry = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Registry::class);
        $this->supportedFileExtensions = $registry->get('tx_extractor', 'tika.extensions.metadata');
        if (empty($this->supportedFileExtensions)) {
            if (($tikaService = $this->getTikaService()) !== null) {
                $this->supportedFileExtensions = $tikaService->getSupportedFileExtensions();
                $registry->set('tx_extractor', 'tika.extensions.metadata', $this->supportedFileExtensions);
            }
        }
    }

    /**
     * Checks if the given file can be processed by this extractor.
     *
     * @param File $file
     * @return boolean
     */
    protected function _canProcess(File $file): bool
    {
        $tikaService = $this->getTikaService();
        $fileExtension = strtolower($file->getProperty('extension'));
        return ($tikaService !== null && in_array($fileExtension, $this->supportedFileExtensions));
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

        $extractedMetadata = $this->getTikaService()->extractMetadata($file);
        if (!empty($extractedMetadata)) {
            $dataMapping = $this->getDataMapping($file);
            $metadata = $this->remapServiceOutput($extractedMetadata, $dataMapping);
            $this->processCategories($file, $metadata);
        }

        return $metadata;
    }

    /**
     * Returns a Tika service.
     *
     * @return \Causal\Extractor\Service\Tika\TikaServiceInterface
     */
    protected function getTikaService(): TikaServiceInterface
    {
        /** @var \Causal\Extractor\Service\Tika\TikaServiceInterface $tikaService */
        static $tikaService = null;

        if ($tikaService === null) {
            try {
                $tikaService = \Causal\Extractor\Service\Tika\TikaServiceFactory::getTika();
            } catch (\RuntimeException $e) {
                // Nothing to do
            }
        }

        return $tikaService;
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
