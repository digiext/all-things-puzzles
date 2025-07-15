# Name

All Things Puzzles

# Description

All Things Puzzles is an inventory management system of your personal puzzle collection. It has the following features:

* Master Puzzle List
* Multi-user capable
* Wishlist

## Requirements

* Machine with docker installed

## Installation

Clone Repo
```bash
git clone https://github.com/digiext/all-things-puzzles.git
```
Change to cloned directory
```bash
cd all-things-puzzles
```

Copy example env file to app directory and edit
```bash
cp env-example app/.env

nano app/.env file
```

Change permissions to upload folder
```bash
chmod 777 app/images/uploads/thumbnails
```

Edit docker-compose.yml file and set passwords
```bash
nano docker-compose.yml
```
Start dockers up
```bash
docker compose up -d
```

Once dockers are running, use the following command to update composer requirements
```bash
docker exec -it all-things-puzzles-php-1 composer update
```

# Access application

Go to your default webpage.  Ex: http://"ip address of machine"/ or http://"hostname"/