# 🏥 Pharmacy Management System

## Tech Stack
**Backend:**
- Laravel 10.x
- PHP 8.1+
- MySQL 8.0+

**Frontend:**
- Bootstrap 5.3
- Bootstrap Icons

**Tools:**
- Composer
- Git

---

## Requirements

Before you begin, ensure you have the following installed:

- **PHP** >= 8.1
  - Extensions: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo
- **Composer** >= 2.0
- **MySQL** >= 8.0 
- **Git** (optional, for cloning) or extract .rar file . 


## Installation

### Option 1: Clone from GitHub

# Clone the repository
git clone 

# Navigate to project directory
cd pharmacy-management-system
```

### Option 2: Extract from .rar File

1. Extract the downloaded `.rar` file
2. Open terminal/command prompt in the extracted folder

---

## 📥 Setup Steps

### Step 1: Install PHP Dependencies

```bash
composer install
```

If you encounter memory issues:
```bash
composer install --no-dev --optimize-autoloader
```

### Step 2: Install Node Dependencies

```bash
npm install
```

### Step 3: Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Database

Open `.env` file and update database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pharmacy_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

---

## 🗄️ Database Setup

### Step 1: Create Database

**Option A: Using MySQL Command Line**
```bash
mysql -u root -p
```

```sql
CREATE DATABASE pharmacy_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

**Option B: Using phpMyAdmin**
1. Open phpMyAdmin
2. Click "New" to create database
3. Name: `pharmacy_db`
4. Collation: `utf8mb4_unicode_ci`

### Step 2: Run Migrations

```bash
# Run all migrations
php artisan migrate
```

### Step 3: Seed Database (Optional but Recommended)

```bash
# Seed with sample data (1000 products, 200 pharmacies)
php artisan db:seed
```

This will create:
- ✅ 1000+ sample products
- ✅ 200+ sample pharmacies
- ✅ 5000+ pharmacy-product relationships

**Skip seeding if you want to start with empty database.**

### Step 4: Create Storage Link

```bash
php artisan storage:link
```

This creates a symbolic link for file uploads.

---

## ▶️ Running the Application

### Start Development Server

```bash
php artisan serve
```

The application will be available at: **http://localhost:8000**

### Run Vite (for assets) - Optional

In a new terminal:
```bash
npm run dev
```

For production build:
```bash
npm run build
```

---

## 🌐 Access the Application

### Web Interface

Open your browser and navigate to:

```
http://localhost:8000
```

**Default Pages:**
- Products: `http://localhost:8000/products`
- Pharmacies: `http://localhost:8000/pharmacies`
- Search: `http://localhost:8000/products/search`

### API Endpoints

Base URL: `http://localhost:8000/api`

**Health Check:**
```bash
curl http://localhost:8000/api/health
```

**Get Products:**
```bash
curl http://localhost:8000/api/products
```

---

## 📚 API Documentation

### Complete API Endpoints (21 Total)

#### **Products API (6 endpoints)**

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/products` | List all products (paginated) |
| GET | `/api/products-search?q={query}` | Search products |
| POST | `/api/products` | Create new product |
| GET | `/api/products/{id}` | Get product details |
| PUT | `/api/products/{id}` | Update product |
| DELETE | `/api/products/{id}` | Delete product |

#### **Pharmacies API (5 endpoints)**

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/pharmacies` | List all pharmacies (paginated) |
| POST | `/api/pharmacies` | Create new pharmacy |
| GET | `/api/pharmacies/{id}` | Get pharmacy details |
| PUT | `/api/pharmacies/{id}` | Update pharmacy |
| DELETE | `/api/pharmacies/{id}` | Delete pharmacy |

