# EEC Task

## Tech Stack
**Backend:**
- Laravel 10.x  ,  PHP 8.1+  ,  MySQL 8.0+

**Frontend:**
- Bootstrap 5.3  , Bootstrap Icons (should use internet using CDNs)

**Tools:**
Composer  , Git


## Requirements
Before you begin, ensure you have the following installed:

## Installation
### Option 1: Clone from GitHub
# Clone the repository
git clone https://github.com/Alfawakhry2/EEC-Task.git

### Option 2: Extract from .rar File
Extract the downloaded ".rar" file (task.rar)

##  Setup Steps
1- composer install
2- cp .env.example .env (copy .env.example file to .env (create it first))
3- php artisan key:generate
4- php artisan storage:link

## Configure Database
Open .env file and update database credentials:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pharmacy_db
DB_USERNAME=root
DB_PASSWORD=


## Database Setup (should create new database)
**Using phpMyAdmin**
1. Open phpMyAdmin
2. Click "New" to create database
3. Name: `pharmacy_db`
4. Collation: `utf8mb4_unicode_ci`

## migrate and seed data 
php artisan migrate

# Seed with sample data (1000 products, 200 pharmacies)
php artisan db:seed

## Running the Application (http://localhost:8000)
php artisan serve

**Default Pages:**
- Products: `http://localhost:8000/products`
- Pharmacies: `http://localhost:8000/pharmacies`
- Search: `http://localhost:8000/products/search`
- localization (ar , en)

### CLI Command

**Find 5 cheapest pharmacies for a product**

php artisan products:search-cheapest {product_id}   , example : php artisan products:search-cheapest 1

# API Collection and published collection of postman via these method
1 - link collection in postman : https://www.postman.com/go-grow/workspace/go-grow-workspace/collection/29335427-e51a038f-2d91-4566-8525-37398f7ea49c?action=share&creator=29335427
2 - published link : https://documenter.getpostman.com/view/29335427/2sBXVihVR8 
3 - import file in project folder named (EEC Task.postman_collection.json) to your postman 
