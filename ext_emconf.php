<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "extractor".
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
    'title' => 'Metadata and content analysis service',
    'description' => 'This extension detects and extracts metadata (EXIF / IPTC / XMP / ...) from potentially thousand different file types (such as MS Word/Powerpoint/Excel documents, PDF and images) and bring them automatically and natively to TYPO3 when uploading assets. Works with built-in PHP functions but takes advantage of Apache Tika and other external tools for enhanced metadata extraction.',
    'category' => 'services',
    'author' => 'Xavier Perseguers',
    'author_company' => 'Causal Sàrl',
    'author_email' => 'xavier@causal.ch',
    'state' => 'stable',
    'version' => '2.6.0',
    'constraints' => [
        'depends' => [
            'php' => '7.4.0-8.3.99',
            'typo3' => '11.5.0-13.4.99',
            'filemetadata' => '',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
