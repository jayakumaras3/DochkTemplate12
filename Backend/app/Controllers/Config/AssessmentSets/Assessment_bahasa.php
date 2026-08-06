<?php

namespace Config\AssessmentSets;

use CodeIgniter\Config\AssessmentSets;

class Assessment_bahasa
{
    // Please use unique array id for each template
    // Page level settings
    public static $assessment_sets = array(
        "42" => "Penilaian",
        "33" => "Jumlah total pertanyaan: ",
        "34" => "Skor minimum kelulusan: ",
        "35" => "Jumlah total percobaan: ",
        "41" => "Waktu yang diberikan untuk menyelesaikan penilaian: ",
        "43" => "Jika Anda tidak mencapai skor minimum dan masih memiliki kesempatan tersisa, silakan coba lagi.",
        "40" => "<i>Pilih jawaban yang benar, lalu klik <strong>Kirim.</strong></i>",
        // "66" => "<i>Select the best answers, then click Submit</i>",
        "44" => "Mulai",
        "48" => "<i>Klik <strong>Mulai</strong> untuk memulai.</i>",
        "45" => "Kirim",
        "47" => "Lihat Hasil",
        "46" => "Coba Lagi",
        "36" => "Kuis Selesai!",
        "37" => "Skor Anda:",
        "38" => "Selamat! Anda lulus kuis.",
        "39" => "Anda tidak lulus penilaian. Silakan klik <strong>Coba Lagi</strong> untuk mencoba kembali.",
        "67" => "Anda tidak lulus penilaian. Silakan hubungi manajer Anda untuk langkah selanjutnya.",
        "49" => "Aduh! Waktu habis!",
        "69" => "Pertanyaan",
        "70" => "Dari",
        "71" => "menit",
        "73" => "<i>Klik gambar untuk memperbesarnya.</i>"

    );
    // Course level settings
    public static $assessment_export_sets = array(
        "50" => "Menu",
        "51" => "Maju",
        "52" => "Mundur",
        "53" => "Menu",
        "54" => "Transkrip",
        "55" => "Lanjutkan",
        "56" => "Anda yakin ingin melanjutkan dari saat terakhir?",
        "57" => "Ya",
        "58" => "Tidak",
        "64" => "ba", // VttLanguage
        "65" => "Bahasa", // VttLabel
        "62" => 1, // free navigation (master)
        "63" => 0, // page level course completion 
        "74" => 1, //CertificateEnabled
        "72" => "Pilih <b>Berikutnya</b> untuk Melanjutkan.",
        '75' => "LearningAids"
    );
    public static $assessment_scqmcq_sets = array(
        "59" => "<i>Pilih jawaban yang benar, lalu klik <strong>Kirim.</strong></i>",
        "60" => "<i>Pilih jawaban yang benar, lalu klik <strong>Kirim.</strong></i>",
        "61" => "Kirim",
        "68" => "Silakan pilih jawaban.",
    );
}
