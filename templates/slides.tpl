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
{assign var="tittel2" value='Liste med slides'}
{include file="header.tpl" tittel="Liste med slides"}

Dette er en liste med alle slides i databasen.

<br><br>
<table>
{foreach from=$slides item=slide}
	<tr>
		<td class="slidelist">
			({$slide.slide_pri}) 
			<a href="vis.php?slide_id={$slide.slide_id}">{iconHTML ico="page"} {$slide.slide_navn}</a>
			<span class="editlinks">
				&nbsp;&nbsp;
				<a href="slide_edit.php?slide_id={$slide.slide_id}">{iconHTML ico="page_edit" extra='height="12"'}</a>
				<a href="slide_edit.php?slide_id_fra={$slide.slide_id}">{iconHTML ico="page_copy" extra='height="12"'}</a>
				<a href="slide_delete.php?slide_id={$slide.slide_id}">{iconHTML ico="page_delete" extra='height="12"'}</a>
			</span>
		</td>
		<!--
		<td class="slidelist">
			<span class="mindretekst">Kjører fra:</span> {$slide.slide_fra|printDato}<span class="mindretekst">, kjører til:</span> {$slide.slide_til|printDato}
		</td>
		-->
	</tr>
{/foreach}
</table>


{include file="footer.tpl"}