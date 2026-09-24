
<img width="1890" height="902" alt="image" src="https://github.com/user-attachments/assets/9bc2e781-76d8-45b4-a763-0e2fb6914a93" />

<img width="1910" height="907" alt="image" src="https://github.com/user-attachments/assets/291fd5f5-8b6e-4f10-b34c-9f8f0077edd3" />

# NutriTrace

NutriTrace est une application Laravel dédiée à la traçabilité dans la chaîne alimentaire et nutritionnelle. Elle prend en charge plusieurs rôles utilisateurs, la vérification des comptes, les demandes d'approbation et un tableau de bord sécurisé.

## Fonctionnalités

- Inscription, connexion, déconnexion et réinitialisation du mot de passe
- Authentification à deux facteurs par e-mail
- Sélection d'un rôle : producteur, transformateur, distributeur ou consommateur
- Processus d'approbation des comptes
- Gestion des permissions avec Spatie Laravel Permission
- Authentification JWT pour les APIS
- Gestion des ressources frontend avec Vite

## Prérequis

- PHP 8.3 ou version ultérieure
- Composer
- Node.js et npm
- Une base de données configurée

## Installation

```bash
git clone <repository-url>
cd NUTRITRACEPROJECT
composer run setup
```

Configurez la base de données, la messagerie, reCAPTCHA et les services Google dans `.env` avant d'utiliser l'application. La commande d'installation installe les dépendances, crée la clé de l'application, exécute les migrations, installe les dépendances frontend et compile les ressources.

## Lancement en local

```bash
composer run dev
```

L'application sera disponible à l'adresse locale affichée par Laravel. Pour lancer uniquement le serveur de développement frontend, utilisez :

```bash
npm run dev
```

## Architecture NutriTrace

<img width="1555" height="1011" alt="9dd2b574-c565-4815-9fa8-46a424f9de57" src="https://github.com/user-attachments/assets/c7e8c94d-d79e-49d0-8ce0-775b62114cf3" />

