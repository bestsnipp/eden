<?php

namespace BestSnipp\Eden\Components\DataTable\Filters;

use BestSnipp\Eden\Traits\CanBeRendered;
use BestSnipp\Eden\Traits\Makeable;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;

/**
 * @method static make(mixed $name, string $column = null)
 */
abstract class Filter
{
    use Makeable;
    use CanBeRendered;

    public $title = '';

    protected $key = '';

    protected $uid = '';

    public $initialValue = '';

    public $value = '';

    public $isApplied = false;

    protected function __construct($title, $key = null)
    {
        $this->title = $title;
        $this->key = is_null($key) ? Str::snake(Str::lower($title)) : $key;
        $this->initialValue = $this->value;
        $this->uid = Str::lower('__' . Str::random());

        if (method_exists($this, 'onMount')) {
            $this->onMount();
        }
    }

    public function value($value)
    {
        $this->value = value($value);
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function label()
    {
        return $this->value;
    }

    // Handle Pipeline Actions
    public function handle($request, \Closure $next)
    {
        if (empty($this->value)) {
            $this->isApplied = $this->isApplied();
            return $next($request); // Return Original Query
        }
        $query = $next($request); // Prepare Query
        $this->isApplied = $this->isApplied();

        return $this->apply($query, $this->value);
    }

    protected function isApplied()
    {
        return !empty($this->value);
    }

    /**
     * @param Builder|\Illuminate\Database\Eloquent\Builder $query
     * @param $value
     * @return mixed
     */
    abstract protected function apply($query, $value);

    public function defaultViewParams()
    {
        return [
            'key' => $this->key,
            'uid' => $this->uid,
            'title' => $this->title,
            'value' => $this->value,
            'initial' => $this->initialValue
        ];
    }

    public function view()
    {
        return view('eden::datatable.filters.text');
    }
}
