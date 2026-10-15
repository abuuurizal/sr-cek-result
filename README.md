# SR Cek Result

Aplikasi Laravel 12 dengan autentikasi Livewire (Volt) dan Tailwind CSS.

## Tech Stack

| Technology     | Version |
| -------------- | ------- |
| PHP            | 8.3     |
| Laravel        | 12      |
| Livewire       | 3.x     |
| Tailwind CSS   | 3.x     |
| Vite           | 7.x     |

## Fitur

- Authentication (Login, Register, Forgot Password, Confirm Password, Email Verification)
- Role & Permission Management (Spatie Permission)
- Admin Panel & User Management
- Product Test CRUD
- Profile Management
- Dark Mode Support
- CI/CD Auto-Deploy via GitHub Actions ke VPS (aaPanel)

## Alat Developer (Developer Experience/DX)

- **`laravel/pail`**: Memantau file log secara realtime di CLI.
- **Script `"dev"` kustom**: Menjalankan server, antrean queue, log, dan Vite sekaligus via `composer run dev`.
- **Script `"setup"` kustom**: Menjalankan instalasi awal lengkap secara otomatis dengan `composer run setup`.

## Instalasi Lokal

```bash
# 1. Install dependensi
composer install
npm install

# 2. Siapkan environment
cp .env.example .env
php artisan key:generate

# 3. Migrasi database dan isi data awal (Roles & Admin)
php artisan migrate --seed

# 4. Hubungkan symbolic link storage
php artisan storage:link
```

## Menjalankan Aplikasi

### Jika Menggunakan Laravel Herd:
Cukup jalankan Vite di terminal:
```bash
npm run dev
```
Buka browser di: **`http://sr-cek-result.test`**

### Jika Menggunakan Terminal Biasa:
```bash
composer run dev
```
*(Atau jalankan `php artisan serve` di terminal 1 dan `npm run dev` di terminal 2).*  
Buka browser di: **`http://127.0.0.1:8000`**

---

## Akun Login Default

Setelah menjalankan migrasi dengan seeder (`--seed`), Anda dapat login menggunakan:

- **Super Admin:** `superadmin@gmail.com` | Password: `password` *(Akses penuh)*
- **Admin:** `admin@gmail.com` | Password: `password`

---

## Panduan Deployment ke Server (CI/CD)

Untuk panduan lengkap setup GitHub Actions, SSH Key, dan konfigurasi aaPanel, silakan baca:
👉 **[DEPLOYMENT.md](DEPLOYMENT.md)**

## License

[MIT](https://opensource.org/licenses/MIT)
