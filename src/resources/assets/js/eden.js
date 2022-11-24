import ApexCharts from 'apexcharts';
window.ApexCharts = ApexCharts; // return apex chart

import jQuery from "jquery";
window.$ = window.jQuery = jQuery;
import select2 from 'select2'

import flatpickr from "flatpickr";
window.flatpickr = flatpickr;

window.refresh = function ($id, $type) {
    if ($type === 'select2') {
        $($id).select2();
    }
}

import trix from 'trix';
import Pickr from '@simonwep/pickr';
window.Pickr = Pickr;


window.iniliatizeSelect2 = function () {
    $('#{{ $key }}')
        .not('.select2-hidden-accessible')
        .select2()
        .on('select2:select', (event) => {
            model = $(event.target).val();
        })
        .on('select2:unselect', (event) => {
            model = $(event.target).val();
        });
}
