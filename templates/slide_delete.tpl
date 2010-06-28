{*
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
*}
{assign var='skriv_tittel2' value=true}
{assign var="tittel2" value='Slette slide'}
{include file="header.tpl" tittel="Slette slide"}

Er du sikker på at du vil slette denne sliden?

<br><br>
<table class="prettytable">
	<tr>
		<th>Id</th>
		<td>{$slide.slide_id}</td>
	</tr>
	<tr>
		<th>Internt navn</th>
		<td>{$slide.slide_navn}</td>
	</tr>
	<tr>
		<th>Overskrift</th>
		<td>{$slide.slide_innhold_heading}</td>
	</tr>
	<tr>
		<th>Innhold</th>
		<td>{$slide.slide_innhold}</td>
	</tr>
	<tr>
		<th>Tilkoblede skjermer</th>
		<td>
			{if !$slide.connections|count}
				<i>Ingen</i>
			{else}
				<table>
					<tr>
						<th>Skjermid</th>
						<th>Slidenr</th>
						<th>Fra</th>
						<th>Til</th>
						<th>Prioritet</th>
					</tr>
					{foreach from=$slide.connections item=connection}
					<tr>
						<td>{$connection.skjerm_id}</td>
						<td>{$connection.slide_nr}</td>
						<td>{$connection.slide_fra|printDato}</td>
						<td>{$connection.slide_til|printDato}</td>
						<td>{$connection.slide_pri}</td>
					</tr>
					{/foreach}
				</table>
			{/if}
		</td>
	</tr>
</table>

<span style="font-size:1.5em;">- <a href="slide_delete.php?slide_id={$slide.slide_id}&amp;bekreft=1">Ja, jeg vil slette</a>&nbsp;&nbsp;</span><br>
<span style="font-size:1.5em;">- <a href="slides.php">Nei, jeg vil ikke slette</a></span>

{include file="footer.tpl"}