<?php

namespace BestSnipp\Eden\Components\Fields;

class CheckBoxes extends Field
{
    protected $value = [];

    protected $meta = [
        'type' => 'checkbox',
        'class' => 'focus:ring-0 h-5 w-5 text-indigo-600 border-gray-300 rounded dark:bg-slate-600 dark:border-slate-700 focus-within:border-indigo-700 dark:text-slate-300 dark:checked:bg-slate-800 dark:hover:bg-slate-500 dark:focus:ring-slate-500',
    ];

    /**
     * Column to show during create and update, default - 1
     *
     * @var int
     */
    protected $column = 1;

    protected $hideUnchecked = false;

    public function hideUnchecked($should = true)
    {
        $this->hideUnchecked = appCall($should);

        return $this;
    }

    public function column($column = 1)
    {
        $this->column = $column;

        return $this;
    }

    public function view()
    {
        $cols = isset($this->getColumns()[$this->column])
            ? $this->getColumns()[$this->column]
            : head($this->getColumns());

        return view('eden::fields.input.radio-checkbox')
            ->with('cols', $cols);
    }

    public function viewForIndex()
    {
        parent::viewForIndex();

        return view('eden::fields.row.checkbox');
    }

    public function viewForRead()
    {
        parent::viewForRead();

        $cols = isset($this->getColumns()[$this->column])
            ? $this->getColumns()[$this->column]
            : head($this->getColumns());

        return view('eden::fields.view.checkbox')
            ->with('hideUnchecked', $this->hideUnchecked)
            ->with('cols', $cols);
    }

    protected function getColumns()
    {
        return [
            1 => 'md:grid-cols-1',
            2 => 'md:grid-cols-2',
            3 => 'md:grid-cols-3',
            4 => 'md:grid-cols-4',
            5 => 'md:grid-cols-5',
            6 => 'md:grid-cols-6',
            7 => 'md:grid-cols-7',
            8 => 'md:grid-cols-8',
            9 => 'md:grid-cols-9',
            10 => 'md:grid-cols-10',
            11 => 'md:grid-cols-11',
            12 => 'md:grid-cols-12',
        ];
    }
}
