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

/*
 * addField()
 * 
 * adds a row of fields after the last row (<span>)
 */

$fields = array();

function addField ($var, $type, $name, $description = '')
{
	global $fields;
	
	if ($var == 'empty')
	{
		$fields[] = array(
			'var'	=> 'empty',
			'type'	=> 'empty'
		);
		
	}
	else
	{
		$fields[$var]['var']		= $var;
		$fields[$var]['type']		= $type;
		$fields[$var]['name']		= $name;
		$fields[$var]['desc']		= $description;
		$fields[$var]['id']		= ''; // Default = no ID
		
		$fields[$var]['value']		= ''; // The value/whats selected
		$fields[$var]['value_array']	= array(); // The value/whats selected (if type is radio, select, etc)
		$fields[$var]['choice']		= array();
	}
}

echo '<script language="javascript">

function cE (b,c,d,e)
{
	var f=document.createElement(b);
	if(!f)
		return 0;
	for(var a in c)
		f[a]=c[a];
	var t=typeof(d);
	if(t=="string"&&!e)
		f.appendChild(document.createTextNode(d));
	else if(t=="string"&&e)
		f.innerHTML=d;
	else if(t=="object")
		f.appendChild(d);
	return f
};

function addField ()
{
	form = document.forms[\'fieldrows\'];
	formelements = form.elements;
	valuelastrow = 0;
	valuehighestrow = 0;
	valuehighestline = 0;
	for (var i = 0; i<formelements.length;i++)
	{
		if(formelements[i].name == "rows[]")
		{
			if(formelements[i].value > valuehighestrow)
				valuehighestrow = formelements[i].value;
			valuelastrow = formelements[i].value;
		}
		else if(formelements[i].name.substring(0,4) == "line")
		{
			if(formelements[i].value > valuehighestline)
				valuehighestline = formelements[i].value;
		}
	}
	valuehighestrow++;
	thisvalue = valuehighestrow;
	valuehighestline++;
	thisline = valuehighestline;
	
	var tr=document.createElement(\'tr\');
	tr.id = "row"+ thisvalue;
	var input=document.createElement(\'input\');
	input.name="rows[]";
	input.type="hidden";
	input.value=thisvalue;
	tr.appendChild(input);
	
	var td=document.createElement(\'td\');
	var input=document.createElement(\'input\');
	input.name="line"+thisvalue;
	input.value=thisline;
	input.size="1";
	td.appendChild(input);
	tr.appendChild(td);
	
	var td=document.createElement(\'td\');
	var input=document.createElement(\'input\');
	input.name="txt"+thisvalue;
	input.value="";
	input.size="4";
	td.appendChild(input);
	tr.appendChild(td);
	
	var td=document.createElement(\'td\');
	var input=document.createElement(\'input\');
	input.name="price"+thisvalue;
	input.value="";
	input.size="2";
	td.appendChild(input);
	tr.appendChild(td);
	
	table = document.getElementById(\'fieldrows\');
	table.appendChild(tr);
}
</script>
'.chr(10);


$fieldrows = array();
$fieldrows[0]['line']	= '1';
$fieldrows[0]['txt']	= 'ABC!!!';
$fieldrows[0]['price']	= '100';

if(isset($_POST['rows']))
{
	if(is_array($_POST['rows']))
	{
		$fieldrows = array();
		foreach ($_POST['rows'] as $row)
		{
			if(is_numeric($row))
			{
				$fieldrows[$row] = array();
				if(isset($_POST['line'.$row]))
					$fieldrows[$row]['line'] = $_POST['line'.$row];
				if(isset($_POST['txt'.$row]))
					$fieldrows[$row]['txt'] = $_POST['txt'.$row];
				if(isset($_POST['price'.$row]))
					$fieldrows[$row]['price'] = $_POST['price'.$row];
			}
		}
	}
}

echo '<br><br><a href="'.$_SERVER['PHP_SELF'].'">På ny</a><br><br>'.chr(10);
echo '<form action="'.$_SERVER['PHP_SELF'].'" name="fieldrows" method="POST">'.chr(10);

echo '<table id="fieldrows">'.chr(10);
echo ' <tr>'.chr(10);
echo '  <td><b>Line</b></td>'.chr(10);
echo '  <td><b>Txt</b></td>'.chr(10);
echo '  <td><b>Price</b></td>'.chr(10);
echo ' </tr>'.chr(10);
$lastid = -1;
$valuehighestline = 0;
foreach($fieldrows as $id => $fieldrow)
{
	if($valuehighestline < $fieldrow['line'])
		$valuehighestline = $fieldrow['line'];
	echo '<tr id="row'.$id.'">'.chr(10);
	echo '<input type="hidden" name="rows[]" value="'.$id.'">'.chr(10);
	echo '<td><input type="text" size="1" name="line'.$id.'" id="line'.$id.'" value="'.$fieldrow['line'].'"></td>'.chr(10);
	echo '<td><input type="text" size="4" name="txt'.$id.'" id="txt'.$id.'" value="'.$fieldrow['txt'].'"></td>'.chr(10);
	echo '<td><input type="text" size="2" name="price'.$id.'" value="'.$fieldrow['price'].'"></td>'.chr(10);
	echo '</tr>'.chr(10);
	$lastid = $id;
}
$id = $lastid + 1;

/*
echo '<tr id="row'.$id.'">'.chr(10);
echo '<input type="hidden" name="rows[]" value="'.$id.'">'.chr(10);
echo '<td><input type="text" size="1" name="line'.$id.'" id="line'.$id.'" value="'.($valuehighestline + 1).'"></td>'.chr(10);
echo '<td><input type="text" size="4" name="txt'.$id.'" id="txt'.$id.'" value=""></td>'.chr(10);
echo '<td><input type="text" size="2" name="price'.$id.'" value=""></td>'.chr(10);
echo '</tr>'.chr(10);*/

echo '</table>'.chr(10);

echo '<br>'.chr(10);

echo '<input type="submit" value="JAda!!! submit...">'.chr(10);
echo '<input type="button" value="Legg til felt" onclick="addField();">'.chr(10);

echo '</form>'.chr(10);


?>