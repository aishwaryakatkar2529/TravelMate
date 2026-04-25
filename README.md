## 📋 About the Project

**TravelMate** is a full-featured travel tour package booking platform. Users can browse curated travel packages across India, view complete package details including inclusions and gallery, and register or login to book their trips. The project is fully deployed and live on an **AWS EC2 instance** running a **LAMP stack** (Linux, Apache, MySQL, PHP).

---

## 🌐 Live Demo

🔗 **[http://65.2.169.91/travelmate/](http://65.2.169.91/travelmate/)**

---


## ✨ Features

| Feature | Description |
|---------|-------------|
| 🏠 Home Page | Hero section with tagline and 6 featured package cards |
| 📦 All Packages | Grid of all 10 curated packages with pricing |
| 🔍 Package Detail | Full info — description, inclusions, gallery, booking |
| 🔐 User Auth | Secure Login and Registration system |
| 💰 INR Pricing | Transparent per-person pricing in ₹ |
| 📱 Responsive | Mobile-friendly layout |
| 🔒 Secure Booking | Login-protected booking flow |

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|------------|
| **OS** | Ubuntu (AWS EC2) |
| **Web Server** | Apache2 |
| **Database** | MySQL |
| **Backend** | PHP |
| **Frontend** | HTML, CSS, JavaScript |
| **Cloud Hosting** | AWS EC2 (LAMP Stack) |

---

## ☁️ AWS Infrastructure

Cloud Provider  →  Amazon Web Services (AWS)
Service         →  EC2 (Elastic Compute Cloud)
Stack           →  LAMP (Linux + Apache + MySQL + PHP)
Public IP       →  65.2.169.91
Web Root        →  /var/www/html/travelmate/
OS              →  Ubuntu Server LTS

---

## 📁 Project Structure

travelmate/
├── index.php                  # Home page
├── packages.php               # All packages listing
├── package-detail.php         # Individual package detail
├── login.php                  # User login
├── register.php               # User registration
├── config/
│   └── db.php                 # Database connection
├── assets/
│   ├── css/style.css          # Stylesheet
│   ├── js/main.js             # JavaScript
│   └── images/                # Static images
├── includes/
│   ├── header.php             # Common nav header
│   └── footer.php             # Common footer
└── README.md

---

## 🗺️ Tour Packages

| # | Package | Destination | Duration | Price/Person | Group |
|---|---------|-------------|----------|--------------|-------|
| 1 | 🏖️ Goa Beach Paradise | Goa | 4D/3N | ₹12,999 | Max 15 |
| 2 | 🏰 Royal Rajasthan Tour | Rajasthan | 7D/6N | ₹24,999 | Max 20 |
| 3 | 🌿 Kerala Backwaters Bliss | Kerala | 6D/5N | ₹19,999 | Max 12 |
| 4 | 🏔️ Manali Snow Adventure | Manali, HP | 5D/4N | ₹16,999 | Max 18 |
| 5 | 🏝️ Andaman Island Escape | Andaman & Nicobar | 6D/5N | ₹32,999 | Max 10 |
| 6 | 💑 Shimla Manali Honeymoon | Shimla & Manali | 7D/6N | ₹28,999 | Max 8 |
| 7 | 🕌 Varanasi Spiritual Journey | Varanasi, UP | 3D/2N | ₹8,999 | Max 25 |
| 8 | 🏍️ Leh Ladakh Bike Trip | Leh Ladakh | 8D/7N | ₹34,999 | Max 15 |
| 9 | ⛰️ Ooty Coorg Hill Station | Ooty & Coorg | 5D/4N | ₹14,999 | Max 20 |
| 10 | 🗺️ Golden Triangle Tour | Delhi-Agra-Jaipur | 6D/5N | ₹21,999 | Max 25 |

---

## 🚀 Deployment on AWS LAMP

```bash
# Step 1: Launch EC2 Ubuntu instance from AWS Console

# Step 2: SSH into your server
ssh -i your-key.pem ubuntu@65.2.169.91

# Step 3: Install LAMP stack
sudo apt update
sudo apt install apache2 mysql-server php php-mysqli libapache2-mod-php -y

# Step 4: Start and enable services
sudo systemctl start apache2
sudo systemctl enable apache2
sudo systemctl start mysql
sudo systemctl enable mysql

# Step 5: Upload project files
sudo cp -r travelmate/ /var/www/html/

# Step 6: Set correct permissions
sudo chown -R www-data:www-data /var/www/html/travelmate
sudo chmod -R 755 /var/www/html/travelmate

# Step 7: Import database
mysql -u root -p < travelmate_db.sql

# Step 8: Open in browser
# http://65.2.169.91/travelmate/
```

---

## 👨‍💻 Author

**Aishwarya Katkar**
📧 katkaraishwarya25@gmail.com
🐙 [GitHub](https://github.com/aishwaryakatkar2529)

---

## 📜 License

This project is open source and available under the [MIT License](LICENSE).

---

