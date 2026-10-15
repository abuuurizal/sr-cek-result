# Starter Livewire Blade

Starter project Laravel dengan autentikasi menggunakan Breeze + Livewire.

## Tech Stack

| Technology     | Version |
| -------------- | ------- |
| PHP            | 8.3     |
| Laravel        | 12      |
| Livewire       | 3.x     |
| Laravel Breeze | 2.x     |
| Tailwind CSS   | 4.x     |
| Vite           | 7.x     |

## Fitur

- Authentication (Login, Register, Forgot Password)
- Profile Management
- Livewire Components
- Tailwind CSS Styling

## Alat Developer (Developer Experience/DX)

- **`laravel/pail`**: Memantau (tail) file log dengan tampilan CLI yang interaktif.
- **Script `"dev"` kustom**: Script ini dikonfigurasi di `composer.json` untuk menjalankan 4 hal sekaligus menggunakan `concurrently` (menjalankannya dengan `composer run dev`):
  1. Web server (`php artisan serve`)
  2. Queue worker (`php artisan queue:listen`)
  3. Log tailer (`php artisan pail`)
  4. Vite asset bundler (`npm run dev`)
- **Script `"setup"` kustom**: Memudahkan proses instalasi. Cukup jalankan `composer run setup`, dan semua proses dari instalasi dependensi, copy `.env`, key generate, hingga migrasi database akan otomatis dijalankan.

## Instalasi

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

## Menjalankan

```bash
npm run dev
```

Buka di browser: `http://starter-livewire-blade.test`

## License

[MIT](https://opensource.org/licenses/MIT)
