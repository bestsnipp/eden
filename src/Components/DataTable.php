<?php

namespace BestSnipp\Eden\Components;

use App\Models\User;
use BestSnipp\Eden\Components\DataTable\Actions\Action;
use BestSnipp\Eden\Components\DataTable\Actions\StaticAction;
use BestSnipp\Eden\Components\DataTable\Column\ActionField;
use BestSnipp\Eden\Components\DataTable\Column\SelectorField;
use BestSnipp\Eden\Components\Fields\Field;
use BestSnipp\Eden\Facades\Eden;
use BestSnipp\Eden\RenderProviders\DataTableRenderer;
use BestSnipp\Eden\Traits\InteractsWithAction;
use BestSnipp\Eden\Traits\WithModel;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\WithPagination;

abstract class DataTable extends EdenComponent
{
    use WithPagination;
    use WithModel;
    use InteractsWithAction;

    /**
     * Title to show in front end
     *
     * @var string
     */
    public $title = '';

    /**
     * Define component width
     *
     * @var string
     */
    public $width = 'full';

    /**
     * is Table Layout
     *
     * @var bool
     */
    public $isTableLayout = true;

    /**
     * Show Header Rows
     *
     * @var bool
     */
    public $showHeader = true;

    public $latestFirst = true;

    /**
     * Show filters dialog so that user can apply filter to datatable
     *
     * @var bool
     */
    public $showFilters = false;

    /**
     * Should show search field
     *
     * @var bool
     */
    public $isSearchable = true;

    /**
     * Datatable Search Query String
     *
     * @var string
     */
    public $searchQuery = '';

    protected $queryString = [
        'searchQuery' => ['except' => '', 'as' => 'q'],
    ];

    /**
     * Columns which will be used to search the query
     *
     * @var array
     */
    protected $searchFields = [];

    /**
     * Should show rows per page selection in front end
     *
     * @var bool
     */
    public $showRowsPerPageFilter = true;

    /**
     * pagination styles. Available values simple, cursor, paginate [default]
     *
     * @var string
     */
    public $paginationType = 'paginate';

    /**
     * Rows per page
     *
     * @var int
     */
    public $rowsPerPage = 10;

    /**
     * Initial Value of Rows Per Page - Auto Inject
     *
     * @var null|0
     */
    public $initialRowsPerPage = 0;

    /**
     * Rows Per Page Options
     *
     * @var int[]
     */
    public $rowsPerPageOptions = [
        5 => 5,
        10 => 10,
        25 => 25,
        50 => 50,
        100 => 100,
    ];

    /**
     * Should show actions in table row
     *
     * @var bool
     */
    protected $showActions = true;

    /**
     * Can user able to select multiple records
     *
     * @var bool
     */
    protected $isMultiSelectable = true;

    /**
     * Should use global Eden actions
     *
     * @var bool
     */
    protected $useGlobalActions = false;

    /**
     * Should use global Eden filters
     *
     * @var bool
     */
    protected $useGlobalFilters = false;

    /**
     * is Pooling enabled or not
     *
     * @var bool
     */
    public $pooling = false;

    /**
     * Pooling time in milliseconds - Default 5000 Milliseconds
     *
     * @var int
     */
    public $poolingInterval = 5000;

    /**
     * Table Header Style
     *
     * @var string
     */
    public $headerStyle = 'bg-white shadow-md rounded-t-md md:rounded-t-md py-5 px-5 dark:bg-slate-600';

    /**
     * Applied Filters Style
     *
     * @var string
     */
    public $appliedFilterStyle = 'py-3 px-5 bg-white shadow-md border-y border-slate-100 flex flex-wrap gap-3 items-center mt-3 md:mt-0 md:rounded-none border-t dark:bg-slate-600 dark:border-slate-500';

    /**
     * Table Body Style
     *
     * @var string
     */
    public $bodyStyle = '';

    /**
     * Additional HTML Attributes to be included in body wrapper
     *
     * @var string
     */
    public $bodyAttrs = [];

    /**
     * Pagination Area Style
     *
     * @var string
     */
    public $paginationStyle = 'flex flex-col md:flex-row justify-between items-center bg-white shadow-md rounded-md md:rounded-none md:rounded-b-md overflow-hidden dark:bg-slate-600 dark:text-slate-300';

    /**
     * Multiple Rows that is selected by User
     *
     * @var array
     */
    public $selectedRows = [];

    protected $allFilters = [];

    public $filters = [];

    protected $allFields = [];

    public $sorting = [];

    protected $appliedFilters = [];

    protected $actions = [];

    protected $paginationName = 'page';

    abstract protected function fields();

    abstract protected function filters();

    abstract protected function actions();

    protected function operations()
    {
        return [];
    }

