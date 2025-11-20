# Sambodhan: Mental Health Portal

**Sambodhan** is a web-based platform designed to promote mental health awareness, self-care, and support for youth in Nepal. It provides a safe space for mood check-ins, anonymous journaling, inspiring stories, helplines, and resources for both youth and adults (parents/teachers).

---

## 🌟 Features

### For All Users
- **Mood Check-In Quiz**:  
  Take a 10-question, research-backed quiz to reflect on your mental state. Get personalized feedback, encouragement, and actionable suggestions.  
  _[pages/mood_checkin.php]_

- **Mental Health Articles**:  
  Read practical articles on stress relief, mindfulness, and self-care.  
  _[pages/articles.php]_

- **Helplines**:  
  Find trusted helplines and support services in Nepal.  
  _[pages/helplines.php]_

- **Anonymous Journal**:  
  Write your thoughts privately. Optionally submit for admin approval to share with the community.  
  _[pages/journal.php]_

- **Inspiring Stories**:  
  Read and share stories of hope, recovery, and resilience.  
  _[pages/inspiring_stories.php]_

- **Parent & Teacher Guide**:  
  A culturally sensitive, practical guide for adults supporting youth mental health.  
  _[pages/PT_guide.php]_

- **Contact Counselor**:  
  Anonymous chat with a counselor for support.  
  _[pages/council_chat.php]_

- **Stress Reduction Resources**:  
  Access videos, audios, and articles to help you relax and manage stress.  
  _[pages/stress_reduction.php]_

- **Real-Time Notifications**:  
  Users receive notifications for warnings, admin messages, and more, with a bell icon and live counter in the navbar.

---

### Admin Panel

Accessible to users with the admin email (`admin@sambodhan.com`):

- **Dashboard**: Overview and navigation for all admin features.
- **Manage Articles, Helplines, Journals, Stories, Stress Resources**:  
  - Add, edit, or delete content.
  - Dedicated edit pages for each type.
- **User Management**:  
  - View all users.
  - Warn or ban users (banned users cannot log in).
- **Notifications System**:  
  - Send warnings or custom notifications to users.
- **Database Setup & Migration Tools**:  
  - Scripts to safely add new tables/fields without resetting data.

---

## 🛠️ Installation & Setup

### 1. Requirements

- PHP 7.x or newer
- MySQL/MariaDB
- XAMPP, WAMP, or similar local server (default config assumes XAMPP)

### 2. Database Setup

- Place the project folder (`Sambodhan`) in your web server's root directory (e.g., `htdocs` for XAMPP).
- Start Apache and MySQL from your XAMPP/WAMP control panel.

#### a. Initial Database Creation

1. Open your browser and run:  
   `http://localhost/Sambodhan/name_setup.php`  
   This will create the database and all required tables.

2. (Optional) For future migrations or fixes, you can use:  
   - `edit.php` (adds new columns/tables as needed)
   -  `admin/setup_notifications.php` (adds notifications and ban system)

> **After running setup scripts, you may delete or secure these files for safety.**

### 3. Default Admin Account

- Register a new user with the email: `admin@sambodhan.com`
- This account will have admin privileges.

### 4. Configuration

- Database connection settings are in each PHP file (default: `root` user, no password, database `sambodhan`).  
  Update these if your MySQL config is different.

---

## 🚀 Usage

- Visit `http://localhost/Sambodhan/index.php` to access the homepage.
- Register or log in to access all features.
- Admins can access the dashboard at `admin/dashboard.php`.

---

## 📁 Project Structure

```
Sambodhan/
│
├── index.php                # Homepage
├── login.php, register.php  # Auth pages
├── name_setup.php           # Initial DB setup script
├──  admin/setup_notifications.php  # DB migration scripts
│
├── includes/
│   ├── navbar.php           # Top navigation bar (with notification bell)
│   └── footer.php           # Footer
│
├── admin/                   # Admin panel files
│   ├── dashboard.php
│   ├── Articles.php, edit_articles.php
│   ├── Helplines.php, edit_helplines.php
│   ├── Journals.php, edit_journals.php
│   ├── stories.php, stress_resources.php
│   ├── Users.php, warn_user.php
│   └── setup_notifications.php
│
├── pages/                   # User-facing features
│   ├── mood_checkin.php     # Mood quiz
│   ├── articles.php         # Articles
│   ├── helplines.php        # Helplines
│   ├── journal.php, journal_like.php
│   ├── inspiring_stories.php
│   ├── notifications.php, get_notification_count.php
│   ├── PT_guide.php         # Parent & Teacher Guide
│   ├── council_chat.php     # Counselor chat
│   └── stress_reduction.php # Stress resources
│
├── assets/                  # Images, CSS, etc.
│   ├── indexbackground.png, indexbackground2.png
│   └── css/
│
└── ...
```

---


- **Passwords** are stored in plain text for demo purposes.  
  **Change this to use password hashing (e.g., `password_hash()`) for production!**
- **Security**:  
  - Delete or secure setup scripts after use.
  - Sanitize all user input for production deployment.
- **Customization**:  
  - Update helplines, articles, and resources to fit your audience.
  - Add more admin features as needed.

---

## 📣 Credits

- Designed and developed for youth mental health awareness in Nepal.
- UI inspired by modern, accessible design principles.
- Content for the Parent & Teacher Guide is original and culturally adapted.

---

## 📬 Contact

For questions, suggestions, or contributions, please open an issue or contact the project maintainer. 

CREATED BY:
## THE RUNTIME TERRORS:
Sabnam – Team Leader & Full Stack Coordinator
Pabitra  – Frontend Developer (UI/UX Design Focus)
Nikhil   – Frontend Developer (JavaScript & Interactivity)
Ranish  – Backend Developer (PHP , MySQL Expert& Frontend)
Saman  – Mental Health Researcher and Content Writer


