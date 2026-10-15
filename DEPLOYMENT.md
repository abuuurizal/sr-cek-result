# Panduan CI/CD & Deploy Otomatis via GitHub Actions ke VPS (aaPanel)

Proyek ini telah dikonfigurasi dengan alur **CI/CD GitHub Actions** (`.github/workflows/deploy-production.yml`) untuk *auto-deploy* langsung ke VPS Anda setiap kali ada pembaruan kode yang di-*push* ke branch `main`.

---

## 1. Persiapan Awal di Lokal (Inisialisasi Git)

Karena proyek ini belum terhubung ke Git Repository, jalankan perintah berikut di terminal lokal Anda:

```bash
# 1. Inisialisasi Git repository lokal
git init

# 2. Ubah nama branch utama menjadi main
git branch -M main

# 3. Masukkan seluruh file ke staging (file rahasia .env & folder vendor/node_modules otomatis diabaikan oleh .gitignore)
git add .

# 4. Buat commit pertama
git commit -m "feat: initial commit with auth and ci/cd workflow"

# 5. Hubungkan dengan repository GitHub baru Anda (ganti URL di bawah dengan URL repo Anda)
git remote add origin https://github.com/USERNAME/NAMA-REPO.git

# 6. Push kode pertama kali ke GitHub
git push -u origin main
```

---

## 2. Membuat SSH Key di VPS untuk GitHub Actions

Agar GitHub Actions dapat masuk ke VPS melalui SSH tanpa password:

1. Buka terminal VPS Anda (sebagai `root`), lalu jalankan:
   ```bash
   ssh-keygen -t ed25519 -C "github-actions-deploy" -f ~/.ssh/github_deploy -N ""
   ```
2. Daftarkan kunci publik ke daftar kunci yang diizinkan:
   ```bash
   cat ~/.ssh/github_deploy.pub >> ~/.ssh/authorized_keys
   chmod 600 ~/.ssh/authorized_keys
   chmod 700 ~/.ssh
   ```
3. Salin isi **Private Key** untuk dimasukkan ke GitHub:
   ```bash
   cat ~/.ssh/github_deploy
   ```
   *(Salin seluruh teks mulai dari `-----BEGIN OPENSSH PRIVATE KEY-----` sampai `-----END OPENSSH PRIVATE KEY-----`)*

---

## 3. Mendaftarkan Secrets di GitHub Repository

Buka repository Anda di GitHub, buka menu **Settings** > **Secrets and variables** > **Actions** > **New repository secret**, lalu tambahkan:

| Nama Secret | Deskripsi / Nilai Contoh |
|-------------|-------------------------|
| `PROD_SERVER_IP` | Alamat IP Publik VPS Anda (contoh: `103.187.xxx.xxx`) |
| `PROD_SERVER_USERNAME` | Username SSH (biasanya `root`) |
| `PROD_SERVER_SSH_KEY` | Isi **Private Key** yang Anda dapatkan dari langkah 2 |
| `PROD_SERVER_PORT` | Port SSH VPS Anda (default: `22`) |
| `PROD_DEPLOY_PATH` | Path direktori website di VPS (contoh: `/www/wwwroot/result.domainanda.com`) |

---

## 4. Setup Awal di aaPanel (Hanya Dijalankan 1x di Awal)

1. **Tambahkan Website Baru di aaPanel:**
   - Masuk ke panel aaPanel > menu **Website** > **Add site**.
   - Masukkan Domain/Subdomain Anda (misal `result.domainanda.com`).
   - Buat Database MySQL baru sekaligus (simpan nama database, user, dan password-nya).

2. **Kloning Proyek ke Direktori Website:**
   Buka Terminal di aaPanel atau SSH VPS:
   ```bash
   # Masuk ke direktori webroot
   cd /www/wwwroot

   # Hapus folder default yang dibuat aaPanel jika masih kosong
   rm -rf result.domainanda.com

   # Clone repository dari GitHub
   git clone https://github.com/USERNAME/NAMA-REPO.git result.domainanda.com
   cd result.domainanda.com

   # Buat file environment production
   cp .env.example .env
   nano .env
   ```
   *Sesuaikan `APP_URL`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di `.env`.*

3. **Install Dependensi & Setup Laravel:**
   ```bash
   export COMPOSER_ALLOW_SUPERUSER=1
   composer install --no-dev --optimize-autoloader
   php artisan key:generate
   php artisan migrate --seed --force
   php artisan storage:link

   # Build aset frontend
   npm install
   npm run build

   # Atur hak akses agar Nginx (www:www) dapat membaca dan menulis
   chown -R www:www /www/wwwroot/result.domainanda.com
   chmod -R 775 storage bootstrap/cache
   ```

4. **Konfigurasi Nginx di aaPanel:**
   - Buka pengaturan website di aaPanel:
     - **Site Directory**: Ubah *Running directory* ke `/public` lalu klik Save.
     - **URL Rewrite**: Pilih template **`laravel5`** lalu klik Save (wajib agar routing web tidak 404).

---

## 5. Cara Update & Auto-Deploy Seterusnya

Setelah setup di atas selesai, Anda tidak perlu lagi login ke VPS untuk deploy. Cukup lakukan commit dan push dari komputer lokal:

```bash
# 1. Simpan perubahan
git add .

# 2. Tulis pesan update
git commit -m "Update fitur atau perbaikan tampilan"

# 3. Kirim ke branch main
git push origin main
```

Pantau proses deployment di tab **Actions** pada repository GitHub Anda. GitHub Actions akan secara otomatis menjalankan:
- `git pull origin main`
- `composer install --no-dev`
- `npm run build`
- `php artisan migrate --force`
- Optimasi cache config, route, dan view
- Perbaikan permission kepemilikan file (`www:www`)
