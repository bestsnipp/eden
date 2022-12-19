<?php

namespace BestSnipp\Eden;

use Illuminate\Support\Str;

class AssetsManager
{
    private $styles = [];

    private $scripts = [];

    public function styles()
    {
        return $this->styles;
    }

    public function scripts()
    {
        return $this->scripts;
    }

    public function registerStyle($url, $key = null, $attributes = [])
    {
        $key = is_null($key) ? strtolower(Str::random()) : Str::slug($key);
        if (!isset($this->styles[$key])) {
            $this->styles[$key] = [
                'url' => $url,
                'key' => $key,
                'attributes' => $attributes
            ];
        }
    }

    public function registerScripts($url, $key = null, $attributes = [])
    {
        $key = is_null($key) ? strtolower(Str::random()) : Str::slug($key);
        if (!isset($this->scripts[$key])) {
            $this->scripts[$key] = [
                'url' => $url,
                'key' => $key,
                'attributes' => $attributes
            ];
        }
    }

    protected function toRGBString ($hexCode) {
        if ( Str::startsWith($hexCode, '#')) {
            $hex = str_ireplace('#', '', $hexCode);

            if (strlen($hex) == 3) {
                $hex = "${hex[0]}${hex[0]}${hex[1]}${hex[1]}${hex[2]}${hex[2]}";
            }

            $hexCode = array_map('hexdec', str_split($hex, 2));
            return implode(', ', $hexCode);
        }

        return $hexCode;
    }

    protected function adjustBrightness($hexCode, $adjustPercent)
    {
        $hexCode = ltrim($hexCode, '#');

        if (strlen($hexCode) == 3) {
            $hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
        }

        $hexCode = array_map('hexdec', str_split($hexCode, 2));

        foreach ($hexCode as & $color) {
            $adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
            $adjustAmount = ceil($adjustableLimit * $adjustPercent);

            $color = str_pad(dechex($color + $adjustAmount), 2, '0', STR_PAD_LEFT);
        }

        return '#' . implode($hexCode);
    }

    public function generateBrandColors()
    {
        $brandColor = config('eden.color');
        $response = '';
        if (null == $brandColor) {
            return $response;
        }

        if (is_string($brandColor)) {
            $brandColor = [500 => $brandColor];
        }

        if (is_array($brandColor)) {
            // Variations and Color Brightness
            $variations = [
                50 => 0.9,
                100 => 0.8,
                200 => 0.7,
                300 => 0.5,
                400 => 0.2,
                500 => 0,
                600 => -0.1,
                700 => -0.2,
                800 => -0.3,
                900 => -0.4
            ];

            $response = '<style type="text/css">';
            $response .= ':root {'; // Root Start

            foreach ($variations as $variation => $brightness) {
                if (isset($brandColor[$variation])) {
                    $response .= "--colors-primary-$variation: " . $this->toRGBString($brandColor[$variation]) . ";";
                } else {
                    $baseColor = isset($brandColor[500]) ? $brandColor[500] : head($brandColor);
                    $response .= "--colors-primary-$variation: " . $this->toRGBString($this->adjustBrightness($baseColor, $brightness)) . ";";
                }
                $response .= PHP_EOL;
            }

            $response .= '}'; // Root End
            $response .= '</style>';
            return $response;
        }

        return $response;
    }
}
