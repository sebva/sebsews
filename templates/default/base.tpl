<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
 "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
	<head>
		<title>{$titre}</title>
		{INCLUDE file=./templates/head.php}
	</head>
	<body>
		<div id="conteneur">
			<div id="header_extensible">
				<div id="header">
				</div>
			</div>			
			<p id="navigation">				
				{$navigation}			
			</p>
			<div id="centre">
				<div id="menu">
					<div class="bouton">+ Menu
					</div>
					<ul id="menutitre">	
						<ul id="menutitre">
						{menu id=1}												
							<li><a href="{$chemin}{$nomCourt}.html">{$nomLong}</a></li>
						{/menu}
						</ul>
					</ul>
				</div>
				<div id="texte">
					<h1 class="soustitre">{$titre}</h1>
					<div class="texte">
						<!--bof Texte-->{$texte}<!--eof Texte-->
					</div>
				</div>
				<p id="footer">
					{INCLUDE file=./templates/default/footer.php}
				</p>
			</div>
		</div>
	</body>
</html>