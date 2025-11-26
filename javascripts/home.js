$(document).ready(function () {
  // Show notification toast
  function showToast(message) {
    const toast = $("#notificationToast");
    $("#toastMessage").text(message);
    toast.addClass("show");
    setTimeout(() => toast.removeClass("show"), 3000);
  }

  // Change profile picture
  $("#changepf").click(function () {
    window.location = "changepf.php";
  });

  // Create quiz
  $(".btn-create-quiz").click(function () {
    $.ajax({
      type: "GET",
      url: "check_role.php",
      success: function (response) {
        if (response === "Guest") {
          Swal.fire({
            title: "Sign In Required",
            text: "You need to sign in to create a quiz",
            icon: "info",
            showCancelButton: true,
            confirmButtonText: "Sign In",
            cancelButtonText: "Cancel",
            confirmButtonColor: "#CE0037",
            cancelButtonColor: "#6B7280",
          }).then((result) => {
            if (result.value) {
              $.post("logout.php", function () {
                window.location.href = "index.php";
              });
            }
          });
        } else {
          window.location = "create.php";
        }
      },
    });
  });

  // Join quiz
  $("#joinBtn").click(function () {
    const quizCode = $("#quizCodeInput").val().trim();
    if (quizCode) {
      $.get("check_quiz_code.php?code=" + quizCode, function (response) {
        if (response === "not_exists") {
          Swal.fire({
            title: "Quiz Not Found!",
            text: "The quiz code doesn't exist. Please check and try again.",
            icon: "error",
            confirmButtonText: "OK",
            confirmButtonColor: "#CE0037",
          });
        } else if (response === "error" || response === "missing_code") {
          Swal.fire({
            title: "Error",
            text: "Please enter a valid quiz code.",
            icon: "error",
            confirmButtonText: "OK",
            confirmButtonColor: "#CE0037",
          });
        } else {
          window.location.href = "play.php?quizid=" + response;
        }
      });
    } else {
      Swal.fire({
        title: "Invalid Code",
        text: "Please enter a quiz code.",
        icon: "warning",
        confirmButtonText: "OK",
        confirmButtonColor: "#CE0037",
      });
    }
  });

  // Enter key to join
  $("#quizCodeInput").keypress(function (e) {
    if (e.which === 13) {
      $("#joinBtn").click();
    }
  });

  // Quiz card click - open modal
  let currentQuizId = "";
  $(".quiz-card").click(function () {
    const quizCode = $(this).data("quizcode");
    const quizId = $(this).data("quizid");
    const questions = $(this).data("questions");
    const plays = $(this).data("plays");
    const title = $(this).data("title");
    const author = $(this).data("author");
    const image = $(this).data("image");

    currentQuizId = quizId;

    $("#modalTitle").text(title);
    $("#modalAuthor").html(
      `<i class="bi bi-person-circle"></i><span>By ${author}</span>`
    );
    $("#modalPlays").html(
      `<i class="bi bi-play-circle"></i><span>${plays} ${
        plays <= 1 ? "Play" : "Plays"
      }</span>`
    );
    $("#modalQuestions").html(
      `<i class="bi bi-question-circle"></i><span>${questions} ${
        questions <= 1 ? "Question" : "Questions"
      }</span>`
    );
    $("#modalQuizCode").text(quizCode);
    $("#modalImage").attr("src", image);
    $("#playBtn").attr("data-quizid", quizId);
    $("#leaderboardBtn").attr("data-quizid", quizId);

    $("#quizModal").css("display", "flex").hide().fadeIn(300);
  });

  // Close modal
  $("#closeModal").click(function () {
    $("#quizModal").fadeOut(300);
  });

  // Close when clicking outside the modal content (on backdrop)
  $("#quizModal").click(function (e) {
    if (e.target === this) {
      $("#quizModal").fadeOut(300);
    }
  });

  // Copy quiz code
  $("#copyCodeBtn").click(function () {
    const quizCode = $("#modalQuizCode").text();
    navigator.clipboard.writeText(quizCode).then(function () {
      showToast("Quiz code copied to clipboard!");
      $("#copyCodeBtn").html('<i class="bi bi-check"></i>Copied!');
      setTimeout(() => {
        $("#copyCodeBtn").html('<i class="bi bi-clipboard"></i>Copy');
      }, 2000);
    });
  });

  // Play button
  $("#playBtn").click(function () {
    const quizId = $(this).attr("data-quizid");
    window.location.href = "play.php?quizid=" + quizId;
  });

  // Leaderboard button
  $("#leaderboardBtn").click(function () {
    const quizId = $(this).attr("data-quizid");
    window.location.href = "leaderboard.php?scorequiz=" + quizId;
  });

  // Lazy load sections on scroll
  let currentIndex = 0;
  const sections = $(".show");

  // Show first section immediately
  if (sections.length > 0) {
    sections.eq(0).show().addClass("visible");
    currentIndex = 1;
  }

  $(window).scroll(function () {
    const windowHeight = $(window).height();
    const documentHeight = $(document).height();
    const scrollTop = $(window).scrollTop();

    if (scrollTop + windowHeight >= documentHeight - 500) {
      if (currentIndex < sections.length) {
        sections.eq(currentIndex).show().addClass("visible");
        currentIndex++;
      }
    }
  });

  // Add smooth scroll behavior
  $("html").css("scroll-behavior", "smooth");

  // Add loading animation to cards
  $(".quiz-card").each(function (index) {
    $(this).css({
      "animation-delay": (index % 4) * 0.1 + "s",
      animation: "fadeInUp 0.6s ease forwards",
    });
  });
});
