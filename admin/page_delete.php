<?php  require 'header.php';
if($_POST['confirm']=='yes')
	if(mysql_query('DELETE FROM '.$mysqlTablePages.' WHERE shorttitle = \''.$_POST['page'].'\''))
		echo 'Page '.$_POST['page'].' supprimée !';
	else
		echo 'Erreur, la page n\'a pas été supprimée';
else
	echo 'Vous n\'avez pas confirmé l\'action ! Action annulée';
echo '<br/><a href="page_index.php">Retour page précédente</a>';
require 'footer.php'; ?>