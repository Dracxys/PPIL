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

Pour importer la base de données, créez une bdd avec le nom que vous voulez puis importez SQL/initBDD.sql dans cette base.

Il faut ensuite à modifier le fichier db.ppil.conf.ini :
```
driver=mysql
username=nom
password=mot de passe
host=localhost
database=bdd2folie
charset=utf8
collation=utf8_unicode_ci
```

avec vos identifiants mysql et le nom que vous avez choisi pour que l'application puisse se connecter à la base de données.

Compte gmail de la messagerie :
```
Email : ppil.email1@gmail.com
MDP : L3INFORMATIQUE
```