#### **Pharmacy-Product Management (6 endpoints)**

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/pharmacies/{id}/products` | Get all products in pharmacy |
| GET | `/api/pharmacies/{id}/available-products` | Get products NOT in pharmacy |
| POST | `/api/pharmacies/{id}/products` | Add product to pharmacy |
| GET | `/api/pharmacies/{id}/products/{productId}` | Get product details in pharmacy |
| PUT | `/api/pharmacies/{id}/products/{productId}` | Update product price/quantity |
| DELETE | `/api/pharmacies/{id}/products/{productId}` | Remove product from pharmacy |

#### **Utilities (4 endpoints)**

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/health` | API health check |
| GET | `/api/statistics` | System statistics |

---

### API Response Format

All API responses follow this consistent format:

**Success Response:**
```json
{
    "status_code": 200,
    "message": "Operation successful",
    "data": { ... },
    "error": null
}
```

**Error Response:**
```json
{
    "status_code": 404,
    "message": "Resource not found",
    "data": null,
    "error": "Additional error details"
}
```

**Paginated Response:**
```json
{
    "status_code": 200,
    "message": "Products retrieved successfully",
    "data": {
        "products": [ ... ],
        "pagination": {
            "total": 150,
            "count": 15,
            "per_page": 15,
            "current_page": 1,
            "total_pages": 10,
            "links": {
                "first": "...",
                "last": "...",
                "prev": null,
                "next": "..."
            }
        }
    },
    "error": null
}
```

---

### Example API Requests

#### **1. Create Product**
```bash
curl -X POST http://localhost:8000/api/products \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Panadol 500mg",
    "description": "Pain relief medication",
    "price": 25.50,
    "quantity": 100
  }'
```

#### **2. Get All Products (Paginated)**
```bash
curl http://localhost:8000/api/products?page=1
```

#### **3. Search Products**
```bash
curl "http://localhost:8000/api/products-search?q=panadol"
```

#### **4. Add Product to Pharmacy**
```bash
curl -X POST http://localhost:8000/api/pharmacies/1/products \
  -H "Content-Type: application/json" \
  -d '{
    "product_id": 5,
    "price": 23.50,
    "quantity": 100
  }'
```

#### **5. Update Product Quantity Only**
```bash
curl -X PUT http://localhost:8000/api/pharmacies/1/products/5 \
  -H "Content-Type: application/json" \
  -d '{
    "quantity": 150
  }'
```

#### **6. Get Products in Pharmacy**
```bash
curl http://localhost:8000/api/pharmacies/1/products
```

#### **7. Get System Statistics**
```bash
curl http://localhost:8000/api/statistics
```

---

## 📮 Postman Collection

A Postman collection is included for easy API testing.

### Import Collection

1. Open **Postman**
2. Click **Import** button (top left)
3. Select the file: `Pharmacy_API_Collection.json` (included in project root)
4. Click **Import**

### Configure Environment

After importing:
1. Click on the **Pharmacy Management API** collection
2. Go to **Variables** tab
3. Set `base_url` to: `http://localhost:8000/api`
4. Save

### Test Endpoints

All 21 endpoints are organized in folders:
- 📁 Health Check (2 requests)
- 📁 Products (6 requests)
- 📁 Pharmacies (5 requests)
- 📁 Pharmacy-Product Management (6 requests)
- 📁 Search (2 requests)

Simply click on any request and click **Send** to test!

---

## 📂 Project Structure

