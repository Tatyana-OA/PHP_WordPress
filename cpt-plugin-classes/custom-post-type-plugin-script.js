console.log("works for custom post type plugin");

jQuery('input[name="activeStudent"]').on("change", (e) => {
  console.log(e.target);
  const isActive = e.target.checked;
  const studentID = e.target.id.split("_")[1];
  console.log(isActive)

  let data = {
    action: "student_checkbox_handle", // the function that will receive data
    isActive: isActive,
    student_id: studentID,
  };
  if (data != null) {
    jQuery.ajax({
      type: "post",
      dataType: "json",
      url: ajax_object.ajax_url,
      data: data,
      success: function (response) {
        console.log(response.data);
      },
      error: function (response) {
        console.log("error", response.data);
      },
    });
  }
});
