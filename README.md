# GREED – Premium Streetwear E-commerce 🚀

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Stripe](https://img.shields.io/badge/Stripe-635BFF?style=for-the-badge&logo=stripe&logoColor=white)](https://stripe.com)
[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)

**GREED** is a high-end digital streetwear store built with a focus on premium UI/UX, seamless checkout experience, and a Web3-inspired aesthetic. This project utilizes the Laravel framework and Stripe's secure payment ecosystem.

---

## ✨ Key Features

* **Premium Web3 Aesthetic:** Minimalist and high-end interface inspired by Stripe.com.
* **Stripe Integration:** Secure checkout process supporting IDR currency.
* **Dynamic Cart System:** Real-time cart management using Laravel sessions.
* **Dual Checkout Flow:** Supports both "Direct Checkout" (Buy Now) and "Cart-based Checkout."
* **Multi-Address System:** Users can save multiple shipping addresses (Default & Secondary).
* **Admin Logistics Panel:** Comprehensive dashboard for inventory and order monitoring.
* **Smooth UX:** Integrated with modern scrolling and animation libraries for a premium feel.

---

## 🛠️ Tech Stack

* **Backend:** Laravel (Eloquent ORM, Controllers, Migrations)
* **Frontend:** Blade Templates, Tailwind CSS
* **Payments:** Stripe API (Checkout Session)
* **Animations:** GSAP, ScrollTrigger, & Lenis Scroll
* **Database:** MySQL with scalable modular structure

---

## 🚀 Quick Start

### Prerequisites
* PHP >= 8.x
* Composer
* MySQL
* Stripe Account (API Keys)

### Installation
1.  **Clone the repo:**
    ```bash
    git clone [https://github.com/nicholasrold/ecommerce-laravel.git](https://github.com/nicholasrold/ecommerce-laravel.git)
    cd ecommerce-laravel
    ```
2.  **Install dependencies:**
    ```bash
    composer install
    npm install && npm run build
    ```
3.  **Environment Setup:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
4.  **Configure Database & Stripe:**
    Edit `.env` and fill in your DB credentials and Stripe Secret Keys:
    ```env
    STRIPE_KEY=your_public_key
    STRIPE_SECRET=your_secret_key
    ```
5.  **Run Migrations & Seeders:**
    ```bash
    php artisan migrate:fresh --seed
    ```
6.  **Launch:**
    ```bash
    php artisan serve
    ```

---

## 📦 Project Structure
* `app/Http/Controllers/CheckoutController.php` - Core logic for Stripe & Order processing.
* `app/Models/Order.php` - Data structure for user transactions.
* `resources/views/catalog.blade.php` - Premium product listing.

---

## 🛡️ License
This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---
<p align="center">
  Developed with ❤️ by <b>Nicholas Rolando Victor</b> - <a href="https://github.com/nicholasrold">Verusa Design</a>
</p>
