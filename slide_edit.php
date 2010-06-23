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

// Expand editor
class editor2 extends editor {
	function processInput_Date ($var, $input) {
		return behandleDato ($input, array ('y', 'm', 'd', 'h', 'i'));
	}
	
	function checkInput_Date ($var) {
		return true;
	}
	
	function printDato($string)
	{
		if($string == 0)
			return '0';
		else
			return date('Y-m-d H:i', $string);
	}
}

function splittIDs ($input)
{
	$input = explode(';', $input);
	$return_array = array();
	foreach ($input as $id)
	{
		if($id != '' && is_numeric($id))
		{
			$return_array[$id] = $id;
		}
		elseif($id != '' && strpos($id, '=') !== FALSE)
		{
			list($id2, $value) = explode('=', $id, 2);
			$return_array[$id2] = $value;
		}
		elseif($id != '') {
			$return_array[] = $id;
		}
	}
	return $return_array;
}

// Edit?

$id = 0;
if(isset($_GET['slide_id']) && is_numeric($_GET['slide_id']))
	$id = (int)$_GET['slide_id'];
if(isset($_POST['slide_id']) && is_numeric($_POST['slide_id']))
	$id = (int)$_POST['slide_id'];
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
	$editor = new editor2('slides', $_SERVER['PHP_SELF'].$redirecting);
	$editor->setHeading('Opprett slide');
	$editor->setSubmitTxt('Opprett');
	$smarty->assign('tittel2', 'Opprett slide');
}
else
{
	$editor = new editor2('slides', $_SERVER['PHP_SELF'].$redirecting, $id);
	$editor->setHeading('Endre slide');
	$editor->setSubmitTxt('Endre');
	$smarty->assign('tittel2', 'Endre slide');
}

$slide_navn	= '';
$slide_pri		= '';
$skjerm_id	= 0;
$slide_til		= 0;
$slide_fra		= 0;
$slide_nr		= 1;
$slide_innhold_heading	= '';
$slide_innhold				= '';

if(isset($_GET['slide_id_fra']) || is_numeric($_GET['slide_id_fra']))
{
	// Ny fra en annen slide
	$Q_slide = mysql_query("select * from `slides` where `slide_id` = '".(int)$_GET['slide_id_fra']."'");
	if(!mysql_num_rows($Q_slide))
	{
		echo 'Fant ikke sliden du ville endre.';
		exit();
	}
	$slide = mysql_fetch_assoc($Q_slide);
	$slide_navn = $slide['slide_navn'];
	$slide_pri = $slide['slide_pri'] + 1;
	echo $slide['skjerm_id'];
	$skjerm_id = splittIDs ($slide['skjerm_id']);
	print_r($skjerm_id);
	if(isset($_GET['dato']) && is_numeric($_GET['dato']) && $_GET['dato'] > 0)
	{
		$slide_fra = mktime(0, 0, 0, date('m', $_GET['dato']), date('d', $_GET['dato']), date('Y', $_GET['dato']));
		$slide_til = mktime(23, 59, 59, date('m', $_GET['dato']), date('d', $_GET['dato']), date('Y', $_GET['dato']));
	} else {
		$slide_fra = $slide['slide_fra'];
		$slide_til = $slide['slide_til'];
	}
	$slide_nr = splittIDs ($slide['slide_nr']);
	$slide_innhold_heading = $slide['slide_innhold_heading'];
	$slide_innhold = $slide['slide_innhold'];
}
else
{
	if(isset($_GET['skjerm_id']) && is_numeric($_GET['skjerm_id']))
		$skjerm_id = (int)$_GET['skjerm_id'];
	
	if(isset($_GET['slide_nr']) && is_numeric($_GET['slide_nr']) && $skjerm_id != 0) {
		$slide_nr = $skjerm_id.','.(int)$_GET['slide_nr'];
	}
	/*if($skjerm_id > 0 && $slide_nr > 0)
	{
		$Q_slides = mysql_query("select slide_pri from `slides` where 
			skjerm_id = '".$skjerm['skjerm_id']."' and 
			slide_id='".$i."' and
			(
				slide_fra <= '".$slide_fra."' and
				slide_fra >= '".$slide_fra."'
			) or (
				slide_til <= '".$slide_fra."' and
				slide_til >= '".$slide_til."'
			) or 
		order by slide_pri desc");
		if(mysql_num_rows($Q_slides))
			$slide_pri = mysql_result($Q_slides, 0, 'slide_pri');
	}*/
	//if(isset(
	if(isset($_GET['dato']) && is_numeric($_GET['dato']) && $_GET['dato'] > 0)
	{
		$slide_fra = mktime(0, 0, 0, date('m', $_GET['dato']), date('d', $_GET['dato']), date('Y', $_GET['dato']));
		$slide_til = mktime(23, 59, 59, date('m', $_GET['dato']), date('d', $_GET['dato']), date('Y', $_GET['dato']));
	}
}


