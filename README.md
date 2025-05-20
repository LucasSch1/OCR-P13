# 🌿 Projet 13

Le projet consiste à développer une plateforme complète pour GreenGoodies, boutique lyonnaise spécialisée dans la vente de produits biologiques, éthiques et écologiques.

Ce projet inclut :

- **Le front-end**, permettant aux clients de consulter les produits, de s’authentifier et de passer commande. Le design devra respecter fidèlement les maquettes fournies afin d’assurer une expérience utilisateur optimale.

- **Le back-end**, qui fournira une API avec des routes spécifiques pour l’authentification et la récupération de la liste des produits. Cette API permettra notamment à des partenaires externes d’afficher les produits GreenGoodies sur leur propre site. Un bouton dans la gestion utilisateur activera cet accès API.

Le développement de l’API sera intégré directement dans le projet Symfony principal, évitant ainsi la création de projets séparés.

---

# 🚀 Installation et Configuration du Projet

## 📥 1. Cloner le projet

Clonez le dépôt sur votre machine locale :
```bash
git clone https://github.com/LucasSch1/OCR-P13.git
cd OCR-P13
```

---

## 📦 2. Installer les dépendances

Dans le terminal, exécutez la commande suivante pour installer toutes les dépendances PHP :
```bash
composer install
```

---

## 🛠 3. Configurer les variables d'environnement

Créez un fichier `.env.local` à la racine du projet et configurez votre connexion à la base de données :

```bash
DATABASE_URL="mysql://votre_user:votre_password@127.0.0.1:3306/nom_de_votre_bdd?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
```

---

## 🔐 4. Générer les clés pour JWT

Créez un dossier `jwt` dans le répertoire `config/` :
```bash
mkdir config/jwt
```

Générez les clés JWT depuis votre terminal (Git Bash par exemple) avec un mot de passe :

```bash
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```

**Attention** : retenez bien le mot de passe choisi lors de la génération des clés !

---

## ✏️ 5. Modifier `.env.local` pour JWT

Ajoutez les variables suivantes dans votre fichier `.env.local`, en remplaçant `votre_mot_de_passe` par celui choisi pour les clés :

```bash
###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=votre_mot_de_passe
###< lexik/jwt-authentication-bundle ###
```

---

## 🏗️ 6. Préparer la base de données

Créer la base de données :
```bash
php bin/console doctrine:database:create
```

Appliquer les migrations :
```bash
php bin/console doctrine:migrations:migrate
```

Vérifier la synchronisation avec le schéma :
```bash
php bin/console doctrine:schema:validate
```

Charger les données de test (fixtures) :
```bash
php bin/console doctrine:fixtures:load
```

---

## 🚀 7. Lancer le serveur Symfony

Démarrez le serveur Symfony avec :
```bash
symfony server:start
```

---

## 📚 8. Tester l'API et le site web

Vous pouvez tester l’API avec Postman sur les routes suivantes :

- `https://127.0.0.1:8000/api/login`  
- `https://127.0.0.1:8000/api/products`

Et également accéder au site web en ouvrant dans votre navigateur :

- `https://127.0.0.1:8000/`  

---

# ✅ Votre projet est maintenant prêt à l'emploi !
