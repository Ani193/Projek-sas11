<?php
// Contoh data promo
$promoList = [
    "Gold" => 10,
    "Platinum" => 15,
    "VIP" => 25
];

// Contoh user login
$userMembership = "Platinum"; // ini biasanya diambil dari session user login

// Cek promo otomatis
if (array_key_exists($userMembership, $promoList)) {
    $diskon = $promoList[$userMembership];
    echo "Selamat! Anda mendapatkan diskon sebesar $diskon% karena membership Anda $userMembership.";
} else {
    echo "Tidak ada diskon untuk membership Anda.";
}
?>