# FDPCI Aube - Site Web

Site web de la Fédération Départementale des Propriétaires et Copropriétaires Immobiliers de l'Aube.

## Installation

1. Cloner le repository
2. Configurer la base de données dans `app/config/config.php`
3. Configurer le système d'envoi d'emails (voir section ci-dessous)
4. Importer la base de données depuis `database/db.sql`

## Configuration du système de contact avec Resend

Le site utilise [Resend](https://resend.com/) pour l'envoi d'emails depuis le formulaire de contact.

### 1. Obtenir une clé API Resend

1. Créez un compte sur [resend.com](https://resend.com/)
2. Vérifiez votre domaine dans Resend
3. Générez une clé API

### 2. Configuration

Modifiez les constantes dans `app/config/config.php` :

```php
// Configuration Resend pour l'envoi d'emails
define('RESEND_API_KEY', 're_votre_cle_api_ici');
define('CONTACT_FROM_EMAIL', 'contact@votre-domaine.fr'); // Doit être vérifié dans Resend
define('CONTACT_TO_EMAIL', 'admin@votre-domaine.fr'); // EMAIL DE DESTINATION - CHANGEZ ICI
define('CONTACT_FROM_NAME', 'Site FDPCI Aube');
```

### 3. Changer l'email de destination

Pour modifier l'email qui recevra les messages de contact, il suffit de changer la valeur de `CONTACT_TO_EMAIL` dans `app/config/config.php`.

**Exemple :**
```php
define('CONTACT_TO_EMAIL', 'president@fdpci-aube.fr'); // Nouveau destinataire
```

### 4. Fonctionnalités du système de contact

- ✅ Validation côté client et serveur
- ✅ Envoi d'emails via API Resend
- ✅ Template HTML professionnel
- ✅ Réponse AJAX avec feedback utilisateur
- ✅ Bouton de loading pendant l'envoi
- ✅ Reply-to automatique vers l'expéditeur
- ✅ Logs d'erreur pour debug

### 5. Test du formulaire

1. Configurez votre clé API Resend
2. Vérifiez que votre domaine est validé dans Resend
3. Testez le formulaire sur `/contact`
4. Vérifiez les logs en cas d'erreur

## Structure du projet

```
unpi-v1/
├── app/
│   ├── config/
│   ├── controller/
│   │   ├── ContactController.php  # Nouveau contrôleur pour le contact
│   │   └── ...
│   └── models/
├── public/
│   ├── view/
│   │   ├── contact.php  # Page de contact mise à jour
│   │   └── ...
│   └── ...
└── database/
```

## Fonctionnalités

- Gestion des actualités
- Gestion des biens immobiliers
- Gestion des partenaires
- **Formulaire de contact fonctionnel avec Resend**
- Espace adhérent
- Interface d'administration

## Technologies

- PHP 8+
- MySQL
- Bootstrap 5
- **Resend API pour les emails**
- FontAwesome
- JavaScript (Fetch API)

## Support

Pour toute question concernant la configuration du système de contact, consultez la documentation de [Resend](https://resend.com/docs) ou vérifiez les logs d'erreur PHP.
```
unpi
├─ app
│  ├─ config
│  │  └─ config.php
│  ├─ controller
│  │  ├─ ActualiteController.php
│  │  ├─ BienController.php
│  │  └─ PartenaireController.php
│  └─ model
│     ├─ Actualite.php
│     ├─ ActualiteImage.php
│     ├─ BaseModel.php
│     ├─ Bien.php
│     ├─ BienImage.php
│     └─ Partenaire.php
├─ database
│  └─ db.sql
└─ public
   ├─ .htaccess
   ├─ asset
   │  ├─ css
   │  │  └─ style.css
   │  └─ js
   ├─ include
   │  ├─ footer.php
   │  └─ header.php
   ├─ index.php
   └─ view
      ├─ actualite-detail.php
      ├─ actualites.php
      ├─ adherents.php
      ├─ adhesion.php
      ├─ bien-detail.php
      ├─ biens.php
      ├─ contact.php
      ├─ home.php
      ├─ mentions-legales.php
      └─ partenaires.php

```