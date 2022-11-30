<?php
namespace Dgharami\Eden\Components;

use Dgharami\Eden\Components\Fields\Field;
use Dgharami\Eden\Components\Fields\File;
use Dgharami\Eden\RenderProviders\FormRenderer;
use Dgharami\Eden\Traits\WithModel;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

abstract class Form extends EdenComponent
{
    use WithFileUploads;
    use WithModel;

    public $width = 'half';

    public $resource = '';

    protected $isUpdate = false;

    protected $primaryKey = 'id';

    public $styleContainer = '';

    // Dynamic Fields
    public $fields = [];

    // Dynamic File Fields
    public $files = [];

    // All dependent fields keys
    protected $dependentFields = [];

    // Laravel LiveWire Validation Rules
    protected $rules = [];

    // Laravel LiveWire Validation Attributes
    protected $validationAttributes = [];

    // All Mentioned Fields
    private $allFields = [];

    // Let User Decide the Fields
    abstract protected function fields();

    /**
     * Initial Component Mount - Only First Render
     *
     * @return void
     */
    public function mount()
    {
        $this->initForm('mount');
    }

    /**
     * While Field Value Changed - Dependent Field
     *
     * @return void
     */
    public function hydrate()
    {
        $this->initForm('hydrate');
    }

    /**
     * During form submit, any action
     *
     * @return void
     */
    public function updated()
    {
        $this->initForm('updated');
    }

    /**
     * Initialize Form and Process Internal Data
     *
     * @param $caller
     * @return void
     * @throws \Exception
     */
    private function initForm($caller = 'mount')
    {
        $this->resolveModel();
        $this->resolveRecord();
        $this->prepareFields();
        //$this->processExistingUploads();
        $this->finalizeFields();
    }

    /**
     * Is record need to be updated ?
     *
     * @return bool|mixed
     */
    public function isUpdate()
    {
        return $this->isUpdate;
    }

    /**
     * Is record need to be created ?
     *
     * @return bool
     */
    public function isCreate()
    {
        return !$this->isUpdate;
    }

    public function clearUploadedPhoto($key, $value = '')
    {
        $this->syncInput('fields.' . $key, $value);
        $this->syncInput('files.' . $key, $value);
        $this->processExistingUploads();
    }

