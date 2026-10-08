# All Things Tobago — deploying to Hostinger (`public_html`)

The whole project goes into `public_html`. The root `.htaccess` sends every request
into `public/` and blocks direct access to `.env`, `vendor/`, `storage/` and the rest.
The lock file is pinned to PHP 8.3, so it installs on Hostinger's default PHP.

## 1. hPanel

1. **PHP**: Websites → PHP configuration → **8.3** or **8.4**, with `pdo_mysql`, `mbstring`, `fileinfo`, `gd` enabled (default).
2. **MySQL**: Databases → create a database and user; note the three values.
3. **Email**: create a mailbox such as `bookings@your-domain.com` for the new-booking alerts.
4. **SSL**: Security → SSL → active for the domain.

## 2. After `git clone` into `public_html` (SSH)

```sh
cd ~/public_html
php /usr/local/bin/composer install --no-dev --optimize-autoloader --no-interaction
cp .env.hostinger.example .env
php artisan key:generate --force
nano .env            # APP_URL, DB_*, MAIL_* values from hPanel
php artisan migrate --force
php artisan db:seed --class=ExperienceSeeder     # loads the 13 experiences; never run plain db:seed on production
php artisan site:admin                           # creates the dashboard login (prompts for a password)
php artisan storage:link                         # photo uploads (experience photos, gallery)
chmod -R ug+rwx storage bootstrap/cache
php artisan optimize
```

`public/build/` is committed, so no Node is needed on the server. If `composer` is not
on the path, upload `composer.phar` and use `php composer.phar install --no-dev`.

## 3. Check

- Home page loads with styles and the weather strip.
- `/.env` returns 403. `/up` returns OK.
- Submit a booking request on any experience: it appears at `/admin/bookings` and an email arrives.
- `/sitemap.xml` shows the live domain.

## 4. Before launch (content)

Open `config/site.php` and replace every item marked **CONFIRM**: phone and WhatsApp
number, email, hours, USD rate. Check every price and inclusion in
`config/experiences.php` with the client, then re-run the experience seeder.
Upload real photos in Admin → Experiences and Admin → Gallery.

## Updating later

```sh
cd ~/public_html && git pull && php /usr/local/bin/composer install --no-dev --optimize-autoloader --no-interaction && php artisan migrate --force && php artisan optimize:clear && php artisan optimize
```
