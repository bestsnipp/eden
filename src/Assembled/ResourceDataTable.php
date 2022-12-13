<?php

namespace BestSnipp\Eden\Assembled;

use App\Models\User;
use BestSnipp\Eden\Components\DataTable;
use BestSnipp\Eden\Components\EdenButton;
use BestSnipp\Eden\Traits\HasEdenResource;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

final class ResourceDataTable extends DataTable
{
    use HasEdenResource;


    protected function init()
    {
        $this->getResourceData(function ($edenResource) {
            $this->edenResourceObject = $edenResource;
            $this->mapResourceProperties($edenResource, $edenResource->toDataTable());
        });
    }

    public function mount()
    {
        $this->init();

        // RowsPerPage
        $this->getResourceData(function ($edenResource) {
            $this->rowsPerPage = $edenResource->rowsPerPage;
        });
        parent::mount();
    }

    public function hydrate()
    {
        $this->init();
        parent::hydrate();
    }

    public function updated()
    {
        $this->init();
        parent::hydrate();
    }

    protected function mapResourceProperties($edenResource, $params = [])
    {
        self::$model = $edenResource::$model;
        $this->title = $params['title'];
        $this->isTableLayout = $params['isTableLayout'];
        $this->showHeader = $params['showHeader'];
        $this->showFilters = $params['showFilters'];
        $this->isSearchable = $params['isSearchable'];
        $this->searchFields = $params['searchFields'];
        $this->showRowsPerPageFilter = $params['showRowsPerPageFilter'];
        $this->paginationType = $params['paginationType'];
        $this->initialRowsPerPage = $params['initialRowsPerPage'];
        $this->rowsPerPageOptions = $params['rowsPerPageOptions'];
        $this->showActions = $params['showActions'];
        $this->isMultiSelectable = $params['isMultiSelectable'];
        $this->useGlobalActions = $params['useGlobalActions'];
        $this->useGlobalFilters = $params['useGlobalFilters'];
        $this->pooling = $params['pooling'];
        $this->poolingInterval = $params['poolingInterval'];
        $this->headerStyle = $params['headerStyle'];
        $this->appliedFilterStyle = $params['appliedFilterStyle'];
    }

    protected function operations()
    {
        $operations = $this->getResourceData(function ($edenResource) {
            return $edenResource->getOperations();
        }, []);

        return collect(array_merge($operations, [
            EdenButton::make('Create New')->route('eden.create', [
                'resource' => $this->resource
            ])->noIcon()
        ]))
        ->reject(function ($field) {
            return !$field->visibilityOnIndex;
        })
        ->all();
    }

    protected function fields()
    {
        return collect($this->getResourceData(function ($edenResource) {
            return $edenResource->getFields();
        }, []))
        ->reject(function ($field) {
           return !$field->visibilityOnIndex;
        })
        ->all();
    }

    protected function filters()
    {
        return $this->getResourceData(function ($edenResource) {
            return $edenResource->getFilters();
        }, []);
    }

    protected function actions()
    {
        return collect($this->getResourceData(function ($edenResource) {
            return $edenResource->getActions();
        }, []))
            ->reject(function ($action) {
                return !$action->visibilityOnIndex;
            })
            ->all();
    }

    protected function indexQuery($query)
    {
        if ($this->edenResourceObject->hasMethod('query') ?? false) {
            return $this->edenResourceObject->callMethod('query', $query);
        }

        return parent::query($query);
    }

    protected function view()
    {
        return $this->edenResourceObject->getViewForIndex() ?? parent::view();
    }

    public function emptyView()
    {
        return $this->edenResourceObject->getEmptyViewForIndex() ?? parent::emptyView();
    }

    public function rowView($record, $fields = [], $records = [])
    {
        return $this->edenResourceObject->getRowViewForIndex($record, $fields, $records) ?? parent::rowView($record, $fields, $records);
    }

    public function headerView($fields = [], $records = [])
    {
        return $this->edenResourceObject->getHeaderViewForIndex($fields, $records) ?? parent::headerView($fields, $records);
    }
}
