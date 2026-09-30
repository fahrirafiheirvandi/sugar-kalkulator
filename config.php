<?php
// =========================================================
// TIMEZONE
// =========================================================
// PENTING: tanpa ini, date()/CURDATE() PHP default ke UTC,
// sedangkan MySQL biasanya ikut jam lokal server. Bedanya bikin
// tanggal "hari ini" versi PHP dan versi MySQL nggak sinkron
// (contoh: PHP masih mikir tanggal kemarin padahal sudah lewat
// tengah malam WIB). Makanya total gula "hari ini" & riwayat
// bisa keliatan salah tanggal.
date_default_timezone_set('Asia/Jakarta');

// =========================================================
// KONEKSI DATABASE
// =========================================================

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'sugar_kalkulator_db';

$koneksi = new mysqli($host, $user, $pass, $dbname);

if ($koneksi->connect_error) {
    die('Koneksi database gagal: ' . $koneksi->connect_error);
}

$koneksi->set_charset('utf8mb4');

// Samain juga timezone koneksi MySQL biar CURDATE()/NOW() di query
// selalu sama dengan jam lokal (WIB), bukan UTC bawaan server MySQL.
$koneksi->query("SET time_zone = '+07:00'");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}