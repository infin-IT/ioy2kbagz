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
 * FoodDisplay helper.
 */
class FoodDisplayHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '')
	{
	}
	
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_fooddisplay';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
	
	private static function isAllowedExtension($filename, $allowed = null)
	{
		$ext = "";
		$dotpos = strrpos($filename, ".");
		if($dotpos !== false)
		{
			$ext = substr($filename, $dotpos + 1);
			$ext = trim(strtolower($ext));
		}
		if($allowed == null)
			$allowed = "zip csv xlsx docx doc xls";
		$allowed_ext = explode(" ", $allowed);
		foreach($allowed_ext as $e)
		{
			if($ext == trim(strtolower($e)))
				return true;
		}
		JFactory::getApplication()->enqueueMessage(
			JText::_("COM_FOODDISPLAY_INVALID_EXTENSION"), "error");
		return false;
	}
	
	private static function createImageThumb($image, $thumbwidth, $thumbheight)
	{
		if(function_exists("getimagesize"))
		{
			$img = null;
			$size = getimagesize($image);
			switch($size[2])
			{
			case 1: //gif
				$img = imagecreatefromgif($image);
				break;
			case 2: //jpg
				$img = imagecreatefromjpeg($image);
				break;
			case 3: //png
				$img = imagecreatefrompng($image);
				break;
			case 6: //bmp
				$img = imagecreatefromwbmp($image);
				break;
			default: 
				return false;
			}
			$nw = $width = (int)$size[0];
			$nh = $height = (int)$size[1];
			$startx = 0;
			$starty = 0;
			if($width > $height)
			{
				$nw = $thumbwidth;
				$nh = ($height * $nw) / $width;
				$starty = ($thumbheight - $nh) / 2;
			}
			else
			{
				$nh = $thumbheight;
				$nw = ($nh * $width) / $height;
				$startx = ($thumbwidth - $nw) / 2;
			}
			$dest = imagecreatetruecolor($thumbwidth, $thumbheight);
			$icon = imagecreatetruecolor(32, 32); 
			$background = imagecolorallocate($dest, 255, 13, 252);
			imagecolortransparent ($dest, $background);
			imagefilledrectangle($dest, 0, 0, $thumbwidth - 1, $thumbheight - 1, $background);
			imagecolortransparent ($icon, $background);
			imagefilledrectangle($icon, 0, 0, 31, 31, $background);
			imagecopyresized($dest, $img, $startx, $starty, 0, 0, $nw, $nh, $width, $height);
			imagecopyresized($icon, $dest, 0, 0, 0, 0, 32, 32, $thumbwidth, $thumbheight);
			imagepng($dest, $image . "_small.jpg");
			imagepng($icon, $image . "_icon.png");
			imagedestroy($img);
			imagedestroy($dest);
			imagedestroy($icon);
		}
	}
	
	private static function randomizeFileName($filename)
	{
		$ext = "";
		$dotpos = strrpos($filename, ".");
		if($dotpos !== false)
		{
			$ext = substr($filename, $dotpos + 1);
			$filename = substr($filename, 0, $dotpos);
		}
		for($i = 0; $i < 10; $i++)
			$filename .= rand(0, 9);
		return $filename . "." . $ext;
	}
	
	public static function uploadFileFromJForm($dest_folder, $input, &$data, 
		$allowedExtensions = null, $thumbWidth = null, $thumbHeight = null)
	{
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		if(!JFolder::exists(JPATH_ROOT . "/" . $dest_folder))
		{
			JFolder::create(JPATH_ROOT . "/" . $dest_folder);
		}
		if(JRequest::getInt("jform_" . $input . "_remove"))
		{
			$data[$input] = '';
			return true;
		}
		if($_FILES['jform']['name'][$input])
		{
			if (is_uploaded_file($_FILES['jform']['tmp_name'][$input]))
			{
				$fileName = JFile::makeSafe($_FILES['jform']['name'][$input]);
				$fileName = FoodDisplayHelper::randomizeFileName($fileName);
				if(FoodDisplayHelper::isAllowedExtension($fileName, $allowedExtensions))
				{
					if(!JFile::upload($_FILES['jform']['tmp_name'][$input], 
						JPATH_ROOT . "/" . $dest_folder . "/" . $fileName))
					{
						return false ;
					}
					$data[$input] = $dest_folder . "/" . $fileName;
					if($thumbWidth && $thumbHeight)
						FoodDisplayHelper::createImageThumb(JPATH_ROOT . "/" . $data[$input],
							$thumbWidth, $thumbHeight);
				}
				else
				{
					return false ;
				}
			}
		}
		return true;
	}
	
	public static function getMultipleOptionsValue($id)
	{
		$count = JRequest::getInt("jform_" . $id . "_count");
		$sum = 0;
		for($i = 0; $i < $count; $i++)
		{
			$checked = JRequest::getInt("jform_" . $id . "_checks_" . $i);
			if($checked)
				$sum += (1 << $i);
		}
		return $sum;
	}
	
	public static function saveForeignFields($parentId, $parentField, $table, $foreign_field, $checks)
	{
		$parentId = (int)$parentId;
		$db = JFactory::getDBO();
		$query = "DELETE FROM " . $db->quoteName($table) . " WHERE " . 
			$db->quoteName($parentField) . " = " . $parentId;
		$db->setQuery( $query );
		$db->query();
		foreach($checks as $check)
		{
			$value = (int)$check;
			$query = "INSERT INTO " . $db->quoteName($table) . "(" . 
				$db->quoteName($parentField) .", " . $db->quoteName($foreign_field) . ") VALUES($parentId, $value)";
			$db->setQuery( $query );
			$db->query();
		}
	}
}
