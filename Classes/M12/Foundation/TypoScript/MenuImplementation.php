<?php
namespace M12\Foundation\TypoScript;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Neos".            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Neos\TypoScript\MenuImplementation as NeosMenuImplementation;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;


/**
 * Overrides default Neos MenuImplementation
 */
class MenuImplementation extends NeosMenuImplementation {

	/**
	 * {inheritdoc}
	 */
	protected function buildMenuLevelRecursive(array $menuLevelCollection) {
		$items = parent::buildMenuLevelRecursive($menuLevelCollection);
		$siteNode = $this->currentNode->getContext()->getCurrentSiteNode();

		$currentStateAlreadySet = false;
		foreach ($items as &$item) {
			// Initialize new variable with css classes
			$item['cssClasses'] = '';

			/** @var NodeInterface $node */
			$node = $item['originalNode'];

			//
			// [FEATURE]
			// Resolve #fragmentId part of url (set in node property 'fragmentId')
			// and store it in $item, so it's easily available in the template.
			//
			$item['fragmentId'] = null;
			if (($fragmentId = $node->getProperty('fragmentId')))
				$item['fragmentId'] = $fragmentId;

			//
			// [IMPROVEMENT]
			// Allow *only one* STATE_CURRENT on given menu level.
			// This is useful when there's more then one URL on the same level pointing to the
			// current page (and eg. #fragmentId is used to point different section on the page).
			//
			if (static::STATE_CURRENT === $item['state']) {
				if ($currentStateAlreadySet)
					$item['state'] = static::STATE_NORMAL;
				else
					$currentStateAlreadySet = true;
			}

			//
			// [FEATURE]
			// Collect necessary classes, compatible with Zurb Foundation
			//
			// 'active' for currently displayed node
			if (static::STATE_CURRENT === $item['state'])
				$item['cssClasses'] .= ($item['cssClasses']?' ':'') . 'active';
			// 'active-trail' for nodes in the root-line
			elseif (static::STATE_ACTIVE === $item['state']) {
				// Exclude it for nodes pointing to site node.
				// Root site node is always in the root-line, there might be plenty
				// of items pointing (via shortcut) to that node and we don't want them
				// all highlighted.
				if ($item['node'] !== $siteNode)
					$item['cssClasses'] .= ($item['cssClasses']?' ':'') . 'active-trail';
			}
			// 'has-dropdown' if there are items in sub-menu
			if (false === empty($item['subItems']))
				$item['cssClasses'] .= ($item['cssClasses']?' ':'') . 'has-dropdown';
		}

//		\TYPO3\Flow\var_dump($items);
		return $items;
	}
}
