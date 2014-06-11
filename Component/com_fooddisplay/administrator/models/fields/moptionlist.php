<?php
/**
 * @version     1.0
 * @package     com_newtest25
 * @copyright  
 * @license     GNU
 * @author      Ratmil
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

/**
 * Supports an HTML select list of categories
 */
class JFormFieldMOptionList extends JFormField
{
    /**
     * The form field type.
     *
     * @var     string
     * @since   1.6
     */
    protected $type = 'MOptionList';

    /**
     * Method to get the field input markup.
     *
     * @return  string  The field input markup.
     * @since   1.6
     */
    protected function getInput()
    {
        $html = "<div style=\"float: left; border: 1px solid rgb(204, 204, 204); padding: 5px; margin: 5px; border-radius: 3px 3px 3px 3px;\"><ul style=\"list-style-type: none;list-style-position: oside; margin: 0px;\">";
        $i = 0;
        $html .= "<input type=\"hidden\" name=\"" . $this->id . "_count\" value=\"" . count($this->element->children()) . "\"/>";
        foreach ($this->element->children() as $option)
        {
            $checked = "";
            if(($this->value & (1 << $i)) > 0)
                $checked = " checked=\"checked\" ";
            $html .= "<li style=\"clear:both;\"><input type=\"checkbox\" name=\"" . $this->id . "_checks_" . $i . "\" value=\"1\"";
            $html .= $checked;
            $html .= "/>&nbsp;&nbsp;&nbsp;";
            $html .= htmlspecialchars( JText::_( (string)$option )) . "</li>";
            $i++;
        }
        $html .= "</ul></div>";
        return $html;
    }
    
    
}