    public function sortField($fieldKey)
    {
        $currentSort = $this->sorting[$fieldKey];

        if ($currentSort == '') {
            $this->sorting[$fieldKey] = 'asc';
        } elseif ($currentSort == 'asc') {
            $this->sorting[$fieldKey] = 'desc';
        } elseif ($currentSort == 'desc') {
            $this->sorting[$fieldKey] = '';
        }
    }

    public function removeFilter($keyToRemove, $default = '')
    {
        if (strtolower($keyToRemove) == 'search') {
            $this->searchQuery = '';
        } elseif (strtolower($keyToRemove) == 'page') {
            $this->setPage(1);
        } else {
            if (isset($this->filters[$keyToRemove])) {
                $this->filters[$keyToRemove] = $default;
            }
        }
        $this->processFilters();
    }

    protected function getAppliedFilters()
    {
        if (! empty($this->searchQuery)) {
            $this->appliedFilters[] = [
                'key' => 'search',
                'title' => 'Search',
                'value' => $this->searchQuery,
                'canRemove' => true,
                'initial' => '',
            ];
        }

        collect($this->allFilters)
            ->each(function ($filter) {
                if ($filter->isApplied) {
                    $this->appliedFilters[] = [
                        'key' => $filter->getKey(),
                        'title' => $filter->getTitle(),
                        'value' => $filter->getAppliedValue(),
                        'canRemove' => true,
                        'initial' => $filter->initialValue,
                    ];
                }
            });

        if ($this->page > 1) {
            $this->appliedFilters[] = [
                'key' => 'page',
                'title' => 'Page',
                'value' => $this->page,
                'canRemove' => true,
                'initial' => 1,
            ];
        }

        return $this->appliedFilters;
    }

    public function applyFilters()
    {
        $this->showFilters = false;
        $this->setPage(1);
    }

    public function mount()
    {
        $this->initialRowsPerPage = $this->rowsPerPage;
        $this->processActions();
        $this->processFilters();
    }

    public function hydrate()
    {
        $this->processActions();
        $this->processFilters();
    }

    public function updated()
    {
        $this->processFilters();
    }

    public function updatedSearchQuery()
    {
        $this->setPage(1);
    }

    protected function processFields()
    {
        $fields = $this->fields();
        if ($this->showActions) {
            $fields[] = ActionField::make('Actions')->withActions($this->actions);
        }
        if ($this->isMultiSelectable) {
            array_unshift($fields, SelectorField::make('Select'));
        }

        $this->allFields = collect($fields)->transform(function ($field) {
            $field = $this->processFieldOrdering($field);

            // is Searchable ?
            if ($field->isSearchable()) {
                $this->searchFields[] = $field->getKey();
            }

            return $field;
        })->all();
    }

    /**
     * Create or Assign Sorting Fields
     *
     * @param  Field  $field
     * @return Field
     */
    protected function processFieldOrdering(Field $field)
    {
        if (isset($this->sorting[$field->getKey()])) {
            $field->orderBy($this->sorting[$field->getKey()]);
        } else {
            $this->sorting[$field->getKey()] = $field->getOrderBy();
        }

        return $field;
    }

    protected function getBulkActions()
    {
        return collect($this->actions)->filter(function (Action $action) {
            return $action->allowBulk() && ! ($action instanceof StaticAction);
        })->all();
    }

    protected function processActions()
    {
        $globalActions = $this->useGlobalActions ? Eden::actions() : [];
        $this->actions = collect(array_merge($this->actions(), $globalActions))
            ->reject(function ($action) {
                return ! $action->visibilityOnIndex;
            })
            ->transform(function ($action) {
                return $action->setOwner($this)
                    ->setResource($this->resource);
            })
            ->all();
    }

    protected function processFilters()
    {
        $filters = array_merge($this->filters(), $this->useGlobalFilters ? Eden::filters() : []);
        $this->allFilters = collect($filters)->transform(function ($filter) {
            if (isset($this->filters[$filter->getKey()])) {
                $filter->value = $this->filters[$filter->getKey()];
            } else {
                $this->filters[$filter->getKey()] = $filter->value;
            }

            return $filter;
        })->all();
    }

    public function applyBulkAction($actionID)
    {
        $this->applyAction($actionID, $this->selectedRows, true);
        $this->selectedRows = [];
    }

    protected function prepareModelQuery()
    {
        if ($this->model() instanceof Relation) {
            return $this->model();
        }

        try {
            $query = app($this->model())->newQuery()->select('*');
        } catch (\Exception $exception) {
            $query = DB::table($this->model())->select('*');
        }

        return $query;
    }

