<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento2\Sniffs\Files;

use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff as FilesLineLengthSniff;

/**
 * Line length sniff which ignores long lines in case they contain strings intended for translation.
 */
class LineLengthSniff extends FilesLineLengthSniff
{
    /**
     * Having previous line content allows to ignore long lines in case of multi-line declaration.
     *
     * @var string
     */
    protected $previousLineContent = '';

    /**
     * @inheritdoc
     */
    public $lineLimit = 120;

    /**
     * @inheritdoc
     */
    public $absoluteLineLimit = 120;

    /**
     * @inheritdoc
     */
    protected function checkLineLength($phpcsFile, $stackPtr, $lineContent)
    {
        $previousLineRegexp = '~__\($|\bPhrase\($~';
        $currentLineRegexp = '~__\(.+\)|\bPhrase\(.+\)~';
        $currentLineMatch = preg_match($currentLineRegexp, $lineContent) !== 0;
        $previousLineMatch = preg_match($previousLineRegexp, $this->previousLineContent) !== 0;
        $this->previousLineContent = $lineContent;
        if (! $currentLineMatch && !$previousLineMatch) {
            parent::checkLineLength($phpcsFile, $stackPtr, $lineContent);
        }
    }
}