$editor->setDBFieldID('slide_id');
$editor->showID (TRUE);

## SLIDE_INNHOLD_HEADING
$editor->makeNewField('slide_innhold_heading', 'Overskrift på skjerm', 'text', array('defaultValue' => $slide_innhold_heading));
$editor->vars['slide_innhold_heading']['desc'] = 'Sett inn &lt;br&gt; for linjeskift (andre HTML-tags er også gyldige).';

## SLIDE_INNHOLD
$editor->makeNewField('slide_innhold', 'Innhold', 'textarea', array(
		'defaultValue' => $slide_innhold,
		'rows' => 30,
		'cols' => 135,
		'id' => 'slide_innhold'
	));


## SLIDE_NAVN
if($slide_navn != '')
	$editor->makeNewField('slide_navn', 'Internt navn', 'text', array('defaultValue' => $slide_navn));
else
	$editor->makeNewField('slide_navn', 'Internt navn', 'text');

## SLIDE_FRA
$editor->makeNewField(
	'slide_fra', 
	'Kjøres fra', 
	'text', 
	array(
		'defaultValue' => $slide_fra
	)
);
$editor->vars['slide_fra']['desc'] = 'Sett inn 0 for evigvarende ellers sett inn Y-m-d t:m';
$editor->vars['slide_fra']['checker'] = 'Date';
$editor->vars['slide_fra']['processor'] = 'Date';


## SLIDE_TIL
$editor->makeNewField(
	'slide_til', 
	'Kjøres til', 
	'text', 
	array(
		'defaultValue' => $slide_til
	)
);
$editor->vars['slide_til']['desc'] = 'Sett inn 0 for evigvarende ellers sett inn Y-m-d t:m';
$editor->vars['slide_til']['checker'] = 'Date';
$editor->vars['slide_til']['processor'] = 'Date';

## SLIDE_PRI
$editor->makeNewField(
	'slide_pri', 
	'Prioritet', 
	'text', 
	array(
		'defaultValue' => $slide_pri,
	)
);
$editor->vars['slide_pri']['desc'] = 'Den med høyest tall på et tidspunkt vil bli kjørt.';

## SKJERM_ID && SLIDE_NR
if($skjerm_id > 0)
{
	if(!is_array($skjerm_id))
		$editor->makeNewField('skjerm_id', 'Skjerm', 'checkbox', array('defaultValueArray' => array($skjerm_id)));
	else
		$editor->makeNewField('skjerm_id', 'Skjerm', 'checkbox', array('defaultValueArray' => $skjerm_id));
}
else
	$editor->makeNewField('skjerm_id', 'Skjerm', 'checkbox');

if(!is_array($slide_nr))
	$editor->makeNewField('slide_nr', 'Skjermbildenr', 'checkbox', array('defaultValueArray' => array($slide_nr)));
else
	$editor->makeNewField('slide_nr', 'Skjermbildenr', 'checkbox', array('defaultValueArray' => $slide_nr));

