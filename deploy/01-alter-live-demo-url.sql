-- Tambah kolom live_demo_url (jalankan sekali di phpMyAdmin)
-- Abaikan jika muncul error: Duplicate column name 'live_demo_url'

ALTER TABLE `templates`
    ADD COLUMN `live_demo_url` VARCHAR(255) NULL AFTER `demo_url`;
