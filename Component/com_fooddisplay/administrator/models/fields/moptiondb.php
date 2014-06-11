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
class JFormFieldMoptiondb extends JFormField
{
    /**
     * The form field type.
     *
     * @var     string
     * @since   1.6
     */
    protected $type = 'Moptiondb';

    /**
     * Method to get the field input markup.
     *
     * @return  string  The field input markup.
     * @since   1.6
     */
    protected function getInput()
    {
        // Initialize variables.
        $id = JRequest::getInt('id', 0);
        $table = $this->element["table"];
        $display = $this->element["display_field"];
        $rel_table = $this->element["rel_table"];
        $key_field = $this->element["key_field"];
        $parent_key = $this->element["parent_key"];
        $foreign_key = $this->element["foreign_key"];
        
        $html = "<div style=\"float: left; border: 1px solid silver; padding: 5px; margin: 5px; border-radius: 3px 3px 3px 3px;\"><ul style=\"list-style-type: none;list-style-position: oside; margin: 0px;\">";
        $i = 0;
        $html .= "<input type=\"hidden\" name=\"" . $this->id . "_count\" value=\"" . count($this->element->children()) . "\"/>";
        $items = $this->getItems($table, $key_field, $display, 
            $rel_table, $parent_key, $foreign_key, $id);
        foreach ($items as $item)
        {
            $checked = "";
            if($item->checked)
                $checked = " checked=\"checked\" ";
            $html .= "<li style=\"clear:both\"><input type=\"checkbox\" name=\"" . $this->id . "_checks[]" . "\" ";
            $html .= "value=\"" . htmlspecialchars($item->value) . "\"";
            $html .= $checked;
            $html .= "/>&nbsp;&nbsp;&nbsp;";
            $html .= htmlspecialchars( $item->display ) . "</li>";
        }
        $html .= "</ul></div>";
        return $html;
    }
    
    private function getItems($table, $key_field, $display_field, 
        $rel_table, $parent_key, $foreign_key, $parent_value)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery( true );
        $query->select($table . "." . $key_field . " AS value");
        $query->select($table . "." . $display_field . " AS display");
        $query->select($rel_table . "." . $foreign_key . " AS checked");
        $query->from($table);
        $query->join('LEFT', 
            $rel_table . " ON " . $rel_table . "." . $foreign_key . 
                " = " . $table . "." . $key_field . 
                " AND " . $rel_table . "." . $parent_key . " = " . 
                (int)$parent_value);
        $db->setQuery( $query );
        return $db->loadObjectList();
    }
    
}