# 🚀 Novu - Laravel Realtime Chat & Notifications System

**Novu** is a modern and lightweight **Realtime Chat & Notification System** built with **Laravel** and **Livewire v3**. It brings real-time private messaging and user alerts to any Laravel-based web application using WebSockets (Laravel Reverb or Pusher).

---

## ⚙️ Features

- 💬 Realtime private chat between users  
- 🔔 Instant push-style notifications  
- ⚡ Reactive UI using Livewire v3 (no page reloads)  
- 🌐 WebSocket integration via Laravel Reverb or Pusher  
- 🔐 Authenticated users with roles (e.g., Admin, User)  
- 📱 Fully responsive with Tailwind CSS & Alpine.js

---

## 🧰 Tech Stack

- Laravel 11+
- Livewire v3
- Laravel Echo & WebSockets
- Tailwind CSS & Alpine.js
- MySQL

---

## 🚀 Installation

Run the following steps to install **Novu** locally:

```bash
# Clone the repository
git clone https://github.com/yourusername/novu.git

# Navigate into the project
cd novu

# Install PHP dependencies
composer install

# Copy and setup environment file
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Install frontend dependencies
npm install && npm run dev

# Start development server
php artisan serve
