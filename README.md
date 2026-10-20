# Cek Result VPS (Cloud Live Timing & Sync Receiver)

Project Laravel ini didesain khusus untuk berjalan di VPS sebagai **Cloud Receiver** data hasil timing dari laptop/server lokal MyLaps lapangan, sekaligus melayani **Portal Publik Cek Result Real-Time** bagi peserta dan penonton.

---

## 🎯 Fitur Utama

1. **API Receiver Sync (`POST /api/v1/sync/participants`):**
   - Menerima kiriman batch data peserta dan hasil waktu lari yang sudah diolah dari laptop event lokal.
   - Menggunakan mekanisme **Batch Upsert** (cepat & efisien, update jika nomor BIB sudah ada, insert jika baru).
   - Dilindungi **API Key Protection** (`X-API-KEY` atau Bearer Token). Port MySQL 3306 VPS tetap tertutup rapat dari internet.
2. **API Status & Health Check (`GET /api/v1/sync/status`):**
   - Memantau total peserta, total finisher, jumlah DNS/DNF, serta timestamp batch terakhir yang masuk.
3. **API Delta Polling (`GET /api/v1/participants/delta?since=...`):**
   - Mendukung Kiosk mandiri (*Offline-First*) untuk menarik pembaruan data terbaru secara berkala.
4. **Portal Publik Web Cek Result (`/`):**
   - Antarmuka modern, cepat, dan responsif di smartphone.
   - Pencarian berdasarkan nomor BIB atau Nama Peserta.
   - Filter berdasarkan Kategori Lomba.
   - Popup E-Result Card (Kartu Hasil Finisher) lengkap dengan Chip Time, Gun Time, Pace, dan tombol cetak.
5. **CI/CD Auto-Deploy GitHub Actions:**
   - Terintegrasi alur deploy otomatis ke aaPanel VPS pada branch `main`.

---

## ⚙️ Konfigurasi Database VPS

Pada server VPS (aaPanel), konfigurasi database telah disesuaikan dengan kredensial:

```env
APP_NAME="Cek Result Live"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://demo-cek-result.solusina.id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sql_demo_cek_result_solusina_id
DB_USERNAME=sql_demo_cek_result_solusina_id
DB_PASSWORD=ad080642c6bdb

# Kunci API untuk sinkronisasi (samakan dengan project lokal)
SYNC_API_KEY=sr-sync-secret-key-2026
```

---

## 📡 Dokumentasi Endpoint REST API

### 1. Push Batch Data Peserta & Hasil (Khusus Local Engine)
* **Method:** `POST`
* **URL:** `/api/v1/sync/participants`
* **Header Wajib:**
  * `Content-Type: application/json`
  * `X-API-KEY: sr-sync-secret-key-2026`

#### Contoh Payload JSON:
```json
{
  "event_name": "Demo Marathon 2026",
  "batch_id": "batch-1710001",
  "participants": [
    {
      "no_bib": "7796",
      "mylaps_code": "MYL-001",
      "chip_code": "E2801170",
      "nama_lengkap": "Budi Santoso",
      "gender": "Male",
      "kategori": "10K",
      "sub_kategori": "Master",
      "status": "finished",
      "pace": "04:25",
      "gun_time": "00:45:10",
      "chip_time": "00:44:12"
    },
    {
      "no_bib": "7797",
      "mylaps_code": "MYL-002",
      "chip_code": "E2801171",
      "nama_lengkap": "Siti Aminah",
      "gender": "Female",
      "kategori": "5K",
      "status": "finished",
      "pace": "05:10",
      "gun_time": "00:26:05",
      "chip_time": "00:25:50"
    }
  ]
}
```

#### Contoh Respons (200 OK):
```json
{
  "success": true,
  "message": "Data peserta berhasil disinkronisasi.",
  "batch_id": "batch-1710001",
  "synced_count": 2,
  "synced_at": "2026-10-19T22:50:00+07:00"
}
```

---

### 2. Cek Status Server & Ringkasan Hasil
* **Method:** `GET`
* **URL:** `/api/v1/sync/status`
* **Header:** `X-API-KEY: sr-sync-secret-key-2026`

#### Contoh Respons:
```json
{
  "success": true,
  "server_time": "2026-10-19T22:55:00+07:00",
  "stats": {
    "total_participants": 2500,
    "finished": 1850,
    "dns": 600,
    "dnf": 50
  },
  "last_sync": {
    "batch_id": "batch-1710001",
    "event_name": "Demo Marathon 2026",
    "records_count": 50,
    "status": "success",
    "synced_at": "2026-10-19T22:54:10+07:00"
  }
}
```

---

### 3. Detail Peserta Tunggal (Publik)
* **Method:** `GET`
* **URL:** `/api/v1/participants/{identifier}` *(Bisa isi nomor BIB atau Kode Chip)*

---

## 🧪 Menguji Push Data Menggunakan cURL

Jalankan perintah ini di terminal untuk menguji endpoint:

```bash
curl -X POST http://127.0.0.1:8000/api/v1/sync/participants \
  -H "Content-Type: application/json" \
  -H "X-API-KEY: sr-sync-secret-key-2026" \
  -d '{
    "event_name": "Test Run",
    "participants": [
      {
        "no_bib": "1001",
        "nama_lengkap": "Test Runner",
        "kategori": "10K",
        "status": "finished",
        "chip_time": "00:49:30",
        "pace": "04:57"
      }
    ]
  }'
```
