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
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="style-infoskjerm.css" rel="stylesheet" type="text/css">
{config_load file=smarty.tpl.conf}
<title>{$tittel} - {#sidetittel#}</title>
<script src="js/jquery-1.4.2.min.js" type="text/javascript"></script>
</head>
<body>

MENY:
<a href="skjermer.php">Skjermer</a> -:-
<a href="slides.php">Slides</a>

{if $skriv_tittel}<h1>{$tittel}</h1>{/if}
{if $skriv_tittel2}<h1>{$tittel2}</h1>{/if}
