<?php require('config.php'); ?>
<meta http-equiv="Content-Type" content="<?php echo $type?>; charset=utf-8" />
<?php
if ($googleWebmasterToolsMetaTag !=''){?><meta name="verify-v1" content="<?php echo $googleWebmasterToolsMetaTag.'" />';}?>
		<link rel="icon" href="http://<?php echo $domaine.$repertoire?>/favicon.png" />
		<link rel="Shortcut Icon" href="http://<?php echo $domaine.$repertoire?>/favicon.png" />
        <link rel="stylesheet" type="text/css" media="all" href="http://<?php echo $domaine.$repertoire.'/templates/loader.php?css';?>" />		
		<link rel="alternate" type="application/rss+xml" title="<?php echo $nom?> :: News" href="http://<?php echo $domaine.$repertoire;echo ($rewrite=='on') ?'/news.xml':'/rss.php';?>" />
		<!--Sortie des Frames--><script type="text/javascript">
			<!--
			if (window !=top ) {top.location=window.location;}
		   //-->
		</script>
		<?php if(!in_array($_SERVER['REMOTE_ADDR'], $googleAnalyticsBlacklist) && $erreur == 200 && $googleAnalyticsCode != ''){?>
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