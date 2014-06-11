<?php
/**
 * @version     1.0
 * @package     com_fooddisplay
 * @copyright  2014
 * @license     
 * @author      infinIT
 */

// No direct access
defined('_JEXEC') or die;

/**
 * View class for About page
 */
class FoodDisplayViewAbout extends JViewLegacy
{
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors));
		}
        
		$this->addToolbar();
        
        $input = JFactory::getApplication()->input;
        $view = $input->getCmd('view', '');
        FoodDisplayHelper::addSubmenu($view);
        
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.'/helpers/fooddisplay.php';

		$user = JFactory::getUser();
		JToolBarHelper::title(JText::_('COM_FOODDISPLAY_ABOUT'), 'about.png');
		if ($user->authorise('core.admin', 'com_fooddisplay')) {
			JToolBarHelper::preferences('com_fooddisplay');
		}
	}
}
