<? require 'header.php';
if($_POST['confirm']=='yes')
	if(mysql_query('DELETE FROM '.$mysqlTableNews.' WHERE id = \''.$_POST['news'].'\''))
		echo 'News n&deg; '.$_POST['news'].' supprimée !';
	else
		echo 'Erreur, la news n\'a pas été supprimée';
else
	echo 'Vous n\'avez pas confirmé l\'action ! Action annulée';
echo '<br/><a href="news_index.php">Retour page précédente</a>';
require 'footer.php'; ?>