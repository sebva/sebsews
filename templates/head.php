<?php
/*
	Séb's Easy Website, a tiny CMS for your personal website
	Copyright (C) 2010 Sébastien Vaucher
	
	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
require('config.php'); ?><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
if ($googleWebmasterToolsMetaTag !=''){?><meta name="verify-v1" content="<?php echo $googleWebmasterToolsMetaTag.'" />';}?>
		<link rel="icon" href="http://<?php echo $domaine.$repertoire?>/favicon.png" />
		<link rel="Shortcut Icon" href="http://<?php echo $domaine.$repertoire?>/favicon.png" />
        <link rel="stylesheet" type="text/css" media="all" href="http://<?php echo $domaine.$repertoire.'/templates/loader.php?css';?>" />		
		<link rel="alternate" type="application/rss+xml" title="<?php echo $nom?> :: News" href="http://<?php echo $domaine.$repertoire;echo ($rewrite=='on') ?'/news.xml':'/rss.php';?>" />
		<!--Lightbox-->
		<script type="text/javascript" src="http://<?php echo $domaine.$repertoire.'/templates/lightbox/js/prototype.js'; ?>"></script>
		<script type="text/javascript" src="http://<?php echo $domaine.$repertoire.'/templates/lightbox/js/scriptaculous.js?load=effects,builder'; ?>"></script>
		<script type="text/javascript" src="http://<?php echo $domaine.$repertoire.'/templates/lightbox/js/lightbox.js'; ?>"></script>
		<link rel="stylesheet" href="http://<?php echo $domaine.$repertoire.'/templates/loader.php?css=lightbox'; ?>" type="text/css" media="screen" />
		<!--Sortie des Frames--><script type="text/javascript">
			<!--
			if (window !=top ) {top.location=window.location;}
		   //-->
		</script>
		<?php if($googleAnalyticsCode != ''){?>
		<!--Google Analytics-->
		<script type="text/javascript">
			var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
			document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
			</script>
			<script type="text/javascript">
			try {
			var pageTracker = _gat._getTracker("<?php echo $googleAnalyticsCode?>");
			pageTracker._trackPageview();
			} catch(err) {}
		</script>
		<?php } ?>