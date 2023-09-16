# bilemo_api [![Codacy Badge](https://app.codacy.com/project/badge/Grade/031726461c12457dbfab0c0a13228764)](https://www.codacy.com/gh/genesis59/snowtricks_com/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=genesis59/snowtricks_com&amp;utm_campaign=Badge_Grade)
## Environnement de développement
### Prérequis
* git https://git-scm.com/downloads
* composer https://getcomposer.org/
* PHP 8.1
* Symfony CLI https://github.com/symfony-cli/symfony-cli
* Un système de gestion de bases de données relationnelles (PosgreSQL, MySQL, ...)
### Installation du projet
1. Cloner le projet à l'aide de la commande git clone via HTTPS:
   ```bash
   git clone https://github.com/genesis59/bilemo_api.git
   ```
   ou par SSH nécessite que votre clé SSH soit configurée sur GitHub
   ```bash
   git clone git@github.com:genesis59/bilemo_api.git
   ```
   puis entrez dans le projet
   ```bash
   cd bilemo_api
   ```
2. Installer les dépendances PHP :
    ```bash
    composer install
    ```
3. Variables d'environnement
   1. Copier le fichier .env dans un fichier .env.local
   2. Renseignez avec vos données les variables d'environnement dans le fichier .env.local
      - DATABASE_URL
      - SODIUM_KEY
      - JWT_SECRET_KEY
      - JWT_PUBLIC_KEY
      - JWT_PASSPHRASE (À renseigner avant la génération des clés privée et publique)
   3. Exemple pour la variable DATABASE_URL avec une base de données PosgreSQL :
   ```php
   DATABASE_URL="postgresql://user_name:your_password@localhost:5432/bilemo?serverVersion=15&charset=utf8"
   ```
   4. Assurez-vous d'avoir l'extension Sodium activée dans votre installation PHP. Vous pouvez vérifier cela en exécutant php -m | grep sodium dans votre terminal. Si ce n'est pas activé, vous devrez peut-être activer l'extension Sodium dans votre fichier php.ini ou installer la bibliothèque Sodium si elle n'est pas déjà installée.
   5. Pour générer la variable SODIUM_KEY, vous pouvez exécuter le script PHP suivant :
   ```php
   // Génération d'une nouvelle clé secrète
   $secretKey = sodium_crypto_secretbox_keygen();
   // Convertir la clé secrète en hexadécimal (pour l'afficher ou la stocker)
   $hexSecretKey = bin2hex($secretKey);
   // Afficher la clé secrète en hexadécimal
   echo "Clé secrète : " . $hexSecretKey . PHP_EOL;
   ```
   6. Pour générer les variables JWT_SECRET_KEY, JWT_PUBLIC_KEY, JWT_PASSPHRASE, vous pouvez vous référer à la documentation officielle: https://symfony.com/bundles/LexikJWTAuthenticationBundle/current/index.html
4. Configuration de la base de données locale :
    ```bash
    symfony console doctrine:database:create
    symfony console make:migration
    symfony console doctrine:migrations:migrate
    symfony console doctrine:fixtures:load
   ```
***
5. Lancement du serveur PHP depuis la racine du projet
   ```bash
   symfony server:start --port=3000
   ```
6. Pour finir, rendez-vous à l'adresse: https://127.0.0.1:3000/api/doc