    /**
     * Prepare query with search, sorting, filters
     *
     * @return mixed
     */
    protected function prepareData()
    {
        $this->selectedRows = [];
        // Process Column Fields for Proper Sorting
        $this->processFields();

        $query = $this->prepareModelQuery();

        // Apply Field Specific Query and Sorting
        collect($this->allFields)->each(function ($field) use ($query) {
            $query = $field->apply($query);

            if (in_array($field->getOrderBy(), ['asc', 'desc'])) { // Sorting
                $query = $query->orderBy($field->getKey(), $field->getOrderBy());
            }
        });

        // Apply Search
        if (! empty($this->searchQuery)) {
            $query->where(function ($q) {
                collect($this->searchFields)->each(function ($searchField) use ($q) {
                    $q->orWhere($searchField, 'LIKE', "%$this->searchQuery%");
                });
            });
        }

        // Apply Filters via Pipeline
        $query = app(Pipeline::class)
            ->send($query)
            ->through($this->allFilters)
            ->thenReturn();

        if ($this->latestFirst) {
            $query = $query->latest();
        }

        return $query;
    }

    /**
     * Work with query after processing the search, sorting, filters
     *
     * @param $query
     * @return mixed
     */
    protected function query($query)
    {
        return $query;
    }

    protected function paginatedData()
    {
        $queryToPaginate = $this->query($this->prepareData());

        if (! (
            $queryToPaginate instanceof \Illuminate\Database\Query\Builder ||
            $queryToPaginate instanceof \Illuminate\Database\Eloquent\Builder ||
            $queryToPaginate instanceof Relation)) {
            return $queryToPaginate;
        }

        if (strtolower($this->paginationType) == 'simple') {
            return $queryToPaginate->simplePaginate($this->rowsPerPage, ['*'], $this->paginationName);
        } elseif (strtolower($this->paginationType) == 'cursor') {
            return $queryToPaginate->cursorPaginate($this->rowsPerPage, ['*'], $this->paginationName);
        }

        return $queryToPaginate
            ->paginate($this->rowsPerPage, ['*'], $this->paginationName)
            ->withQueryString()
            ->onEachSide(1);
    }

    /**
     * Work with paginated data before displaying to view
     *
     * @param $records
     * @return mixed
     */
    protected function afterPaginated($records)
    {
        return $records;
    }

    /**
     * @param  string  $class
     * @param  array  $params
     * @return DataTableRenderer
     */
    protected static function renderer($class, $params)
    {
        return new DataTableRenderer($class, $params);
    }

    public function defaultViewParams()
    {
        $records = $this->afterPaginated($this->paginatedData());

        $shouldShowPagination = false;
        try {
            $records->links();
            $shouldShowPagination = true;
        } catch (\Exception $exception) {
        }

        return [
            'fields' => $this->allFields,
            'appliedFilters' => $this->getAppliedFilters(),
            'shouldShowPagination' => $shouldShowPagination,
            'allFilters' => $this->allFilters,
            'actions' => $this->getBulkActions(),
            'operations' => $this->operations(),
            'records' => $records,
        ];
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    protected function view()
    {
        return view('eden::components.datatable');
    }

    public function paginationView()
    {
        return 'eden::datatable.pagination';
    }

    public function paginationSimpleView()
    {
        return 'eden::datatable.pagination-simple';
    }

    public function emptyView()
    {
        return view('eden::datatable.empty')
            ->with('fields', $this->allFields);
    }

    public function rowView($record, $fields = [], $records = [])
    {
        return view('eden::datatable.row')
            ->with('record', $record)
            ->with('records', $records)
            ->with('fields', $fields);
    }

    public function headerView($fields = [], $records = [])
    {
        return view('eden::datatable.header')
            ->with(compact('fields', 'records'));
    }

    /**
     * Calling from front end to render row
     *
     * @param $record
     * @param $records
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|string
     *
     * @throws \Throwable
     */
    public function row($record, $records = [])
    {
        $fieldsToRender = collect($this->allFields)
            ->transform(function (Field $field) use ($record) {
                $value = is_array($record) ? $record[$field->getKey()] : (is_object($record) ? $record->{$field->getKey()} : '');
                $field->setValue($value);

                return $field;
            })->all();
        $view = $this->rowView($record, $fieldsToRender, $records);

        if ($view instanceof View) {
            return $view->render();
        }

        return is_null($view) ? '' : $view;
    }

    /**
     * Calling From Frontend to Render Table Header
     *
     * @param $record
     * @param $records
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|string
     *
     * @throws \Throwable
     */
    public function header($records = [])
    {
        $fieldsToRender = collect($this->allFields)->all();
        $view = $this->headerView($fieldsToRender, $records);

        if ($view instanceof View) {
            return $view->render();
        }

        return is_null($view) ? '' : $view;
    }
}
