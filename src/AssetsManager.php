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

}
