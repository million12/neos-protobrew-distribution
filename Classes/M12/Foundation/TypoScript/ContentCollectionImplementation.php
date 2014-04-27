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

use TYPO3\TYPO3CR\Domain\Model\Node;
use TYPO3\Neos\TypoScript\ContentCollectionImplementation as NeosContentCollectionImplementation;
use TYPO3\TypoScript\TypoScriptObjects\AbstractCollectionImplementation;

/**
 * Overrides Neos ContentCollections
 */
class ContentCollectionImplementation extends NeosContentCollectionImplementation {

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * M12.FoundationGrid settings
	 *
	 * @param array $settings
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

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
		if ($contentCollectionNode === null) {
			return parent::evaluate();
		}

		$this->setDefaultProperties($contentCollectionNode);

		return parent::evaluate();
    }

	/**
	 * Set essential default properties on some nodes
	 *
	 * E.g. when Grid with 2 columns is inserted, it sets default size for each column,
	 * before user sets its own values.
	 *
	 * This is experimental, probably there's a better way to inject these properties.
	 *
	 * @param Node $contentCollectionNode
	 */
	protected function setDefaultProperties(Node $contentCollectionNode) {
		$parentNodeType = $contentCollectionNode->getParent()->getNodeType()->getName();
		$nodeType = $contentCollectionNode->getNodeType()->getName();

		switch ($nodeType) {
			case 'M12.Foundation:Column':
			case 'M12.Foundation:ColumnEnd':
				$gridSize = $this->settings['gridSize'];
				$columns = (int)str_replace('M12.Foundation:GridColumns', '', $parentNodeType);
				$defaultColumns = floor($gridSize / $columns);

				$sizeSettings = array();
				foreach (array_keys($this->settings['devices']) as $device) {
					$name = 'class'.ucfirst($device).'Size';
					$sizeSettings[$name] = $contentCollectionNode->getProperty($name);
				}
				if (!array_filter($sizeSettings)) {
					$keys = array_keys($sizeSettings);
					$property = $keys[0];
					$contentCollectionNode->setProperty($property, 'small-'.$defaultColumns);
				}
				break;
		}
	}
}
