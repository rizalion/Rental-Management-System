# Askari Rentals — Rental Management System

A web-based **Rental Management System** developed to manage properties, tenants, rental contracts, payments, and maintenance requests through a centralized database-driven application.

The project was developed as part of the **Advanced Database Management Systems** course and demonstrates practical implementation of database design, normalization, CRUD operations, relationships, foreign keys, and SQL queries.

## Project Overview

**Askari Rentals** provides a centralized platform for managing rental property operations. It allows administrators to maintain property and tenant records, create rental contracts, track payments, and manage maintenance requests.

The system uses **PHP** for server-side processing, **MySQL** for database management, and **CSS** for the web interface.

## Key Features

* 🏠 **Property Management**

  * Add, view, update, and delete properties
  * Store property location, rent, and landlord information

* 👤 **Tenant Management**

  * Register and manage tenant records
  * Store tenant contact information

* 📄 **Contract Management**

  * Create rental contracts
  * Link tenants with properties
  * Manage contract start and end dates

* 💰 **Payment Management**

  * Record rental payments
  * Track payment dates, amounts, and payment methods

* 🔧 **Maintenance Management**

  * Submit maintenance requests
  * Track request status and dates

* 🔐 **Admin Management**

  * Admin user authentication
  * Session-based access control

* 📊 **Database Operations**

  * Full CRUD operations
  * Foreign-key relationships
  * SQL joins and relational queries
  * Cascading deletes

## Technology Stack

| Technology     | Purpose                          |
| -------------- | -------------------------------- |
| PHP            | Backend / Server-side Processing |
| MySQL          | Database Management              |
| HTML           | Web Structure                    |
| CSS            | Styling and Layout               |
| SQL            | Database Queries                 |
| XAMPP / Apache | Local Web Server                 |

## Database Design

The database is named:

```sql
askari_rentals
```

### Main Tables

```text
askari_rentals
│
├── tenants
│   └── Stores tenant information
│
├── properties
│   └── Stores rental property information
│
├── contracts
│   └── Links tenants with properties
│
├── payments
│   └── Stores rental payment records
│
├── maintenance_requests
│   └── Stores property maintenance requests
│
└── users
    └── Stores administrator login information
```

### Relationships

```text
Tenants
   │
   │ 1 ──── N
   ▼
Contracts
   │
   │ N ──── 1
   ▼
Properties
   │
   ├──────────► Maintenance Requests
   │
   └──────────► Contracts
                    │
                    └──────────► Payments
```

## Database Concepts Demonstrated

The project demonstrates several important database management concepts:

* Relational database design
* Primary keys
* Foreign keys
* Referential integrity
* One-to-many relationships
* Database normalization
* 3NF principles
* CRUD operations
* SQL joins
* `AUTO_INCREMENT`
* `ON DELETE CASCADE`
* Constraints such as `NOT NULL` and `UNIQUE`

## Database Setup

### 1. Create the Database

Open MySQL/phpMyAdmin and execute the provided SQL script.

```sql
CREATE DATABASE IF NOT EXISTS askari_rentals;
USE askari_rentals;
```

The complete database schema is available in:

```text
database.sql
```

### 2. Start the Local Server

If using XAMPP, start:

```text
Apache
MySQL
```

### 3. Configure the Project

Place the PHP project inside the XAMPP web directory:

```text
xampp/
└── htdocs/
    └── askari-rentals/
```

Update the database connection settings in the PHP configuration file if required.

### 4. Open the Application

Open the project through a browser:

```text
http://localhost/askari-rentals/
```

## Example SQL Operations

### Insert a Tenant

```sql
INSERT INTO tenants (name, phone, email)
VALUES ('John Doe', '03001234567', 'john@example.com');
```

### Add a Property

```sql
INSERT INTO properties
(title, location, monthly_rent, landlord_name, landlord_contact)
VALUES
('Apartment 101', 'Taxila', 35000, 'Ahmed Khan', '03001234567');
```

### Create a Contract

```sql
INSERT INTO contracts
(tenant_id, property_id, start_date, end_date)
VALUES
(1, 1, '2026-01-01', '2026-12-31');
```

### Record a Payment

```sql
INSERT INTO payments
(contract_id, payment_date, amount_paid, payment_method)
VALUES
(1, '2026-01-05', 35000, 'Bank Transfer');
```

### View Tenant and Property Information

```sql
SELECT
    tenants.name AS tenant_name,
    properties.title AS property,
    properties.location,
    contracts.start_date,
    contracts.end_date
FROM contracts
JOIN tenants
    ON contracts.tenant_id = tenants.tenant_id
JOIN properties
    ON contracts.property_id = properties.property_id;
```

## Project Structure

```text
askari-rentals/
│
├── database.sql
├── README.md
│
├── *.php
├── css/
├── images/
└── other project files
```

> The PHP application files are included separately with the project submission.

## Learning Outcomes

Through this project, we gained practical experience in:

* Designing relational databases
* Applying database normalization
* Creating relationships between tables
* Writing SQL queries
* Implementing CRUD functionality
* Using primary and foreign keys
* Performing SQL joins
* Connecting PHP applications with MySQL
* Managing data through a database-driven web application

## Authors

**Muhammad Awais** — 23-CS-055
**Muhammad Huzaifa** — 23-CS-007

**Section:** 5C
**Department of Computer Science**
**HITEC University, Taxila

## Course

**Advanced Database Management Systems**

Submitted to: **Ms. Shamshad Bibi**

## Disclaimer

This project was developed for **educational purposes** as part of a university course.
