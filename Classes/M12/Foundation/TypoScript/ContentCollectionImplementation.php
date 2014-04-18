<?php
namespace M12\Foundation\TypoScript;

/*                                                                        *
 * This script belongs to the M12.Foundation package                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Neos\TypoScript\ContentCollectionImplementation as NeosContentCollectionImplementation;
use TYPO3\TypoScript\TypoScriptObjects\AbstractCollectionImplementation;

/**
 * Overrides Neos ContentCollections
 */
class ContentCollectionImplementation extends NeosContentCollectionImplementation {

    /**
     * Inside live workspace, it does NOT render extra DIV.content-collection.
     *
     * This is rather dirty/temporary hack as it breaks the rule, that rendered markup
     * is the same in the Neos back-end and in the front-end.
     *
     * @return string
     */
    public function evaluate() {
        $contentCollectionNode = $this->getContentCollectionNode();
//        \TYPO3\Flow\var_dump($contentCollectionNode->getContextPath());
        if ($contentCollectionNode === NULL) {
            return parent::evaluate();
        }
        if ($contentCollectionNode->getContext()->getWorkspaceName() !== 'live') {
            return parent::evaluate();
        }

        // Find if Neos in development context, so we can output extra HTML comments
        // @todo This is very nasty hack, find how to get FLOW_CONTEXT in a proper way...
        $debugMode = isset($_SERVER['FLOW_CONTEXT']) && stristr($_SERVER['FLOW_CONTEXT'], 'Development');
        $contextPath = $contentCollectionNode->getContextPath();

        $output  = $debugMode ? "<!-- content-collection: $contextPath START -->" : '';
        $output .= AbstractCollectionImplementation::evaluate();
        $output .= $debugMode ? "<!-- content-collection: $contextPath END -->" : '';
        return $output;
    }
}
