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

require "skjermer.php";

/*
$krever_innlogging = FALSE;

require "configs/infoskjerm.php";

*/
/* ## Innlogging ## */
/*
if(isset($_POST['innlogging']))
{
	// Sjekker mot database siden epost og passord ble sendt
	$bruker_epost		= addslashes(htmlspecialchars(strip_tags($_POST['epost']),ENT_QUOTES)); // E-posten
	$bruker_passord_md5	= md5($_POST['passord']); // Lager md5 hash av passordet
	
	$Q_bruker_login		= mysql_query("select * from `brukere` where bruker_epost = '$bruker_epost' and bruker_passord_md5 = '$bruker_passord_md5'");
	if(mysql_num_rows($Q_bruker_login))
	{
		// Rett epost og passord, gå videre
		$_SESSION['bruker_id']			= mysql_result($Q_bruker_login,'0','bruker_id');
		$_SESSION['bruker_passord_md5']	= mysql_result($Q_bruker_login,'0','bruker_passord_md5');
		
		// Setter "sist innlogget" til rett tid
		mysql_query("UPDATE `brukere` SET `bruker_tid_sistinnlogg` = '".time()."' WHERE `bruker_id` = ".$_SESSION['bruker_id']." LIMIT 1");
		
		header('Location: skjermer.php');
		exit();
	}
	else
	{
		$smarty->assign('feil_epost_eller_passord', TRUE);
	}
}

$smarty->assign('skriv_tittel2',TRUE);
$smarty->assign('tittel2','Infoskjerm');
$smarty->display('index.tpl');
*/
