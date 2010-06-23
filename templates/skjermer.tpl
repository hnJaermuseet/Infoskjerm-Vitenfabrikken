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
{assign var="tittel2" value='Liste med infoskjermer'}
{include file="header.tpl" tittel="Liste med skjermer"}

Velkommen til kontroll av infoskjermer. F&oslash;lgende skjermer er tilgjengelige i systemet.

<br><br>
<table>
{foreach from=$skjermer item=skjerm}
	<tr>
		<td><font size="5">- <a href="skjerm.php?skjerm_id={$skjerm.skjerm_id}">{$skjerm.skjerm_navn}</a></font></td>
	</tr>
{/foreach}
</table>

<br><br><br>
- <a href="skjerm_edit.php">Opprett ny skjerm</a>

{include file="footer.tpl"}
