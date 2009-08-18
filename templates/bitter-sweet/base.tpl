<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
{INCLUDE file=./templates/head.php}
<title>{$titre}</title>
</head>

<body>

<div class="container">

	<div class="top">
		<a href="{$chemin}"><span>{$nomsite}</span></a>
	</div>
	
	<div class="header"></div>
		
	<div class="main">		
		
		<div class="item">

			<div class="date">
				<div>&nbsp;</div>
				<span>&nbsp;</span>
			</div>

			<div class="content">

				<h1>{$titre}</h1>

				<div class="body">				
					{$texte}
				</div>

			</div>

		</div>

	</div>

	<div class="navigation">

		<h1>Menu</h1>
			<ul>
				{menu id=1}
					<li><a href="{$chemin}{$nomCourt}.html">{$nomLong}</a></li>
				{/menu}
			</ul>

	</div>
	
	<div class="clearer"><span></span></div>

	<div class="footer">
	
		<span class="left">{INCLUDE file=./templates/bitter-sweet/footer.php}</span>
		
		<span class="right"><a href="http://templates.arcsin.se/">Website template</a> by <a href="http://arcsin.se/">Arcsin</a></span>

		<div class="clearer"><span></span></div>

	</div>

</div>

</body>

</html>