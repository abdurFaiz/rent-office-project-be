#!/bin/bash

# Menjalankan migrasi database
php artisan migrate --force

# Mengoptimalkan aplikasi
php artisan optimize

# Jika menggunakan storage:link
php artisan storage:link

# Menjalankan server
php artisan serve --host=0.0.0.0 --port=$PORT 