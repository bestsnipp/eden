<?php

namespace BestSnipp\Eden\Components;

use BestSnipp\Eden\Components\Fields\Field;
use BestSnipp\Eden\Components\Fields\File;
use BestSnipp\Eden\Facades\Eden;
use BestSnipp\Eden\RenderProviders\FormRenderer;
use BestSnipp\Eden\Traits\WithModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

abstract class Form extends EdenComponent
{
    use WithFileUploads;
    use WithModel;

    /**
     * Component Width
     *
     * @var string
     */
    public $width = 'half';

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

    /**
     * Fields that will be bind with form in FrontEnd
     *
     * @var array
     */
    public $fields = [];

    /**
     * Fields that will be bind with form in FrontEnd for Files
     *
     * @var array
     */
    public $files = [];

    /**
     * Keys that has some dependent
     *
     * @var array
     */
    protected $dependentFields = [];

    /**
     * Laravel LiveWire Validation Rules
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Laravel LiveWire Validation Attributes
     *
     * @var array
     */
    protected $validationAttributes = [];

    /**
     * All Mentioned Fields
     *
     * @var array
     */
    private $allFields = [];

    /**
     * Form Fields
     *
     * @return mixed
     */
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
     *
     * @throws \Exception
     */
    private function initForm($caller = 'mount')
    {
        $this->resolveModel();
        $this->resolveRecord();
        $this->prepareFields();
        $this->processExistingUploads();
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
        return ! $this->isUpdate;
    }

    public function clearUploadedPhoto($key, $value = '')
    {
        $this->syncInput('fields.'.$key, $value);
        //$this->syncInput('files.' . $key, $value);
        $this->processExistingUploads();
    }

    /**
     * Submit the form
     *
     * @return void
     */
    public function submit()
    {
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

            if (! is_null($field)) {
                if (isset($this->files[$key])) {
                    $item = $this->getTemporaryUploadFile($item);
                }

                $item = $field->process();

                // Post Process
                $transform = $field->getTransformCallback();
                if (! is_null($transform)) {
                    $item = appCall($transform, [
                        'value' => $item,
                        'field' => $field,
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
     * Process Already LiveWire Uploaded File
     *
     * @return void
     */
    protected function processExistingUploads()
    {
//        collect($this->fields)->each(function ($itemValue, $itemKey) {
//            if (isset($this->files[$itemKey]) && !empty($itemValue)) {
//                $this->files[$itemKey] = $this->getTemporaryUploadFile($itemValue);
//            }
//        });
    }

    protected function getTemporaryUploadFile($path)
    {
        if (! is_null($path)) {
            if (is_array($path)) {
                return collect($path)->map(function ($i) {
                    return TemporaryUploadedFile::createFromLivewire($i);
                })->toArray();
            }

            return TemporaryUploadedFile::createFromLivewire($path);
        }

        return null;
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

    /**
     * Transformed properties and remove unwanted before action
     *
     * @return mixed
     */
    private function filterTransformedProperties($data = [])
    {
        return Arr::except($data, $this->propertiesToRemove($this->isUpdate));
    }

    protected function action($validated = [], $all = [], $transformed = [])
    {
        $transformed = $this->filterTransformedProperties($transformed);
        try {
            if ($this->isUpdate) {
                $actionData = $this->updateRecord($validated, $all, $transformed);
            } else {
                $actionData = $this->createRecord($validated, $all, $transformed);
            }

            $this->onActionCompleted($actionData);

            if (! is_null(Eden::getPreviousUrl())) {
                return $this->redirect(Eden::getPreviousUrl());
            }
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
            $record = $this->record()->forceFill($transformed)->save();
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
     * @param  Field  $field
     * @return void
     */
    protected function collectDependentFields(Field $field)
    {
        $this->dependentFields = collect(array_merge($this->dependentFields, $field->getDependentTargets()))
            ->flatten()
            ->unique()
            ->all();
    }

    protected function syncDependentFields(Field $field)
    {
    }

    /**
     * Sync file value with form fields values to enable user interaction
     *
     * @param  Field  $field
     * @return void
     */
    private function syncField(Field $field)
    {
        $key = $field->getKey();

        // Create Record and Sync in Files Array
        //if ($field instanceof File) {
        //$this->files[$key] = $this->getFormFieldValue($field);
        //} else {
        $this->fields[$key] = $this->getFormFieldValue($field);
        //}
    }

    /**
     * Get form Field value or fill that via record
     *
     * @param  Field  $field
     * @return array|\ArrayAccess|mixed|null
     */
    private function getFormFieldValue(Field $field)
    {
        $key = $field->getKey();
        $value = $field->exportToForm();

        if (isset($this->fields[$key])) { // Assign filled form value if exists - Non Files
            $value = $this->fields[$key];

        //} else if (isset($this->files[$key])) { // Assign filled form value if exists - Files
        //    $value = $this->files[$key];
        } else { // Fill from record
            $value = $this->getRecordValue($key, $value);
        }

        return $value;
    }

    /**
     * Sync field rules with the form validation rules
     *
     * @param  Field  $field
     * @return void
     */
    private function syncFieldRule(Field $field)
    {
        $key = $field->getKey();
        $fieldRules = $field->getRules($this->isUpdate);

        if ((is_string($fieldRules) && ! empty($fieldRules)) || (is_array($fieldRules) && count($fieldRules) > 0)) {
            if ($field instanceof File) {
                $multipleKey = $field->isMultiple() ? '.*' : '';

                $this->rules['fields.'.$key.$multipleKey] = $fieldRules;
                $this->validationAttributes['fields.'.$key.$multipleKey] = htmlentities(strip_tags($field->title));
            } else {
                $this->rules['fields.'.$key] = $fieldRules;
            }
        }
        $this->validationAttributes['fields.'.$key] = htmlentities(strip_tags($field->title));
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
                    return $field->importFromFrom($this->fields[$field->getKey()], $this->getMappedFields());
                }

                return $field;
            })
            ->transform(function (Field $field) {
                return $field->resolveUsing($field->exportToForm(), $this->getMappedFields(), $this);
            })
            ->each(function (Field $field) {
                //if (is_subclass_of($field, File::class)) {
                //$this->files[$key] = $this->fields[$key];
                //} else {
                $this->fields[$field->getKey()] = $field->exportToForm();
                //}
            })
            ->all();
    }

    public function defaultViewParams()
    {
        return [
            'rules' => $this->rules,
            'formFields' => $this->allFields,
            'depends' => $this->dependentFields,
            'record' => $this->record,
        ];
    }

    /**
     * View for the form
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    protected function view()
    {
        return view('eden::components.form');
    }

    /**
     * @param  string  $class
     * @param  array  $params
     * @return FormRenderer
     */
    protected static function renderer($class, $params)
    {
        return new FormRenderer($class, $params);
    }
}
