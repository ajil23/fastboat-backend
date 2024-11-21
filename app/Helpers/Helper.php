<?php

namespace App\Helpers;

// Fungsi untuk menangani path gambar
function imagePath($imagePath)
{
    // Jika menggunakan asset()
    if (strpos($imagePath, 'assets/') === 0) {
        return public_path($imagePath);
    }

    // Handle path untuk cpn_logo
    // Karena di database mungkin hanya tersimpan nama filenya saja
    return storage_path('app/public/' . $imagePath);
}
