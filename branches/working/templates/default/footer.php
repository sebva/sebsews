<?php require('config.php'); ?>

<!--Copyright-->&copy; Copyright <?php echo date(Y)?> - <a href="http://<?php echo $domaine.$repertoire?>"><?php echo $nom?></a>&nbsp;-&nbsp;
					<!--Veuillez SVP laisser cette indication, Merci--><a href="http://sebseasywebsite.sourceforge.net/">Powered by Séb's EasyWebSite</a><span class="finfooter">&nbsp;-&nbsp;
					<!--Administration--><a href="http://<?php echo $domaine.$repertoire?>/admin/">Administration</a>&nbsp;-&nbsp;
					<!--RSS--><a href="http://<?php echo $domaine.$repertoire;echo ($rewrite=='on') ?'/news.xml':'/rss.php';?>"><img src="http://<?php echo $domaine.$repertoire.'/templates/'.$template?>/images/feedicon.png" alt="Flux RSS -" height="20" width="20" /></a>
					<!--Valid RSS--><a href="http://validator.w3.org/feed/check.cgi?url=http%3A//<?php echo $domaine.$repertoire;echo ($rewrite=='on') ?'/news.xml':'/rss.php';?>"><img src="http://<?php echo $domaine.$repertoire.'/templates/'.$template?>/images/valid-rss.png" alt="RSS Valide -" height="20" width="59" /></a>
					<!--Valid XHTML--><a href="http://validator.w3.org/check?uri=referer"><img src="http://<?php echo $domaine.$repertoire.'/templates/'.$template?>/images/valid-xhtml11.png" alt="XHTML 1.1 Valide -" height="20" width="59" /></a>
					<!--Valid CSS--><a href="http://jigsaw.w3.org/css-validator/check/referer"><img width="59" height="20" src="http://<?php echo $domaine.$repertoire.'/templates/'.$template?>/images/valid-css2.png" alt="CSS 2.1 Valide !" /></a></span>