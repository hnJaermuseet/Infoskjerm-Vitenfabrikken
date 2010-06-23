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
{include file="header.tpl" tittel="Detaljer for infoskjerm"}

<table width="100%"><tr>
<td>
<h1>Infoskjerm - {$skjerm.skjerm_navn}</h1>
- <a href="skjerm_edit.php?skjerm_id={$skjerm.skjerm_id}">Endre skjerm</a><br><br>
<b>Antall slides:</b> {$skjerm.skjerm_slidenr}<br>

<h2>Nå vises: {$dag}</h2>
</td>
<td><table><tr>
<td class="slidelist">
	{printMonth skjerm_id=$skjerm.skjerm_id year=$tid|date_format:"%Y" month=$month1 selected=$tid|date_format:"%d"}
</td>
<td class="slidelist">
	{printMonth skjerm_id=$skjerm.skjerm_id year=$tid|date_format:"%Y" month=$month2}
</td>
<td class="slidelist">
	{printMonth skjerm_id=$skjerm.skjerm_id year=$tid|date_format:"%Y" month=$month3}
</td>
</tr></table>
</tr></table>
<br><br>
<table cellspacing="0" cellpadding="4">
	<tr>
		<td class="slidelist"><span class="mindretekst">&nbsp;</span></td>
		<td class="slidelist"><span class="mindretekst">Slide som kjører:</span></td>
		<td class="slidelist"><span class="mindretekst">Kjører fra/til:</span></td>
		<td class="slidelist"><span class="mindretekst">&nbsp;</span></td>
	</tr>
	
{foreach from=$slides_nu key="slidenr" item='slides'}
	<tr>
		<td class="slidelist"><font size="5"><a href="vis.php?skjerm_id={$skjerm.skjerm_id}&amp;skjerm_nr={$slidenr}">Skjermbilde {$slidenr}</a></font></td>
		<td class="slidelist">
			({$slides.slide_pri}) 
			{if $slides.slide_id != ''}
				<a href="vis.php?slide_id={$slides.slide_id}">{iconHTML ico="page"} {$slides.slide_navn}</a>
				<span class="editlinks">
					&nbsp;&nbsp;
					<a href="slide_edit.php?slide_id={$slides.slide_id}&amp;redirect1=skjerm&amp;redirect2={$skjerm.skjerm_id}&amp;redirect3={$tid|date_format:"%Y-%m-%d"}">{iconHTML ico="page_edit" extra='height="12"'}</a>
					<a href="slide_edit.php?slide_id_fra={$slides.slide_id}&amp;dato={$tid}&amp;redirect1=skjerm&amp;redirect2={$skjerm.skjerm_id}&amp;redirect3={$tid|date_format:"%Y-%m-%d"}">{iconHTML ico="page_copy" extra='height="12"'}</a>
				</span>
			{else}
				{$slides.slide_navn}
			{/if}
		</td>
		<td class="slidelist">
			<span class="mindretekst">Fra:</span> {$slides.slide_fra|printDato}<br>
			<span class="mindretekst">Til:</span> {$slides.slide_til|printDato}
		</td>
		<td class="slidelist">
			<a href="slide_edit.php?skjerm_id={$skjerm.skjerm_id}&amp;slide_nr={$slidenr}&amp;dato={$tid}&amp;redirect1=skjerm&amp;redirect2={$skjerm.skjerm_id}&amp;redirect3={$tid|date_format:"%Y-%m-%d"}">{iconHTML ico="page_add"} Legg til ny på denne dagen</a><br>
		</td>
	</tr>
	{if $slides_andre.$slidenr|@count > 0}
		<tr id="unused">
			<td class="slidelist">&nbsp;</td>
			<td colspan="3" class="slidelist"><span class="mindretekst">Andre slides på skjermbildet denne dagen:</span><br>
			{foreach from=$slides_andre.$slidenr item="slides"}
				({$slides.slide_pri}) 
				<a href="vis.php?slide_id={$slides.slide_id}">{iconHTML ico="page"} {$slides.slide_navn}</a>
				<span class="editlinks">
					&nbsp;&nbsp;
					<a href="slide_edit.php?slide_id={$slides.slide_id}&amp;redirect1=skjerm&amp;redirect2={$skjerm.skjerm_id}&amp;redirect3={$tid|date_format:"%Y-%m-%d"}">{iconHTML ico="page_edit" extra='height="12"'}</a>
					<a href="slide_edit.php?slide_id_fra={$slides.slide_id}&amp;dato={$tid}&amp;redirect1=skjerm&amp;redirect2={$skjerm.skjerm_id}&amp;redirect3={$tid|date_format:"%Y-%m-%d"}">{iconHTML ico="page_copy" extra='height="12"'}</a>
				</span>
				<span class="mindretekst">
					Vises ikke pga. 
					{if $slides.grunn_pri && $slides.grupp_tid} utenfor tidsrom og prioritet
					{elseif $slides.grunn_pri} prioritet
					{elseif $slides.grunn_tid} utenfor tidsrom ({$slides.slide_fra|printDato} til {$slides.slide_til|printDato})
					{else} ukjent grunn
					{/if}
				</span><br>
			{/foreach}
			</td>
	{/if}
	
{/foreach}
</table>

{include file="footer.tpl"}