<?php
//Config générale
$domaine=''; //Le domaine sur lequel votre site est hebergé Ex:sebseasywebsite.sourceforge.net
$repertoire=''; //Le répertoire dans lequel le site est hebergé. A écrire sous la forme /repertoire/sous-repertoire ou vide si à la racine Ex:/sebseasywebsite
$nom=''; //Le nom de votre site Ex:Séb\'s EasyWebSite
$emailWebmaster=''; //L'email du Webmaster (facultatif) (Non utilisé dans cette release)
$emailDomaine=''; //Le domaine de vos adresses e-mail (est ajouté automatiquement aux adresses e-mail) Ex:users.sourceforge.net
$rewrite='on'; //L'URL rewriting doit être activé (on) ou desactivé (off)

//Services Google
$googleAnalyticsCode = ''; //Votre code de suivi Google Analytics (si vide, Google Analytics est desactivé)
$googleAnalyticsBlacklist = array('127.0.0.1', '192.168.1.60'); //Si vous souhaitez ne pas activer Google Analytics pour certaines IP (pour ne pas fausser les statistques par exemple
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