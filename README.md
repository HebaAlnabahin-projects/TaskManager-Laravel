# 📋 Laravel Livewire Task Manager

A simple Task Manager web application built with Laravel and Livewire. The application helps users organize their daily tasks, track their progress, and manage task status.

## ✨ Features

➕ Add new tasks.

✏️ Edit existing tasks.

🗑️ Delete tasks.

✅ Mark tasks as Pending or Completed.

🕒 Record the completion date and time.

📊 View dashboard statistics for total, pending, and completed tasks.

💾 Store task data in a MySQL database.

## 🛠️ Technologies Used

PHP 8.2

Laravel 12

Livewire 4

MySQL 8.0

Docker

Docker Compose

Apache

Node.js 22

Vite

## 📁 Project Structure

```text
TaskManager-Laravel/
├── app/
├── bootstrap/
├── config/
├── database/
├── docker/
│   └── apache/
├── docs/
│   ├── notes.md
│   └── screenshots/
├── public/
├── resources/
├── routes/
├── tests/
├── Dockerfile
├── docker-compose.yml
├── .dockerignore
├── .env.example
└── README.md
```

## 📋 Requirements

Docker Desktop installed and running.

Git installed.

## 🚀 Installation and Setup

### 1. Clone the Repository

```
git clone https://github.com/HebaAlnabahin-projects/TaskManager-Laravel.git
cd TaskManager-Laravel
```

### 2. Create the Environment File

```
Copy-Item .env.example .env
```

### 3. Configure Environment Variables

Open the `.env` file and configure the following values:

`APP_KEY`: A generated Laravel application key.

`DB_PASSWORD`: A database password.

`MYSQL_ROOT_PASSWORD`: A MySQL root password.

The `DB_PASSWORD` value must be the same for the application and the MySQL user.

To generate an application key using Docker, run:

```
docker run --rm php:8.2-cli php -r "echo 'base64:'.base64_encode(random_bytes(32)).PHP_EOL;"
```

Copy the generated key into the `APP_KEY` value in `.env`.

### 4. Build and Start the Containers

```
docker compose up -d --build
```

### 5. Run Database Migrations

```
docker compose exec app php artisan migrate --force
```

### 6. Open the Application

http://localhost:8000/tasks

## 🐳 Docker Commands

**Stop the containers**

```
docker compose stop
```

**Start the containers again**

```
docker compose up -d
```

**Check container status**

```
docker compose ps
```

**View application logs**

```
docker compose logs app
```

## 📚 Documentation

Additional project notes and screenshots are available in the `docs/` directory.

## 👩‍💻 Author

**Heba Alnabahin**

GitHub: https://github.com/HebaAlnabahin-projects