    /**
     * Submit the form
     *
     * @return void
     */
    public function submit(){
        $invalidFields = collect($this->allFields)
            ->reject(function (Field $field) {
                return $field->isValid($this->isUpdate);
            });

        if ($invalidFields->count() <= 0) {
            $validatedFieldsValues = [];
            if (isset($this->fields)) {
                $validatedFieldsValues = array_merge($validatedFieldsValues, $this->fields);
            }
            if (isset($this->files)) {
                $validatedFieldsValues = array_merge($validatedFieldsValues, $this->files);
            }

            $validatedFields = $this->processValidatedFields($validatedFieldsValues);
            $allFields = array_merge($validatedFieldsValues, $validatedFields);

            $transformed = $this->transform($validatedFields, $allFields);
            $this->action($validatedFields, $allFields, $transformed);
        }
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

    /**
     * Process validated fields
     *
     * @param $validatedFields
     * @return array
     */
    private function processValidatedFields($validatedFields): array
    {
        return collect($validatedFields)->transform(function ($item, $key) {
            $field = $this->getField($key);

            if (!is_null($field)) {
                if (isset($this->files[$key])) {
                    $item = $this->getTemporaryUploadFile($item);
                }

                $item = $field->process();

                // Post Process
                $transform = $field->getTransformCallback();
                if (!is_null($transform)) {
                    $item  = appCall($transform, [
                        'value' => $item,
                        'field' => $field
                    ]);
                }
            }

            return $item;
        })->all();
    }

    /**
     * Get Field by key
     *
     * @param $key
     * @return \Closure|mixed|null
     */
    private function getField($key)
    {
        return collect($this->allFields)->first(function ($i) use ($key) {
            return $i->getKey() == $key;
        });
    }

    /**
     * LiveWire Method Override for Multiple Upload Fields
     *
     * @return void
     */
    public function finishUpload($name, $tmpPath, $isMultiple)
    {
        $sourceKey = str_ireplace('files.', '', $name);

        $this->cleanupOldUploads();
        $this->processExistingUploads();

        // Process New Upload
        if ($isMultiple) {
            $file = collect($tmpPath)->map(function ($i) {
                return $this->getTemporaryUploadFile($i);
            })->toArray();
            $this->emit('upload:finished', $name, collect($file)->map->getFilename()->toArray())->self();
            $this->fields[$sourceKey] = $tmpPath;
        } else {
            $file = $this->getTemporaryUploadFile($tmpPath[0]);
            $this->emit('upload:finished', $name, [$file->getFilename()])->self();
            $this->fields[$sourceKey] = head($tmpPath);

            // If the property is an array, but the upload ISNT set to "multiple"
            // then APPEND the upload to the array, rather than replacing it.
            if (is_array($value = $this->getPropertyValue($name))) {
                $file = array_merge($value, [$file]);
            }
        }

        $this->syncInput($name, $file, false);
    }

    /**
     * Process Already LiveWire Uploaded File
     *
     * @return void
     */
    protected function processExistingUploads()
    {
        collect($this->fields)->each(function ($itemValue, $itemKey) {
            if (isset($this->files[$itemKey]) && !empty($itemValue)) {
                $this->files[$itemKey] = $this->getTemporaryUploadFile($itemValue);
            }
        });
    }

    protected function getTemporaryUploadFile($path)
    {
        if (!is_null($path)) {
            if (is_array($path)) {
                return collect($path)->map(function ($i) {
                    return TemporaryUploadedFile::createFromLivewire($i);
                })->toArray();
            }
            return TemporaryUploadedFile::createFromLivewire($path);
        }
        return null;
    }

    protected function action($validated = [], $all = [], $transformed = [])
    {
        try {
            if ($this->isUpdate) {
                $actionData = $this->updateRecord($validated, $all, $transformed);
            } else {
                $actionData = $this->createRecord($validated, $all, $transformed);
            }

            $this->onActionCompleted($actionData);
        } catch (\Exception $exception) {
            $this->onActionException($exception);
        }
    }

    protected function createRecord($validated, $all, $transformed)
    {
        try {
            $model = app($this->model());
            $record = $model->forceFill($transformed)->save();
            if (method_exists($this, 'afterRecordCreated')) {
                $this->afterRecordCreated($record);
            }
            return $record;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    protected function updateRecord($validated, $all, $transformed)
    {
        try {
            $model = app($this->record());
            $record = $model->forceFill($transformed)->save();
            if (method_exists($this, 'afterRecordUpdated')) {
                $this->afterRecordUpdated($record);
            }
            return $record;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    protected function onActionCompleted($data)
    {
        $this->toastSuccess(sprintf('Record %s', ($this->isUpdate ? 'updated' : 'created')));
    }

    protected function onActionException(\Exception $exception)
    {
        $this->toastError(json_encode($exception->getMessage()));
    }

    /**
     * Process All Fields and Collect Values
     *
     * @return void
     */
    private function prepareFields()
    {
        $this->allFields = collect($this->fields())
            ->each(function (Field $field) {
                $field->prepare($this);
                $this->syncField($field);
                $this->syncFieldRule($field);
                $this->collectDependentFields($field);
                $this->syncDependentFields($field);
            })
            ->transform(function (Field $field) {
                return $field->isDependent($this->dependentFields);
            })
            ->all();
    }

    /**
     * Fetch and Save Dependent targets
     *
     * @param Field $field
     * @return void
     */
    protected function collectDependentFields(Field $field)
    {
        $this->dependentFields = collect( array_merge($this->dependentFields, $field->getDependentTargets()) )
            ->flatten()
            ->unique()
            ->all();
    }

    protected function syncDependentFields(Field $field)
    {

    }

    // TODO : Need file values
    /**
     * Sync file value with form fields values to enable user interaction
     *
     * @param Field $field
     * @return void
     */
    private function syncField(Field $field)
    {
        $key = $field->getKey();

        // Create Record and Sync in Files Array
        if (is_subclass_of($field, File::class)) {
            //$this->files[$key] = $this->fields[$key];
        } else {
            $this->fields[$key] = $this->getFormFieldValue($field);
        }
    }

    /**
     * Get form Field value or fill that via record
     *
     * @param Field $field
     * @return array|\ArrayAccess|mixed|null
     */
    private function getFormFieldValue(Field $field)
    {
        $key = $field->getKey();
        $value = $field->exportToForm();

        if (isset($this->fields[$key])) { // Assign filled form value if exists
            $value = $this->fields[$key];

        } else { // Fill from record
            $value = $this->getRecordValue($key, $value);
        }

        return $value;
    }

    // TODO : Need file rules
    /**
     * Sync field rules with the form validation rules
     *
     * @param Field $field
     * @return void
     */
    private function syncFieldRule(Field $field)
    {
        $key = $field->getKey();
        $fieldRules = $field->getRules($this->isUpdate);

        if ((is_string($fieldRules) && !empty($fieldRules)) || (is_array($fieldRules) && count($fieldRules) > 0)) {
            if (is_subclass_of($field, File::class)) {
//                $multipleKey = $field->isMultiple() ? '.*' : '';
//
//                $this->rules['files.' . $key . $multipleKey] = $fieldRules;
//                $this->validationAttributes['files.' . $key . $multipleKey] = htmlentities(strip_tags($field->title));
            } else {
                $this->rules['fields.' . $key] = $fieldRules;
            }
        }
        $this->validationAttributes['fields.' . $key] = htmlentities(strip_tags($field->title));
    }

    /**
     * Map field with form fields
     *
     * @return array
     */
    private function getMappedFields()
    {
        return collect($this->allFields)->mapWithKeys(function (Field $field) {
            return [$field->getKey() => $field];
        })->all();
    }

    // TODO : Need file rules
    /**
     * Finalize Fields with values and dependencies
     *
     * @return void
     */
    private function finalizeFields()
    {
        $this->allFields = collect($this->allFields)
            ->transform(function (Field $field) {
                if (isset($this->fields[$field->getKey()])) {
                    $field->importFromFrom($this->fields[$field->getKey()], $this->getMappedFields());
                }
                return $field;
            })
            ->transform(function (Field $field) {
                return $field->resolveUsing($field->exportToForm(), $this->getMappedFields(), $this);
            })
            ->each(function (Field $field) {
                if (is_subclass_of($field, File::class)) {
                    //$this->files[$key] = $this->fields[$key];
                } else {
                    $this->fields[$field->getKey()] = $field->exportToForm();
                }
            })
            ->all();
    }

    /**
     * View for the form
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function view()
    {
        return view('eden::components.form')
            ->with('rules', $this->rules)
            ->with('formFields', $this->allFields)
            ->with('depends', $this->dependentFields);
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

}
