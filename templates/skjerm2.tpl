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
<h1>Infoskjerm - Viser oversikt over alle skjermer</h1>

<h2>Nå vises: {$dag}</h2>
</td>
<td><table><tr>
<td class="slidelist">
	{printMonth skjerm_id=0 year=$tid|date_format:"%Y" month=$month1 selected=$tid|date_format:"%d"}
</td>
<td class="slidelist">
	{printMonth skjerm_id=0 year=$tid|date_format:"%Y" month=$month2}
</td>
<td class="slidelist">
	{printMonth skjerm_id=0 year=$tid|date_format:"%Y" month=$month3}
</td>
</tr></table>
</tr></table>
<br><br>
<table cellspacing="0" cellpadding="4">
	<tr>
		<td class="slidelist"><span class="mindretekst">&nbsp;</span></td>
		{foreach from=$skjermer key="skjerm_id" item='skjerm'}
		<td class="slidelist">
			<span class="mindretekst">
				<a href="skjerm.php?skjerm_id={$skjerm.skjerm_id}&amp;dato={$tid|date_format:"%Y-%m-%d"}">{$skjerm.skjerm_navn}</a>
			</span>
		</td>
		{/foreach}
	</tr>

{section name=foo loop=$slides_max}
	{assign var='key' value=$smarty.section.foo.iteration}
	<tr>
		<td class="slidelist" style="font-size:1.3em;">Skjerm- bilde {$key}</td>
		{foreach from=$skjermer key="skjerm_id" item='skjerm'}
			{if isset($skjerm.slides_nu.$key)}
				{assign var='slides' value=$skjerm.slides_nu.$key}
				<td class="slidelist{if $slides.slide_id != ''} slidemouseover slidenum{$slides.slide_id}{/if}"> 
					{if $slides.slide_id != ''}
						<a href="vis.php?slide_id={$slides.slide_id}">{$slides.slide_navn}</a>
						<a href="slide_edit.php?slide_id={$slides.slide_id}&amp;redirect1=skjerm&amp;redirect2={$skjerm.skjerm_id}&amp;redirect3={$tid|date_format:"%Y-%m-%d"}">{iconHTML ico="page_edit" extra='height="12"'}</a>
					{else}
						{$slides.slide_navn}
					{/if}
				</td>
			{else}
				<td>&nbsp;</td>
			{/if}
		{/foreach}
	</tr>
{/section}
</table>

<script src="js/skjerm2.js"></script>

{include file="footer.tpl"}