<?php
//Config générale
$domaine=$_SERVER['HTTP_HOST']; //Le domaine sur lequel votre site est hebergé Ex:sebseasywebsite.sourceforge.net
$repertoire=''; //Le répertoire dans lequel le site est hebergé. A écrire sous la forme /repertoire/sous-repertoire ou vide si à la racine Ex:/sebseasywebsite
$template='default'; //Le template à utiliser
$nom=''; //Le nom de votre site Ex:Séb\'s EasyWebSite
$emailDomaine=''; //Le domaine de vos adresses e-mail (est ajouté automatiquement aux adresses e-mail) Ex:users.sourceforge.net
$rewrite='on'; //L'URL rewriting doit être activé (on) ou desactivé (off)

//Services Google
$googleAnalyticsCode = ''; //Votre code de suivi Google Analytics (si vide, Google Analytics est desactivé)
$googleWebmasterToolsMetaTag = ''; //Idem, mais pour Google Webmaster Tools
$googleAppsDomain = ''; //Encore la même chose, mais pour votre domaine Google Apps

//MySQL
$mysqlHost = ''; //Hôte MySQL Ex:localhost
$mysqlUser = ''; //Utilisateur MySQL
$mysqlPassword = ''; //Mot de passe MySQL
$mysqlDb = ''; //Base de données MySQL
$mysqlTablePages = 'pages'; //Table MySQL pour les pages
$mysqlTableNews = 'news'; //Table MySQL pour les news
?>