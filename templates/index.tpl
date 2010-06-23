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
{include file="header.tpl" tittel=Innlogging}

Velkommen til kontroll av infoskjermer. Du må logge inn for å kunne gå videre.

<br><br>

{if $feil_epost_eller_passord}<font class="feil">Feil epost eller passord. Prøv igjen.</font><br><br>{/if}

<form method="post" action="{$SCRIPT_NAME}">
<input type="text"	name="epost"> - Epost<br>
<input type="password"	name="passord"> - Passord<br>
<input type="submit"	name="innlogging" value="Logg inn">
</form>

{include file="footer.tpl"}