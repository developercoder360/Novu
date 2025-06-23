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
git clone https://github.com/developercoder360/Novu.git

# Navigate into the project
cd novu

# Install PHP dependencies
composer install

# Copy and setup environment file
cp .env.example .env
php artisan key:generate

# Add this code in .env file
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=731663
REVERB_APP_KEY=jfcoebns1ryjnz5zjyxm
REVERB_APP_SECRET=j3bcfwxo0vdrwnj5h7tb
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"

# Run migrations
php artisan migrate

# Install frontend dependencies
npm install && npm run dev

# Start development server
php artisan serve
