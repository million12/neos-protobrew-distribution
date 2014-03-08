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
 * This Processor updates ...
 */
class ColumnNodeTypePostprocessor implements NodeTypePostprocessorInterface {

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
	 * M12.FoundationGrid settings
	 * @param array $settings
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * Reads settings from M12.Foundation and merges them into current $settings
	 */
	protected function includeParentSettings() {
		$parentSettings = $this->configurationManager->getConfiguration('Settings', 'M12.Foundation');
		if (is_array($parentSettings))
			$this->settings = array_merge($this->settings, $parentSettings);
	}

	/**
	 * Returns the processed Configuration
	 *
	 * @param \TYPO3\TYPO3CR\Domain\Model\NodeType $nodeType (uninitialized) The node type to process
	 * @param array $configuration input configuration
	 * @param array $options The processor options
	 * @return void
	 */
	public function process(NodeType $nodeType, array &$configuration, array $options) {
		$this->includeParentSettings();
//		\TYPO3\Flow\var_dump($this->settings);

		if (empty($this->settings['devices']) || !is_array($this->settings['devices']))
			return;

		foreach ($this->settings['devices'] as $device=>$ds) {
			// property name in format: classMD, classXS
			$configuration['properties'][$this->_getPropertyName($device)] = array(
				'type' => 'string',
				'defaultValue' => '',
				'ui' => array(
					'label' => sprintf('%s (%s device) %s', $ds['label'], $device, $ds['size']),
					'reloadIfChanged' => true,
					'inspector' => array(
						'group' => $this->settings['uiInspectorGroupName'],
						'editor' => 'TYPO3.Neos/Inspector/Editors/SelectBoxEditor',
						'editorOptions' => $this->getEditorOptions($device),
					),
				),
			);
		}

//		\TYPO3\Flow\var_dump($configuration);
	}


	/**
	 * Generates SelectBoxEditor options
	 * @param string $device
	 * @return array
	 */
	protected function getEditorOptions($device) {
		$k = $this->settings['gridSize'];
		$col = 1;

		$editorOptions = array();
		$editorOptions['placeholder'] = '- not set -';
		$editorOptions['values'][''] = array('label' => '');

		do {
			$val = $this->_getOptionValue($col, $device);
			$editorOptions['values'][$val] = array(
				'label' => $this->_getOptionLabel($col, $val),
			);
			$col++;
		} while (--$k);

		return $editorOptions;
	}

	/**
	 * Generates option label
	 * @param $col
	 * @param $cls
	 * @return string
	 */
	protected function _getOptionLabel($col, $cls) {
		return sprintf('%d/%d (.%s)', $col, $this->settings['gridSize'], $cls);
	}

	/**
	 * Generates options value (ie class name)
	 *
	 * @param int $col: column number (1...x)
	 * @param string $device: device names (md, xs etc)
	 * @return string
	 */
	protected function _getOptionValue($col, $device) {
		// e.g. small-6
		return "$device-$col";
	}

	/**
	 * Generates property name for node
	 *
	 * @param string $device: device names (md, xs etc)
	 * @return string	Property name in format 'classMD', 'classXS' etc.
	 */
	protected function _getPropertyName($device) {
		// e.g. classMD, classXS
		return 'class'.strtoupper($device);
	}
}
