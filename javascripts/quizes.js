$(document).ready(function () {
  $("#myInput").on("keyup", function () {
    var value = $(this).val().toLowerCase();
    $("#myTable .ccc").filter(function () {
      var found = $(this).text().toLowerCase().indexOf(value) > -1;
      $(this).toggle(found);
      return found;
    });
    if ($("#myTable .ccc:visible").length === 0) {
      $(".no-results").show();
      $("#searchTerm").text(value);
    } else {
      $(".no-results").hide();
    }
  });

  $(".ccc").fadeIn(700);
  $(".carde").click(function () {
    var quizcode = $(this).children().eq(0).val();
    var quizid = $(this).children().eq(1).val();
    var numofques = $(this).children().eq(2).val();
    var image = $(this).children().eq(4).children().eq(0).attr("src");
    var titles = $(this).find(".quiztitles").html();
    var author = $(this).find(".mrauthor").html();
    var play = $(this).children().eq(3).val();
    $(".detail").fadeIn(300);
    $(".detail").css("display", "flex");
    $(".detail .quiztitle").html(titles);
    $(".detail img").attr("src", image);
    $(".detail .authorr").html(author);
    $(".detail .plays").html(play + " Plays");
    $(".detail .quizcodee").html(
      "QuizCode : " +
        "<span id='quizCode'>" +
        quizcode +
        "</span>" +
        " <i class='bi bi-copy' id='copyIcon'></i>"
    );
    $(".detail .numofques").html(
      numofques + " " + (numofques <= 1 ? "Question" : "Questions")
    );
    $(".detail .edit").click(function () {
      var url = "edit.php?quizid=" + quizid;
      window.location.href = url;
    });
    $(".detail .delques").click(function () {
      Swal.fire({
        title: "Delete Quiz!",
        text: "Do you want to delete this quiz?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        confirmButtonColor: "red",
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            type: "POST",
            url: "delete_quiz.php",
            data: {
              quizId: quizid,
            },
            success: function (response) {
              Swal.fire({
                title: "Deleted Successfully!",
                icon: "success",
                timer: 1000,
                showConfirmButton: false,
              }).then(() => {
                location.reload();
              });
            },
            error: function (xhr, status, error) {
              console.error(error);
            },
          });
        } else {
          // Perform any alternative action or do nothing
        }
      });
    });
    $(".close").click(function () {
      $(".detail").fadeOut(300);
    });
    function showToast(message) {
      const toast = document.getElementById("toast");
      const toastMessage = document.getElementById("toastMessage");
      toastMessage.innerText = message;
      toast.classList.add("show");
      setTimeout(() => {
        toast.classList.remove("show");
      }, 1800);
    }

    document.getElementById("copyIcon").addEventListener("click", () => {
      const quizCode = document.getElementById("quizCode").innerText;
      navigator.clipboard
        .writeText(quizCode)
        .then(() => {
          showToast("Quiz code copied!");
        })
        .catch(() => {
          showToast("Failed to copy");
        });
    });
  });
});
