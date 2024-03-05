# Install Apache

	sudo apt-get update
	sudo apt-get install apache2

	sudo ufw app list

	sudo ufw allow 'Apache'
	sudo ufw status

	sudo systemctl stop apache2
	sudo systemctl start apache2
	sudo systemctl restart apache2
	sudo systemctl reload apache2
	sudo systemctl disable apache2
	sudo systemctl enable apache2

# Install Composer Git

	sudo apt-get update
	sudo apt-get install git composer -y

# Install PHP

	apt-get install libapache2-mod-php

# Install Laravel

## Option 1

	cd /var/www/html
	git clone https://github.com/laravel/laravel.git .
	composer install

## Option 2

	cd /var/www/html
	composer create-project --prefer-dist laravel/laravel .


# Update ENV File

	cd /var/www/html
	cp .env.example .env
	php artisan key:generate

	cd /var/www/html
	nano .env

	APP_URL=https://techvblogs.com

	DB_CONNECTION=mysql
	DB_HOST=YOUR_DB_HOST
	DB_PORT=3306
	DB_DATABASE=techvblogs
	DB_USERNAME=admin
	DB_PASSWORD=YOUR_PASSWORD

# Configure Apache for Laravel

	sudo nano /etc/apache2/sites-available/quickstart.conf

	<VirtualHost *:80>

    ServerAdmin admin@techvblogs.com
    ServerName 92.119.58.98
    DocumentRoot /var/www/html/quickstart/public

    <Directory /var/www/html/quickstart/public>
       Options +FollowSymlinks
       AllowOverride All
       Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined

	</VirtualHost>


	sudo a2enmod rewrite
	sudo a2ensite quickstart.conf

	sudo service apache2 restart

	sudo chown -R $USER:www-data storage
	sudo chown -R $USER:www-data bootstrap/cache

	chmod -R 775 storage
	chmod -R 775 bootstrap/cache

	ps aux|grep nginx|grep -v grep

	ps aux | egrep '(apache|httpd)'


# Create mysql users
	docker exec -it db bash -l

	CREATE USER 'root'@'%' IDENTIFIED BY 'passowrd';
	GRANT ALL PRIVILEGES ON *.* TO 'root'@'%';

	CREATE USER 'mjimani'@'%' IDENTIFIED BY 'passowrd';
	GRANT ALL PRIVILEGES ON *.* TO 'mjimani'@'%';


php artisan migrate
php artisan schedule:work
