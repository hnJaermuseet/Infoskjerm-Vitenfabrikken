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

if(!isset($_GET['skjerm_id']) || !is_numeric($_GET['skjerm_id']))
{
	echo 'Ingen skjerm spesifisert.';
	exit();
}

$Q_skjermer = mysql_query("select * from `skjermer` where `skjerm_id` = '".(int)$_GET['skjerm_id']."'");
if(!mysql_num_rows($Q_skjermer))
{
	echo 'Fant ikke skjermen.';
	exit();
}
$skjerm = mysql_fetch_assoc($Q_skjermer);

if(!isset($_GET['dato']))
{
	$tid = time();
	$dag = 'N&aring;v&aelig;rende slides';
}
else
{
	$tid = behandleDato($_GET['dato'], array('y', 'm', 'd'));
	$dag = date('H:i d-m-Y', $tid);
}

$slides_nu = array();
$slides_andre = array();
for ($i = 1; $i <= $skjerm['skjerm_slidenr']; $i++)
{
	$slides_nu[$i] = array();
	$slides_andre[$i] = array();
	
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
		c.`skjerm_id` = '".$skjerm['skjerm_id']."' AND
		c.`slide_nr` = '".$i."' AND
		(
			(
				c.slide_fra <= '".$tid."' and
				c.slide_til >= '".$tid."'
			) or (
				c.slide_fra <= '".$tid."' and
				c.slide_til = '0'
			) or (
				c.slide_fra = '0' and
				c.slide_til >= '".$tid."'
			) or (
				c.slide_fra = '0' and
				c.slide_til = '0'
			)
		)
	ORDER BY
		c.slide_pri desc");
	
	if(!mysql_num_rows($Q_slides))
	{
		$slides_nu[$i] = array(
			'slide_id' => '',
			'slide_navn' => 'Standardslide for skjerm',
			'slide_pri' => '0');
	}
	else
	{
		$slides_nu[$i] = mysql_fetch_assoc($Q_slides);
		while($a = mysql_fetch_assoc($Q_slides))
		{
			$slides_andre[$i][$a['slide_id']] = $a;
			$slides_andre[$i][$a['slide_id']]['grunn_pri'] = true;
			$slides_andre[$i][$a['slide_id']]['grunn_tid'] = false;
		}
	}
	
	/* # Hent fra hele dagen # */
	$startdag = mktime(0,0,0,date('m', $tid), date('d', $tid), date('Y', $tid));
	$sluttdag = mktime(23,59,59,date('m', $tid), date('d', $tid), date('Y', $tid));
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
		c.`skjerm_id` = '".$skjerm['skjerm_id']."' AND
		c.`slide_nr` = '".$i."' AND
		(
			(
				c.slide_fra <= '".$startdag."' AND
				c.slide_fra >= '".$sluttdag."'
			) or (
				c.slide_til >= '".$startdag."' AND
				c.slide_til <= '".$sluttdag."'
			)
		)
	order by slide_pri desc");
	while($a = mysql_fetch_assoc($Q_slides))
	{
		if($a['slide_id'] != $slides_nu[$i]['slide_id'])
		{
			if(isset($slides_andre[$i][$a['slide_id']]))
				$slides_andre[$i][$a['slide_id']]['grunn_tid'] = true;
			else
			{
				$slides_andre[$i][$a['slide_id']] = $a;
				$slides_andre[$i][$a['slide_id']]['grunn_pri'] = false;
				$slides_andre[$i][$a['slide_id']]['grunn_tid'] = true;
			}
		}
	}
}

