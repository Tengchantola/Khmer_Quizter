$(document).ready(function () {
  $("#myInput").on("keyup", function () {
    var value = $(this).val().toLowerCase();
    $("#myTable .meow").filter(function () {
      var found = $(this).text().toLowerCase().indexOf(value) > -1;
      $(this).toggle(found);
      return found;
    });
    if ($("#myTable .meow:visible").length === 0) {
      $(".no-results").show();
      $("#searchTerm").text(value);
    } else {
      $(".no-results").hide();
    }
  });

  const buttons = document.getElementsByClassName("disableButton");
  for (let i = 0; i < buttons.length; i++) {
    const button = buttons[i];
    if (button.classList.contains("disabled")) {
      button.textContent = "Enable User";
    }
  }
  $(".delete").click(function () {
    var userid = $(this).siblings().eq(4).val();
    Swal.fire({
      title: "Delete User!",
      text: "Do you want to delete this user?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Yes",
      cancelButtonText: "No",
      confirmButtonColor: "red",
    }).then((result) => {
      if (result.value) {
        $.ajax({
          type: "POST",
          url: "delete_user.php",
          data: {
            userid: userid,
          },
          success: function (response) {
            location.reload();
          },
          error: function (xhr, status, error) {
            console.error(error);
          },
        });
      }
    });
  });
  $(".diable").click(function () {
    var userid = $(this).siblings().eq(4).val();
    console.log(userid);
    $.ajax({
      url: "disableuser.php",
      method: "GET",
      data: {
        userid: userid,
      },
      success: function (response) {
        location.reload();
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
        alert(
          "An error occurred while fetching questions. Please try again later."
        );
      },
    });
  });
});
