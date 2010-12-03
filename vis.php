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

/* VISNING AV SLIDES FOR INFOSKJERMER

Av Hallvard Nygård

*/

$krever_innlogging = false;

require "configs/infoskjerm.php";

/* ## FINN HVILKEN SLIDE SOM SKAL VISES ## */
if(isset($_GET['slide_id']))
{
	if(!is_numeric($_GET['slide_id']))
	{
		echo 'Finner ikke slide';
		exit();
	}
	
	$Q_slide = mysql_query("select * from `slides` where `slide_id` = '".(int)$_GET['slide_id']."'");
	if(!mysql_num_rows($Q_slide))
	{
		echo 'Fant ikke slide.';
		exit();
	}
	$slide = mysql_fetch_assoc($Q_slide);
	$slide['hidden_msg'] = '';
	
	$heading = 'Slide '.$slide['slide_id'];
}
else
{
	// Hvis slide_id ikke er spesifisert, så viser vi uansett en side.
	// -> Ingen feilmeldinger på skjermene
	$slide = array(
		'slide_id' => 0,
		'skjerm_id' => 0,
		'slide_innhold_heading' => '',
		'slide_innhold' => '',
		'hidden_msg' => ''
	);
	
	$slide_nr_failed = false;
	
	if(isset($_GET['skjerm_id']) && is_numeric($_GET['skjerm_id']))
	{
		// Prøver å finne en slide
		if(isset($_GET['skjerm_nr']) && is_numeric($_GET['skjerm_nr']))
		{
			/* ## Nåværende slide ## */
			$Q_slides = mysql_query("
				SELECT
					s.slide_id as slide_id, 
					s.slide_navn as slide_navn,
					c.skjerm_id as skjerm_id,
					c.slide_fra as slide_fra,
					c.slide_til as slide_til,
					c.slide_nr as slide_nr,
					c.slide_pri as slide_pri,
					s.slide_innhold_heading, 
					s.slide_innhold 
				FROM `slides` s, `slides_connect` c
				WHERE
					s.`slide_id` = c.`slide_id` AND
					c.`skjerm_id` = '".(int)$_GET['skjerm_id']."' AND
					c.`slide_nr` = '".(int)$_GET['skjerm_nr']."' AND
					(
						(
							c.slide_fra <= '".time()."' and
							c.slide_til >= '".time()."'
						) or (
							c.slide_fra <= '".time()."' and
							c.slide_til = '0'
						) or (
							c.slide_fra = '0' and
							c.slide_til >= '".time()."'
						) or (
							c.slide_fra = '0' and
							c.slide_til = '0'
						)
					)
				ORDER BY
					c.slide_pri desc
				LIMIT 1");
			if(!mysql_num_rows($Q_slides))
			{
				$slide['hidden_msg'] = 'Fant ingen slides.';
				$slide_nr_failed = true;
			}
			else
			{
				$slide = mysql_fetch_assoc($Q_slides);
				$slide['hidden_msg'] = 'Slide ok';
			}
		}
		else {
			$slide['hidden_msg'] = 'Skjerm_nr ikke funnet eller ikke tall.';
			$slide_nr_failed = true;
		}
		
		if($slide_nr_failed)
		{
			$Q_skjerm = mysql_query("select skjerm_id, skjerm_defaultslide_heading, skjerm_defaultslide from `skjermer` where `skjerm_id` = '".(int)$_GET['skjerm_id']."'");
			if(mysql_num_rows($Q_skjerm))
			{
				$slide['skjerm_id'] = mysql_result($Q_skjerm, 0, 'skjerm_id');
				$slide['slide_innhold_heading'] = mysql_result($Q_skjerm, 0, 'skjerm_defaultslide_heading');
				$slide['slide_innhold'] = mysql_result($Q_skjerm, 0, 'skjerm_defaultslide');
				$slide['hidden_msg'] .= chr(10).'Bruker default slide.';
			}
		}
	}
	else
		$slide['hidden_msg'] = 'Skjermid ikke funnet.';
	$heading = 'Infoskjerm '.$slide['skjerm_id'];
}

echo '<html><head><title>';
echo $heading.' - '.date('d.m.Y');
echo '</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="css/vis.css" />';
 ?>
 
<script type="text/javascript">
<?php 
/* 		JavaScript klokke 
	* Denne klokken henter ned tiden ved hjelp av PHP funksjonen for tid. 
	* Da JavaScript er ett klient basert script og ikke et server-side script 
	* må vi ty til denne funksjonen for at klokkene skal stemme på skjermene. 
	* Vi henter tid ved hjelp av time() og ganger så dette med *1000 for å få det ned i millisekunder
	* så får vi Javascript til å sortere ut de forskjellige linjene til Timer, minutter og sekunder. 
	* så sier vi javascript skal oppdatere dette hvert 1000 millisekund, altså, hvert sekund. 
	* Denne vil oppdateres hver gang siden blir lastet på nytt slik at den alltid er konfigurert til serverklokken.
	
	- av Christer Nordbø - 2IKT - Akademiet
	*/
?>
serverdato = new Date(<?php echo time()*1000; ?>);
servertid = serverdato.getTime();

function visTid() {
	dato = new Date(servertid);
	time = (dato.getHours() < 10) ? "0"+(Number(dato.getHours())) : (Number(dato.getHours()));
	minutt = (dato.getMinutes() < 10) ? "0"+(Number(dato.getMinutes())) : (Number(dato.getMinutes()));
	sekund = (dato.getSeconds() < 10) ? "0"+(Number(dato.getSeconds())) : (Number(dato.getSeconds()));
	document.getElementById("klokke").innerHTML = time+":"+minutt+":"+sekund;
	servertid += 1000;
}
setInterval("visTid()", 1000);
</script>

</head>
<?php

echo '<body onload="visTid()" background="img/infoskjerm-bg.png" style="margin: 0px; padding: 0px;">'.chr(10);
echo '<div id="klokke" class="klokkeTekst"></div>'.chr(10);
echo '<!-- '.$slide['hidden_msg'].' -->'.chr(10);
echo '  <div id="heading" style="
max-width: 90%; font-family: arial;
position: absolute; top: 80px; left: 200px;
width: 760; height: 240px; overflow: scroll;
"><table height="100%" width="100%"><tr><td id="tekst" style="border: 0px; vertical-align: middle; text-align: center;
font-size: 80pt; 
">'.htmlspecialchars_decode($slide['slide_innhold_heading']).'</td></tr></table></div>';
echo '<div style="
clear: none;
position: absolute;
top: 340px;
left: 100px;
height: 360px;
width: 844px;
overflow: visible;
">
<table height="100%" width="100%" ><tr><td style="border: 0px;
font-family: arial;
	font-size: 40pt;
	text-align: center;
padding: 0px;
">
';
echo htmlspecialchars_decode($slide['slide_innhold']);
echo '</td></tr></table></div>';
echo '<div style="position: absolute; top: 0px; left: 0px; width: 1024px; height: 768px; min-width: 1024px; min-height: 768px;">&nbsp;</div>';

echo '<script type="text/javascript">

$divheight="240";
$fontsize=80;
$div = document.getElementById("heading");
$text = document.getElementById("tekst");
while($div.scrollHeight>$div.offsetHeight) {
	$fontsize = $fontsize-1;
	$text.style.fontSize=$fontsize + "pt";
}
while($div.scrollWidth>$div.offsetWidth) {
	$fontsize = $fontsize-1;
	$text.style.fontSize=$fontsize + "pt";
}
$div.style.overflow="hidden";
</script>
';
echo '</body>'.chr(10);
echo '</html>'.chr(10);
?>
