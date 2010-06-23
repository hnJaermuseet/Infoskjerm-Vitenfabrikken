<?php

/*
Infoskjerm-Vitenfabrikken
Copyright (C) 2008-2010  Jaermuseet <http://www.jaermuseet.no>
Contact: <hn@jaermuseet.no> 
Project: <http://github.com/hnJaermuseet/Infoskjerm-Vitenfabrikken>

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

require "configs/infoskjerm.php";
require "libs/editor.class.php";

// Edit?

$id = 0;
if(isset($_GET['skjerm_id']) && is_numeric($_GET['skjerm_id']))
	$id = (int)$_GET['skjerm_id'];
if(isset($_POST['skjerm_id']) && is_numeric($_POST['skjerm_id']))
	$id = (int)$_POST['skjerm_id'];
if(isset($_POST['id']) && is_numeric($_POST['id']))
	$id = (int)$_POST['id'];

$redirect1 = '';
$redirect2 = '';
$redirect3 = '';
$redirect = '';
if(isset($_GET['redirect1'])) {
	switch ($_GET['redirect1'])
	{
		case 'skjerm':
			if(isset($_GET['redirect2']) && is_numeric($_GET['redirect2']) && isset($_GET['redirect3']))
			{
				$redirect1 = 'skjerm';
				$redirect2 = $_GET['redirect2'];
				$redirect3 = $_GET['redirect3'];
				$redirect	= 'skjerm.php?skjerm_id='.$redirect2.'&dato='.$_GET['redirect3'];
			}
			break;
	}
}

$redirecting = '';
if($redirect1 != '') {
	$redirecting = '?redirect1='.$redirect1;
	if($redirect2 != '')
		$redirecting .= '&amp;redirect2='.$redirect2;
	if($redirect3 != '')
		$redirecting .= '&amp;redirect3='.$redirect3;
}

if($id <= 0)
{
	$editor = new editor('skjermer', $_SERVER['PHP_SELF'].$redirecting);
	$editor->setHeading('Opprett skjerm');
	$editor->setSubmitTxt('Opprett');
	$smarty->assign('tittel2', 'Opprett skjerm');
}
else
{
	$editor = new editor('skjermer', $_SERVER['PHP_SELF'].$redirecting, $id);
	$editor->setHeading('Endre skjerm');
	$editor->setSubmitTxt('Endre');
	$smarty->assign('tittel2', 'Endre skjerm');
}


$editor->setDBFieldID('skjerm_id');
$editor->showID (TRUE);


## SKJERM_NAVN
$editor->makeNewField('skjerm_navn', 'Navn på skjerm', 'text');

## SKJERM_SLIDE_NR
$editor->makeNewField('skjerm_slidenr', 'Antall slides', 'text');

## SKJERM_DEFAULTSLIDE_HEADING
$editor->makeNewField('skjerm_defaultslide_heading', 'Overskrift på skjerm', 'text');
$editor->vars['skjerm_defaultslide_heading']['desc'] = 'Sett inn &lt;br&gt; for linjeskift (andre HTML-tags er også gyldige).';

## SKJERM_DEFAULTSLIDE
$editor->makeNewField('skjerm_defaultslide', 'Innhold', 'textarea', array(
		'rows' => 30,
		'cols' => 135
	));

$editor->getDB();

if(isset($_POST['editor_submit']))
{
	if($editor->input($_POST))
	{
		if($editor->performDBquery())
		{
			// Redirect
			if($redirect != '')
				header('Location: '.$redirect);
			else
				header('Location: skjermer.php');
			exit();
		}
		else
		{
			echo 'Error occured while performing query on database:<br>'.chr(10),
			//echo '<b>Error:</b> '.$editor->error();
			exit();
		}
	}
}

$smarty->display('header.tpl');

echo '<!-- TinyMCE -->
<script type="text/javascript" src="libs/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,code,|,insertdate,inserttime,preview,|,forecolor,backcolor,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,advhr,|,fullscreen",
		theme_advanced_buttons3 : "tablecontrols,|,insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,del,ins,attribs,|,visualchars,nonbreaking,template",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : false,

		// Example content CSS (should be your site CSS)
		content_css : "css/vis.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>
<!-- /TinyMCE -->';

$editor->fields_before .= "\t<tr>\n\t\t<td>&nbsp;</td>\n\t\t<td><input type=\"submit\" value=\"".$editor->submit_txt."\"></td>\n\t</tr>\n".
"\t<tr>\n\t\t<td colspan=\"2\">&nbsp;</td>\n\t</tr>\n\n";
$editor->printEditor();

$smarty->display('footer.tpl');