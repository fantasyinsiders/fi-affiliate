<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| COPYRIGHT NOTICE                                                     
| Copyright 2007 - 2013 JROX Technologies, Inc.  All Rights Reserved.  
| -------------------------------------------------------------------------                                                                        
| This script may be only used and modified in accordance to the license      
| agreement attached (license.txt) except where expressly noted within      
| commented areas of the code body. This copyright notice and the  
| comments above and below must remain intact at all times.  By using this 
| code you agree to indemnify JROX Technologies, Inc, its corporate agents   
| and affiliates from any liability that might arise from its use.                                                        
|                                                                           
| Selling the code for this program without prior written consent is       
| expressly forbidden and in violation of Domestic and International 
| copyright laws.  
|	
| -------------------------------------------------------------------------
| FILENAME - editor_pi.php
| -------------------------------------------------------------------------     
| 
| This plugin loads the JavaScript HTML editor
|
*/


function encodeHTML($sHTML)
{
	$sHTML=str_replace("&","&amp;",$sHTML);
	$sHTML=str_replace("<","&lt;",$sHTML);
	$sHTML=str_replace(">","&gt;",$sHTML);
	
	return $sHTML;
}

function HTML_Editor($instance = 'oEdit1', $type = '1', $toolbar = 'basic', $content = '', $textarea = 'txtContent', $height = '300px', $width = '100%', $dynamic_tags = true)
{
	
	$CI =& get_instance();
	
	//load javascript
	
	if ($type == '1')
	{
		
		$data = '<textarea id="' . $textarea . '" name="' . $textarea . '" rows=4 cols=30>' . encodeHTML($content) . '</textarea>
				<script>
				var ' . $instance . ' = new InnovaEditor("' . $instance . '");
				' . $instance . '.mode="XHTMLBody";
				' . $instance . '.fileBrowser ="' . base_url() . 'js/assetmanager/asset.php";
				' . $instance . '.width="' . $width . '";
				' . $instance . '.height="' . $height . '";
				' . $instance . '.returnKeyMode = 3; 
				' . $instance . '.enableLightbox = false;
				' . $instance . '.css="' . base_url() . 'themes/main/editor_style.css";';
				
		switch ($toolbar)
		{
			
			
			case 'full':
			
				 $data .= $instance . '.groups = [
					["group1", "", ["Bold", "Italic", "Underline", "FontName", "FontSize", "ForeColor", "FontDialog", "TextDialog", "RemoveFormat"]],
					["group2", "", ["JustifyLeft", "JustifyCenter", "JustifyRight", "Paragraph", "Bullets", "Numbering", "Quote"]],
					["group3", "", ["Table","TableDialog", "Emoticons", "LinkDialog", "ImageDialog", "YoutubeDialog"]],
					["group4", "", ["Undo", "Redo", "Line", "SourceDialog", "FullScreen"]]
					];';


			
			break;
			
			case 'pdf':
				$data .= $instance . '.groups = [
                ["group1", "", ["Bold", "Italic", "Underline", "TextDialog", "RemoveFormat"]],
                ["group2", "", ["Bullets", "Numbering", "JustifyLeft", "JustifyCenter", "JustifyRight"]]
                ];';
			break;
			
			case 'basic':
				$data .= $instance . '.groups = [
                ["group1", "", ["Bold", "Italic", "Underline", "TextDialog", "RemoveFormat"]],
                ["group2", "", ["Bullets", "Numbering", "LinkDialog", "JustifyLeft", "JustifyCenter", "JustifyRight"]],
				["group3", "", ["Undo", "Redo", "SourceDialog"]]
                ];';
			break;
			
			default:
			
				$data .= $instance . '.groups = [
                ["group1", "", ["Bold", "Italic", "Underline", "FontDialog", "ForeColor", "TextDialog", "RemoveFormat"]],
                ["group2", "", ["Bullets", "Numbering", "JustifyLeft", "JustifyCenter", "JustifyRight"]],
                ["group3", "", ["LinkDialog", "ImageDialog", "TableDialog", "Emoticons"]],
                ["group4", "", ["Undo", "Redo", "FullScreen", "SourceDialog"]]
                ];';
			
			break;
		}
		
				
		$data .= $instance . '.REPLACE("' . $textarea . '");
				</script>';
			
	}
	else
	{
		$data = '<div align="center"><textarea id="' . $textarea . '" name="' . $textarea . '" rows=20 cols=80 class="form-control required">' . $content . '</textarea></div>';
	}
	
	return $data;

}

?>