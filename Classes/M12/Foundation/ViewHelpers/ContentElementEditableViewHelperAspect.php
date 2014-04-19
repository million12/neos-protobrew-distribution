<?php
namespace M12\Foundation\ViewHelpers;

/*                                                                        *
 * This script belongs to the "M12.Foundation" package.                   *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Aspect
 */
class ContentElementEditableViewHelperAspect {

	/**
	 * Strip off wrapping <div></div> from all inline properties
	 * when not in back-end editing workspace.
	 *
	 * @param \TYPO3\Flow\AOP\JoinPointInterface $joinPoint
	 * @Flow\Around("method(TYPO3\Neos\ViewHelpers\ContentElement\EditableViewHelper->render())")
	 * @return string
	 */
	public function catchRender(\TYPO3\Flow\AOP\JoinPointInterface $joinPoint) {
		// get original output
		$res = $joinPoint->getAdviceChain()->proceed($joinPoint);

		// Match for exact <div>...</div>
		// If DIV contains extra properties, it means we're in back-end editing mode
		// and it should NOT be stripped off.
		if ($res && 'div' === $joinPoint->getMethodArgument('tag')) {
			$res = preg_replace('#^<div>(.+?)</div>$#', '$1', $res);
		}

		return $res;
	}
}
