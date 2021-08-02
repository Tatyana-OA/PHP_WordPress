jQuery( document ).ready(function() {
	const modal = document.getElementById("myModal");
	const btn = document.getElementsByClassName("wpcrm-add-btn")[0];
	const span = document.getElementsByClassName("close")[0];
    jQuery( "tbody.dashboard-calendar-body" ).one( "click", function(e) {
		if (e.target.className !== 'calendar-day-np' ) {
			console.log('correct target');
			let wpcrmAddBtn = createElement('button', `Add New 	\u2795 `, 'wpcrm-add-btn');
			e.target.append(wpcrmAddBtn);
			jQuery( wpcrmAddBtn ).on( "click", function(e) {
			modal.style.display = "block";
			console.log('correct target');
			jQuery( span ).on( "click", function(e) {
				modal.style.display = "none";
				btn.style.display='none';
				});
			});

		}
	  });

	  jQuery("select").on('click', function(e) {
		  if (e.target.value != 'add-new') {
			Array.from(document.querySelector('.open-on-option').children).forEach(ch => ch.style.display='none');
			switch (e.target.value) {
        case "new-wpcrm-opportunity":
			document.getElementById("wpcrm-opportunity-modalbox").style.display='block';
          break;
        case "new-wpcrm-contact":
			document.getElementById("wpcrm-contact-modalbox").style.display='block';
          break;
        case "new-wpcrm-campaign":
			document.getElementById("wpcrm-campaign-modalbox").style.display='block';
          break;
        case "new-wpcrm-project":
			document.getElementById("wpcrm-project-modalbox").style.display='block';
          break;
        case "new-wpcrm-task":
			document.getElementById("wpcrm-task-modalbox").style.display='block';
          break;
        case "new-wpcrm-organization":
			document.getElementById("wpcrm-organization-modalbox").style.display='block';
          break;
      }
		  }
	  })
	  jQuery('input.submit-fast-cpt').on('click', function(e){
		let inputValue;
		const postType = e.target.parentNode.id.slice(0,-9) //Getting post type.
		if (postType=='wpcrm-contact'){
		inputValue = [];
		Array.from(e.target.parentNode.querySelectorAll('input.wpcrm-fast-cpt-creation')).forEach(el => inputValue.push(el.value))
		} else {
			inputValue = e.target.parentNode.querySelector('input.wpcrm-fast-cpt-creation').value
		}
		let data = {
			action: 'wpcrm_system_handle_quick_create_jquery',
			name: inputValue,
			type: postType
		}
		if ( data != null ) {
			jQuery.ajax({
				type: "post",
				dataType: "json",
				url: ajax_object.ajax_url,
				data: data,
				success: function (response) {
					console.log('in response', response.data)
					modal.querySelector('.modal-content').textContent='Success!'
					setTimeout(function(){ location.reload() }, 1000)
				},
				error: function (response) {
					console.log(response)
					modal.querySelector('.modal-content').textContent='An error occurred!'
					setTimeout(function(){ location.reload() }, 1000)
				}
			})

		}
	})
});
