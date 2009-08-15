<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
{INCLUDE file=./templates/head.php}
<title>{$titre}</title>
</head>

<body>

<div class="outer-container">

<div class="inner-container">

	<div class="header">
		
		<div class="title">

			<span class="sitename"><a href="{$chemin}">{$nomsite}</a></span>

		</div>
		
	</div>

	<div class="path">			
			{$navigation}
	</div>

	<div class="main">		
		
		<div class="content">

			<h1>{$titre}</h1>
			<!--bof Texte-->{$texte}<!--eof Texte-->

		</div>

		<div class="navigation">

			<h2>Menu</h2>
			<ul>
				{menu id=1}
					<li><a href="{$chemin}{$nomCourt}.html">{$nomLong}</a></li>
				{/menu}
			</ul>

		</div>

		<div class="clearer">&nbsp;</div>

	</div>

	<div class="footer">

		<span class="left">
			{INCLUDE file=./templates/dirtylicious/footer.php}
		</span>

		<span class="right"><a href="http://templates.arcsin.se/">Website template</a> by <a href="http://arcsin.se/">Arcsin</a></span>

		<div class="clearer"></div>

	</div>

</div>

</div>

</body>

</html>