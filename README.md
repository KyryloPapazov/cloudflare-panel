# Web Application for Managing Cloudflare Accounts, Domains, and Page Rules

This project is created using Laravel v11.21.0 (PHP v8.3.10).

### installation on Windows may be different

### login: admin@example.com
### password: admin
## Installation Requirements

### For Ubuntu:

To install this application, you need the following:

- **LAMP Stack** ([DigitalOcean tutorial](https://www.digitalocean.com/community/tutorials/how-to-install-lamp-stack-on-ubuntu))  
  Or:
    - [PHP version 8.1+](https://www.geeksforgeeks.org/how-to-install-php-on-ubuntu/) (tutorial in link)
    - [Composer](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-composer-on-ubuntu-20-04) (tutorial in link)
    - [Node.js](https://www.digitalocean.com/community/tutorials/how-to-install-node-js-and-create-a-local-development-environment-on-windows) (tutorial in link)
    - [Any relational database](https://www.digitalocean.com/community/tutorials/how-to-install-mysql-on-ubuntu-22-04) (I used MySQL with MySQL Workbench - not necessarily)
    - [Git](https://git-scm.com/downloads) (Link)

### For Windows:

- **Open Server** ([Open Server Documentation](https://github.com/OSPanel/OpenServerPanel/wiki/%D0%94%D0%BE%D0%BA%D1%83%D0%BC%D0%B5%D0%BD%D1%82%D0%B0%D1%86%D0%B8%D1%8F))  
  Or:
    - [PHP version 8.1+](https://www.geeksforgeeks.org/how-to-install-php-in-windows-10/) (tutorial in link)
    - [Composer](https://www.geeksforgeeks.org/how-to-install-php-composer-on-windows/) (tutorial in link)
    - [Node.js](https://www.digitalocean.com/community/tutorials/how-to-install-node-js-on-ubuntu-22-04) (tutorial in link)
    - [Any relational database](https://www.geeksforgeeks.org/how-to-install-mysql-in-windows/) (I used MySQL with MySQL Workbench - not necessarily)
    - [Git](https://git-scm.com/downloads) (Link)

## Clone the Project

If these prerequisites are established, you can start cloning the project.

Clone the Git repository:  
[https://github.com/KyryloPapazov/cloudflare-panel.git](https://github.com/KyryloPapazov/cloudflare-panel.git)

## Installation on Ubuntu

### 1. Create and Navigate to the Project Directory

Open the terminal in Ubuntu (CTRL+ALT+T) and run:

```bash
mkdir name_project
cd name_project
```

> **Important:** Make sure you are working in the correct folder.  
> You may need to use `sudo`; use it carefully!

### 2. Clone the Repository

```bash
git clone https://github.com/KyryloPapazov/cloudflare-panel.git
```

### 3. Install Dependencies

Navigate to the project directory and install the dependencies:

```bash
cd cloudflare-panel
npm install
composer install
```

> If you encounter errors, check if PHP, its dependencies, and npm are installed.

### 4. Create an `.env` File

Generate the APP key:

```bash
php artisan key:generate
```

If not complete, copy the `.env` file:

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Connect to the Database

Configure the database connection in `.env`:

```env
DB_CONNECTION=sqlite #mysql
```

If not using SQLite, fill in the following fields:

```env
#DB_HOST=127.0.0.1 
#DB_PORT=
#DB_DATABASE=
#DB_USERNAME=
#DB_PASSWORD=
```

### 6. Run Migrations

```bash
php artisan migrate --seed
```

### 7. Run Servers for Laravel and Vue.js

Run these commands in the project directory (`your/path/cloudflare-panel`):

- Start Vue.js server:

  ```bash
  npm run dev
  ```

- Start Laravel server:

  ```bash
  php artisan serve
  ```

### Visit the project at [http://localhost:8000](http://localhost:8000). 
### login: admin@example.com
### password: admin
