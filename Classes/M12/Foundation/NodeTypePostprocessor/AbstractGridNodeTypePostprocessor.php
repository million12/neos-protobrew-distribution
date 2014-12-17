<?php
namespace M12\Foundation\NodeTypePostprocessor;

/*                                                                        *
 * This script belongs to the M12.Foundation package                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Configuration\Exception;
use TYPO3\TYPO3CR\Domain\Model\NodeType;
use TYPO3\Flow\Configuration\ConfigurationManager;
use TYPO3\TYPO3CR\NodeTypePostprocessor\NodeTypePostprocessorInterface;

/**
 * This PostProcessor generates required grid properties.
 * 
 * Due to large amount of required properties it's easier to generate them here
 * instead of typing statically in NodeTypes.yaml.
 */
abstract class AbstractGridNodeTypePostprocessor implements NodeTypePostprocessorInterface {

	/**
	 * Settings section used to generate properties
	 * @var string
	 */
	protected static $SETTINGS_SECTION;

	/**
	 * @var ConfigurationManager
	 * @Flow\Inject
	 */
	protected $configurationManager;

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * M12.Foundation settings
	 *
	 * @param array $settings
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * Returns the processed $configuration with grid-related node properties
	 *
	 * @param \TYPO3\TYPO3CR\Domain\Model\NodeType $nodeType      (uninitialized) The node type to process
	 * @param array                                $configuration input configuration
	 * @param array                                $options       The processor options
	 * @return void
	 */
	public function process(NodeType $nodeType, array &$configuration, array $options) {
		$this->validateSettings();

		$k = 0;
		foreach ($this->settings[static::$SETTINGS_SECTION] as $set => $setData) {
			$propertyName = sprintf('classGrid%s', ucfirst($set));

			$editorValues = [];
			$defaultValue = isset($setData['defaults']) ? $setData['defaults'] : [''];
			
			/** @var string $device: small, medium, large */
			foreach ($this->settings['devices'] as $device => $deviceData) {
				$groupLabel = $deviceData['label'];
				$editorValues += $this->getEditorValues($set, $setData, $device, $groupLabel);
			}

			$configuration['properties'][$propertyName] = [
				'type' => 'array',
				'defaultValue' => $defaultValue,
				'ui' => [
					'label' => $setData['label'],
					'reloadIfChanged' => true,
					'inspector' => [
						'group' => $setData['uiInspectorGroup'],
						'position' => ($k+1)*10,
						'editor' => 'TYPO3.Neos/Inspector/Editors/SelectBoxEditor',
						'editorOptions' => [
							'multiple' => TRUE,
							'allowEmpty' => TRUE,
							'placeholder' => 'placeholder text...',
							'values' => $editorValues,
						],
					],
				],
			];
			
			$k++;
		}
		
//		\TYPO3\Flow\var_dump($configuration);
	}

	/**
	 * Generates SelectBoxEditor options
	 *
	 * @param string $device  eg: small, medium, large
	 * @param string $set     eg: size, offset, push, pull
	 * @param array  $setData settings for the $set
	 * @return array
	 */
	protected function getEditorValues($set, array $setData, $device, $groupLabel) {
		$groupLabel = $groupLabel ? $groupLabel : $device;
		$editorValues = [];

		$cssSuffixes = $this->settings[static::$SETTINGS_SECTION][$set]['cssClassSuffixes'];
		foreach ($cssSuffixes as $cssSuffix) {
			$cssClass = "$device$cssSuffix";

			//
			// with column number, eg: small-X, medium-offset-X
			//
			if (true === $setData['appliedPerColumn']) {
				$k   = $this->settings['gridSize'];
				$col = 1;
				do {
					$valueName = $cssClass.$col;
					$labelName = $valueName;
					$editorValues[$valueName] = array(
						'label' => $labelName,
						'group' => $groupLabel,
					);
					$col++;
				} while (--$k);
			}

			//
			// without column number, eg: small-reset-order, large-centered
			//
			else {
				$valueName = $cssClass;
				$labelName = $valueName;
				$editorValues[$valueName] = array(
					'label' => $labelName,
					'group' => $groupLabel,
				);
			}
		}

		return $editorValues;
	}

	protected function validateSettings() {
		if (false === isset(static::$SETTINGS_SECTION))
			throw new \TYPO3\Neos\Exception(get_called_class().'::$SETTINGS_SECTION needs to be defined.', 1396555463);

		if (empty($this->settings['devices']) || !is_array($this->settings['devices']))
			throw new \TYPO3\Neos\Exception('M12.Foundation.devices settings are missing. Please check Settings.yaml file!', 1396555463);

		if (empty($this->settings[static::$SETTINGS_SECTION]) || !is_array($this->settings[static::$SETTINGS_SECTION]))
			throw new \TYPO3\Neos\Exception('M12.Foundation.gridSettings settings are missing. Please check Settings.yaml file!', 1396555464);
	}
}
