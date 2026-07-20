<div align="center">

# 🎓 EduNotify

### Smart Student Result Management & Notification System

A full-stack web application developed using **PHP, MySQL, Bootstrap 5, PHPMailer, and FPDF** for managing student examination results with automated email notifications and downloadable PDF marksheets.

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap)
![GitHub](https://img.shields.io/badge/GitHub-Portfolio-black?style=for-the-badge&logo=github)

</div>

---

# 📖 Overview

EduNotify is a complete Student Result Management System designed to simplify examination result management for educational institutions.

The system provides separate Admin and Student portals where administrators can manage students, subjects, semesters, publish results, and automatically notify students via email. Students can securely view their results and download professional PDF marksheets.

---

# ✨ Features

## 👨‍💼 Admin Module

- 🔐 Secure Admin Login
- 📊 Dashboard with Statistics
- 👨‍🎓 Student Management (CRUD)
- 📚 Subject Management
- 📅 Semester Management
- 📝 Result Management
- 📢 Publish / Unpublish Results
- 📧 Automatic Email Notifications
- 📈 Dashboard Analytics
- 🏆 Top Scorer Information

---

## 👨‍🎓 Student Module

- Hall Ticket Search
- Semester-wise Result Viewing
- Percentage Calculation
- Overall Grade
- Overall Result
- Print Result
- Download PDF Marksheet

---

# 📧 Email Notification

When an administrator publishes examination results, EduNotify automatically sends result notifications to students using **PHPMailer** with Gmail SMTP.

---

# 📄 PDF Marksheet

Students can download a professional marksheet containing:

- Student Details
- Semester Details
- Subject-wise Results
- Total Marks
- Percentage
- Overall Grade
- Overall Result
- Generated Date

---

# 🛠 Technologies Used

| Technology | Purpose |
|------------|----------|
| PHP | Backend Development |
| MySQL | Database |
| Bootstrap 5 | Responsive UI |
| HTML5 | Structure |
| CSS3 | Styling |
| JavaScript | Client-side Functionality |
| PHPMailer | Email Service |
| FPDF | PDF Generation |
| XAMPP | Local Development |

---

# 📂 Project Structure

```
EduNotify
│
├── admin
├── assets
├── config
├── database
├── fpdf
├── includes
├── PHPMailer
├── screenshots
├── student
├── uploads
│
├── .gitignore
├── README.md
└── index.php
```

---

# 🗄 Database Tables

- admin
- students
- subjects
- semesters
- results

---

# 📸 Project Screenshots

## 🏠 Landing Page

![Landing Page](screenshots/Landing%20Page.png)

---

## 🔐 Admin Login

![Admin Login](screenshots/Admin%20Login.png)

---

## 📊 Admin Dashboard

![Admin Dashboard](screenshots/Admin%20Dashboard.png)

---

## 👨‍🎓 Students Management

![Students Management](screenshots/Students%20Management.png)

---

## 📚 Subjects Management

![Subjects Management](screenshots/Subjects%20Management.png)

---

## 📝 Results Management

![Results Management](screenshots/Results%20Management.png)

---

## 📢 Publish Results

![Publish Results](screenshots/Publish%20Results.png)

---

## 🎓 Student Portal

![Student Portal](screenshots/Student%20Portal.png)

---

## 📄 Student Result

![Student Result](screenshots/Student%20Result%20Page.png)

---

## 📑 PDF Marksheet

![PDF Marksheet](screenshots/PDF%20Marksheet.png)

---

## 📧 Email Notification

![Email Notification](screenshots/Email%20Notification.png)

---

# 🚀 Installation

## Clone Repository

```bash
git clone https://github.com/Mulintikoushik/EduNotify.git
```

---

## Import Database

Import

```
database/edunotify.sql
```

using phpMyAdmin.

---

## Configure Database

Open

```
config/database.php
```

Update your MySQL credentials.

---

## Configure Email

Open

```
config/mail.php
```

Enter your Gmail address and App Password before testing email notifications.

---

## Run Project

Start

- Apache
- MySQL

using XAMPP.

Then open:

```
http://localhost/ResultAlertSystem/
```

---

# 🚀 Future Enhancements

- Student Login Authentication
- CGPA Calculator
- SMS Notifications
- OTP Verification
- QR Code Verification
- Excel Export
- Analytics Dashboard
- Role-based Access Control

---

# 👨‍💻 Author

## Koushik Kumar Reddy Mulinti

GitHub:

https://github.com/Mulintikoushik

---

# ⭐ If you like this project

Please consider giving it a ⭐ on GitHub.

---

<div align="center">

Made with ❤️ using PHP & MySQL

</div>