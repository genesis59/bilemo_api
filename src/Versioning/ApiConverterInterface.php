<?php

namespace App\Versioning;

interface ApiConverterInterface
{
    public function convert(string $content): string;
}
