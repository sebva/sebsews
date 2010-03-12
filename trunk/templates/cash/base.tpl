<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	{INCLUDE file=./templates/head.php}
	<title>{$titre}</title>
</head>
<body>
	<div id="page" align="center">
		<div id="toppage" align="center">
			<div id="date">
				<div class="smalltext" style="padding:13px;"><strong>&nbsp;</strong></div>
			</div>
			<div id="topbar">
				<div align="right" style="padding:12px;" class="smallwhitetext">{$navigation}</div>
			</div>
		</div>
		<div id="header" align="center">
			<div class="titletext" id="logo">
				<div class="logotext" style="margin:30px"><span class="orangelogotext">$ </span>{$nomsite}</div> 
			</div>
			<div id="pagetitle">
				<div id="title" class="titletext" align="right">Bienvenue !</div>
			</div>
		</div>
		<div id="content" align="center">
			<div id="menu" align="right">
				<div align="right" style="width:189px; height:8px;"><img src="{$cheminimages}mnu_topshadow.gif" width="189" height="8" alt="mnutopshadow" /></div>
				<div id="linksmenu" align="center">
					{menu id=1}
						<a href="{$chemin}{$nomCourt}.html" title="{$nomLong}">{$nomLong}</a>
					{/menu}
				</div>
				<div align="right" style="width:189px; height:8px;"><img src="{$cheminimages}mnu_bottomshadow.gif" width="189" height="8" alt="mnubottomshadow" /></div>
			</div>
		<div id="contenttext">
			<div class="panel" align="justify">
				<span class="orangetitle">{$titre}</span>
				<span class="bodytext"><br />
					{$texte}
				</span>			</div>
			</div>
		</div>
		<div id="footer" class="smallgraytext" align="center">
			{INCLUDE file=./templates/cash/footer.php}<br />
			<a href="http://www.winkhosting.com" title="Hosting Colombia">Hosting Colombia</a><br />
		</div>
	</div>
</body>
</html>
