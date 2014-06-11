<?php
/**
 * @version     1.0
 * @package     com_fooddisplay
 * @copyright  2014
 * @license     
 * @author      infinIT
 */


// no direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_fooddisplay')) 
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}



$controller	= JControllerLegacy::getInstance('FoodDisplay');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();