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

$id = 0;
if(isset($_GET['slide_id']) && is_numeric($_GET['slide_id']))
	$id = (int)$_GET['slide_id'];
if(isset($_POST['slide_id']) && is_numeric($_POST['slide_id']))
	$id = (int)$_POST['slide_id'];
if(isset($_POST['id']) && is_numeric($_POST['id']))
	$id = (int)$_POST['id'];

$Q_skjerm = mysql_query("select * from `slides` where slide_id = '".$id."'");
if(!mysql_num_rows($Q_skjerm))
{
	echo 'Lol! Finner ikke sliden du vil slettet. Har allerede slettet den?';
	exit();
}

if(isset($_GET['bekreft']) && $_GET['bekreft'] == '1')
{
	mysql_query("delete from `slides` where slide_id = '".$id."' limit 1");
	header('Location: slides.php');
	exit();
}

$slide = mysql_fetch_assoc($Q_skjerm);

$slide['slide_innhold_heading'] = htmlspecialchars_decode($slide['slide_innhold_heading']);
$slide['slide_innhold'] = htmlspecialchars_decode($slide['slide_innhold']);

// Connections
$Q_connection = mysql_query("select * from `slides_connect` where slide_id='".$id."'");
$slide['connections'] = array();
while($connection = mysql_fetch_assoc($Q_connection))
{
	$slide['connections'][] = $connection;
}

$smarty->assign('slide', $slide);
$smarty->display('slide_delete.tpl');