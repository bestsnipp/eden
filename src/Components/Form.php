<?php
namespace Dgharami\Eden\Components;

use Dgharami\Eden\Components\Fields\Field;
use Dgharami\Eden\Components\Fields\File;
use Dgharami\Eden\RenderProviders\FormRenderer;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

abstract class Form extends EdenComponent
{
    use WithFileUploads;

    public $width = 'half';

    const DRIVER_ELOQUENT = 'eloquent';
    const DRIVER_DATABASE = 'database';
    const DRIVER_MODEL = 'model';
    const DRIVER_ARRAY = 'array';
    const DRIVER_OBJECT = 'object';

    /**
     * @var string|array|Model
     */
    public static $model = null;

    public $resource = '';

    /**
     * @var 'model_instance'|'object'|'array'|'model_class'|'table'
     */
    private $modelType = self::DRIVER_ELOQUENT;

    protected $isUpdate = false;

    protected $primaryKey = 'id';

    public $styleContainer = '';

    // Dynamic Fields
    public $fields = [];

    // Dynamic File Fields
    public $files = [];

    // Laravel LiveWire Validation Rules
    protected $rules = [];

    // Laravel LiveWire Validation Attributes
    protected $validationAttributes = [];

    // All Mentioned Fields
    private $allFields = [];

    // Let User Decide the Fields
    abstract protected function fields();

    /**
     * @return string|array|Model|null
     */
    protected function model() {
        return self::$model;
    }

    /**
     * Initial Component Mount - Only First Time
     *
     * @throws \Exception
     */
    public function mount()
    {
        $this->resolveModel();
        $this->prepareFields();
        $this->processExistingUploads();
        $this->finalizeFields();
    }

    public function hydrate()
    {
        $this->resolveModel();
        $this->prepareFields();
        $this->processExistingUploads();
        $this->finalizeFields();
    }

    public function isUpdate()
    {
        return $this->isUpdate;
    }

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
        if ($this->modelType == self::DRIVER_ELOQUENT) {
            return (new self::$model)->forceCreate($validated);
        }
        if ($this->modelType == self::DRIVER_DATABASE) {
            $id = DB::table(self::$model)->insertGetId($all);
            if ($id) {
                return DB::table(self::$model)->where($this->primaryKey, $id)->first();
            }
            return $id;
        }
        return null;
    }

    protected function updateRecord($validated, $all, $transformed)
    {
        if ($this->modelType == self::DRIVER_MODEL) {
            self::$model->forceFill($all)->save();
            return self::$model->refresh();
        }
        if ($this->modelType == self::DRIVER_OBJECT && !is_null($this->table)) {
            $dbRecord = DB::table($this->table)->where($this->primaryKey, self::$model->{$this->primaryKey});
            $dbRecord->update($all);

            return $dbRecord->first();
        }
        return null;
    }

    protected function onActionCompleted($data)
    {
        $this->toastSuccess(sprintf('Record %s', ($this->isUpdate ? 'updated' : 'created')));
    }

    protected function onActionException(\Exception $exception)
    {
        $this->toastError(json_encode($exception->getMessage()));
    }

    private function getField($key)
    {
        return collect($this->allFields)->first(function ($i) use ($key) {
            return $i->getKey() == $key;
        });
    }

    private function processValidatedFields($validatedFields): array
    {
        return collect($validatedFields)->transform(function ($item, $key) {
            $field = $this->getField($key);

            if (!is_null($field)) {
                if (isset($this->files[$key])) {
                    $item = $this->getTemporaryUploadFile($item);
                }
                $field->fromFormData($item, []);

                $item = $field->process($field->getValue());

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

    protected function resolveModel()
    {
        $model = $this->model();
        if (is_null($model)) {
            return;
        }

        if (is_array($model)) {
            $this->modelType = self::DRIVER_ARRAY;
            self::$model = $model;

        } else if ($model instanceof Model) {
            $this->modelType = self::DRIVER_MODEL;
            self::$model = $model;

        } else if (is_object($model)) {
            $this->modelType = self::DRIVER_OBJECT;
            self::$model = $model;

        } else if (is_string($model)) {
            try {
                app($model);
                $this->modelType = self::DRIVER_ELOQUENT;
                self::$model = $model;

            } catch (BindingResolutionException $exception) {
                $this->modelType = self::DRIVER_DATABASE;
                self::$model = $model;
            }
        } else {
            throw new \Exception(sprintf('"Only Array, String and %s accepted as Model', Model::class));
        }
    }

    protected function prepareFields()
    {
        $this->allFields = collect($this->fields())
            ->each(function (Field $field) {
                $this->syncField($field);
                $this->syncFieldRule($field);
            })->all();
    }

    private function getFieldValue(Field $field)
    {
        $key = $field->getKey();
        $value = $field->toFormData();

        if (isset($this->fields[$key])) {
            $value = $this->fields[$key] ?? $value;

        }else if (!is_null(self::$model)) {
            if (in_array($this->modelType, [self::DRIVER_ARRAY, self::DRIVER_MODEL]) && Arr::exists(self::$model, $key)) {
                $value = Arr::get(self::$model, $key) ?? $value;

            } else if (in_array($this->modelType, [self::DRIVER_OBJECT]) && property_exists(self::$model, $key)) {
                $value = self::$model->$key ?? $value;
            }
        }

        return $value;
    }

    private function syncField(Field $field)
    {
        $key = $field->getKey();
        $this->fields[$key] = $this->getFieldValue($field) ?? $field->toFormData();

        // Create Record and Sync in Files Array
        if ($field instanceof File) {
            $this->files[$key] = $this->fields[$key];
        }
    }

    private function syncFieldRule(Field $field)
    {
        $key = $field->getKey();
        $fieldRules = $field->getRules($this->isUpdate);
        if ((is_string($fieldRules) && !empty($fieldRules)) || (is_array($fieldRules) && count($fieldRules) > 0)) {
            if ($field instanceof File) {
                $multipleKey = $field->isMultiple() ? '.*' : '';

                $this->rules['files.' . $key . $multipleKey] = $fieldRules;
                $this->validationAttributes['files.' . $key . $multipleKey] = htmlentities($field->title);
            } else {
                $this->rules['fields.' . $key] = $fieldRules;
            }
        }
        $this->validationAttributes['fields.' . $key] = htmlentities($field->title);
    }

    private function getMappedFields()
    {
        return collect($this->allFields)->mapWithKeys(function (Field $field) {
            return [$field->getKey() => $field];
        })->all();
    }

    protected function transform($validated, $all)
    {
        return $all;
    }

    private function finalizeFields()
    {
        $this->allFields = collect($this->allFields)
            ->transform(function (Field $field) {
                return $field->fromFormData(($this->fields[$field->getKey()] ?? $field->toFormData()), $this->getMappedFields());
            })
            ->transform(function (Field $field) {
                return $field->resolveUsing($field->getValue(), $this->getMappedFields(), $this);
            })
            ->each(function (Field $field) {
                $this->fields[$field->getKey()] = $field->toFormData();
            })
            ->all();
    }

    public function view()
    {
        return view('eden::components.form')
            ->with('rules', $this->rules)
            ->with('formFields', $this->allFields);
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
