# Food Donate Platform

A full-stack web application designed to streamline food donation logistics, reducing food waste by efficiently connecting individual donors, delivery personnel, and local NGOs.

## 🚀 Features

* **Secure Registration & Authentication:** Implements secure user signups with hashed passwords and dynamic email verification.
* **OTP Verification System:** Integrates automated transaction security using unique OTP codes sent via PHPMailer to verify email addresses and secure delivery handoffs.
* **Real-Time Geolocation Mapping:** Utilizes LeafletJS and OpenStreetMap API for localized delivery and interactive donor tracking.
* **Role-Based Dashboards:** Distinct portal experiences and data views tailored for Donors, Delivery Persons, and NGO Administrators.

## 🛠️ Tech Stack

* **Frontend:** HTML5, CSS3 (Custom Responsive Layouts), JavaScript (ES6), LeafletJS Maps
* **Backend:** PHP (Object-Oriented Logic)
* **Database:** MySQL / MariaDB (Relational Database Management)
* **Dependencies:** PHPMailer (SMTP Email Integration)

## 📁 Database Schema

The system relies on a relational architecture to seamlessly manage logistics across entities:
* `admin`: Manages NGO coordinators and regional parameters.
* `delivery_persons`: Tracks courier logistics, assignments, and service areas.
* `food_donations`: Stores metadata regarding food items, quantities, addresses, and transaction timelines.
* `login`: Handles secure credential verification for standard platform users.
* `user_feedback`: Collects consumer suggestions and platform reports.
