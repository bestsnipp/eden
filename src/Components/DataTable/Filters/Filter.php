<?php

namespace Dgharami\Eden\Components\DataTable\Filters;

use Dgharami\Eden\Traits\CanBeRendered;
use Dgharami\Eden\Traits\Makeable;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;

/**
 * @method static make(mixed $name, string $key = null)
 */
abstract class Filter
{
    use Makeable;
    use CanBeRendered;

    public $title = '';

    protected $key = '';

    public $initialValue = '';

    public $value = '';

    public $isApplied = false;

    protected function __construct($title, $key = null)
    {
        $this->title = $title;
        $this->key = is_null($key) ? Str::snake(Str::lower($title)) : $key;
        $this->initialValue = $this->value;
    }

    public function value($value)
    {
        $this->value = value($value);
        return $this;
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

    abstract public function render($value);
}
