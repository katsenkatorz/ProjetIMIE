Projet
===

Procédure d'installation
==

Créer la base de données:
=
     - php bin/console doctrine:database:create

Créer les tables de la base de données:
=
     - php bin/console doctrine:schema:update --force
 
Ajouter le jeux de données de base avec:
=
     - php bin/console doctrine:fixture:load
 
Ajouter les dépendances avec composer:
=
     - composer install
 
Installation de CKEditor:
=
     - php bin/console ckeditor:install
     - php bin/console assets:install web

Connecter vous sur l'administration avec les crédentials de base:
=
     - Nom d'utilisateur: admin
     - Mot de passe: P@ssword

Puis changer ces informations dans éditer le profil !


Package installer:
==
    - beberlei/DoctrineExtensions
    - egeloen/ckeditor-bundle
    - thadafinser/user-agent-parser
    - whichbrowser/parser
    - doctrine/doctrine-fixtures-bundle


Il faut aussi que geoip v1.1.0 minimum soit installer sur le serveur, pour l'installer:

DEBIAN :
= 
     - sudo apt-get install php7.0-geoip


MAMP OSX :
=
    - sudo pecl install http://pecl.php.net/get/geoip-1.1.1.tgz