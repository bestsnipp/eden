<?php

namespace BestSnipp\Eden\Components\Fields;

use Illuminate\Support\Str;

class Textarea extends Field
{
    protected $previewCharLimit = 37;

    protected function onMount()
    {
        $this->meta = array_merge($this->meta, [
            'rows' => 2,
            'cols' => 30
        ]);
    }

    public function rows($row)
    {
        $this->meta = array_merge($this->meta, [
            'rows' => $row
        ]);
        return $this;
    }

    public function cols($cols)
    {
        $this->meta = array_merge($this->meta, [
            'cols' => $cols
        ]);
        return $this;
    }

    public function view()
    {
        return view('eden::fields.input.textarea');
    }

    public function viewForIndex()
    {
        parent::viewForIndex();

        return view('eden::fields.row.textarea')
            ->with([
                'preview' => Str::limit(strip_tags($this->value), 37),
                'hasExtra' => Str::length(strip_tags($this->value)) > 100
            ]);
    }

    public function viewForRead()
    {
        parent::viewForRead();

        return view('eden::fields.view.textarea')
            ->with([
                'preview' => Str::limit(strip_tags($this->value), $this->previewCharLimit),
                'hasExtra' => Str::length(strip_tags($this->value)) > $this->previewCharLimit
            ]);
    }

}
