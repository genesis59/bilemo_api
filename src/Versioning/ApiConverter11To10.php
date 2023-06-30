<?php

namespace App\Versioning;

use Symfony\Component\Serializer\Exception\UnexpectedValueException;

class ApiConverter11To10 implements ApiConverterInterface
{
    public function convert(string $content): string
    {
        $content = json_decode($content, true);
        if (array_key_exists('items', $content)) {
            foreach ($content['items'] as &$item) {
                if (array_key_exists('smartphones', $item)) {
                    foreach ($item['smartphones'] as &$smartphone) {
                        unset($smartphone['price']);
                    }
                } else {
                    if (array_key_exists('price', $item)) {
                        unset($item['price']);
                    }
                }
            }
        } else {
            if (array_key_exists('smartphones', $content)) {
                foreach ($content['smartphones'] as &$smartphone) {
                    unset($smartphone['price']);
                }
            } else {
                if (array_key_exists('price', $content)) {
                    unset($content['price']);
                }
            }
        }
        if (!is_string(json_encode($content))) {
            throw new UnexpectedValueException();
        }
        return json_encode($content);
    }
}
