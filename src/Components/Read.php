<?php
namespace Dgharami\Eden\Components;

use App\Models\User;
use Dgharami\Eden\Components\Fields\Field;
use Dgharami\Eden\Components\Fields\File;
use Dgharami\Eden\Eden;
use Dgharami\Eden\RenderProviders\DataTableRenderer;
use Dgharami\Eden\RenderProviders\FormRenderer;
use Dgharami\Eden\Traits\HasActions;
use Dgharami\Eden\Traits\HasModel;
use Dgharami\Eden\Traits\HasOwner;
use Dgharami\Eden\Traits\HasToast;
use Dgharami\Eden\Traits\InteractsWithModal;
use Dgharami\Eden\Traits\MakeableComponent;
use Dgharami\Eden\Traits\RouteAware;
use Dgharami\Eden\Traits\UseCallable;
use Dgharami\Eden\Traits\WithModel;
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

    /**
     * Title to show in front end
     *
     * @var string
     */
    public $title = '';

    public $width = 'half';

    // Dynamic Fields
    public $allFields = [];

    // Let User Decide the Fields
    abstract protected function fields();

    protected function actions() {
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

    private function getFieldByKey($key)
    {
        return collect($this->allFields)->first(function ($i) use ($key) {
            return $i->getKey() == $key;
        });
    }

    private function prepareFields()
    {
        $this->allFields = collect($this->fields())->transform(function ($field) {
            $field->default($this->syncFieldValue($field));
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

    private function syncFieldValue(Field $field)
    {
        $key = $field->getKey();
        $value = $field->getValue();

        if (!is_null($this->record)) {

            if (is_subclass_of($this->record, Model::class) && Arr::exists($this->record, $key)) {
                $value = Arr::get($this->record, $key) ?? $value;

            } else if (in_array($this->record, ['array']) && Arr::exists($this->record, $key)) {
                $value = Arr::get($this->record, $key) ?? $value;

            } else if(in_array($this->record, [get_class($this->record)]) && Arr::exists($this->record, $key)){
                $value = $this->record->$key ?? $value;

            } else if (in_array($this->record, ['object']) && property_exists($this->record, $key)) {
                $value = $this->record->$key ?? $value;
            }
        }
        return $value;
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
        return [
            'fields' => $this->allFields,
            'actions' => $this->actions,
            'record' => $this->record,
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
