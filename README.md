# Bookworm

Requirements:
PHP (>= 8.2), 
Composer, 
Symfony CLI,
git, 
Docker

Install:
1. clone repo
2. cd to project and run composer install
3. setup .env (generate APP_SECRET using symfony, ENV and database globals)
4. cd .docker and run docker build .
5. cd .. and run docker-compose up -d
