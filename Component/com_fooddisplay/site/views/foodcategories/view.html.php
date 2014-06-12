<?php
/**
 * @version     1.0
 * @package     com_fooddisplay
 * @copyright  2014
 * @license
 * @author      infinIT
 */

// No direct access
defined('_JEXEC') or die("Restricted Access");

jimport('joomla.application.component.view');

class FoodDisplayViewFoodCategories extends JViewLegacy
{
	function display($tpl = NULL) {
		$this->msg = $this->get('Messagezz');
		
		if (count($errors = $this->get('Errors')))
		{
			JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
			return false;
		}
		
		parent::display($tpl);
	}
}