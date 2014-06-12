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

jimport('joomla.application.component.controller');

class FoodDisplayController extends JControllerLegacy
{
	public function display()
	{
		$view = $this->getView( 'foodcategories', 'html' );
		$view->display();
	}
	
	function dor()
	{
		echo 'halo';
	}
}