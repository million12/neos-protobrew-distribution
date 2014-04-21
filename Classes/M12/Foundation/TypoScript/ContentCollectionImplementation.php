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
		if ($contentCollectionNode === null) {
			return parent::evaluate();
		}
		if ($contentCollectionNode->getContext()->getWorkspaceName() !== 'live') {
			return parent::evaluate();
		}

		// Render collection content
		$content = AbstractCollectionImplementation::evaluate();

		// M12 hack: some ContentCollection elements might have this extra wrapper around themselves,
		// like in case of BlockGrid. Seems like ContentCollection is bit buggy here and
		// does not work well when on the top of the structure it has eg. UL element.
		// This is why we add extra DIV wrapper, which we remove here, when rendering
		// inside @live workspace.
		$cls = 'extra-wrapper-required-by-content-collection';
		if (strstr($content, $cls)) {
			$content = preg_replace('#^<div class="'.$cls.'">(.+?)</div>$#ms', '$1', trim($content));
		}

		// Add some extra debug information.
		// @todo This is bit nasty hack, find out how to get FLOW_CONTEXT in an appropriate way...
		$debugMode   = isset($_SERVER['FLOW_CONTEXT']) && stristr($_SERVER['FLOW_CONTEXT'], 'Development');
		$contextPath = $contentCollectionNode->getContextPath();

        $output  = $debugMode ? "<!-- content-collection: $contextPath START -->" : '';
		$output .= trim($content);
        $output .= $debugMode ? "<!-- content-collection: $contextPath END -->" : '';

        return $output;
    }
}
