<?php
namespace Dgharami\Eden\Components;

use Dgharami\Eden\Components\DataTable\Actions\Action;
use Dgharami\Eden\Components\DataTable\Column\ActionField;
use Dgharami\Eden\Components\DataTable\Column\SelectorField;
use Dgharami\Eden\Components\Fields\Field;
use Dgharami\Eden\Facades\Eden;
use Dgharami\Eden\RenderProviders\DataTableRenderer;
use Dgharami\Eden\Traits\CanRefresh;
use Dgharami\Eden\Traits\HasActions;
use Dgharami\Eden\Traits\HasModel;
use Dgharami\Eden\Traits\HasOwner;
use Dgharami\Eden\Traits\HasToast;
use Dgharami\Eden\Traits\InteractsWithModal;
use Dgharami\Eden\Traits\MakeableComponent;
use Dgharami\Eden\Traits\RouteAware;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

abstract class DataTable extends EdenComponent
{
    use WithPagination;

    /**
     * Title to show in front end
     *
     * @var string
     */
    public $title = '';

    /**
     * @var string|array|Model
     */
    public static $model = null;

    protected $actions = [];

    public $width = 'half';

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

    public $paginationType = 'paginate'; // simple, cursor, paginate

    /**
     * Rows per page
     *
     * @var int
     */
    public $rowsPerPage = 5;

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
        100 => 100
    ];

    private $allFilters = [];
    public $filters = [];

    private $allFields = [];
    public $sorting = [];

    private $appliedFilters = [];

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
     * Multiple Rows that is selected by User
     *
     * @var array
     */
    public $selectedRows = [];

    /**
     * Should use global Eden filters
     *
     * @var bool
     */
    protected $useGlobalFilters = true;

    /**
     * is Pooling enabled or not
     *
     * @var bool
     */
    public $pooling = false;

    /**
     * Pooling time in milliseconds - Default 5 Seconds
     *
     * @var int
     */
    public $poolingInterval = 5000;

    public $headerStyle = 'bg-white shadow-md rounded-t-md md:rounded-t-md py-5 px-5';

    public $appliedFilterStyle = 'py-3 px-5 bg-white shadow-md border-y border-slate-100 flex flex-wrap gap-3 items-center mt-3 md:mt-0 md:rounded-none border-t';

    public $paginationStyle = 'flex flex-col md:flex-row justify-between items-center bg-white shadow-md rounded-md md:rounded-none md:rounded-b-md overflow-hidden';

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
        } else if ($currentSort == 'asc') {
            $this->sorting[$fieldKey] = 'desc';
        } else if ($currentSort == 'desc') {
            $this->sorting[$fieldKey] = '';
        }
    }

    public function removeFilter($keyToRemove, $default = '')
    {
        if (strtolower($keyToRemove) == 'search') {
            $this->searchQuery = '';
        }else if (strtolower($keyToRemove) == 'page') {
            $this->setPage(1);
        } else {
            if (isset($this->filters[$keyToRemove])) {
                $this->filters[$keyToRemove] = $default;
            }
        }
        $this->processFilters();
    }

    private function getAppliedFilters()
    {
        if (!empty($this->searchQuery)) {
            $this->appliedFilters[] = [
                'key' => 'search',
                'title' => 'Search',
                'value' => $this->searchQuery,
                'canRemove' => true,
                'initial' => ''
            ];
        }

        collect($this->allFilters)->each(function ($filter) {
            if ($filter->isApplied) {
                $this->appliedFilters[] = [
                    'key' => $filter->getKey(),
                    'title' => $filter->getTitle(),
                    'value' => $filter->value,
                    'canRemove' => true,
                    'initial' => $filter->initialValue
                ];
            }
        });

        if ($this->page > 1) {
            $this->appliedFilters[] = [
                'key' => 'page',
                'title' => 'Page',
                'value' => $this->page,
                'canRemove' => true,
                'initial' => 1
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
        //$this->resolveModel();
        //$this->prepareActions();
        //$this->processFields(); // -> Will Handle This During Data Preparation
        $this->processFilters();
    }

    public function hydrate()
    {
        //$this->resolveModel();
        //$this->prepareActions();
        //$this->processFields(); // -> Will Handle This During Data Preparation
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

    private function processFields()
    {
        $fields = $this->fields();
        if ($this->showActions) {
            $fields[] = ActionField::make("Actions")->withActions($this->actions);
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
     * @param Field $field
     * @return Field
     */
    private function processFieldOrdering(Field $field)
    {
        Log::debug($field->getKey() . ' = ' . json_encode(isset($this->sorting[ $field->getKey() ])) );
        if ( isset($this->sorting[ $field->getKey() ]) ) {
            $field->orderBy( $this->sorting[ $field->getKey() ] );
        } else {
            $this->sorting[$field->getKey()] = $field->getOrderBy();
        }

        return $field;
    }

    private function getBulkActions()
    {
        return collect($this->actions)->filter(function (Action $action) {
            return $action->allowBulk();
        })->all();
    }

    private function processFilters()
    {
        $filters = array_merge($this->filters(), $this->useGlobalFilters ? Eden::filters() : []);
        $this->allFilters = collect($filters)->transform(function ($filter) {
            if (isset($this->filters[ $filter->getKey() ])) {
                $filter->value = $this->filters[$filter->getKey()];
            } else {
                $this->filters[$filter->getKey()] = $filter->value;
            }

            return $filter;
        })->all();
    }

    protected function model()
    {
        return get_class($this)::$model;
    }

    protected function prepareData()
    {
        $this->selectedRows = [];
        // Process Column Fields for Proper Sorting
        $this->processFields();

        // Create a Blank Query
        try {
            $query = app($this->model())->newQuery()->select('*');
        } catch (\Exception $exception) {
            $query = DB::table($this->model())->select('*');
        }

        // Apply Field Specific Query and Sorting
        collect($this->allFields)->each(function ($field) use ($query) {
            $query = $field->apply($query);

            if (in_array($field->getOrderBy(), ['asc', 'desc'])) { // Sorting
                $query = $query->orderBy($field->getKey(), $field->getOrderBy());
            }
        });

        // Apply Search
        if (!empty($this->searchQuery)) {
            collect($this->searchFields)->each(function ($searchField) use ($query) {
                $query = $query->orWhere($searchField, 'LIKE', "%$this->searchQuery%");
            });
        }

        // Apply Filters via Pipeline
        $query = app(Pipeline::class)
            ->send($query)
            ->through($this->allFilters)
            ->thenReturn();

        $query->latest();

        return $query;
    }

    protected function paginatedData()
    {
        if (strtolower($this->paginationType) == 'simple') {
            return $this->prepareData()->simplePaginate($this->rowsPerPage);

        } else if (strtolower($this->paginationType) == 'cursor') {
            return $this->prepareData()->cursorPaginate($this->rowsPerPage);

        }

        return $this->prepareData()
            ->paginate($this->rowsPerPage)
            ->onEachSide(1);
    }

    /**
     * @param string $class
     * @param array $params
     * @return DataTableRenderer
     */
    protected static function renderer($class, $params)
    {
        return new DataTableRenderer($class, $params);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        $records = $this->paginatedData();

        return view('eden::components.datatable')
            ->with('fields', $this->allFields)
            ->with('appliedFilters', $this->getAppliedFilters())
            ->with('allFilters', $this->allFilters)
            ->with('actions', $this->getBulkActions())
            ->with('operations', $this->operations())
            ->with('records', $records);
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

    public function showActions($row)
    {
        return view('eden::datatable.actions')
            ->with('actions', $this->actions)
            ->with('row', $row)
            ->with(['buttonStyle' => 'bg-white hover:bg-slate-10 transition w-auto border border-slate-200 text-slate-500 rounded-md py-2 px-3 text-sm inline-block']);
    }

    public function rowView($record, $fields = [], $records = [])
    {
        return view('eden::datatable.row')
            ->with('record', $record)
            ->with('records', $records)
            ->with('fields', $fields);
    }

    /**
     * Calling from front end to render row
     *
     * @param $record
     * @param $records
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|string
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

    public function headerView($fields = [], $records = [])
    {
        return view('eden::datatable.header')
            ->with(compact('fields', 'records'));
    }

    /**
     * Calling From Frontend to Render Table Header
     * @param $record
     * @param $records
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|string
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