```
pharmacy-management-system/
├── app/
│   ├── Console/Commands/
│   │   └── SearchCheapestPharmacies.php   # CLI command
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/                        # API Controllers
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── PharmacyController.php
│   │   │   │   └── PharmacyProductController.php
│   │   │   ├── ProductController.php       # Web Controllers
│   │   │   ├── PharmacyController.php
│   │   │   ├── PharmacyProductController.php
│   │   │   └── LanguageController.php
│   │   ├── Middleware/
│   │   │   └── SetLocale.php              # Language middleware
│   │   ├── Requests/                       # Form validation
│   │   │   ├── StoreProductRequest.php
│   │   │   ├── UpdateProductRequest.php
│   │   │   └── StorePharmacyRequest.php
│   │   └── Resources/                      # API Resources
│   │       ├── ProductResource.php
│   │       └── PharmacyResource.php
│   ├── Models/
│   │   ├── Product.php
│   │   └── Pharmacy.php
│   ├── Services/                           # Business logic
│   │   ├── ProductService.php
│   │   └── PharmacyService.php
│   └── Traits/
│       └── ApiResponse.php                 # Consistent API responses
├── database/
│   ├── factories/
│   │   ├── ProductFactory.php
│   │   └── PharmacyFactory.php
│   ├── migrations/
│   │   ├── create_products_table.php
│   │   ├── create_pharmacies_table.php
│   │   └── create_pharmacy_product_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   ├── lang/                               # Translations
│   │   ├── en/                             # English
│   │   │   ├── nav.php
│   │   │   ├── products.php
│   │   │   ├── pharmacies.php
│   │   │   └── messages.php
│   │   └── ar/                             # Arabic
│   │       ├── nav.php
│   │       ├── products.php
│   │       ├── pharmacies.php
│   │       └── messages.php
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php               # Main layout (with RTL)
│       ├── products/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   ├── show.blade.php
│       │   └── search.blade.php
│       └── pharmacies/
│           ├── index.blade.php
│           ├── create.blade.php
│           ├── edit.blade.php
│           ├── show.blade.php
│           ├── add-product.blade.php
│           └── edit-product.blade.php
├── routes/
│   ├── web.php                             # Web routes
│   └── api.php                             # API routes
├── public/
│   └── storage/                            # Uploaded images
├── storage/
│   └── app/
│       └── public/
│           └── products/                   # Product images
├── .env.example                            # Environment template
├── composer.json                           # PHP dependencies
├── package.json                            # Node dependencies
├── Pharmacy_API_Collection.json            # Postman collection
└── README.md                               # This file
```

---

## 🎯 Key Features Explained

### 1. Product Management

**Full CRUD operations for products**

**Web Interface:**
- View all products: `/products`
- Create new product: `/products/create`
- Edit product: `/products/{id}/edit`
- View details: `/products/{id}`
- Delete product: Delete button on index/show pages
- Search products: `/products/search?q=query`

**API Endpoints:**
- `GET /api/products` - List (paginated)
- `POST /api/products` - Create
- `GET /api/products/{id}` - View
- `PUT /api/products/{id}` - Update
- `DELETE /api/products/{id}` - Delete
- `GET /api/products-search?q=` - Search

---

### 2. Pharmacy Management

**Manage multiple pharmacy locations**

**Features:**
- Store pharmacy name and address
- View products in each pharmacy
- Custom pricing per pharmacy
- Stock management per location

**Web Routes:**
- List: `/pharmacies`
- Create: `/pharmacies/create`
- View: `/pharmacies/{id}` (shows all products in pharmacy)
- Edit: `/pharmacies/{id}/edit`

---

### 3. Pharmacy-Product Relationships

**Link products to pharmacies with custom pricing**

**Key Features:**
- Add products to pharmacies
- Set custom price for each pharmacy
- Track stock quantity per location
- Update price/quantity independently
- Partial updates (price only OR quantity only)
- Remove products from pharmacies

**Web Workflow:**
1. Go to pharmacy detail page
2. Click "Add Product"
3. Select product, set price and quantity
4. Submit
5. Product now available in that pharmacy

**API Workflow:**
```bash
# 1. Get available products
GET /api/pharmacies/1/available-products

# 2. Add product to pharmacy
POST /api/pharmacies/1/products
{
    "product_id": 5,
    "price": 23.50,
    "quantity": 100
}

# 3. Update quantity only
PUT /api/pharmacies/1/products/5
{
    "quantity": 150
}

# 4. View all products in pharmacy
GET /api/pharmacies/1/products
```

---

