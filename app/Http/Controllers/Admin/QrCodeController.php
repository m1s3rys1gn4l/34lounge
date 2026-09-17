<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Response;

class QrCodeController extends Controller
{
    public function index()
    {
        return view('admin.qr-code.index', [
            'menuUrl' => route('menu.index'),
            'dataUri' => $this->build()->getDataUri(),
            'cardDataUri' => 'data:image/png;base64,' . base64_encode($this->buildCard()),
        ]);
    }

    public function download(): Response
    {
        $result = $this->build();

        return response($result->getString(), 200, [
            'Content-Type' => $result->getMimeType(),
            'Content-Disposition' => 'attachment; filename="34lounge-menu-qr.png"',
        ]);
    }

    public function downloadCard(): Response
    {
        return response($this->buildCard(), 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="34lounge-menu-card.png"',
        ]);
    }

    private function build()
    {
        $qrCode = new QrCode(
            data: route('menu.index'),
            // A logo covers the center, so error correction must be high enough
            // to stay scannable with that chunk of the code obscured.
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 600,
            margin: 20,
            foregroundColor: new Color(20, 16, 10),
            backgroundColor: new Color(255, 255, 255),
        );

        $logo = null;
        $logoPath = public_path('images/logo.png');
        if (file_exists($logoPath)) {
            // Kept under ~30% of the QR width so it stays scannable even with High error correction.
            $logo = new Logo(path: $logoPath, resizeToWidth: 180);
        }

        $writer = new PngWriter();

        return $writer->write($qrCode, $logo);
    }

    /**
     * Composes a printable card: a large logo banner above a clean,
     * unobstructed QR code (kept fully scannable since the logo lives
     * outside the code here rather than overlapping its modules).
     */
    private function buildCard(): string
    {
        $canvasWidth = 700;
        $padding = 50;
        $gap = 35;

        $logoPath = public_path('images/logo.png');
        $logoImg = file_exists($logoPath) ? imagecreatefrompng($logoPath) : null;

        $logoTargetWidth = $canvasWidth - ($padding * 2);
        $logoTargetHeight = 0;
        if ($logoImg) {
            $ratio = imagesy($logoImg) / imagesx($logoImg);
            $logoTargetHeight = (int) round($logoTargetWidth * $ratio);
        }

        // Plain QR code, no embedded logo needed since the big logo sits above it.
        $qrCode = new QrCode(
            data: route('menu.index'),
            errorCorrectionLevel: ErrorCorrectionLevel::Medium,
            size: 500,
            margin: 20,
            foregroundColor: new Color(20, 16, 10),
            backgroundColor: new Color(255, 255, 255),
        );
        $qrResult = (new PngWriter())->write($qrCode);
        $qrImg = imagecreatefromstring($qrResult->getString());
        $qrWidth = imagesx($qrImg);
        $qrHeight = imagesy($qrImg);

        $captionHeight = 40;
        $canvasHeight = $padding + $logoTargetHeight + $gap + $qrHeight + $gap + $captionHeight + $padding;

        $canvas = imagecreatetruecolor($canvasWidth, $canvasHeight);
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $white);
        imagesavealpha($canvas, true);

        $y = $padding;

        if ($logoImg) {
            imagealphablending($canvas, true);
            imagecopyresampled(
                $canvas, $logoImg,
                $padding, $y, 0, 0,
                $logoTargetWidth, $logoTargetHeight,
                imagesx($logoImg), imagesy($logoImg)
            );
            $y += $logoTargetHeight;
        }

        $y += $gap;
        $qrX = (int) round(($canvasWidth - $qrWidth) / 2);
        imagecopy($canvas, $qrImg, $qrX, $y, 0, 0, $qrWidth, $qrHeight);
        $y += $qrHeight + $gap;

        $caption = 'SCAN TO VIEW OUR MENU';
        $dark = imagecolorallocate($canvas, 20, 16, 10);
        $fontWidth = imagefontwidth(5);
        $textX = (int) round(($canvasWidth - strlen($caption) * $fontWidth) / 2);
        imagestring($canvas, 5, $textX, $y, $caption, $dark);

        ob_start();
        imagepng($canvas);
        $bytes = ob_get_clean();

        imagedestroy($canvas);
        imagedestroy($qrImg);
        if ($logoImg) {
            imagedestroy($logoImg);
        }

        return $bytes;
    }
}
