<?php

namespace App\Services\Syarat;

use InvalidArgumentException;

class SyaratFactory
{
    /**
     * Membuat instance service berdasarkan ID jenis syarat.
     *
     * @param int $jenisSyaratId
     * @return SyaratServiceInterface
     */
    public static function make(int $jenisSyaratId): SyaratServiceInterface
    {
        $map = [
            1  => SyaratIdentitasAplikasiService::class,
            2  => SyaratKakService::class,
            3  => SyaratSuratRekomendasiService::class,
            4  => SyaratDokumenTeknisPengembanganService::class,
            5  => SyaratDokumenTeknisPengembanganService::class,
            6  => SyaratDokumenPengujianService::class,
            7  => SyaratDokumenPengujianService::class,
            8  => SyaratDokumenPengujianService::class,
            9  => SyaratDokumenPengujianService::class,
            10 => SyaratNdaService::class,
            11 => SyaratDokumenPengujianService::class,
        ];

        if (!isset($map[$jenisSyaratId])) {
            // throw new InvalidArgumentException("Jenis syarat ID '{$jenisSyaratId}' tidak dikenali.");
            $map[$jenisSyaratId] = SyaratLainnyaService::class;
        }

        return app($map[$jenisSyaratId]);
    }
}
