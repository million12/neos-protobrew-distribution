<?php
namespace M12\Foundation\TypoScript;

/*                                                                        *
 * This script belongs to the "M12.Foundation" package.                   *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\TypoScript\TypoScriptObjects\AttributesImplementation as NeosAttributesImplementation;
use TYPO3\Flow\Annotations as Flow;

/**
 * Renders a string of xml attributes from the properties of this TypoScript object.
 * So a configuration like:
 *
 * attributes = TYPO3.TypoScript:Attributes
 * attributes.class = TYPO3.TypoScript:RawArray {
 *  class1: 'class1'
 *  class2: 'class2'
 * }
 * attributes.id = 'my-id'
 *
 * will result in the string: class="class1 class2" id="my-id"
 */
class AttributesImplementation extends NeosAttributesImplementation {

	/**
	 * @var array
	 */
	protected $attributes;

	/**
	 * @var string
	 */
	protected $renderedAttributes;

	/**
	 * @return string
	 */
	public function evaluate() {
		$attributes = array();
		$renderedAttributes = '';
		foreach (array_keys($this->properties) as $attributeName) {
			$encodedAttributeName = htmlspecialchars($attributeName, ENT_COMPAT, 'UTF-8', FALSE);
			$attributeValue = $this->tsValue($attributeName);

			if (null === $attributeValue) {
				continue;
			} else if (is_string($attributeValue) && 0 === strlen($attributeValue)) {
				$attributes[$attributeName] = $attributeValue;
				$renderedAttributes .= ' ' . $encodedAttributeName;
			} else {
				if (is_array($attributeValue)) {
					$attributeValue = implode(' ', $attributeValue);
				}
				$encodedAttributeValue = htmlspecialchars($attributeValue, ENT_COMPAT, 'UTF-8', FALSE);

				$attributes[$attributeName] = $attributeValue;
				$renderedAttributes .= ' ' . $encodedAttributeName . '="' . $encodedAttributeValue . '"';
			}
		}

		$this->attributes = $attributes;
		$this->renderedAttributes = $renderedAttributes;

		return $this;
	}

	public function getAsString() {
		return $this->renderedAttributes;
	}

	public function getAsArray() {
		return $this->attributes;
	}

	public function __toString() {
		return $this->renderedAttributes;
	}
}
