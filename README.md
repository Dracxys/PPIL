# PPIL

## Prérequis
- php 7 au minimum
- apache
- composer installé
- Mysql/Mariadb

## Installation
Pour installer les dépendances, executez :
```
composer update
composer install
```
Un script doit être executé pour placer bootstrap dans le dossier assets/bootstrap et créer le dossier imports/
Les dossiers imports/ et assets/images/profil_pictures doivent avoir l'accès, la lecture et l'écriture autorisées.

Pour importer la base de données, créez une bdd avec le nom que vous voulez puis importez SQL/initBDD.sql dans cette base.

Il faut ensuite créer le fichier db.ppil.conf.ini et le placer à la racine du projet :
```
driver=mysql
username=nom
password=mot de passe
host=localhost
database=bdd2folie
charset=utf8
collation=utf8_unicode_ci
```

avec vos identifiants mysql et le nom que vous avez choisi à la BDD pour que l'application puisse se connecter à la base de données.

Compte gmail de la messagerie :
```
Email : ppil.email1@gmail.com
MDP : L3INFORMATIQUE
```
