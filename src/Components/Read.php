<?php
namespace BestSnipp\Eden\Components;

use App\Models\User;
use BestSnipp\Eden\Components\DataTable\Column\ActionField;
use BestSnipp\Eden\Components\Fields\Field;
use BestSnipp\Eden\Components\Fields\File;
use BestSnipp\Eden\Eden;
use BestSnipp\Eden\RenderProviders\DataTableRenderer;
use BestSnipp\Eden\RenderProviders\FormRenderer;
use BestSnipp\Eden\Traits\HasActions;
use BestSnipp\Eden\Traits\HasModel;
use BestSnipp\Eden\Traits\HasOwner;
use BestSnipp\Eden\Traits\HasToast;
use BestSnipp\Eden\Traits\InteractsWithAction;
use BestSnipp\Eden\Traits\InteractsWithModal;
use BestSnipp\Eden\Traits\MakeableComponent;
use BestSnipp\Eden\Traits\RouteAware;
use BestSnipp\Eden\Traits\UseCallable;
use BestSnipp\Eden\Traits\WithModel;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\RequiredIf;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

abstract class Read extends EdenComponent
{
    use WithModel;
    use InteractsWithAction;

    /**
     * Title to show in front end
     *
     * @var string
     */
    public $title = '';

    public $width = 'full';

    /**
     * Dynamic Fields
     *
     * @var array
     */
    private $allFields = [];

    /**
     * Let User Decide the Fields
     *
     * @return mixed
     */
    abstract protected function fields();

    protected function actions() {
        return [];
    }

    protected function operations()
    {
        return [];
    }

    /**
     * Initial Component Mount - Only First Time
     *
     * @throws \Exception
     */
    public function mount()
    {
        $this->resolveModel();
        $this->resolveRecord();
        $this->prepareFields();
        $this->prepareActions();
    }

    public function hydrate()
    {
        $this->resolveModel();
        $this->resolveRecord();
        $this->prepareFields();
        $this->prepareActions();
    }

    public function updated()
    {
        $this->resolveModel();
        $this->resolveRecord();
        $this->prepareFields();
        $this->prepareActions();
    }

    private function getFieldByKey($key)
    {
        return collect($this->allFields)->first(function ($i) use ($key) {
            return $i->getKey() == $key;
        });
    }

    private function prepareFields()
    {
        $this->allFields = collect($this->fields())->transform(function (Field $field) {
            $field->default($this->getRecordValue($field->getKey(), $field->getValue()));
            return $field;
        })->all();
    }

    private function prepareActions()
    {
        $this->actions = collect($this->actions())
            ->reject(function ($action) {
                return !$action->visibilityOnDetails;
            })
            ->all();
    }

    /**
     * @param string $class
     * @param array $params
     * @return FormRenderer
     */
    protected static function renderer($class, $params)
    {
        return new FormRenderer($class, $params);
    }

    public function defaultViewParams()
    {
        $actionColumn = ActionField::make("Actions")
            ->withActions($this->actions)
            ->withRecord($this->record);
        return [
            'fields' => $this->allFields,
            'actions' => $this->actions,
            'record' => $this->record,
            'operations' => $this->operations(),
            'actionButtons' => $actionColumn
        ];
    }

    protected function view()
    {
        return view('eden::components.read');
    }

    public function showActions()
    {
        return view('eden::datatable.actions')
            ->with('actions', $this->actions)
            ->with('row', $this->data)
            ->with(['buttonStyle' => 'bg-white hover:bg-slate-10 transition w-auto border border-slate-200 text-slate-500 rounded-md py-2 px-3 text-sm inline-block']);
    }

}
