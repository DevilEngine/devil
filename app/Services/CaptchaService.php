<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CaptchaService
{
    protected int $width = 360;
    protected int $height = 100;
    protected int $circleCount = 6;
    protected int $borderThickness = 3;
    protected int $minDistance = 30;

    public function generateCaptcha(): array
    {
        $image = imagecreatetruecolor($this->width, $this->height);

        $bg = imagecolorallocate($image, 26, 29, 33);
        $circleColor = imagecolorallocate($image, 26, 29, 33);
        $borderColor = imagecolorallocate($image, 0, 221, 119);
        $cutColor = imagecolorallocate($image, 26, 29, 33);

        imagefill($image, 0, 0, $bg);

        // Optionnel : bruit
        for ($i = 0; $i < 20; $i++) {
            imageline($image, rand(0, $this->width), rand(0, $this->height), rand(0, $this->width), rand(0, $this->height), $borderColor);
        }

        $positions = [];

        // Génération des cercles
        for ($i = 0; $i < $this->circleCount; $i++) {
            $attempts = 0;
            do {
                $x = rand(40, $this->width - 40);
                $y = rand(30, $this->height - 30);
                $r = rand(18, 26);

                $overlap = false;
                foreach ($positions as $p) {
                    $d = sqrt(pow($x - $p['x'], 2) + pow($y - $p['y'], 2));
                    if ($d < $r + $p['r'] + $this->minDistance) {
                        $overlap = true;
                        break;
                    }
                }

                $attempts++;
                if ($attempts > 50) {
                    break;
                }
            } while ($overlap);

            $positions[] = ['x' => $x, 'y' => $y, 'r' => $r];

            imagefilledellipse($image, $x, $y, $r * 2, $r * 2, $circleColor);
            for ($t = 0; $t < $this->borderThickness; $t++) {
                imageellipse($image, $x, $y, ($r * 2) - $t, ($r * 2) - $t, $borderColor);
            }
        }

        // Sélection du cercle à couper
        $cutIndex = array_rand($positions);
        $cut = $positions[$cutIndex];

        // Coupure du cercle
        imagefilledarc($image, $cut['x'], $cut['y'], $cut['r'] * 2, $cut['r'] * 2, 240, 300, $bg, IMG_ARC_PIE);
        imagearc($image, $cut['x'], $cut['y'], $cut['r'] * 2 - 2, $cut['r'] * 2 - 2, 240, 300, $cutColor);

        // Sauvegarde
        Session::put('captcha_circles', $positions);
        Session::put('captcha_cut', $cutIndex);

        // Image base64
        ob_start();
        imagepng($image);
        $data = ob_get_clean();
        imagedestroy($image);

        return [
            'image' => 'data:image/png;base64,' . base64_encode($data),
        ];
    }

    public function verifyClick(int $x, int $y): bool
    {
        $cutIndex = Session::pull('captcha_cut');
        $positions = Session::pull('captcha_circles');

        if (!is_numeric($cutIndex) || !isset($positions[$cutIndex])) {
            return false;
        }

        $target = $positions[$cutIndex];
        $dx = $x - $target['x'];
        $dy = $y - $target['y'];
        $distance = sqrt($dx ** 2 + $dy ** 2);

        return $distance <= $target['r'];
    }

    public function isCaptchaBlocked(): bool
    {
        $attempts = session()->get('captcha_attempts', 0);
        return $attempts >= 5;
    }

    public function registerCaptchaAttempt(): void
    {
        $current = session()->get('captcha_attempts', 0);
        session()->put('captcha_attempts', $current + 1);
    }

    public function resetCaptchaAttempts(): void
    {
        session()->forget('captcha_attempts');
    }
}
