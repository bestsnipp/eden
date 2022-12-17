import _ from 'lodash';
window._ = _;

window.addEventListener('alpine:init', function () {

    // Eden NiceScroll
    Alpine.directive('eden-nice-scroll', (el, { value, modifiers, expression }, { Alpine, effect, cleanup }) => {
        $(el).niceScroll();

        $(el).on('DOMSubtreeModified', _.debounce(() => {
            $(el).niceScroll().resize();
        }, 300))

        cleanup(() => {
            $(el).off('DOMSubtreeModified')
        })
    })

    // Eden Select/MultiSelect With Select2
    Alpine.data('edenSelectField', (value = '', showSearch = true) => ({
        model: value,

        init() {
            $(this.$el).select2({
                minimumResultsForSearch: showSearch ? 0 : Infinity
            }).on('select2:select', (evt) => {
                this.model = $(evt.target).val();
            }).on('select2:unselect', (evt) => {
                this.model = $(evt.target).val();
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
