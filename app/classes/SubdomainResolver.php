<?php

namespace App\Classes;

class SubdomainResolver
{
    public function getSubdomain(): ?string
    {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if (!$host) {
            return null;
        }
        $parts = explode('.', $host);
        if (count($parts) < 3) {
            return null;
        }
        return $parts[0];
    }
}
