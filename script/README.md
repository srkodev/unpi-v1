# Docker Setup for FDPCI

Ce dossier contient les configurations Docker pour déployer l'application FDPCI avec deux conteneurs séparés :
- Un conteneur pour l'application web PHP
- Un conteneur pour la base de données MySQL

## Prérequis

- Docker et Docker Compose installés sur votre machine
- Git pour cloner le dépôt

## Structure

- `docker-compose.yml` : Configuration des services Docker
- `Dockerfile.web` : Configuration du conteneur PHP/Apache
- `Dockerfile.db` : Configuration du conteneur MySQL
- `my.cnf` : Configuration MySQL
- `db-test.php` : Script PHP de test de connexion à la base de données
- `docker-config.php` : Configuration PHP adaptée pour l'environnement Docker
- `update-links.php` : Script pour remplacer les chemins "/unpi/public/" par "/"
- `update-docker-paths.sh` : Script pour configurer l'application pour Docker
- `site-url.php` : Définit l'URL de base du site
- `fix-includes.php` : Corrige les problèmes d'inclusion de fichiers
- `fix-mentions-legales.php` : Correction spécifique pour le fichier mentions-legales.php
- `add-admin.php` : Script pour créer/mettre à jour le compte administrateur

## Fonctionnalités

- **Base de données MySQL** préinstallée avec le schéma nécessaire
- **Compte administrateur** créé automatiquement (email: admin@fdcpi.fr, mot de passe: admin)
- **Initialisation automatique** : un service dédié exécute automatiquement le script d'ajout d'administrateur
- **Répertoires d'upload** précréés et configurés avec les bonnes permissions:
  - `/var/www/html/public/uploads/partenaires`
  - `/var/www/html/public/uploads/biens`
  - `/var/www/html/public/uploads/actualites`

## Configuration des chemins

Pour préparer l'application à fonctionner dans Docker (où la racine est "/"), exécutez :
```bash
cd script
php update-links.php
php fix-includes.php
php fix-mentions-legales.php
```

Ou utilisez le script qui automatise toutes les étapes :
```bash
cd script
php update-docker-paths.sh
```

Ce script va :
1. Remplacer tous les chemins "/unpi/public/" par "/" dans les fichiers
2. Corriger les problèmes d'inclusion de fichiers (includes)
3. Corriger spécifiquement le fichier mentions-legales.php qui utilise des chemins absolus
4. Mettre à jour la configuration Apache dans le Dockerfile
5. Créer les fichiers de configuration nécessaires

## Démarrage

1. Depuis la racine du projet, exécutez :
   ```bash
   docker-compose -f script/docker-compose.yml up -d --build
   ```

2. Accédez à l'application :
   - Site web : http://localhost:8080
   - Test de connexion à la base de données : http://localhost:8080/db-test.php
   - MySQL est accessible sur le port 3307 (au lieu de 3306 par défaut)

3. Pour arrêter les conteneurs :
   ```bash
   docker-compose -f script/docker-compose.yml down
   ```

## Accès à l'administration

Un compte administrateur est automatiquement créé lors de l'initialisation de la base de données :
- URL : http://localhost:8080/admin/login
- Email : admin@fdcpi.fr
- Mot de passe : admin

**IMPORTANT** : Pour la production, ce compte doit être modifié ou supprimé après la première utilisation.

## Configuration de la base de données

Le fichier `docker-config.php` remplace la configuration standard de l'application pour utiliser :
- Le nom d'hôte `db` (correspondant au service dans docker-compose.yml)
- Les identifiants configurés dans docker-compose.yml
- Un meilleur logging des erreurs pour faciliter le débogage

## Résolution de problèmes

Si vous rencontrez des erreurs lors du build :
- Vérifiez que les chemins dans docker-compose.yml sont corrects
- Assurez-vous que le dossier public existe à la racine de votre projet
- Vérifiez que les ports 8080 et 3307 sont disponibles
- Si une autre instance MySQL est déjà en cours d'exécution sur votre machine, 
  elle utilise probablement déjà le port 3306, c'est pourquoi nous avons configuré 
  le conteneur pour utiliser le port 3307
- Si les liens dans l'application ne fonctionnent pas, vérifiez si tous les chemins "/unpi/public/"
  ont bien été remplacés par le script update-links.php

### Problèmes d'inclusion de fichiers

Si vous voyez des erreurs comme `Failed to open stream: No such file or directory`, cela peut être 
dû à des chemins d'inclusion incorrects. Les scripts `fix-includes.php` et `fix-mentions-legales.php` 
sont conçus pour corriger automatiquement ces problèmes, mais vous pouvez aussi les corriger manuellement :

1. Remplacez les inclusions absolues comme :
   ```php
   $root = $_SERVER['DOCUMENT_ROOT'] . '/unpi';
   include $root . '/public/include/file.php';
   ```
   
   Par des inclusions relatives :
   ```php
   include __DIR__ . '/../include/file.php';
   ```

## Persistance des données

Les données de la base MySQL sont stockées dans un volume Docker nommé `mysql_data`.
Pour supprimer toutes les données et recommencer à zéro :

```bash
docker-compose -f script/docker-compose.yml down -v
```

## Mot de passe

**Important** : Pour la production, changez les mots de passe définis dans :
- `docker-compose.yml`
- La base de données (database/db.sql)
- Le compte administrateur par défaut 