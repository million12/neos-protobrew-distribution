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
 * Modifies globally how TagBuilder behaves for NULL attributes.
 * We're taking here the same strategy as with AttributesImplementation
 * - we don't want any attributes to render at all if the value
 * was set to NULL or FALSE.
 *
 * @see \TYPO3\Fluid\Core\ViewHelper\TagBuilder
 * @see \M12\Foundation\TypoScript\AttributesImplementation
 *
 * @Flow\Aspect
 */
class TagBuilderAspect {

	/**
	 * This changes how TagBuilder->addAttribute() method works.
	 * When attribute's value is NULL or FALSE, it does *unset* the value.
	 * Otherwise addAttribute() method is called as usually.
	 *
	 * @param \TYPO3\Flow\AOP\JoinPointInterface $joinPoint
	 * @Flow\Around("method(TYPO3\Fluid\Core\ViewHelper\TagBuilder->addAttribute())")
	 * @return void
	 */
	public function catchAddAttribute(\TYPO3\Flow\AOP\JoinPointInterface $joinPoint) {
		if (NULL === $joinPoint->getMethodArgument('attributeValue') || FALSE === $joinPoint->getMethodArgument('attributeValue')) {
			/** @var \TYPO3\Fluid\Core\ViewHelper\TagBuilder $tagBuilder */
			$tagBuilder = $joinPoint->getProxy();
			$tagBuilder->removeAttribute($joinPoint->getMethodArgument('attributeName'));
		} else {
			$joinPoint->getAdviceChain()->proceed($joinPoint);
		}
	}
}
