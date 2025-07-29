# All Things Puzzles

All Things Puzzles is an inventory management system of your personal puzzle collection.

Current Features include:
* Master Puzzle List
* Multi-user capable
* Wishlist
* API
* And more!

## Screenshots
**Main Page**
![Main Page](/images/main-page.png?raw=true "Main Page") 

**Puzzle Inventory**
![Puzzle Inventory Page](/images/puzzle-inv-page.png?raw=true "Puzzle Inventory Page") 

**User Inventory Add Page**
![User Inventory Add Page](/images/user-add-puzzles.png?raw=true "User Inventory Add Page") 

**User Inventory Page**
![User Inventory Page](/images/user-inv-manage.png?raw=true "User Inventory Page") 

**User Wishlist Page**
![User Wishlist](/images/user-wishlist.png?raw=true "User Wishlist")

## Docker Install

### Requirements

* Machine with docker and git installed

### Installation

Clone Repo
```bash
git clone https://github.com/digiext/all-things-puzzles.git
```
Change to cloned directory
```bash
cd all-things-puzzles
```

Copy example env file to app directory and edit.  Make sure to set the password for the database and that it matches what is in the docker-compose.yml file.
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

## Webserver Install

### Requirements

* Machine running a webserver (nginx or apache), php8.4, mariadb, and has php composer

## Installation

* Download apponly.zip from the latest release on the Releases page

* Unzip apponly.zip file to the root of your webserver

* Create database using sql file available in the repo

* Create .env file in root web directory based off the env-example file in the repo

* Navigate to web root directory and run php composer update to generate vendor files

## Access application

Go to your default webpage.  Ex: <http://ip_address_of_machine/> or <http://hostname/>