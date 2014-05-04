<?php
namespace M12\Foundation\Fluid\Core\ViewHelper;

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
 *
 * @see \TYPO3\Fluid\Core\ViewHelper\TagBuilder
 */
class TagBuilderAspect {

	/**
	 * Prevent TagBuilder->addAttribute() method from executing at all,
	 * if passed attribute value is NULL.
	 *
	 * @param \TYPO3\Flow\AOP\JoinPointInterface $joinPoint
	 * @Flow\Around("method(TYPO3\Fluid\Core\ViewHelper\TagBuilder->addAttribute())")
	 * @return void
	 */
	public function catchAddAttribute(\TYPO3\Flow\AOP\JoinPointInterface $joinPoint) {
		if (NULL === $joinPoint->getMethodArgument('attributeValue')) {
			/** @var \TYPO3\Fluid\Core\ViewHelper\TagBuilder $tagBuilder */
			$tagBuilder = $joinPoint->getProxy();
			$tagBuilder->removeAttribute($joinPoint->getMethodArgument('attributeName'));
		} else {
			$joinPoint->getAdviceChain()->proceed($joinPoint);
		}
	}
}