$Q_area = mysql_query("select skjerm_id, skjerm_navn, skjerm_slidenr from `skjermer` order by `skjerm_navn`");
while($R_area = mysql_fetch_assoc($Q_area)) {
	$editor->addChoice(
		'skjerm_id', 
		$R_area['skjerm_id'], 
		$R_area['skjerm_navn'],
		array('onchange' => 'onChangeSkjermid(\''.$R_area['skjerm_id'].'\')')
	);
	for ($i = 1; $i <= $R_area['skjerm_slidenr']; $i++)
		$editor->addChoice(
			'slide_nr', 
			$R_area['skjerm_id'].','.$i, 
			$R_area['skjerm_navn'] .' - Skjermbilde '.$i,
			array('onchange' => 'onChangeSlidenr()')
		);
}

$editor->getDB();

$editor->vars['slide_fra']['value'] = $editor->printDato($editor->vars['slide_fra']['value']);
$editor->vars['slide_til']['value'] = $editor->printDato($editor->vars['slide_til']['value']);

if(isset($_POST['editor_submit']))
{
	if($editor->input($_POST))
	{
		// Passer på at skjerm_id blir rett
		$skjerm_id = array();
		$return = 0;
		foreach ($editor->vars['slide_nr']['value_array'] as $value) {
			list($id, $value) = explode(',', $value, 2);
			$skjerm_id[$id] = $id;
			$return = $id;
		}
		$editor->vars['skjerm_id']['value_array'] = $skjerm_id;
		
		if($editor->performDBquery())
		{
			// Update slides_connect
			mysql_query("DELETE FROM `slides_connect` WHERE `slide_id` = '".$editor->id."'");
			
			foreach ($editor->vars['slide_nr']['value_array'] as $value) {
				list($skjerm_id, $slide_nr) = explode(',', $value, 2);
				mysql_query("INSERT INTO `slides_connect` (
						`slide_id` ,
						`skjerm_id` ,
						`slide_nr` ,
						`slide_fra` ,
						`slide_til` ,
						`slide_pri`
					)
					VALUES (
						'".$editor->id."', 
						'$skjerm_id', 
						'$slide_nr', 
						'".$editor->vars['slide_fra']['value']."', 
						'".$editor->vars['slide_til']['value']."', 
						'".$editor->vars['slide_pri']['value']."'
					);");
			}
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

echo '
<script type="text/javascript">

function onChangeSlidenr() {
	var inputs = document.getElementsByTagName(\'input\');
	var array = new Array();
	for (var i=0;i<inputs.length;i++) {
		if(inputs[i].name.substr(0,8) == \'slide_nr\' && inputs[i].checked){
			split = inputs[i].value.split(",", 2);
			array[split[0]] = split[0];
		}
	}
	for (var i=0;i<inputs.length;i++) {
		if(inputs[i].name.substr(0,9) == \'skjerm_id\')
		{
			if(array[inputs[i].value] != undefined){
				inputs[i].checked = true;
			} else {
				inputs[i].checked = false;
			}
		}
	}
}

function onChangeSkjermid(skjermid) {
	var inputs = document.getElementsByTagName(\'input\');
	var checked = \'nooooo\';
	for (var i=0;i<inputs.length;i++) {
		if(inputs[i].name.substr(0,9) == \'skjerm_id\')
		{
			if(inputs[i].value == skjermid) {
				if(inputs[i].checked) {
					checked = \'yeeeees\';
				} else {
					checked = \'no\';
				}
			}
		}
	}
	if(checked != \'nooooo\') {
		for (var i=0;i<inputs.length;i++) {
			if(inputs[i].name.substr(0,8) == \'slide_nr\'){
				split = inputs[i].value.split(",", 2);
				if(split[0] == skjermid) {
					if(checked == \'no\') {
						inputs[i].checked = false;
					} else {
						inputs[i].checked = true;
					}
				}
			}
		}
	}
}
</script>
';

$editor->fields_before .= "\t<tr>\n\t\t<td>&nbsp;</td>\n\t\t<td><input type=\"submit\" value=\"".$editor->submit_txt."\"></td>\n\t</tr>\n".
"\t<tr>\n\t\t<td colspan=\"2\">&nbsp;</td>\n\t</tr>\n\n";
$editor->printEditor();

$smarty->display('footer.tpl');