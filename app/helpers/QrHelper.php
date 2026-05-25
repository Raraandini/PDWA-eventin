<?php

namespace App\Helpers;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrHelper {
    /**
     * Generate QR Code as raw SVG markup
     * 
     * @param string $text Content to encode in the QR code
     * @return string SVG XML markup
     */
    public static function generateSvg($text) {
        $options = new QROptions([
            'version'      => QRCode::VERSION_AUTO,
            'eccLevel'     => QRCode::ECC_M,
            'outputType'   => QRCode::OUTPUT_MARKUP_SVG,
            'imageBase64'  => false, // Returns raw SVG XML instead of base64 data URI
            'addQuietzone' => true,
        ]);

        $qrcode = new QRCode($options);
        return $qrcode->render($text);
    }

    /**
     * Generate QR Code and save it as an SVG file inside the public/qrcode directory
     * 
     * @param string $text Content to encode in the QR code
     * @param string $filename Name of the file (without extension)
     * @return string Relative URL path to the saved QR code file
     */
    public static function generateFile($text, $filename) {
        $dir = dirname(dirname(__DIR__)) . '/public/qrcode/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $options = new QROptions([
            'version'      => QRCode::VERSION_AUTO,
            'eccLevel'     => QRCode::ECC_M,
            'outputType'   => QRCode::OUTPUT_MARKUP_SVG,
            'imageBase64'  => false, // Returns raw SVG XML to be saved as an SVG file
            'addQuietzone' => true,
        ]);

        $qrcode = new QRCode($options);
        $svgData = $qrcode->render($text);
        
        // Save raw SVG XML content into file
        file_put_contents($dir . $filename . '.svg', $svgData);
        
        return 'qrcode/' . $filename . '.svg';
    }
}
