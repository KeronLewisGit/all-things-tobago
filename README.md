# All Things Tobago

Booking website and dashboard for the Tobago tour operator behind @allthingstobago.
Laravel 13, Tailwind 4, Alpine. No JavaScript framework, no build step on the server.

- Public site: experiences catalogue with live-price booking requests, a multi-experience
  trip planner, accommodation and ticket enquiries, link-in-bio page, FAQ, weather strip.
- Dashboard (`/admin`): bookings pipeline, calendar with blocked dates, prices and photos
  per experience, review moderation, gallery, enquiries, CSV export.

## Local

```sh
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed        # demo bookings and reviews; admin login is in config/site.php email + "password"
npm run build && php artisan serve
```

## Photos

Experience cards ship with stock photos in `public/images/experiences/` (Wikimedia Commons, mostly
Creative Commons; credits in `config/experiences.php` and on `/photo-credits`). Upload the crew's own
photos in Admin → Experiences to replace them; the credits page only lists photos still in use.

See `DEPLOY.md` for Hostinger.
