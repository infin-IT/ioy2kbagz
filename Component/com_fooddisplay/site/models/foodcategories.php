<?php

defined('_JEXEC') or die("Restricted Access");

jimport('joomla.application.component.modelitem');

class FoodDisplayModelFoodCategories extends JModelItem
{
	protected $Messagezz;
	
	public function getMessagezz()
	{
		$this->Messagezz= "Food Categories5555";
		return $this->Messagezz;
	}
}