### 4. Multi-Language Support

**Switch between English and Arabic**

**Features:**
- Automatic language detection
- Persistent selection (stored in session)
- RTL layout for Arabic
- Bootstrap RTL CSS
- Translated:
  - Navigation menu
  - All buttons
  - Form labels
  - Messages
  - Validation errors
  - Pagination

**How to Switch:**
1. Click language dropdown in navbar
2. Select "English" or "العربية"
3. Page reloads with selected language

**Technical Details:**
- Middleware: `SetLocale.php` automatically sets locale
- Translations: `resources/lang/en/` and `resources/lang/ar/`
- Route: `/language/{locale}` to switch

---

### 5. CLI Command

**Find cheapest pharmacies for a product**

**Basic Usage:**
```bash
php artisan products:search-cheapest {product_id}
```

**Examples:**
```bash
# Find cheapest pharmacies for product ID 1
php artisan products:search-cheapest 1

# Limit to 10 results
php artisan products:search-cheapest 1 --limit=10

# Table format (human-readable)
php artisan products:search-cheapest 1 --format=table

# JSON format (default, for API integration)
php artisan products:search-cheapest 1 --format=json
```

**JSON Output:**
```json
{
    "product_id": 1,
    "product_title": "Panadol 500mg",
    "base_price": 25.50,
    "cheapest_pharmacies": [
        {
            "id": 5,
            "name": "CVS Pharmacy - Cairo",
            "price": 23.50,
            "quantity": 100
        },
        {
            "id": 12,
            "name": "Walgreens - Alexandria",
            "price": 24.00,
            "quantity": 75
        }
    ],
    "count": 5,
    "price_stats": {
        "cheapest": 23.50,
        "most_expensive": 26.00,
        "average": 24.50,
        "savings": 2.50
    }
}
```

**Table Output:**
```
Product: Panadol 500mg
Base Price: $25.50

+----+--------------------------------+--------+-------+---------+
| ID | Pharmacy Name                  | Price  | Stock | Savings |
+----+--------------------------------+--------+-------+---------+
| 5  | CVS Pharmacy - Cairo           | $23.50 | 100   | $0.00   |
| 12 | Walgreens - Alexandria         | $24.00 | 75    | +$0.50  |
+----+--------------------------------+--------+-------+---------+

✓ Cheapest: CVS Pharmacy - Cairo at $23.50
  You can save $2.50 by choosing the cheapest pharmacy!
```

---

## 🐛 Troubleshooting

### Common Issues and Solutions

#### 1. "Class not found" Error

**Solution:**
```bash
composer dump-autoload
```

---

#### 2. Database Connection Error

**Check:**
- MySQL is running
- Database exists
- Credentials in `.env` are correct

**Test connection:**
```bash
php artisan tinker
```
Then type:
```php
DB::connection()->getPdo();
```

If successful, you'll see PDO object.

---

#### 3. Permission Errors (Linux/Mac)

```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

For local development:
```bash
sudo chmod -R 777 storage bootstrap/cache
```

---

#### 4. Migration Errors

**Fresh start (deletes all data):**
```bash
php artisan migrate:fresh --seed
```

**Warning:** This will delete all existing data!

---

#### 5. Image Upload Not Working

**Create storage link:**
```bash
php artisan storage:link
```

**Check permissions:**
```bash
chmod -R 775 storage/app/public
```

**Verify link exists:**
```bash
ls -la public/storage
```

---

#### 6. Port 8000 Already in Use

**Use different port:**
```bash
php artisan serve --port=8080
```

Then access: `http://localhost:8080`

---

#### 7. npm install Errors

**Clear cache:**
```bash
npm cache clean --force
rm -rf node_modules package-lock.json
npm install
```

---

#### 8. Composer install Fails

**Increase memory limit:**
```bash
php -d memory_limit=-1 /usr/local/bin/composer install
```

