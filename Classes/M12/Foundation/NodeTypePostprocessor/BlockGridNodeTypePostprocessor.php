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

class BlockGridNodeTypePostprocessor extends AbstractGridNodeTypePostprocessor {

	/**
	 * Settings section used to generate block grid properties
	 * @see Settings.yaml
	 * @var string
	 */
	protected static $SETTINGS_SECTION = 'blockGridSettings';
}
