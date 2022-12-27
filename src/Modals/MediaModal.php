<?php

namespace BestSnipp\Eden\Modals;

use BestSnipp\Eden\Assembled\MediaManager\MediaManagerDataTable;
use BestSnipp\Eden\Components\Modal;
use BestSnipp\Eden\Models\EdenMedia;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class MediaModal extends Modal
{
    use WithFileUploads;

    /**
     * Title in singular form to display in DataTable
     *
     * @var string
     */
    public $title = 'Select or Upload Media';

    /**
     * Modal Maximum Width, default large [ lg ]
     *
     * none, xs, sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl, full,
     * min, max, fit, screen-sm, screen-md, screen-lg, screen-xl, screen-2xl
     *
     * @var string
     */
    public $width = '7xl';

    /**
     * Confirm button Text
     *
     * @var string
     */
    protected $confirmButtonText = 'Select';

    protected $closeOnOutsideClick = true;

    protected $visible = true;

    protected $files = [];

    protected $tabs = [];

    public $selected = [];

    public $via = 'backend';

    public $selectionType = 'multiple';

    public $fileupload = '';

    protected $enableJsInteractions = true;

    protected $listeners = [
        'upload:finished' => 'storeUploadedFile',
    ];

    public function onMount()
    {
        $this->bodyAttrs = [
            'x-data' => "{selected: window.Livewire.find('".$this->id."').entangle('selected').defer, via: window.Livewire.find('".$this->id."').entangle('via').defer, selectionType: window.Livewire.find('".$this->id."').entangle('selectionType').defer, owner: 'all'}",
            '@show-media-manager.window' => '(evt) => {selected = []; isVisible = true; showFromJs = true; via = evt.detail.via; selectionType = evt.detail.selectionType; owner = evt.detail.owner}',
        ];
    }

    public function storeUploadedFile($name, $files)
    {
        $filesUploaded = [];
        $filesErrored = [];
        $filesToStore = [];

        foreach ($this->fileupload as $file) {
            try {
                if (! ($file instanceof TemporaryUploadedFile)) {
                    continue;
                }

                $path = $file->storePublicly('public');
                $path = Str::replace('public/', '', $path);
                $filesUploaded[] = $path;
                $filesToStore[] = [
                    'id' => Str::uuid()->toString(),
                    'name' => $file->getClientOriginalName() ?? $file->getFilename(),
                    'type' => $file->getMimeType(),
                    'extension' => $file->extension(),
                    'path' => $path,
                    'url' => asset('storage/'.$path),
                    'folder' => null,
                    'preview' => $file->isPreviewable(),
                    'created_at' => now(),
                ];
            } catch (\Exception $exception) {
                $filesErrored[] = $exception->getMessage();
            }
        }

        try {
            DB::beginTransaction();
            EdenMedia::insert($filesToStore);
            DB::commit();

            $this->toastSuccess('Media records uploaded successfully');
            $this->emit('refresh'.MediaManagerDataTable::getName());
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->toastError($exception->getMessage());
        }
    }

    public function _finishUpload($name, $tmpPath, $isMultiple)
    {
        $this->cleanupOldUploads();

        if ($isMultiple) {
            $file = collect($tmpPath)->map(function ($i) {
                return TemporaryUploadedFile::createFromLivewire($i);
            })->toArray();
            $this->emit('upload:finished', $name, collect($file)->map->getFilename()->toArray())->self();
        } else {
            $file = TemporaryUploadedFile::createFromLivewire($tmpPath[0]);
            $this->emit('upload:finished', $name, [$file->getFilename()])->self();

            // If the property is an array, but the upload ISNT set to "multiple"
            // then APPEND the upload to the array, rather than replacing it.
            if (is_array($value = $this->getPropertyValue($name))) {
                $file = array_merge($value, [$file]);
            }
        }

        $this->syncInput($name, $file);
    }

    public function prepareForRender()
    {
        $this->tabs = [
            ['type' => 'upload', 'label' => 'Upload Files', 'view' => 'eden::modals.media-manager.upload'],
            ['type' => 'library', 'label' => 'Media Library', 'view' => 'eden::modals.media-manager.library'],
        ];

        $this->files = [];
//        $faker = Factory::create();
//
//        foreach ($this->getExtensionColors() as $extension => $color) {
//            $this->files[] = [
//                'id' => md5($extension),
//                'name' => $faker->uuid() . '.' . $extension,
//                'type' => $extension,
//                'extension' => $extension,
//                'path' => $faker->filePath(),
//                'url' => $faker->imageUrl(rand(500, 1920), rand(200, 1080)),
//                'preview' => in_array($extension, $this->getImageExtensions())
//            ];
//        }
    }

    /**
     * View to render by the modal
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|string
     */
    public function modalView()
    {
        return view('eden::modals.media-manager')
            ->with([
                'tabs' => $this->tabs,
                'files' => $this->files,
                'colors' => $this->getExtensionColors(),
            ]);
    }

    protected function viewForFooter()
    {
        return view('eden::modals.media-manager.footer');
    }

    /**
     * Remove records once confirmed
     *
     * @return mixed|void
     */
    public function confirm()
    {
        $this->visible = false;
        $this->emit('selectedMediaFiles', $this->selected);
    }

    protected function getExtensionColors()
    {
        return [
            'aif' => 'bg-green-500 text-white',
            'cda' => 'bg-green-500 text-white',
            'mid' => 'bg-green-500 text-white',
            'midi' => 'bg-green-500 text-white',
            'mp3' => 'bg-green-500 text-white',
            'mpa' => 'bg-green-500 text-white',
            'ogg' => 'bg-green-500 text-white',
            'wav' => 'bg-green-500 text-white',
            'wma' => 'bg-green-500 text-white',
            'wpl' => 'bg-green-500 text-white',

            '7z' => 'bg-amber-500 text-white',
            'arj' => 'bg-sky-500 text-white',
            'deb' => 'bg-sky-500 text-white',
            'pkg' => 'bg-sky-500 text-white',
            'rar' => 'bg-purple-500 text-white',
            'rpm' => 'bg-sky-500 text-white',
            'gz' => 'bg-sky-500 text-white',
            'z' => 'bg-sky-500 text-white',
            'zip' => 'bg-amber-500 text-white',

            'bin' => 'bg-violet-500 text-white',
            'dmg' => 'bg-violet-500 text-white',
            'toast' => 'bg-violet-500 text-white',
            'iso' => 'bg-violet-500 text-white',
            'vcd' => 'bg-violet-500 text-white',

            'csv' => 'bg-emerald-500 text-white',
            'dat' => 'bg-emerald-500 text-white',
            'db' => 'bg-emerald-500 text-white',
            'dbf' => 'bg-emerald-500 text-white',
            'log' => 'bg-emerald-500 text-white',
            'mdb' => 'bg-emerald-500 text-white',
            'sav' => 'bg-emerald-500 text-white',
            'sql' => 'bg-amber-500 text-white',
            'tar' => 'bg-emerald-500 text-white',
            'xml' => 'bg-emerald-600 text-white',

            'email' => 'bg-red-500 text-white',
            'eml' => 'bg-red-500 text-white',
            'emlx' => 'bg-red-500 text-white',
            'msg' => 'bg-red-500 text-white',
            'oft' => 'bg-red-500 text-white',
            'ost' => 'bg-red-500 text-white',
            'vcf' => 'bg-red-500 text-white',
            'pst' => 'bg-red-500 text-white',

            'apk' => 'bg-fuchsia-500 text-white',
            'bat' => 'bg-fuchsia-500 text-white',
            'cgi' => 'bg-fuchsia-500 text-white',
            'pl' => 'bg-fuchsia-500 text-white',
            'com' => 'bg-fuchsia-500 text-white',
            'exe' => 'bg-fuchsia-500 text-white',
            'gadget' => 'bg-fuchsia-500 text-white',
            'jar' => 'bg-fuchsia-500 text-white',
            'msi' => 'bg-fuchsia-500 text-white',
            'py' => 'bg-fuchsia-500 text-white',
            'wsf' => 'bg-fuchsia-500 text-white',

            'fnt' => 'bg-slate-600 text-white',
            'fon' => 'bg-slate-600 text-white',
            'otf' => 'bg-slate-600 text-white',
            'ttf' => 'bg-slate-600 text-white',

            'ai' => 'bg-amber-600 text-white',
            'webp' => 'bg-red-500 text-white',
            'bmp' => 'bg-slate-600 text-white',
            'gif' => 'bg-rose-500 text-white',
            'ico' => 'bg-rose-500 text-white',
            'jpeg' => 'bg-cyan-500 text-white',
            'jpg' => 'bg-cyan-500 text-white',
            'png' => 'bg-teal-500 text-white',
            'ps' => 'bg-indigo-600 text-white',
            'psd' => 'bg-indigo-600 text-white',
            'svg' => 'bg-lime-600 text-white',
            'tif' => 'bg-red-500 text-white',
            'tiff' => 'bg-red-500 text-white',

            'asp' => 'bg-cyan-500 text-white',
            'aspx' => 'bg-cyan-500 text-white',
            'cer' => 'bg-green-500 text-white',
            'cfm' => 'bg-red-500 text-white',
            'css' => 'bg-sky-500 text-white',
            'htm' => 'bg-orange-500 text-white',
            'html' => 'bg-orange-500 text-white',
            'js' => 'bg-yellow-400 text-black',
            'jsp' => 'bg-slate-600 text-white',
            'part' => 'bg-green-500 text-white',
            'php' => 'bg-sky-600 text-white',
            'rss' => 'bg-yellow-600 text-white',
            'xhtml' => 'bg-orange-500 text-white',

            'key' => 'bg-rose-500 text-white',
            'ppt' => 'bg-rose-500 text-white',
            'pps' => 'bg-rose-500 text-white',
            'odp' => 'bg-rose-500 text-white',
            'pptx' => 'bg-rose-500 text-white',

            'c' => 'bg-blue-600 text-white',
            'class' => 'bg-red-500 text-white',
            'cpp' => 'bg-blue-600 text-white',
            'cs' => 'bg-pink-600 text-white',
            'h' => 'bg-red-700 text-white',
            'java' => 'bg-red-500 text-white',
            'sh' => 'bg-slate-700 text-white',
            'swift' => 'bg-orange-500 text-white',
            'vb' => 'bg-purple-600 text-white',

            'ods' => 'bg-emerald-700 text-white',
            'xls' => 'bg-emerald-700 text-white',
            'xlsm' => 'bg-emerald-700 text-white',
            'xlsx' => 'bg-emerald-700 text-white',

            'bak' => 'bg-lime-600 text-white',
            'cab' => 'bg-lime-600 text-white',
            'cpl' => 'bg-lime-600 text-white',
            'cur' => 'bg-lime-600 text-white',
            'dll' => 'bg-lime-600 text-white',
            'dmp' => 'bg-lime-600 text-white',
            'drv' => 'bg-lime-600 text-white',
            'icns' => 'bg-lime-600 text-white',
            'ini' => 'bg-cyan-700 text-white',
            'lnk' => 'bg-lime-600 text-white',
            'sys' => 'bg-lime-600 text-white',
            'tmp' => 'bg-lime-600 text-white',

            '3g2' => 'bg-teal-600 text-white',
            '3gp' => 'bg-teal-600 text-white',
            'avi' => 'bg-teal-600 text-white',
            'flv' => 'bg-teal-600 text-white',
            'h264' => 'bg-teal-600 text-white',
            'm4v' => 'bg-teal-600 text-white',
            'mkv' => 'bg-teal-600 text-white',
            'mov' => 'bg-teal-600 text-white',
            'mp4' => 'bg-teal-600 text-white',
            'mgp' => 'bg-teal-600 text-white',
            'mpeg' => 'bg-teal-600 text-white',
            'rm' => 'bg-teal-600 text-white',
            'swf' => 'bg-teal-600 text-white',
            'vob' => 'bg-teal-600 text-white',
            'wmv' => 'bg-teal-600 text-white',

            'doc' => 'bg-indigo-600 text-white',
            'docx' => 'bg-indigo-600 text-white',
            'odt' => 'bg-indigo-600 text-white',
            'pdf' => 'bg-red-600 text-white',
            'rtf' => 'bg-slate-900 text-white',
            'tex' => 'bg-indigo-600 text-white',
            'txt' => 'bg-slate-700 text-white',
            'wpd' => 'bg-slate-900 text-white',
        ];
    }

    protected function getImageExtensions()
    {
        return [
            'webp', 'bmp', 'gif', 'ico', 'jpeg', 'jpg', 'png', 'svg',
        ];
    }
}
