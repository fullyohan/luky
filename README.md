# 🛒 Local Marketplace MVP

A monolithic classified ads marketplace built with **PHP**, **MySQL**, **HTML5/CSS3** and **Bootstrap 5**, inspired by local buy-and-sell platforms. This project emphasizes software architecture fundamentals, secure authentication mechanisms, relational database integrity, and clean separation of concerns without relying on external frameworks.

---

## 🚀 Tech Stack

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge\&logo=php\&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge\&logo=mysql\&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-8511FA?style=for-the-badge\&logo=bootstrap\&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge\&logo=html5\&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge\&logo=css3\&logoColor=white)

---

## ✨ Core Features

### 👤 User Management & Security

* Secure user registration and login system.
* Session-based authentication.
* Password hashing using **BCRYPT**.
* Centralized route protection via `auth-guard.php`.
* User profile management interface.
* Secure session validation for protected pages.

### 📢 Classified Posts

* Full CRUD operations for listings.
* Secure image upload support.
* Reverse chronological marketplace feed.
* Keyword-based search on listing titles.
* Ownership verification before editing or deleting ads.

### ⭐ Favorites System

* Save listings to a personal favorites collection.
* Dedicated "My Favorites" page.
* Prevention of duplicate favorite entries through database constraints.

### 💬 Messaging System

* Context-aware conversations linked to listings.
* One unique chat room per:

  * Listing
  * Buyer
  * Seller
* Timestamped messaging history.

### 🛠️ Administration Panel

* User management dashboard.
* User promotion to administrator role.
* Account suspension and deletion.
* Administrative action safeguards.
* Protection against self-deletion and self-suspension.

---

## 🏗️ Technical Architecture

| Layer           | Technology     |
| --------------- | -------------- |
| Backend         | PHP        |
| Database        | MySQL          |
| Frontend        | HTML5          |
| Styling         | Bootstrap 5 / CSS3|
| Authentication  | PHP Sessions   |

---

## 🔒 Security Features

### SQL Injection Protection

Input sanitization is enforced using:

```php
mysqli_real_escape_string($conn, $input);
```

Identifier lookups are strictly cast:

```php
$postId = (int) $_GET['id'];
```

### Password Security

Passwords are hashed using:

```php
password_hash($password, PASSWORD_BCRYPT);
```

### Access Control

* Server-side authorization checks.
* Session validation before sensitive actions.
* Ownership verification for content modifications.

### Administrative Safeguards

* Prevention of self-account deletion.
* Prevention of self-suspension.
* Verification of administrator privileges before mutations.


---

## 📂 Project Structure

```text
luky/
│
├── config/
│   ├── database.php       # Database connection
│   ├── database.sql       # Database schema
│   └── init_db.php        # Database initialization script
│
├── public/
│   ├── auth/              # Authentication pages
│   ├── components/        # Reusable UI components
│   ├── handlers/          # Form processing & business logic
│   ├── security/          # Security utilities and guards
│   ├── uploads/           # Uploaded listing images
│   ├── user/              # User-related pages
│   │
│   ├── admin.php          # Administration dashboard
│   ├── details.php        # Listing details page
│   ├── index.php          # Marketplace homepage
│   ├── search.php         # Search results page
│   └── style.css          # Global stylesheet
│
└── README.md
```

### Directory Responsibilities

| Directory     | Purpose                                                 |
| ------------- | ------------------------------------------------------- |
| `config/`     | Database configuration and initialization scripts       |
| `auth/`       | Login and registration  features                |
| `components/` | Shared layouts, headers, footers, and reusable UI parts |
| `handlers/`   | Form submissions and business logic processing          |
| `security/`   | Authentication guards and security utilities            |
| `uploads/`    | User-uploaded images                                    |
| `user/`       | Profile management and user-specific pages              |

---


# ⚙️ Installation

## 1. Clone or Copy the Project

Place the project inside your local web server directory.

### WampServer

```text
C:/wamp64/www/luky/
```

---

## 2. Create the Database

1. Open **phpMyAdmin**
2. Create a database named:

```text
luky_db
```

3. Select collation:

```text
utf8mb4_general_ci
```

4. Import:

```text
database.sql
```
---
or execute 
```text 
init_db.php
```




located at the root of the project.

---

## 4. Create a Virtual Host

### WampServer (Recommended)

1. Launch **WampServer**.
2. Left-click the WampServer icon in the system tray.
3. Navigate to:

```text
Tools → Add a Virtual Host
```

4. Fill in the form:

```text
Virtual Host Name:
luky.local

Project Path:
C:/wamp64/www/luky/public
```

5. Click **Start the creation of the VirtualHost**.
6. Once the process is complete, restart WampServer if prompted.

WampServer will automatically:

* Create the Apache virtual host configuration.
* Update the Windows hosts file.
* Configure the local domain.

---


## 5. Run the Application

Open your browser and visit:

```text
http://luky.local
```
---

## 🎯 Learning Objectives

This project demonstrates:

* Session-based authentication
* Secure password management
* Relational database design
* Foreign key constraints
* CRUD application architecture
* PHP application structuring
* User authorization workflows
* Administrative role management
* Messaging system implementation

---

## 📜 License
Developed as a group project for the Web Development course during the B1 (Bachelor Year 1) program at ECE Engineering School.

Feel free to modify, extend, and use it as a learning resource.

For educational and portfolio purposes only.
