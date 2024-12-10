# **E-Commerce Database Management System**  
### A Complete Backend Solution with PHP, MySQL, and CRUD Features  
![ecommerce_db](https://img.shields.io/badge/Language-PHP%20%2F%20MySQL-blue?style=flat-square) ![license](https://img.shields.io/github/license/Maevex/DB-Ecommerce?style=flat-square)

Welcome to the **E-Commerce Database Project**! This repository demonstrates a robust and fully functional backend database system for an e-commerce application, featuring **cart functionality**, **transactions**, **user management**, and a complete **admin panel**.

---

## 🔗 **Cloning the Project**
To clone this project and explore the database setup: 
```bash 
git clone https://github.com/Maevex/DB-Ecommerce.git  
cd DB-Ecommerce  
```

---

## ⚙️ **Tech Stack**
- **Backend**: PHP (Core PHP for server-side programming)  
- **Database**: MySQL (MariaDB)  
- **Frontend**: HTML & CSS  
- **Tool**: phpMyAdmin for SQL dump setup  

---

## 🛠️ **Setup Instructions**

Follow these simple steps to run the project locally:

### 1. **Import the Database**
1. Open `phpMyAdmin` or any SQL database tool.  
2. Create a new database called `mikrotik`.  
3. Import the SQL dump file (`mikrotik.sql`) provided in this repository.  

### 2. **Configure the Server**
Make sure to have a local development environment installed:  
- XAMPP, WAMP, or MAMP for PHP/MySQL.  
- Place the project folder (`DB-Ecommerce`) in the `htdocs` directory.  

### 3. **Run the Application**
1. Start the Apache server and MySQL server using XAMPP or WAMP.  
2. Open your browser and navigate to:  
http://localhost/DB-Ecommerce  

---

## 👥 **Features**

### 1. **User Page**
- **Cart System**: Users can add products to the cart, modify quantities, and checkout.  
- **Transactions**: Complete transaction handling with a detailed history.  
- **Login/Register**: Secure login system (password hashed with Bcrypt).  
- **Search Functionality**: Find products easily.  
- **User CRUD**: Users can update their account details.  

### 2. **Admin Page**
- **CRUD Operations**:  
  - Manage **Products**: Add, update, delete product listings.  
  - Manage **Users**: View user data and manage permissions.  
  - Manage **Transactions**: Admins can monitor transaction records.  
- **Product Stock Management**: Easily update stock quantities.  
- **View Orders**: Track active and completed orders.  

---

## 🎯 **Database Schema**

Here’s a breakdown of the database structure:

### **Tables**:
1. **admin**  
   Stores admin credentials for backend access.  

2. **customer**  
   Contains user information like names, emails, and addresses.  

3. **cart**  
   Manages carts linked to specific customers.  

4. **cart_items**  
   Contains items added to each cart (linked to products).  

5. **product**  
   Stores all product details: name, price, description, and stock.  

6. **transaction**  
   Records order transactions with customer references.  

7. **transaction_detail**  
   Tracks individual product purchases within a transaction.  

---

## 🔒 **Security Measures**
- Passwords are securely hashed using **Bcrypt** for login systems.  
- Database relationships are enforced with **Foreign Keys** to maintain integrity.  
- Admin panel access is restricted to authenticated users.  

---

## 🚀 **Demo Workflow**

### **User Journey**:
- Register or log in.  
- Search for products.  
- Add products to the cart.  
- Proceed to checkout (creates a transaction).  
- View order history.  

### **Admin Journey**:
- Log in to the admin panel.  
- Manage product listings (add, update, delete).  
- View user transactions.  
- Monitor inventory stock.  

---

## 📂 **File Structure**
```storage
DB-Ecommerce/  
│  
├── mikrotik.sql          # Database dump file  
├── index.php             # Entry point for users  
├── admin/                # Admin panel  
│   ├── dashboard.php     # Admin dashboard  
│   ├── products.php      # CRUD operations for products  
│   └── transactions.php  # View transactions  
│  
├── user/                 # User-facing pages  
│   ├── cart.php          # Shopping cart page  
│   ├── history.php       # Transaction history page  
│   └── profile.php       # User account settings  
│  
├── assets/               # Static resources (CSS, images)  
└── README.md             # This file!  
```

---

## 🧪 **Testing Credentials**

### **Admin Login**  
- **Username**: `aku`  
- **Password**: `test123` (use hashed version in your setup)  

### **User Login**  
- Use the sample data provided in the `customer` table.  

---

## 🎨 **Screenshots**

### **1. User Dashboard**  
![User Dashboard](https://via.placeholder.com/600x300?text=User+Dashboard)  

### **2. Admin Panel**  
![Admin Panel](https://via.placeholder.com/600x300?text=Admin+Panel)  

---

## 🤝 **Contributing**  
Contributions are welcome! Fork this repository, make your improvements, and submit a pull request.  

---

## 📜 **License**  
This project is licensed under the **MIT License**. Feel free to use, modify, and distribute this project.  

---

## 🚀 **Let's Connect**  
If you like this project, don’t forget to ⭐ **star** the repository!  
For questions or collaborations, reach out via [email]().  

---

⚡ **Clone it. Run it. Build your own e-commerce solution!** ⚡  

---

You can directly copy and paste this into your `README.md` file. No formatting characters (like backticks) have been used. Let me know if you need further adjustments! 🚀