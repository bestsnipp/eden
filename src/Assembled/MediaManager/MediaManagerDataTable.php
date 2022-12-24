<?php

namespace BestSnipp\Eden\Assembled\MediaManager;

use BestSnipp\Eden\Components\DataTable;
use BestSnipp\Eden\Components\Fields\UID;
use BestSnipp\Eden\Models\EdenMedia;

class MediaManagerDataTable extends DataTable
{
    public $title = null;

    public static $model = EdenMedia::class;

    public $rowsPerPage = 12;

    public $rowsPerPageOptions = [
        6 => 6 ,
        12 => 12 ,
        24 => 24 ,
        48 => 48 ,
        96 => 96
    ];

    public $showHeader = false;

    public $isTableLayout = false;

    public $headerStyle = 'border border-slate-100 dark:border-slate-500 rounded-md overflow-hidden dark:text-slate-300 py-3 px-4 bg-slate-50 dark:border-slate-800 dark:bg-slate-700';

    public $appliedFilterStyle = 'py-3 flex flex-wrap gap-3 items-center mt-3 md:mt-0 md:rounded-none border-t dark:bg-slate-600 dark:border-slate-500';

    public $bodyStyle = 'grid grid-cols-1 gap-4 my-6';

    public $bodyAttrs = [
        'x-bind:class' => "{'md:grid-cols-2 lg:grid-cols-3': selected.length > 0, 'md:grid-cols-3 lg:grid-cols-4': selected.length <= 0}"
    ];

    public $paginationStyle = 'flex flex-col md:flex-row justify-between items-center border border-slate-100 rounded-md overflow-hidden dark:border-slate-500 dark:bg-slate-700 dark:text-slate-300';

    protected function fields()
    {
        return [

            UID::make('ID', 'id')

        ];
    }

    protected function filters()
    {
        return [
            //
        ];
    }

    protected function actions()
    {
        return [

            DataTable\Actions\DeleteAction::make()

        ];
    }

    public function rowView($record, $fields = [], $records = [])
    {
        return view("eden::modals.media-manager.row-media")->with([
            'file' => $record->toArray(),
            'fields' => $fields,
            'selectorField' => collect($fields)->firstWhere(function ($item) {
                return $item->getKey() == 'select';
            }),
            'actionField' => collect($fields)->firstWhere(function ($item) {
                return $item->getKey() == 'actions';
            }),
            'colors' => $this->getExtensionColors()
        ]);
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
}
