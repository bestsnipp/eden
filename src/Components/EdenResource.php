<?php

namespace BestSnipp\Eden\Components;

use BestSnipp\Eden\Assembled\ResourceCreateForm;
use BestSnipp\Eden\Assembled\ResourceDataTable;
use BestSnipp\Eden\Assembled\ResourceEditForm;
use BestSnipp\Eden\Assembled\ResourceRead;
use BestSnipp\Eden\Components\Fields\BelongsToMany;
use BestSnipp\Eden\Components\Fields\Field;
use BestSnipp\Eden\Components\Fields\HasOne;
use BestSnipp\Eden\Facades\Eden;
use Illuminate\Database\Eloquent\Model;

/**
 * {@inheritDoc}
 */
abstract class EdenResource extends EdenPage
{
    /**
     * Datatable Model
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public static $model = null;

    /**
     * Label to show
     *
     * @var string
     */
    public static $label = 'id';

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
    public $headerStyle = 'bg-white shadow-md rounded-t-md md:rounded-t-md py-5 px-5 dark:bg-slate-600';

    /**
     * Applied Filters Style
     *
     * @var string
     */
    public $appliedFilterStyle = 'py-3 px-5 bg-white shadow-md border-y border-slate-100 flex flex-wrap gap-3 items-center mt-3 md:mt-0 md:rounded-none border-t dark:bg-slate-600 dark:border-slate-500';

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
     * Get label for current model
     *
     * @param Model $model
     * @return mixed
     */
    public static function label(Model $model)
    {
        return $model->getAttribute(get_called_class()::$label);
    }

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
            'useGlobalActions' => $this->useGlobalActions,
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
    public function index($slug)
    {
        abort_if(! Eden::isActionAuthorized('viewAny', $this->model()), 403);

        $viewParams = $this->viewParams(true);
        $viewParams['components'] = array_merge(
            $this->cards(),
            [ResourceDataTable::make(['edenResource' => get_called_class()])],
            $this->components()
        );

        return view('eden::eden')->with($viewParams);
    }

    /**
     * Create Screen
     *
     * @return mixed
     */
    public function create($slug)
    {
        abort_if(! Eden::isActionAuthorized('create', $this->model()), 403);

        $viewParams = $this->viewParams();
        $viewParams['components'] = array_merge(
            $this->cards(),
            [ResourceCreateForm::make(['edenResource' => get_called_class()])],
            $this->components()
        );

        return view('eden::eden')->with($viewParams);
    }

    /**
     * Edit Screen
     *
     * @return mixed
     */
    public function edit($slug, $id)
    {
        $this->resource = $slug;
        $this->resourceId = $id;
        $this->resolveRecord();

        abort_if(! Eden::isActionAuthorized('update', $this->record()), 403);

        // Force Form that This is an Edit Form
        $this->isUpdate = true;

        $viewParams = $this->viewParams();
        $viewParams['components'] = array_merge(
            $this->cards(),
            [ResourceEditForm::make(['edenResource' => get_called_class()])],
            $this->components()
        );

        return view('eden::eden')->with($viewParams);
    }

    /**
     * Details Screen
     *
     * @return mixed
     */
    public function show($slug, $id)
    {
        $this->resource = $slug;
        $this->resourceId = $id;
        $this->resolveRecord();

        abort_if(! Eden::isActionAuthorized('view', $this->record()), 403);

        $viewParams = $this->viewParams();
        $viewParams['components'] = array_merge(
            $this->cards(),
            [ResourceRead::make(['edenResource' => get_called_class()])],
            $this->prepareRelationFields(),
            $this->components()
        );

        return view('eden::eden')->with($viewParams);
    }

    protected function prepareRelationFields()
    {
        $components = collect([]);

        collect($this->fields())
            ->filter(function ($item) {
                return $item instanceof BelongsToMany;
            })
            ->each(function ($item) use (&$components) {
                $edenResourceClass = $item->getResource();
                $edenResource = app($edenResourceClass);

                $components->add($this->getRelatedComponent($item, $edenResourceClass, $edenResource));
            });

        return $components->filter()->all();
    }

    protected function getRelatedComponent(Field $item, string $edenResourceClass, EdenResource $edenResource)
    {
        if ($item instanceof HasOne) {
            return ResourceRead::make([
                'title' => $item->title,
                'viaRelation' => true,
                'relation' => $item->getRelation(),
                'relationModel' => $this->record(),
                'resource' => $edenResource->getSlug(),
                'edenResource' => $edenResourceClass
            ]);
        }

        if ($item instanceof BelongsToMany) {
            if (Eden::isActionAuthorized('viewAny', $edenResourceClass::$model)) {
                return ResourceDataTable::make([
                    'title' => $item->title,
                    'viaRelation' => true,
                    'relation' => $item->getRelation(),
                    'relationModel' => $this->record(),
                    'resource' => $edenResource->getSlug(),
                    'edenResource' => $edenResourceClass
                ]);
            }
        }

        return null;
    }

    protected function isAuthorizedToSee($ability, $modelOrClass)
    {
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

    /**
     * Override view for Edit page
     *
     * @return null
     */
    protected function viewForEdit()
    {
        return null;
    }

    public function getViewForEdit()
    {
        return $this->viewForEdit();
    }

    /**
     * Override view for Create page
     *
     * @return null
     */
    protected function viewForCreate()
    {
        return null;
    }

    public function getViewForCreate()
    {
        return $this->viewForCreate();
    }

    /**
     * Override view for Index
     *
     * @return null
     */
    protected function viewForIndex()
    {
        return null;
    }

    public function getViewForIndex()
    {
        return $this->viewForIndex();
    }

    /**
     * Override empty view for Index
     *
     * @return null
     */
    protected function emptyViewForIndex()
    {
        return null;
    }

    public function getEmptyViewForIndex()
    {
        return $this->emptyViewForIndex();
    }

    /**
     * Override row view for Index
     *
     * @return null
     */
    protected function rowViewForIndex($record, $fields = [], $records = [])
    {
        return null;
    }

    public function getRowViewForIndex($record, $fields = [], $records = [])
    {
        return $this->rowViewForIndex($record, $fields, $records);
    }

    /**
     * Override table header view for Index
     *
     * @return null
     */
    protected function headerViewForIndex($fields = [], $records = [])
    {
        return null;
    }

    public function getHeaderViewForIndex($fields = [], $records = [])
    {
        return $this->headerViewForIndex($fields, $records);
    }

    /**
     * Transform $validated and $all fields to new data
     *
     * @param $validated
     * @param $all
     * @return mixed
     */
    protected function transform($validated, $all)
    {
        return $all;
    }

    public function getTransformMethod($validated, $all)
    {
        return $this->transform($validated, $all);
    }

    /**
     * Model properties needed to be removed before save/update
     *
     * @return array
     */
    protected function propertiesToRemove($isUpdate = false)
    {
        return [
            'id', 'created_at', 'updated_at',
        ];
    }

    public function getPropertiesToRemoveMethod($isUpdate = false)
    {
        return $this->propertiesToRemove($isUpdate);
    }

    public function hasMethod($method)
    {
        return method_exists($this, $method);
    }

    public function callMethod(string $method, ...$arguments)
    {
        return call_user_func_array([$this, $method], $arguments);
    }
}
