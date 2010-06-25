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

// Skript som kobler til mysql databasen og lar tilkoblingen v\Uffffffff \Uffffffffn videre i skript

// MySQL
$mysql_server     = 'localhost';
$mysql_db         = 'vf-infoskjerm';
$mysql_username   = '';
$mysql_passwd     = '';

// Koble til MySQL server
if(!$database = @mysql_connect($mysql_server, $mysql_username, $mysql_passwd))
{
	$smarty->assign('mysql_errno',mysql_errno());
	$smarty->assign('mysql_error',mysql_error());
	$smarty->assign('mysql_function','mysql_connect');
	$smarty->assign('error','mysql_connect');
	$smarty->display('error.tpl');
	exit();
}
if(!@mysql_select_db($mysql_db,$database))
{
	$smarty->assign('mysql_errno',mysql_errno());
	$smarty->assign('mysql_error',mysql_error());
	$smarty->assign('mysql_function','mysql_select_db');
	$smarty->assign('error','mysql_connect');
	$smarty->display('error.tpl');
	exit();
}

?>