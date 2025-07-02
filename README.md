# Custom Store

A customizable online store built with **Laravel 12**, **Laravel Breeze (Livewire)** and **Tailwind CSS**.  
Allows customers to configure products by size, shape, color, and more.

---

## 🛠️ Features

- User authentication / registration (Laravel Breeze + Livewire)
- Product catalogue with customization options:
  - Select size, shape, color, and other attributes
  - Dynamic preview of selected options
- Shopping cart and checkout workflow
- Responsive UI built with Blade + Tailwind
- Fully customizable product configuration logic
- Ready for enhanced features: payment integration, user profiles, admin dashboard

---

## 🚀 Quick Start

### Requirements

- PHP 8.4+
- Composer
- Node.js & npm
- MySQL (or compatible)
- Laravel Installer (>= 5.11.1)

### Setup steps

```bash
# 1. Clone repo
git clone https://github.com/KenjiWriter/custom-store.git
cd custom-store

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies
npm install

# 4. Setup .env
cp .env.example .env
php artisan key:generate

# 5. Configure DB in .env:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=custom_store
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Run migrations
php artisan migrate

# 7. Build frontend assets
npm run dev

# 8. Serve locally
php artisan serve
```



Visit http://127.0.0.1:8000 and explore the custom store!

📦 Project Structure

app/
├── Http/
│   ├── Controllers/      # Standard Laravel controllers
│   └── Livewire/         # Livewire components (product config, cart, etc.)
resources/
├── views/                # Blade templates
└── css/ & js/            # Frontend assets managed by Tailwind & npm
tests/                    # Pest test files

🎯 Product Customization
Products can be tailored per user needs:

* Users choose size (e.g., S, M, L), shape, color and more

* Configurable via Livewire components with real-time updates

* Add customized items to cart and place orders

🚀 Let's build something great!
This project aims to be both a learning tool and a strong starting point for a real-world customizable store app.
If anything's unclear or you'd like help setting it up, just drop a message 😊

--------------------------------

Enjoy coding!
