#  PAWsome Pets — Pet Adoption Management System

A warm, pet adoption platform built with **Laravel** that connects rescue animals with forever homes. Adopters can browse pets, submit adoption applications, and receive notifications. Admins can manage pets, review requests, and export reports.

---

##  Table of Contents

- [About the Project](#about-the-project)
- [Features](#features)
- [Database Structure](#database-structure)
- [REST API](#rest-api)
- [Setup & Installation](#setup--installation)
- [Default Credentials](#default-credentials)
- [Team](#team)

---

## About the Project

PAWsome Pets is a Laravel-based web application built as a final project for a web development course. The system allows a pet adoption shelter to manage their available animals and process adoption requests through a clean, user-friendly interface.

---

## Features

###  Adopter
- Register and log in securely
- Browse available pets with species, age, and gender filters
- View detailed pet profiles
- Submit adoption applications
- Track application status on personal dashboard
- Receive notifications when requests are approved or rejected
- Rate the shelter and send contact messages

###  Admin
- Manage pets — add, edit, delete with photo upload
- Review and approve or reject adoption requests
- Send automatic notifications to adopters
- View reports and export as PDF or CSV
- View all ratings and contact messages

###  REST API
- RESTful API 
- Supports GET, POST, PUT, DELETE methods

---

## Database Structure

| Table | Description |
|---|---|
| `users` | Adopters and admin accounts with role field |
| `pets` | All pet listings with species, breed, traits, and status |
| `adoption_requests` | Applications submitted by adopters |
| `notifs` | Notifications sent to adopters on status changes |
| `ratings` | Shelter ratings submitted by users |
| `contacts` | Contact form submissions |

---

## REST API

Base URL: `http://localhost:8000/api`

### Public Endpoints
| Method | Endpoint | Description |
|---|---|---|
| POST | `/api/login` | Get API token |
| GET | `/api/pets` | List all pets |
| GET | `/api/pets/{id}` | Get single pet |

### Protected Endpoints (Bearer Token required)
| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/user` | Get authenticated user |
| GET | `/api/adoption-requests` | Get user's requests |
| POST | `/api/adoption-requests` | Submit adoption request |
| PUT | `/api/adoption-requests/{id}` | Approve/reject request (admin) |
| POST | `/api/pets` | Create pet (admin) |
| PUT | `/api/pets/{id}` | Update pet (admin) |
| DELETE | `/api/pets/{id}` | Delete pet (admin) |
| GET | `/api/notifications` | Get user notifications |
| POST | `/api/notifications/read-all` | Mark all as read |

---

## Setup & Installation

### Requirements
- PHP 8.2+
- Composer
- Node.js 18+
- Git

### Steps

**1. Clone the repository**
```bash
git clone https://github.com/YOUR-USERNAME/pawsome-pets.git
cd pawsome-pets
```

**2. Install dependencies**
```bash
composer install
npm install
```

**3. Set up environment**
```bash
cp .env.example .env
php artisan key:generate
```

**4. Configure database**

Open `.env` and make sure:
```
DB_CONNECTION=sqlite
SESSION_DRIVER=file
```

Then create the SQLite file:
```bash
# Mac/Linux
touch database/database.sqlite

# Windows
type nul > database\database.sqlite
```

**5. Run migrations and seed**
```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

**6. Start the development server**

Open two terminals:
```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

**7. Visit the app**
```
http://localhost:8000
```

---

## Default Credentials

| Role | Email | Password |
|---|---|---|
| Admin | admin@pawsome.com | password |
| Adopter | lebron@test.com | password |
| Adopter | josephine@test.com | password |

---

## Team

| Name |
|---|
| [Dan Justin Ferrer] |
| [Lee Harvey Oswald Munar] |
| [Jofil Operaña] |

---

> *"Every pet deserves a loving home, and every home deserves a loving pet."* 🐾