<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateOgImage extends Command
{
    protected $signature = 'og:generate-image';

    protected $description = 'Flatten logo.png onto a white background and save as og-image.png for Open Graph sharing';

    public function handle(): int
    {
        $src  = public_path('images/logo.png');
        $dest = public_path('images/og-image.png');

        if (!file_exists($src)) {
            $this->error("Source not found: {$src}");
            return self::FAILURE;
        }

        $logo = @imagecreatefrompng($src);
        if (!$logo) {
            $this->error("Failed to open PNG: {$src}");
            return self::FAILURE;
        }

        $w = imagesx($logo);
        $h = imagesy($logo);

        $canvas = imagecreatetruecolor($w, $h);
        $white  = imagecolorallocate($canvas, 255, 255, 255);
        imagefilledrectangle($canvas, 0, 0, $w - 1, $h - 1, $white);

        // Flatten RGBA logo onto white canvas pixel-by-pixel
        // (PHP 8.5 removed imagealphablend; this approach is version-agnostic)
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $pixel = imagecolorat($logo, $x, $y);
                $r = ($pixel >> 16) & 0xFF;
                $g = ($pixel >> 8)  & 0xFF;
                $b = ($pixel)       & 0xFF;
                // GD alpha: 0 = opaque, 127 = fully transparent
                $a = ($pixel >> 24) & 0x7F;

                if ($a === 0) {
                    $color = imagecolorallocate($canvas, $r, $g, $b);
                } elseif ($a === 127) {
                    $color = $white;
                } else {
                    $opacity = 1.0 - ($a / 127.0);
                    $color = imagecolorallocate(
                        $canvas,
                        (int) round($r * $opacity + 255 * (1 - $opacity)),
                        (int) round($g * $opacity + 255 * (1 - $opacity)),
                        (int) round($b * $opacity + 255 * (1 - $opacity)),
                    );
                }

                imagesetpixel($canvas, $x, $y, $color);
            }
        }

        imagepng($canvas, $dest, 9);

        $this->info("Generated {$dest} ({$w}x{$h}, RGB, white background)");

        return self::SUCCESS;
    }
}
