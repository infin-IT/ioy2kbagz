<?php
/**
 * @version     1.0.0
 * @package     com_fooddisplay
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ratmil <ratmil_torres@yahoo.com> - http://www.ratmilwebsolutions.com
 */

defined('_JEXEC') or die;


$controller	= JControllerLegacy::getInstance('FoodDisplay');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
