# Espace Adhérent - FDPCI

## Description

L'espace adhérent est une section privée du site FDPCI accessible uniquement aux membres avec un mot de passe.

## Accès

- **URL d'accès** : `/espace-adherent`
- **Page de connexion** : `/espace-adherent-login`
- **Lien** : Disponible dans le footer du site

## Configuration

### Mot de passe

Le mot de passe est défini dans le fichier `app/config/espace-adherent.php` :

```php
define('ESPACE_ADHERENT_PASSWORD', 'adherent2025');
```

⚠️ **IMPORTANT** : Changez ce mot de passe en production !

### Durée de session

Par défaut, la session expire après 24 heures. Vous pouvez modifier cette durée dans le même fichier :

```php
define('ESPACE_ADHERENT_SESSION_DURATION', 24 * 60 * 60); // 24 heures
```

## Fonctionnalités

### Page de connexion (`espace-adherent-login.php`)
- Formulaire de saisie du mot de passe
- Vérification automatique si déjà connecté
- Messages d'erreur en cas de mot de passe incorrect

### Page principale (`espace-adherent.php`)
- Accès aux documents exclusifs
- Informations sur les formations
- Support privilégié
- Actualités membres
- Calendrier des événements
- Bouton de déconnexion

## Sécurité

- **Sessions PHP** : Utilise les sessions pour maintenir l'état de connexion
- **Expiration automatique** : Les sessions expirent après 24 heures
- **Protection des pages** : Redirection automatique vers la connexion si non authentifié
- **Déconnexion sécurisée** : Nettoyage complet des variables de session

## Structure des fichiers

```
app/config/espace-adherent.php    # Configuration et fonctions utilitaires
public/view/espace-adherent-login.php    # Page de connexion
public/view/espace-adherent.php          # Page principale
public/include/footer.php               # Lien ajouté dans le footer
public/index.php                        # Routes ajoutées
```

## Utilisation

1. **Accès** : Cliquez sur "Espace adhérent" dans le footer
2. **Connexion** : Entrez le mot de passe : `adherent2025`
3. **Navigation** : Explorez le contenu réservé aux membres
4. **Déconnexion** : Cliquez sur "Se déconnecter" en haut à droite

## Maintenance

### Changer le mot de passe

1. Ouvrez `app/config/espace-adherent.php`
2. Modifiez la valeur de `ESPACE_ADHERENT_PASSWORD`
3. Sauvegardez le fichier

### Ajouter du contenu

Modifiez le fichier `public/view/espace-adherent.php` pour ajouter :
- Nouvelles sections
- Documents téléchargeables
- Informations spécifiques aux membres

### Personnaliser le design

Les styles CSS sont intégrés dans chaque page. Vous pouvez :
- Modifier les couleurs
- Ajuster la mise en page
- Ajouter des animations

## Support

Pour toute question ou problème, contactez l'administrateur du site. 