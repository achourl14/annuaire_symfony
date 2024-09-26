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
> Ce projet contient des dépendances en plus de celles de Symfony, notamment Tailwind. Des étapes supplémentaires sont nécessaires pour que l'ensemble du projet fonctionne.

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

4) Lancer cette commande à la racine du projet, dans le terminal Docker, afin de lancer Tailwind:
```shell
php bin/console tailwind:build --watch
```

> [!NOTE]
> Si vous avez installé la CLI Symfony en local sur la machine hôte, vous pouvez également le faire dans un terminal de l'hôte:
```shell
symfony console tailwind:build --watch
```

### Configuration
Une configuration générique des variables d'environnement se trouve dans le fichier `.env`. Si vous avez besoin d'y apporter des modifications, créez et utilisez le fichier `.env.local`. 

## Fonctionnement de l'annuaire
Pour accéder à la page d'accueil, simplement accéder à la route `/` (probablement [via ce lien](https://localhost/s5-web-projet1/public) si vous utilisez le docker but3-web-container).

### Routes API
- Obtenir la liste des utilisateurs avec leurs détails (telle qu'on la verrait sur la page d'accueil)
```
/api/utilisateurs
```

- Obtenir les détails d'un utilisateur (en précisant son code secret)
```
/api/utilisateurs/{code}
```

### Liste des commandes

> [!NOTE]
> Les arguments entre crochets sont optionnels.
> Pour les arguments booléens (est visible, est admin...), les valeurs acceptées sont `y` et `n` (oui et non respectivement).

- **Créer un utilisateur.**
```shell
php bin/console app:make-user [login] [email] [password] [visibilité] [admin] [code]
# Exemples
# Les informations manquantes seront demandées via un mode interactif, à la manière de make:entity
php bin/console app:make-user nadal34 cyrille.nadal@umontpellier.fr Nadal34! y y profildenadal34
php bin/console app:make-user 
php bin/console app:make-user achourl
```

## Fonctionnalités effectuées et répartition du travail
### Fonctionnalités et contraintes obligatoires
- [X] Lors de l’**inscription** (via un formulaire) l’utilisateur précise seulement un minimum d’informations : login, adresse email, mot de passe et la visibilité du profil (visible/masqué).
  - [X] Chaque profil doit être associé à un **code unique**. Pendant l’inscription, l’utilisateur peut choisir de préciser lui-même ce code ou non (à condition qu’il ne soit pas déjà pris). S’il ne précise rien, un code aléatoire sera alors généré. Quand l’utilisateur décide lui-même de saisir un code, l’application doit vérifier en temps réel que le code n’est pas déjà pris, avant la soumission du formulaire (donc, en utilisant du javascript et des requêtes asynchrones). Le code ne doit contenir que des caractères alphanumériques.
  - [X] Le profil de l’utilisateur possède un **mode de visibilité** qui indique s’il peut être publiquement listé ou non. Un profil peut donc être soit visible (listé) ou bien masqué (non listé).
- [X] La **page principale** du site doit afficher tous les profils visibles.
  - [ ] À partir de cette page, on doit aussi pouvoir accéder facilement aux **pages de profils des utilisateurs** listés.
- [ ] Une **route incluant le code du profil** permet d’accéder et de visualiser la page de profil d’un utilisateur (par exemple /profil/{code}). Il n’y a pas besoin d’être connecté pour cela.
  - [ ] Attention, même si le profil est masqué, il peut toujours être **consulté via l’adresse et le code du profil**. S’il est masqué, il n’est simplement pas listé sur la page principale.
  - [X] En plus de la route qui permet de visualiser le profil de l’utilisateur sur une page dédiée, une autre route (qui inclue donc aussi le code secret du profil) doit **renvoyer les informations de l’utilisateur au format JSON** (donc, pas une page web complète, seulement les données). Cela vous servira plus tard, lors du 3ᵉ projet où vous utiliserez directement de ce service.
  - [X] Sur le profil, l’application doit afficher la **dernière date où a été édité le profil et la dernière date de connexion** de l’utilisateur.
  - [ ] Attention, vous devrez faire en sorte que la dernière date d’édition du profil soit mise à jour dès que l’objet (entité) stockant l’utilisateur est mise à jour, peu importe l’endroit où cela est fait : dans un contrôleur, dans un service, dans une commande, etc. Il faut ainsi faire en sorte de ne pas avoir à dupliquer le code gérant cette logique si une nouvelle portion de code mettant à jour cette entité est implémentée.
- [ ] Une fois connecté, l’utilisateur peut **éditer son profil** avec des informations complémentaires (par exemple, numéro de téléphone, pays, adresse postale, réseaux sociaux, etc.). À vous de trouver les données qui vous semblent intéressantes à préciser sur le profil.
  - [ ] Le **formulaire d’édition** du profil doit être automatiquement **pré-rempli**.
  - [ ] À tout moment, l’utilisateur peut **changer le code associé à son profil** (soit en spécifiant un nouveau, soit en demandant la génération d’un code aléatoire).
  - [ ] L’utilisateur peut **supprimer son profil**.
  - [ ] L’utilisateur peut **changer la visibilité de son profil** (de visible à masqué ou inversement).
- [X] Certains utilisateurs peuvent **posséder le rôle d’administrateur**.
  - [X] Sur la page principale du site, en plus des profils visibles, un administrateur peut aussi visualiser et accéder aux profils masqués.
  - [ ] Aussi, à partir d’un profil, un administrateur peut supprimer le compte de l’utilisateur qui possède ce profil, sauf si cet utilisateur est aussi un administrateur.
- [X] Le site doit pouvoir être passé en **mode maintenance** à l’aide d’un nouveau paramètre que vous pourrez définir et modifier dans le fichier services.yaml. Quand le site est en mode maintenance, toutes les pages du site doivent rediriger sur une page qui affiche un message expliquant que le site est actuellement en maintenance.
- [X] Une **commande** (Symfony) doit permettre de **créer un utilisateur depuis le terminal** en précisant ses informations et son rôle (normal/administrateur). Les informations pourront être données directement en argument de la commande, ou alors en mode interactif.
- [X] Seul le javascript “nature” est autorisé (pas de framework JS, juste de simples fichiers javascript, comme dans le TD3).

### Répartition du travail
- Xavier T.:
  - Mise en place du projet
  - Connexion
  - Inscription
  - CSS
- Lisa A.:
  - Supprimer son profil
  - Modifier son profil
  - Messages flash
  - Détails du profil
- Ilan V.:
  - Liste des utilisateurs (page d'accueil)
  - Mode maintenance
  - Commandes
  - Gestion du rôle administrateur
  - Routes JSON
