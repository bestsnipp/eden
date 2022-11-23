<?php

namespace Dgharami\Eden\Components;


/**
 * @inheritDoc
 */
abstract class Resource extends EdenPage
{
//    /**
//     * Datatable Model
//     *
//     * @var \Illuminate\Database\Eloquent\Model
//     */
//    public $model = '';
//
//    public $title = '';
//
//    public $paginationType = 'paginate';
//
//    public $showActions = true;
//
//    public $useGlobalActions = true;
//
//    /**
//     * @return array
//     */
//    public function cards()
//    {
//        return [];
//    }
//
//    /**
//     * @return array
//     */
//    abstract public function fields();
//
//    abstract protected function filters();
//
//    abstract protected function actions();
//
//    /**
//     * Index View -> Cards, Datatables
//     *
//     * @return array
//     */
//    public function index($slug)
//    {
//        $resourceTable = ResourceDataTable::make(['resource' => get_called_class()]);
//
//        return [
//            'title' => $this->label(false),
//            'slug' => $this->slug,
//            'cards' => collect($this->cards())->all(),
//            'tables' => collect([$resourceTable])->all(),
//            'tabs' => [],
//            'forms' => [],
//            'read' => []
//        ];
//    }
//
//    /**
//     * Create View -> Tabs, Cards, Forms
//     *
//     * @return array
//     */
//    public function create($slug)
//    {
//        $resourceForm = ResourceCreateForm::make(['resource' => get_called_class()]);
//
//        return [
//            'title' => $this->label(),
//            'slug' => $this->slug,
//            'tabs' => collect($this->tabs())->all(),
//            'cards' => collect($this->cards())->all(),
//            'tables' => [],
//            'forms' => collect([$resourceForm])->all(),
//            'read' => []
//        ];
//    }
//
//    /**
//     * Index View -> Tabs, Cards, Forms and Tables
//     *
//     * @return array
//     */
//    public function edit($slug, $id)
//    {
//        $resourceForm = ResourceEditForm::make([
//            'resource' => get_called_class(),
//            'modelID' => $id
//        ]);
//
//        return [
//            'title' => $this->label(),
//            'slug' => $this->slug,
//            'tabs' => collect($this->tabs())->all(),
//            'cards' => collect($this->cards())->all(),
//            'tables' => collect([])->all(),
//            'forms' => collect([$resourceForm])->all(),
//            'read' => []
//        ];
//    }
//
//    /**
//     * Index View -> Tabs, Cards, Forms and Tables
//     *
//     * @return array
//     */
//    public function show($slug, $id)
//    {
//        $resourceRead = ResourceRead::make([
//            'resource' => get_called_class(),
//            'modelID' => $id
//        ]);
//
//        return [
//            'title' => $this->label(),
//            'slug' => $this->slug,
//            'tabs' => collect($this->tabs())->all(),
//            'cards' => collect($this->cards())->all(),
//            'tables' => collect([])->all(),
//            'forms' => [],
//            'read' => collect([$resourceRead])->all()
//        ];
//    }

}