function printMonth ($params, &$smarty)
{
	global $area, $room;
	
	if(isset($params['year']))
		$year = $params['year'];
	else
		$year = 1990;
	if(isset($params['month']))
		$month = $params['month'];
	else
		$month = 1;
	if(isset($params['selected']))
		$selected = $params['selected'];
	else
		$selected = 0;
	if(isset($params['selectedType']))
		$selectedType1 = $params['selcetedType'];
	else
		$selectedType1 = '';
	if(isset($params['skjerm_id']))
		$skjerm_id = $params['skjerm_id'];
	else
		$skjerm_id = 0;
	
	switch ($selectedType1)
	{
		case 'week':
			$selectedType = 'week';
			break;
		
		case 'month':
			$selectedType = 'month';
			break;
		
		default: // day
			$selectedType = 'day';
			break;
	}
	$monthTime	= mktime (0, 0, 0, $month, 1, $year);
	$monthLast	= mktime (0, 0, 0, ($month+1), 1, $year);
	$numDays	= date('t', $monthTime);
	$startWeek	= date('W', $monthTime);
	
	#$checkTime = checkTime($monthTime, $monthLast, $area);
	$checkTime = array();
	
	echo '<table style="width: 100%;">'.chr(10);
	echo ' <tr><td><center><b>';
	# <a class="monthTableTop" href="month.php?year='.date('Y', $monthTime).'&amp;month='.date('m', $monthTime).'&amp;day=1&amp;area='.$area.'&amp;room='.$room.'">';
	if($selectedType == 'month')
		echo '<font color="red">'._(date('M', $monthTime)).' '.date('Y', $monthTime).'</font>';
	else
		echo _(date('M', $monthTime)).' '.date('Y', $monthTime);
	
	echo '</b></center></td>';
	
	echo '</tr>'.chr(10);
	echo ' <tr>'.chr(10);
	echo '  <td>'.chr(10);
	echo '   <table>'.chr(10);
	$printedWeeks = array();
	$firstWeek = true;
	for ($i = 1; $i < $numDays + 1; $i++)
	{
		
		$thisWeek = date('W', mktime(0, 0, 0, $month, $i, $year));
		// If this week isn't printed, lets print it
		if(!in_array($thisWeek, $printedWeeks))
		{
			if($firstWeek)
			{
				$firstWeek = false;
			}
			else
				echo '    </tr>'.chr(10);
			
			echo '    <tr>'.chr(10);
			echo '     <td class="weeknum"><center>';
			#'<a class="monthTableWeek" href="week.php?year='.date('Y', $monthTime).'&amp;month='.date('m', $monthTime).'&amp;day='.$i.'&amp;area='.$area.'&amp;room='.$room.'">';
			// Is it selected?
			if($selectedType == 'week' && $selected == $thisWeek)
				echo '<font color="red">'.$thisWeek.'</font>';
			else
				echo $thisWeek;
			
			#echo '</a>';
			echo '</center></td>'.chr(10);
			
			echo '     <td>&nbsp;</td>'.chr(10);
			
			// Checking the weekday and adding spaces
			switch (date('w', mktime (0, 0, 0, $month, $i, $year)))
			{
				case '0': // Sunday
					echo '     <td>&nbsp;</td>'.chr(10);
				case '6': // Saturday
					echo '     <td>&nbsp;</td>'.chr(10);
				case '5': // Friday
					echo '     <td>&nbsp;</td>'.chr(10);
				case '4': // Thursday
					echo '     <td>&nbsp;</td>'.chr(10);
				case '3': // Wednesday
					echo '     <td>&nbsp;</td>'.chr(10);
				case '2': // Tuesday
					echo '     <td>&nbsp;</td>'.chr(10);
				case '1': // Mondag, non added
					break;
			}
			
			$printedWeeks[] = $thisWeek;
		}
		
		echo '     <td class="monthTable"><center>'.
		'<a href="'.$_SERVER['PHP_SELF'].'?skjerm_id='.$skjerm_id.'&amp;dato='.date('Y', $monthTime).'-'.date('m', $monthTime).'-'.$i.'">';
		$ymd = $year;
		if(strlen($month) == 1)
			$ymd .= '0';
		$ymd .= $month;
		if(strlen($i) == 1)
			$ymd .= '0';
		$ymd .= $i;
		
		if(isset($checkTime[$ymd]))
			echo '<b>';
		if($selectedType == 'day' && $selected == $i)
			echo '<font color="red">'.$i.'</font>';
		else
			echo $i;
		if(isset($checkTime[$ymd]))
			echo '</b>';
		echo '</a></center></td>'.chr(10);
	}
	echo '    </tr>'.chr(10);
	echo '   </table>'.chr(10);
	echo '  </tr>'.chr(10);
	echo ' </tr>'.chr(10);
	echo '</table>'.chr(10);
}
$smarty->register_function ('printMonth', 'printMonth');

$smarty->assign('skjerm', $skjerm);

$smarty->assign('slides_nu', $slides_nu);
$smarty->assign('slides_andre', $slides_andre);
$smarty->assign('dag', $dag);
$smarty->assign('tid', $tid);
$smarty->assign('month1', date('m', $tid));
$smarty->assign('month2', date('m', $tid)+1);
$smarty->assign('month3', date('m', $tid)+2);
$smarty->display('skjerm.tpl');