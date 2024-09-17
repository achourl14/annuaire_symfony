<h1 align="center">R5.05 - Projet n°1 : Annuaire</h1>

<h2 align="center">Groupe de:</h2>
<p align="center">Xavier TROUCHE</p>
<p align="center">Ilan VELTER</p>
<p align="center">Lisa ACHOUR</p>
<p align="center"><i>Groupe G5 de l'IUT de Montpellier-Sète</i></p>
<p align="center"><i>dans le cadre du cours de Frameworks Web</i></p>

## Liens utiles
- [Dépôt Git](https://github.com/projets-xil/s5-web-projet1)
- [Sujet du projet](https://mgasquet.github.io/R5.A.05-ProgrammationAvancee-Web/tutorials/projet1)
- [Lien vers le tableau Trello](https://github.com/orgs/projets-xil/projects/1)

## Lancer le projet

> [!NOTE]
> Ce présent tutoriel part du principe que vous avez mis en place l'image Docker [but3-web-container](https://gitlabinfo.iutmontp.univ-montp2.fr/progweb-but3/docker).
 
> [!IMPORTANT]
> Ce projet contient des dépendances en plus de celles de Symfony (notamment Tailwind). Vous aurez donc besoin de NPM (sur la machine hôte ou sur le container Docker).

1) Se positionner dans le dossier /shared/public_html et exécuter la commande suivante:
```shell
git clone git@github.com:projets-xil/s5-web-projet1.git
```

2) Si ce n'est pas déjà fait, déclarer l'URL de la base de données dans le fichier `.env` en remplaçant la ligne `DATABASE_URL=...` par:
```shell
DATABASE_URL=mysql://root:root@db:3306/annuaire
```

> [!NOTE]
> Si une base de données du nom d'*annuaire* existe déjà au sein de la BDD du conteneur Docker, pensez à la renommer ou, au cas échéant, à changer la cible de *DATABASE_URL*.

3) Dans le terminal du conteneur Docker (via Docker Desktop ou via la CLI), se placer dans le dossier /shared/public_html/s5_proj1 et exécuter les commandes suivantes:
```shell
composer install
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

4) Lancer ces commandes à la racine du projet (soit dans le terminal hôte, soit dans le terminal Docker, selon là où vous avez installé NPM):
```shell
npm i
npm run watch # Cette action est bloquante, ouvrez un autre terminal si besoin
```

## Fonctionnement de l'annuaire
todo

### Liste des commandes

> [!NOTE]
> Les arguments entre crochets sont optionnels.
> Pour les arguments booléens (est visible, est admin...), les valeurs acceptées sont `o` et `n` (oui et non respectivement).

todo: update les commandes si besoin

- **Donner le rôle d'administrateur à l'utilisateur désigné par le login précisé.**
```shell
php bin/console app:grant-admin login
```

- **Créer un utilisateur.**
```shell
php bin/console app:create-user login email password visibilité [admin] [code]
```
Exemple:
```shell
php bin/console app:create-user nadal34 cyrille.nadal@umontpellier.fr Nadal34! o o profildenadal34
```

## Répartition du travail
todo 
