console.log('works by links plugin')

jQuery('input#submit').on('click', (e) => {
    //alert( "A URL is being submitted." );
    const inputLink = document.querySelector('input#URL').value
    const cacheDuration = document.querySelector('select#duration').value
    console.log(cacheDuration)


    let data = {
        action: 'link_submission', // the function that will receive data
        inputLink:inputLink,//some data 
        cacheDuration: cacheDuration
    }
    if ( data != null ) {
        jQuery.ajax({
            type: "post",
            dataType: "json",
            url: ajax_object.ajax_url,
            data: data,
            success: function (response) {
                //removing an unnecessary amazon link from the retreived html
                response.data = response.data.replace('<a id="skiplink" tabindex="0" class="skip-link">Skip to main content</a>',"")
                console.log(response.data)
                jQuery( ".retrieved_data" ).html(response.data);
                // jQuery('.retrieved_data').html(response.data);

               // console.log(response.data)
                
              
            },
            error: function (response) {
                console.log('error',response)
            }
        })

    }
    
 
    
  });

