-- =====================================================
-- DATABASE SISTEM MANAJEMEN EVENT
-- =====================================================

DROP DATABASE IF EXISTS manajemen_event;
CREATE DATABASE manajemen_event;
USE manajemen_event;

-- =====================================================
-- TABEL USER
-- =====================================================
CREATE TABLE IF NOT EXISTS user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    no_hp VARCHAR(20),
    instansi VARCHAR(100),
    role ENUM('admin', 'peserta') DEFAULT 'peserta',
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    diupdate_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =====================================================
-- TABEL EVENT
-- =====================================================
CREATE TABLE IF NOT EXISTS event (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    deskripsi TEXT NOT NULL,
    lokasi VARCHAR(255) NOT NULL,
    tanggal_event DATE NOT NULL,
    waktu_event TIME NOT NULL,
    kuota INT NOT NULL,
    banner VARCHAR(255),
    status ENUM('open', 'closed', 'finished') DEFAULT 'open',
    dibuat_oleh INT,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    diupdate_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_event_admin FOREIGN KEY (dibuat_oleh) REFERENCES user(id) ON DELETE SET NULL
);

-- =====================================================
-- TABEL PENDAFTARAN
-- =====================================================
CREATE TABLE IF NOT EXISTS pendaftaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    kode_tiket VARCHAR(100) NOT NULL UNIQUE,
    token_qr VARCHAR(255) NOT NULL UNIQUE,
    status_checkin ENUM('pending', 'hadir') DEFAULT 'pending',
    waktu_checkin DATETIME NULL,
    path_sertifikat VARCHAR(255),
    tanggal_daftar TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    diupdate_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_pendaftaran_user FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    CONSTRAINT fk_pendaftaran_event FOREIGN KEY (event_id) REFERENCES event(id) ON DELETE CASCADE,
    CONSTRAINT unique_user_event UNIQUE(user_id, event_id)
);

-- =====================================================
-- TABEL LOG ABSENSI
-- =====================================================
CREATE TABLE IF NOT EXISTS log_absensi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pendaftaran_id INT NOT NULL,
    discan_oleh INT,
    waktu_scan TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('berhasil', 'duplikat', 'invalid') DEFAULT 'berhasil',
    catatan TEXT,
    CONSTRAINT fk_log_pendaftaran FOREIGN KEY (pendaftaran_id) REFERENCES pendaftaran(id) ON DELETE CASCADE,
    CONSTRAINT fk_log_admin FOREIGN KEY (discan_oleh) REFERENCES user(id) ON DELETE SET NULL
);

-- =====================================================
-- INDEX
-- =====================================================
CREATE INDEX idx_email_user ON user(email);
CREATE INDEX idx_status_event ON event(status);
CREATE INDEX idx_tanggal_event ON event(tanggal_event);
CREATE INDEX idx_token_qr ON pendaftaran(token_qr);
CREATE INDEX idx_kode_tiket ON pendaftaran(kode_tiket);
CREATE INDEX idx_status_checkin ON pendaftaran(status_checkin);

-- =====================================================
-- ADMIN DEFAULT
-- EMAIL    : admin@gmail.com
-- PASSWORD : admin123
-- =====================================================
INSERT INTO user (nama, email, password, role)
SELECT 'Administrator', 'admin@gmail.com', '$2y$10$i8p.ed1Xq3lu1fg8pf2/.uH1y9pBme68kUVxhlSY.y6Ml3ZzAIj8a', 'admin'
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM user WHERE email = 'admin@gmail.com');

-- =====================================================
-- DATA EVENT CONTOH
-- =====================================================
INSERT INTO event (judul, slug, deskripsi, lokasi, tanggal_event, waktu_event, kuota, status, dibuat_oleh)
SELECT 'Seminar Teknologi AI', 'seminar-teknologi-ai', 'Seminar perkembangan AI terbaru.', 'Gedung Convention Yogyakarta', '2026-06-15', '09:00:00', 200, 'open', 1
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM event WHERE slug = 'seminar-teknologi-ai');

INSERT INTO event (judul, slug, deskripsi, lokasi, tanggal_event, waktu_event, kuota, status, dibuat_oleh)
SELECT 'Workshop UI UX', 'workshop-ui-ux', 'Pelatihan desain UI UX modern.', 'Auditorium Kampus', '2026-06-20', '13:00:00', 100, 'open', 1
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM event WHERE slug = 'workshop-ui-ux');
