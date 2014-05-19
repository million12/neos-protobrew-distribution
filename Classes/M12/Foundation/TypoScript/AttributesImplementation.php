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
 * {@inheritdoc}
 *
 * Additionally, we change the behaviour slightly so attributes
 * with NULL values are not printed at all.
 */
class AttributesImplementation extends NeosAttributesImplementation {

	/**
	 * Key name under which custom user attributes might be available
	 */
	const CUSTOM_USER_ATTRIBUTES_KEY = 'customUserAttributes';

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
		$this->parseCustomUserAttributes();

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

	/**
	 * Get customUserAttributes, parse them (one attribute per line in format attribute=value)
	 * and inject them to internal TS values, so they can be fetched in evaluate() method.
	 */
	protected function parseCustomUserAttributes() {
		if (($value = trim($this->tsValue(static::CUSTOM_USER_ATTRIBUTES_KEY)))) {
			$customUserAttributes = explode(chr(10), $value);
			foreach ($customUserAttributes as $line) {
				if (empty($line)) continue;

				$attributes = explode('=', $line);
				$attributeName = trim(array_shift($attributes));
				$attributeValue = trim(array_shift($attributes)); // support empty attributes too

				// First: mark existence of custom attribute
				// Second: add it to internal tsValueCache[], so $this->tsValue() can fetch it without evaluating.
				$this->properties[$attributeName] = $attributeValue;
				$this->tsValueCache[$this->path.'/'.$attributeName] = $attributeValue;
			}

			// unset original custom attribute
			unset($this->properties[static::CUSTOM_USER_ATTRIBUTES_KEY]);
		}
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
