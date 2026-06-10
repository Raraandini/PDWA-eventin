<?php

namespace App\Helpers;

class BannerHelper {
    /**
     * Generate an SVG data URI for a default banner image
     */
    public static function generateSvgImageUri($event) {
        $title = htmlspecialchars(mb_substr($event['judul'] ?? 'Event Seminar', 0, 45));
        $date = isset($event['tanggal_event']) ? date('d F Y', strtotime($event['tanggal_event'])) : 'Coming Soon';
        $desc = htmlspecialchars(mb_substr($event['deskripsi'] ?? 'Mengembangkan kemampuan dan keterampilan di era digital.', 0, 75));
        
        $svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="630" viewBox="0 0 1200 630">
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="#e6f4f1"/>
      <stop offset="100%" stop-color="#d1eae5"/>
    </linearGradient>
  </defs>
  <rect width="1200" height="630" fill="url(#bg)"/>
  
  <!-- Decorative Corners -->
  <g fill="none" stroke="#2dd4bf" stroke-width="6" opacity="0.8" stroke-linecap="round" stroke-linejoin="round">
    <path d="M60 100 L60 60 L100 60"/>
    <path d="M1140 100 L1140 60 L1100 60"/>
    <path d="M60 530 L60 570 L100 570"/>
    <path d="M1140 530 L1140 570 L1100 570"/>
  </g>
  
  <!-- Line -->
  <line x1="0" y1="315" x2="1200" y2="315" stroke="#064e3b" stroke-width="2" stroke-dasharray="15,15" opacity="0.1"/>

  <!-- Title -->
  <text x="600" y="240" font-family="system-ui, -apple-system, sans-serif" font-size="56" font-weight="900" fill="#134e4a" text-anchor="middle" text-transform="uppercase" letter-spacing="1">'.$title.'</text>
  
  <!-- Subtitle Pill -->
  <rect x="200" y="280" width="800" height="70" rx="35" fill="#5eead4" opacity="0.9"/>
  <text x="600" y="325" font-family="system-ui, -apple-system, sans-serif" font-size="22" font-style="italic" font-weight="500" fill="#115e59" text-anchor="middle">"'.$desc.'..."</text>
  
  <!-- Date -->
  <text x="600" y="450" font-family="system-ui, -apple-system, sans-serif" font-size="28" font-weight="bold" fill="#134e4a" text-anchor="middle">'.$date.'</text>
  
  <!-- Website -->
  <text x="600" y="490" font-family="system-ui, -apple-system, sans-serif" font-size="20" font-weight="600" fill="#0f766e" text-anchor="middle">www.eventin.com</text>
</svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
