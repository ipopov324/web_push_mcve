MCVE for reproducing 500 error
==========
## Requirements

* [Docker and Docker Compose](https://docs.docker.com/engine/installation)

## Installation

### 1. Start Containers and install dependencies 
On Linux:
```bash
docker-compose up -d
```
### 2. Install vendors
```bash
docker-compose exec php composer install
```
### 3. Update .env:
```bash
cp .env.dist .env
```
And fill .env with correct data

##Testing the 500 bug
### 1.Enter the php container
```bash
docker-compose exec php bash
```
### 2. Run the run.php file
```bash
php -f run.php
```
