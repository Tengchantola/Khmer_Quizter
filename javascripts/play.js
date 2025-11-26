var currentQuestionIndex = 0;
var score = 0;
var questions;
var timer;

$(document).ready(function () {
  initializeEventListeners();
});

function initializeEventListeners() {
  $(".button button").click(function () {
    window.location = "home.php";
  });

  $("#startButton").click(function () {
    score = 0;
    // enterFullscreen();
    $(".maining").hide();
    $(".showing").show();
    startQuiz();
  });
}

function enterFullscreen() {
  if (document.fullscreenEnabled) {
    var element = document.documentElement;
    if (element.requestFullscreen) {
      element.requestFullscreen();
    } else if (element.mozRequestFullScreen) {
      element.mozRequestFullScreen();
    } else if (element.webkitRequestFullscreen) {
      element.webkitRequestFullscreen();
    } else if (element.msRequestFullscreen) {
      element.msRequestFullscreen();
    }
  }
}

function startQuiz() {
  $.ajax({
    url: "fetch_question.php",
    method: "GET",
    data: {
      quizid: quizid,
    },
    success: function (response) {
      questions = JSON.parse(response);
      currentQuestionIndex = 0;
      displayQuestion(currentQuestionIndex);
      startTimer(questions[currentQuestionIndex].duration);
    },
    error: function (xhr, status, error) {
      alert(
        "An error occurred while fetching questions. Please try again later."
      );
    },
  });
}

function startTimer(time) {
  var correctAnswer = $("#answerContainer").find('[data-answer="1"]');
  var timeLeft = time;
  clearInterval(timer);
  timer = setInterval(function () {
    $(".timer").text("Time left: " + timeLeft + " seconds");
    timeLeft--;
    if (timeLeft < 0) {
      correctAnswer.addClass("button-trov");
      clearInterval(timer);
      setTimeout(function () {
        displayNextQuestion();
      }, 2000);
    }
  }, 1000);
}

function displayNextQuestion() {
  currentQuestionIndex++;
  if (currentQuestionIndex < questions.length) {
    displayQuestion(currentQuestionIndex);
    startTimer(questions[currentQuestionIndex].duration);
  } else {
    endQuiz();
  }
}

function displayQuestion(index) {
  var question = questions[index];
  var totalQuestions = questions.length;
  var currentQuestionNumber = index + 1;
  $("#questionNumber").text(
    "Question " + currentQuestionNumber + " of " + totalQuestions
  );
  $("#questionContainer").html(question.question_text);
  $("#answerContainer").html("");
  question.answers.forEach(function (answer) {
    $("#answerContainer").append(
      "<div class='col-xxl-3 col-lg-4 col-md-6 col-12 text-center answerbutton' data-answer='" +
        answer.iscorrect +
        "'><button>" +
        answer.answer_text +
        "</button></div>"
    );
  });
  checkAnswer();
}

function checkAnswer() {
  $(".answerbutton")
    .off("click")
    .on("click", function () {
      var selectedAnswer = $(this).data("answer");
      var correctAnswer = $("#answerContainer").find('[data-answer="1"]');
      var clickedAnswer = $(this);
      clearInterval(timer);
      if (selectedAnswer === 1) {
        clickedAnswer.addClass("button-trov");
        score++;
        setTimeout(function () {
          if (currentQuestionIndex < questions.length) {
            displayNextQuestion();
          }
        }, 2000);
      } else {
        clickedAnswer.addClass("button-khos");
        correctAnswer.addClass("button-trov");
        setTimeout(function () {
          displayNextQuestion();
        }, 2000);
      }
    });
}

function endQuiz() {
  $(".congrats").show();
  $(".showing").hide();
  $(".yourscore").html(
    "Your Score : <span class='text-success'>" +
      score +
      "/" +
      currentQuestionIndex +
      "</span>"
  );

  $.ajax({
    url: "score.php",
    method: "GET",
    data: {
      score: score,
      UserID: userID,
      quizid: quizid,
    },
    success: function (response) {
      console.log("Score saved successfully:", response);
    },
    error: function (xhr, status, error) {
      console.error("Error saving score:", error);
      alert(
        "An error occurred while saving your score. Please try again later."
      );
    },
  });
}
