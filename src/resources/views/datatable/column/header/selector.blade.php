<input
    type="checkbox" x-ref="multi_selector_parent" value="" name="multi_selector_parent"
    x-on:click="evt => {
        let parent = $($el).parents('.dataTableContainer');
        if (evt.target.checked) {
            $('input[name=\'multi_selector\']', parent).prop('checked', false).click();
        } else {
            $('input[name=\'multi_selector\']', parent).prop('checked', true).click();
        }
    }"
    x-init="$watch('selectedRows', value => {
        if (value.length > 0) {
            $($refs.multi_selector_parent).prop('checked', true);
        } else {
            $($refs.multi_selector_parent).prop('checked', false);
        }
    })"
    class="focus:ring-0 h-5 w-5 text-indigo-600 border-gray-300 rounded">
