<?php
namespace M12\Foundation\Tests\Unit\TypoScriptObjects;

/*                                                                        *
 * This script belongs to the TYPO3 package "M12.Foundation".             *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Reflection\ObjectAccess;
use TYPO3\Flow\Tests\UnitTestCase;
use TYPO3\TypoScript\Core\Runtime;
use M12\Foundation\TypoScriptObjects\AttributesImplementation;

/**
 * Test case for the TypoScript Attributes object
 */
class AttributesImplementationTest extends UnitTestCase {

	/**
	 * @var Runtime
	 */
	protected $mockTsRuntime;

	public function setUp() {
		parent::setUp();
		$this->mockTsRuntime = $this->getMockBuilder('TYPO3\TypoScript\Core\Runtime')->disableOriginalConstructor()->getMock();
	}

	public function attributeExamples() {
		return array(
			'null' => array(NULL, '', []),
			'empty array' => array(array(), '', []),
			'boolean values' => array(array('booleanTrueAttribute' => TRUE, 'booleanFalseAttribute' => FALSE), ' booleanTrueAttribute', ['booleanTrueAttribute'=>TRUE]),
			'empty string value' => array(array('emptyStringAttribute' => ''), ' emptyStringAttribute', ['emptyStringAttribute'=>'']),
			'null value' => array(array('nullAttribute' => NULL), '', []),
			'simple array' => array(array('attributeName1' => 'attributeValue1'), ' attributeName1="attributeValue1"', ['attributeName1'=>'attributeValue1']),
			'encoding' => array(array('spec<ial' => 'chara>cters'), ' spec&lt;ial="chara&gt;cters"', ['spec<ial' => 'chara>cters']),
			'array attributes' => array(array('class' => array('icon', NULL, 'icon-neos', '')), ' class="icon icon-neos"', ['class' => 'icon icon-neos']),
			'empty attribute value without allowEmpty' => array(array('emptyStringAttribute' => '', '__meta' => array('allowEmpty' => FALSE)), ' emptyStringAttribute=""', ['emptyStringAttribute'=>'']),
		);
	}

	/**
	 * @test
	 * @dataProvider attributeExamples
	 */
	public function evaluateTests($properties, $expectedOutput, $expectedOutputAsArray) {
//		print_r(func_get_args());
		$path = 'attributes/test';
		$this->mockTsRuntime->expects($this->any())->method('evaluate')->will($this->returnCallback(function($evaluatePath, $that) use ($path, $properties) {
			$relativePath = str_replace($path . '/', '', $evaluatePath);
			return ObjectAccess::getPropertyPath($properties, str_replace('/', '.', $relativePath));
		}));

		$typoScriptObjectName = 'TYPO3.TypoScript:Attributes';
		$renderer = new AttributesImplementation($this->mockTsRuntime, $path, $typoScriptObjectName);
		
		if ($properties !== NULL) {
			foreach ($properties as $name => $value) {
				ObjectAccess::setProperty($renderer, $name, $value);
			}
		}

		$result = $renderer->evaluate();

		$this->assertInstanceOf('M12\Foundation\TypoScriptObjects\AttributesImplementation', $result);
		$this->assertTrue(is_string($result->getAsString()));
		$this->assertTrue(is_array($result->getAsArray()));
		
		$this->assertEquals($expectedOutput, $result);
		$this->assertEquals($expectedOutput, $result->getAsString());
		$this->assertEquals($expectedOutputAsArray, $result->getAsArray());
	}
}
