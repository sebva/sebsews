<? require 'header.php';?>
	<ul>
		<li><a href="page_index.php">Gestion des pages</a></li>
		<li><a href="news_index.php">Gestion des news</a></li>
		<?if ($googleAppsDomain!=''){?><li><a href="https://www.google.com/a/<?echo $googleAppsDomain?>/ServiceLogin">Google Apps</a></li><?}?>
	</ul>
<? require 'footer.php'; ?>