<?php

namespace App\Utils;

class Terbilang
{
    public static function rupiah($angka)
    {
        $angka = abs((int) $angka);
        $baca = [
            "", "Satu", "Dua", "Tiga", "Empat", "Lima",
            "Enam", "Tujuh", "Delapan", "Sembilan",
            "Sepuluh", "Sebelas"
        ];

        if ($angka < 12) {
            return $baca[$angka];
        } elseif ($angka < 20) {
            return self::rupiah($angka - 10) . " Belas";
        } elseif ($angka < 100) {
            return self::rupiah(intval($angka / 10)) . " Puluh " . self::rupiah($angka % 10);
        } elseif ($angka < 200) {
            return "Seratus " . self::rupiah($angka - 100);
        } elseif ($angka < 1000) {
            return self::rupiah(intval($angka / 100)) . " Ratus " . self::rupiah($angka % 100);
        } elseif ($angka < 2000) {
            return "Seribu " . self::rupiah($angka - 1000);
        } elseif ($angka < 1000000) {
            return self::rupiah(intval($angka / 1000)) . " Ribu " . self::rupiah($angka % 1000);
        }

        return "Jumlah Terlalu Besar";
    }
}
