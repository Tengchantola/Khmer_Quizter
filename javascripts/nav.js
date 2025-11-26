$(document).ready(function () {
  $(".logo").click(function () {
    window.location = "home.php";
  });
  $(".homepage").click(function () {
    window.location = "home.php";
  });
  $(".activity").click(function () {
    window.location = "activity.php";
  });
  $(".findquiz").click(function () {
    window.location = "findquiz.php";
  });
  $(".myquiz").click(function () {
    window.location = "myquiz.php";
  });
  $(".dropdown").click(function () {
    $(".dropdown-menu").toggle();
  });
  // $(".menu").mouseleave(function () {
  //   $(".dropdown-menu").hide();
  // });
  $(".settingg").click(function () {
    window.location = "change.php";
  });
  document.getElementById("logout").addEventListener("click", function () {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "logout.php", true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        console.log(xhr.responseText);
        window.location.href = "index.php";
      }
    };
    xhr.send();
  });
});
