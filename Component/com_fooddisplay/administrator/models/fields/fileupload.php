<?php
/**
 * @version     1.0
 * @package     com_test30
 * @copyright  GNU
 * @license     GPL
 * @author      Ratmil
 */

defined('JPATH_BASE') or die;

/**
 * Supports an HTML select list of categories
 */
class JFormFieldFileupload extends JFormField
{
	public $type = 'fileupload';
	
	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		// Initialize some field attributes.
		$accept = $this->element['accept'] ? ' accept="' . (string) $this->element['accept'] . '"' : '';
		$size = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';

		// Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';
		
		$html = "";
		$html .= '<input type="file" name="' . $this->name . '" id="' . $this->id . '"' . ' value=""' . $accept . $disabled . $class . $size
			. $onchange . ' />';
		if($this->value)
		{
			$html .= "&nbsp;&nbsp;&nbsp;" . htmlspecialchars(JText::_("COM_FOODDISPLAY_REMOVE")) . 
				'&nbsp;&nbsp;&nbsp;<input type="checkbox" name="' . $this->id . '_remove" value="1" />';
		}
		
		$html .= "<br/>";
		
		if(isset($this->element['image']) && $this->element['image'] == 'true')
		{
			$html .= "<img src=\"" . JUri::root() . htmlspecialchars($this->value) . "_small.jpg\"/>";
		}
		$html .= "<div>" . htmlspecialchars($this->value) . "</div>";
		return $html;
	}
	
}
?>