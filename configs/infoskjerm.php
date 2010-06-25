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


// Sessions
session_start();

// Smarty
require "smarty/libs/Smarty.class.php";

$smarty = new Smarty;

$smarty->compile_check	= true;
#$smarty->debugging		= true;
$smarty->debugging	= false;
$smarty->security		= false;	// vet ikke hva denne gjør... hehe :P
$smarty->register_modifier('printDato', 'smarty_modifier_printDato');


function iconHTML ($ico, $end = '.png', $extra = '') {
	return '<img src="./img/icons/'.$ico.$end.'" style="border: 0px solid black; vertical-align: middle;" alt="'._('Icon').': '.$ico.'"'. $extra .'>';
}

function templateIconHtml ($params, &$smarty) {
	if(isset($params['ico'])) {
		$ico = $params['ico'];
	}
	else
		$ico = '';
	if(isset($params['end'])) {
		$end = $params['end'];
	}
	else
		$end = '.png';
	if(isset($params['extra'])) {
		$extra = ' '.$params['extra'];
	} else
		$extra = '';
	return iconHTML($ico, $end, $extra);
}

$smarty->register_function ('iconHTML', 'templateIconHtml');

// MySQL-connect
require "configs/mysql_connect.php";

// Sjekker innloggingsstatus
/* INNLOGGING ER AV
if(!$krever_innlogging && isset($krever_innlogging))
{
	
}
else
{
	if($_SESSION['bruker_id'] == '' || $_SESSION['bruker_passord_md5'] == '')
	{
		session_destroy(); // Logges ut
		$smarty->assign('error','ikke innlogget');
		$smarty->display('error.tpl');
		exit();
	}
	else
	{
		$Q_bruker = mysql_query("select * from `brukere` where bruker_id = '".$_SESSION['bruker_id']."' and bruker_passord_md5 = '".$_SESSION['bruker_passord_md5']."' limit 1");
		if(!mysql_num_rows($Q_bruker))
		{
			session_destroy(); // Logges ut
			$smarty->assign('error','ikke innlogget');
			$smarty->display('error.tpl');
			exit();
		}
	}
}
*/

function smarty_modifier_printDato($string)
{
	if($string == 0)
		return 'evigheten';
	else
		return date('H:i d-m-Y', $string);
}

function behandleDato ($line, $format = array ('d', 'm', 'y', 'h', 'i', 's'), $start = 0)
{
	/*
		$format = array ('d', 'm', 'y', 'h', 'i', 's');
	*/
	$denne		= 0;
	$disse_tall	= array();
	$num_siste	= false;
	
	for ($i = $start; $i < strlen($line); $i++)
	{
		if(is_numeric($line{$i}))
		{
			if(isset($format[$denne]))
			{
				if(!isset($disse_tall[$format[$denne]]))
					$disse_tall[$format[$denne]] = '';
				$disse_tall[$format[$denne]] .= $line{$i};
				$num_siste = true;
			}
		}
		elseif($num_siste)
		{
			// Ikke tall, g\Uffffffffil neste
			$denne ++;
			$num_siste = false;
		}
	}
	
	// Krever d, m og y
	if(
		!isset($disse_tall['d']) ||
		!isset($disse_tall['m']) ||
		!isset($disse_tall['y'])
	)
		return 0;
	
	if(!isset($disse_tall['h']))
		$disse_tall['h'] = 0;
	
	
	if(!isset($disse_tall['i']))
		$disse_tall['i'] = 0;
	
	if(!isset($disse_tall['s']))
		$disse_tall['s'] = 0;
	
	return mktime (
		$disse_tall['h'],
		$disse_tall['i'],
		$disse_tall['s'],
		$disse_tall['m'],
		$disse_tall['d'],
		$disse_tall['y']);
}

?>
