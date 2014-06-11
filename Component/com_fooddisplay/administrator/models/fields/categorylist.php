<?php
/**
 * @version     1.0
 * @package     com_fooddisplay
 * @copyright  2014
 * @license     
 * @author      infinIT
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

/**
 * Supports an HTML select list of categories
 */
class JFormFieldCategoryList extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'categorylist';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		// Initialize variables.
		$html = "<select name=\"" . $this->name . "\" id=\"" . $this->name . "\">";
		if($this->element["firstitem"])
		{
			$html .= "<option value=\"\">" . htmlspecialchars(JText::_($this->element["firstitem"])) . "</option>";
		}
		$extension = $this->element['extension'];	
		$db = JFactory::getDBO();
		$extension = $db->escape($extension);
		$query = "SELECT id, parent_id, title FROM #__categories WHERE published = 1 AND extension='$extension'";
		$db->setQuery($query);
		$list = $db->loadObjectList();
		$cats_ordered = array();
		$this->reorderCats($cats_ordered, $list, 1, 0);
		if($list)
		{
			foreach($list as $item)
				{
					$selected = ($item->id == $this->value) ? "selected" : "";
					$html .= "<option value=\"" . htmlspecialchars($item->id) . "\" $selected>";
					$space = '';
					for($i = 0; $i < $item->depth; $i++)
						$space .= '-&nbsp;';
					$html .= $space . htmlspecialchars($item->title);
					$html .= "</option>";
				}
		}
		$html .= "</select>";
		return $html;
	}
	
	private function reorderCats(&$cats_ordered, $cats, $parent_id, $depth)
	{
		$count = count($cats);
		for($i = 0; $i < $count; $i++)
		{
			$cat = $cats[$i];
			if($cat->parent_id == $parent_id)
			{
				$cat->depth = $depth;
				$cats_ordered[] = $cat;
				$this->reorderCats($cats_ordered, $cats, $cat->id, $depth + 1);
			}
		}
	}
}