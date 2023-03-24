## CORONAVIRUS API

#### Installation

For correct installation, your server must have > PHP 7.1 and Composer installed.
To install, do the following:

- To clone the project to the local machine and enter the project folder
```bash
git clone https://github.com/EvhenZhykov/coronavirus.git
cd coronavirus/
```
- Install all application dependencies using [Composer](https://getcomposer.org/)
```bash
composer install
```
- Create an application database, for example (or create DB in phpMyAdmin)
```sql
CREATE DATABASE `coronavirus` COLLATE 'utf8_general_ci'
```
- Set up a connection to the MySQL database in the file **.env**
```bash
DB_DATABASE=coronavirus
DB_USERNAME=yourUserName
DB_PASSWORD=yourPassword
```
- Run the database table generation script
```bash
php artisan migrate
```
- Run the command for fill statistic
```bash
php artisan db:fill_statistic
```

- Run the web server
```bash
php artisan serve
```
