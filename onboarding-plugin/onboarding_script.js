console.log('works by onboarding plugin')

jQuery('input#enableFilters').on('change', (e) => {
    const filterEnabled = e.target.checked;
    console.log(filterEnabled)
    

    //alert('Value changed')

    let data = {
        action: 'filter_value',
        filterValue: filterEnabled,
        checked: ajax_object.state,
    }
    if ( data != null ) {
        jQuery.ajax({
            type: "post",
            dataType: "json",
            url: ajax_object.ajax_url,
            data: data,
            success: function (response) {
                console.log(response.data)
            },
            error: function (response) {
                console.log(response)
            }
        })

    }
});

