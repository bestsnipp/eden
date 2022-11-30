// Import ApexChart
//import ApexCharts from 'apexcharts';
//window.ApexCharts = ApexCharts; // return apex chart

// Import jQuery
//import $ from "jquery";
//window.jQuery = window.$ = $;

// Import Select2
//import select2 from 'select2'
//window.select2 = select2;

// Import FlatPicker
// import flatpickr from "flatpickr";
// window.flatpickr = flatpickr;

// Import Trix
//import trix from 'trix';

// Import Pickr
//import Pickr from '@simonwep/pickr';
//window.Pickr = Pickr;


if (undefined !== jQuery) {

    if (undefined !== NiceScroll) {
        $(function() {
            $("body").niceScroll();
            $("#sidebar").niceScroll();
        });
    }

}

window.addEventListener('alpine:init', function () {

    // Eden Select/MultiSelect With Select2
    Alpine.data('edenSelectField', (value = '', showSearch = true, data = []) => ({
        model: value,

        init() {
            $('select', this.$el).select2({
                data: data,
                minimumResultsForSearch: showSearch ? 0 : Infinity
            }).on('select2:select', (evt) => {
                this.model = $(evt.target).val()
            });
            // Initial Value
            this.model = $(this.$el).val()
        }
    }))
    Alpine.data('edenSelectField_BKUP', (value = '', showSearch = true) => ({
        model: value,

        init() {
            $(this.$el).select2({
                minimumResultsForSearch: showSearch ? 0 : Infinity
            }).on('select2:select', (evt) => {
                this.model = $(evt.target).val()
            });
            // Initial Value
            this.model = $(this.$el).val()
        }
    }))

    // Eden Date/Time Picker With flatpickr
    Alpine.data('edenDateTimePicker', (
        value = '',
        defaultDate = '',
        format = '',
        hideDatePicker = true,
        isTimePicker = true,
        isReadOnly = false
    ) => ({
        model: value,

        init() {
            flatpickr(this.$el, {
                noCalendar: hideDatePicker,
                enableTime: isTimePicker,
                dateFormat: format,
                defaultDate: new Date(defaultDate),
                clickOpens: isReadOnly,
            })
            // Initial Value
            //this.model = $(this.$el).val()
        }
    }))

    // Eden Trix - TODO : Not Working ...
    Alpine.data('edenTrixEditor', (value = '') => ({
        model: value,

        init() {
            document.addEventListener('trix-change', function (event) {
                this.model = event.target.value;
            })
        }
    }))
})
