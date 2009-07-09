<?
$title = 'Erreur '.$erreur;
$e400='Mauvaise requête';
$f400="La requête HTTP n'a pas pu être comprise par le serveur en raison d'une syntaxe erronée.
Le problème peut provenir d'un navigateur web trop récent ou d'un serveur HTTP trop ancien.";
$e401='Non autorisé';
$f401="La requête nécessite une identification de l'utilisateur.
Concrètement, cela signifie que tout ou partie du serveur contacté est protégé par un mot de passe, qu'il faut indiquer au serveur pour pouvoir accéder à son contenu.";
$e403='Interdit';
$f403='Le serveur HTTP a compris la requête, mais refuse de la traiter.
Ce code est généralement utilisé lorsqu\'un serveur ne souhaite pas indiquer pourquoi la requête a été rejetée, ou lorsque aucune autre réponse ne correspond.';
$e404 ='Non Trouvé';
$f404='Le serveur n\'a rien trouvé qui corresponde à l\'adresse (URI) demandée ( non trouvé ).
Cela signifie que l\'URL que vous avez tapée ou cliquée est mauvaise ou obsolète et ne correspond à aucun document existant sur le serveur.';
$e500='Erreur interne du serveur';
$f500="Le serveur HTTP a rencontré une condition inattendue qui l'a empêché de traiter la requête.
Cette erreur peut par exemple être le résultat d'une mauvaise configuration du serveur, ou d'une ressource épuisée ou refusée au serveur sur la machine hôte.";
eval('$title.= " - ".$e'.$erreur.';');
$text = '<p>';
eval('$text .= $f'.$erreur.';');
$text .= '</p>';
header('HTTP/1.1 '.$erreur);
?>