Or edit `php.ini`:
```ini
memory_limit = 512M
```

---

#### 9. Seeder Takes Too Long

**Skip seeding:**
```bash
php artisan migrate
```

Or reduce data in `DatabaseSeeder.php`:
```php
Product::factory(100)->create(); // Instead of 1000
Pharmacy::factory(20)->create(); // Instead of 200
```

---

#### 10. API Returns 404

**Check routes:**
```bash
php artisan route:list --path=api
```

**Clear cache:**
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

---

## 📊 Database Schema

### Products Table
```sql
CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    image VARCHAR(255) NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### Pharmacies Table
```sql
CREATE TABLE pharmacies (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### Pharmacy_Product Table (Pivot)
```sql
CREATE TABLE pharmacy_product (
    pharmacy_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (pharmacy_id, product_id),
    FOREIGN KEY (pharmacy_id) REFERENCES pharmacies(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
```

---

## 🧪 Testing the Application

### Test Web Interface

1. **Products Page:**
   ```
   http://localhost:8000/products
   ```
   - View list of products
   - Click "Add New Product"
   - Fill form and submit
   - Verify product appears in list

2. **Pharmacies Page:**
   ```
   http://localhost:8000/pharmacies
   ```
   - View list of pharmacies
   - Click on a pharmacy to view details
   - See products in that pharmacy

3. **Search:**
   ```
   http://localhost:8000/products/search?q=panadol
   ```
   - Enter search term
   - View results

4. **Language Switch:**
   - Click language dropdown
   - Select "العربية" (Arabic)
   - Verify RTL layout and Arabic text

---

### Test API Endpoints

**Health Check:**
```bash
curl http://localhost:8000/api/health
```

**Get Products:**
```bash
curl http://localhost:8000/api/products
```

**Create Product:**
```bash
curl -X POST http://localhost:8000/api/products \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Test Product",
    "description": "Test Description",
    "price": 10.50,
    "quantity": 100
  }'
```

**Get Statistics:**
```bash
curl http://localhost:8000/api/statistics
```

---

## 🚀 Deployment to Production

### Step 1: Environment Setup

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### Step 2: Optimize for Production

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

### Step 3: Set Permissions

```bash
sudo chmod -R 755 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

### Step 4: Configure Web Server

**Apache (.htaccess):**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

**Nginx:**
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/project/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 📝 Quick Start Checklist

**For First-Time Setup:**

- [ ] PHP 8.1+ installed
- [ ] Composer installed
- [ ] MySQL installed and running
- [ ] Node.js and npm installed
- [ ] Project extracted/cloned
- [ ] `composer install` completed
- [ ] `npm install` completed
- [ ] `.env` file created and configured
- [ ] Database created
- [ ] `php artisan key:generate` executed
- [ ] `php artisan migrate` executed
- [ ] `php artisan db:seed` executed (optional)
- [ ] `php artisan storage:link` executed
- [ ] `php artisan serve` running
- [ ] Application accessible at http://localhost:8000
- [ ] Postman collection imported (optional)

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

---

## 👨‍💻 Author & Contact

**Developer:** [Your Name]
- GitHub: [@yourusername](https://github.com/yourusername)
- Email: your.email@example.com
- LinkedIn: [Your LinkedIn](https://linkedin.com/in/yourprofile)

---

## 🤝 Contributing

Contributions are welcome!

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📞 Support

If you encounter any issues:

1. Check the [Troubleshooting](#troubleshooting) section above
2. Search existing GitHub issues
3. Open a new issue with:
   - Error message
   - Steps to reproduce
   - Your environment (OS, PHP version, etc.)

---

## 🙏 Acknowledgments

- Laravel Framework Team
- Bootstrap Team
- All contributors and testers

---

## ⭐ Star This Repository

If this project helped you, please consider giving it a ⭐ on GitHub!

---

**Made with ❤️ using Laravel**

**Happy Coding! 🎉**