<?php

namespace Dgharami\Eden\Components;


use Dgharami\Eden\Assembled\ResourceCreateForm;
use Dgharami\Eden\Assembled\ResourceDataTable;
use Dgharami\Eden\Assembled\ResourceEditForm;
use Dgharami\Eden\Assembled\ResourceRead;

/**
 * @inheritDoc
 */
abstract class EdenResource extends EdenPage
{
    /**
     * Datatable Model
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public static $model = null;

    /** DATA TABLE - Index **/
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
    protected $useGlobalActions = true;

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
    public $headerStyle = 'bg-white shadow-md rounded-t-md md:rounded-t-md py-5 px-5';

    /**
     * Applied Filters Style
     *
     * @var string
     */
    public $appliedFilterStyle = 'py-3 px-5 bg-white shadow-md border-y border-slate-100 flex flex-wrap gap-3 items-center mt-3 md:mt-0 md:rounded-none border-t';

    /** FORM - Create/Update **/
    /**
     * Is Updating Data or Creating
     *
     * @var bool
     */
    protected $isUpdate = false;

    /**
     * Container Style
     *
     * @var string
     */
    public $styleContainer = '';

    /** READ **/


    /**
     * @return array
     */
    public function cards()
    {
        return [];
    }

    /**
     * @return array
     */
    abstract protected function fields();

    abstract protected function filters();

    abstract protected function actions();

    abstract protected function operations();

    public function toDataTable()
    {
        return [
            'model' => self::$model,
            'title' => $this->labelPlural(),
            'isTableLayout' => $this->isTableLayout,
            'showHeader' => $this->showHeader,
            'showFilters' => $this->showFilters,
            'isSearchable' => $this->isSearchable,
            'searchFields' => $this->searchFields,
            'showRowsPerPageFilter' => $this->showRowsPerPageFilter,
            'paginationType' => $this->paginationType,
            'initialRowsPerPage' => $this->initialRowsPerPage,
            'rowsPerPageOptions' => $this->rowsPerPageOptions,
            'showActions' => $this->showActions,
            'isMultiSelectable' => $this->isMultiSelectable,
            'useGlobalActions' => $this->useGlobalActions,
            'useGlobalFilters' => $this->useGlobalFilters,
            'pooling' => $this->pooling,
            'poolingInterval' => $this->poolingInterval,
            'headerStyle' => $this->headerStyle,
            'appliedFilterStyle' => $this->appliedFilterStyle,
        ];
    }

    public function toForm()
    {
        return [
            'model' => self::$model,
            'title' => $this->labelSingular(),
            'isUpdate' => $this->isUpdate,
            'styleContainer' => $this->styleContainer,
        ];
    }

    public function toDetails()
    {
        return [
            'model' => self::$model,
            'title' => $this->labelSingular(),
            'useGlobalActions' => $this->useGlobalActions
        ];
    }

    public function getFields()
    {
        return $this->fields();
    }

    public function getFilters()
    {
        return $this->filters();
    }

    public function getActions()
    {
        return $this->actions();
    }

    public function getOperations()
    {
        return $this->operations();
    }

    /**
     * Index Screen
     *
     * @return mixed
     */
    final public function index($slug)
    {
        $viewParams = $this->viewParams(true);
        $viewParams['components'] = array_merge(
            $this->cards(),
            [ResourceDataTable::make(['edenResource' => get_called_class()])]
        );

        return view('eden::app')->with($viewParams);
    }

    /**
     * Create Screen
     *
     * @return mixed
     */
    public function create($slug)
    {
        $viewParams = $this->viewParams();
        $viewParams['components'] = array_merge(
            $this->cards(),
            [ResourceCreateForm::make(['edenResource' => get_called_class()])]
        );

        return view('eden::app')->with($viewParams);
    }

    /**
     * Edit Screen
     *
     * @return mixed
     */
    public function edit($slug, $id)
    {
        // Force Form that This is an Edit Form
        $this->isUpdate = true;

        $viewParams = $this->viewParams();
        $viewParams['components'] = array_merge(
            $this->cards(),
            [ResourceEditForm::make(['edenResource' => get_called_class()])]
        );
        return view('eden::app')->with($viewParams);
    }

    /**
     * Details Screen
     *
     * @return mixed
     */
    public function show($slug, $id)
    {
        $viewParams = $this->viewParams();
        $viewParams['components'] = array_merge(
            $this->cards(),
            [ResourceRead::make(['edenResource' => get_called_class()])]
        );

        return view('eden::app')->with($viewParams);
    }

    /**
     * Override view for Read page
     *
     * @return null
     */
    protected function viewForRead()
    {
        return null;
    }

    public function getViewForRead()
    {
        return $this->viewForRead();
    }
}
