<?php
include "database/database.php";
session_start();
if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit;
}
if ($_SESSION['Role'] !== "Admin") {
    header("Location: index.php");
    exit;
}
$currentPage = "quizes.php";
include_once "dashboard.php";
$getquizamount = "SELECT COUNT(*) AS quiz_count FROM Quiz ";
$result = mysqli_query($conn, $getquizamount);
if ($result) {
    $rows = mysqli_fetch_assoc($result);
    $quiz_count = $rows['quiz_count'];
}
?>
<div class="container josefin-sans">
    <div class="row">
        <div class="col-12 pt-5">
            <h2>Total Quiz : <?php echo $quiz_count; ?></h2>
        </div>
    </div>
</div>
<style>
    .card {
        width: 100%;
        border-radius: 20px;
    }

    .image-container {
        border-radius: 20px;
        max-width: 310px;
        max-height: 200px;
        min-height: 200px;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .image-container img {
        border-radius: 5px 5px 0 0;
        width: 100%;
        height: 100%;
        display: block;
    }

    .ccc {
        display: none;
    }

    .plays {
        background-color: #FFCEDB;
        color: #CE0037;
        text-align: center;
        width: 40%;
        padding: 5px 10px;
        border-radius: 10px;
    }

    .detail {
        background-color: rgba(37, 37, 37, 0.445);
        position: fixed;
        top: 0;
        height: 100%;
        z-index: 10;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        display: none;

    }

    .carde {
        transition: 0.3s;
        box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;
    }

    .carde:hover {
        box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;
        transform: translateY(-10px);
        transition: 0.3s;
    }

    .detail .image-container {
        max-width: 500px;
    }

    .titlle {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .titlle i {
        font-size: 20px;
        color: red;
    }

    .titlle i:hover {
        color: #CE0037;
    }


    .play {
        padding: 7px 30px;
        border: none;
        font-size: 30px;
        font-weight: 700;
        background-color: #63FF72;
        color: #02760E;
        border-radius: 25px;
        box-shadow: 0 0 0.3rem black;
        transition: 0.3s;
    }

    .rawr {
        justify-content: space-between;
        align-items: center;
    }

    .modals {
        display: none;
        position: fixed;
        z-index: 15;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-contents {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 30%;
        text-align: center;
    }

    .carde:hover {
        cursor: pointer;
    }

    .close {
        text-align: left;
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    #copyIcon:hover {
        cursor: pointer;
    }

    .edit {
        text-align: center;
        margin: 0;
        width: 30%;
        background-color: yellow;
        padding: 5px 10px;
        border-radius: 15px;
        transition: 0.2s;
    }

    .edit:hover {
        cursor: pointer;
        background-color: black;
        color: yellow;
        transition: 0.2s;
    }

    .delques {
        margin: 0;
        background-color: red;
        color: white;
        padding: 5px 15px;
        border-radius: 15px;
        transition: 0.2s;
        box-shadow: 0 0 0.1rem black;

    }

    .delques:hover {
        cursor: pointer;
        background-color: white;
        color: red;
        transition: 0.2s;

    }

    .search {
        padding-bottom: 10px;
        padding-left: 20px;
    }

    .search input {
        width: 40%;
        background-color: #D9D9D9;
        padding: 10px 20px;
        border: none;
        border-radius: 10px;
        font-size: 20px;
    }

    @media screen and (max-width: 670px) {
        .image-container {
            max-width: 500px;
            max-height: 200px;
            min-height: 200px;
            overflow: hidden;
        }

        .search input {
            width: 80%;
        }

    }

    @media screen and (max-width: 1400px) {
        .image-container {
            max-width: 600px;
            max-height: 200px;
            min-height: 200px;
            overflow: hidden;
        }

        .search input {
            width: 80%;
        }
    }

    .no-results {
        display: none;
        color: red;
    }
</style>
<script>
    $(document).ready(function() {
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#myTable .ccc").filter(function() {
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
    });
</script>
<div id="notificationModal" class="modals">
    <div class="modal-contents">
        <span class="close">&times;</span>
        <p id="notificationMessage"></p>
    </div>
</div>
<div class="detail">
    <div class='col-xxl-3 col-lg-4 col-md-6 col-12 text-center'>
        <div class='card card-display'>
            <div class='titlle pt-3 px-3 d-flex'>
                <h5 class='card-title text-start quiztitle josefin-sans'>$Quiztitle</h5>
                <i class="bi bi-x-square-fill josefin-sans close"></i>
            </div>

            <div class='image-container'>
                <img src='uploads/222.jpg' class='images' alt=''>
            </div>
            <div class='card-body contentbody'>
                <div class="col-12 d-flex rawr">
                    <div class='text-start authorr josefin-sans'>By $Author</div>
                    <h6 class='authorname plays'></h6>
                </div>
                <div class="col-12 d-flex rawr">
                    <div class='text-start quizcodee josefin-sans'>By $Author</div>
                    <h6 class='authorname edit'>Edit Quiz</h6>
                </div>
                <div class="col-12 d-flex rawr mt-1">
                    <div class='text-start numofques josefin-sans'></div>
                    <h6 class='authorname delques'>Delete Quiz</h6>
                </div>
                <br><br>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-12 search">
            <input id="myInput" type="text" placeholder="Search..">
        </div>
    </div>
</div>
<div class="container ">
    <div class="no-results p-3">
        <h3>No results found for "<span id="searchTerm"></span>"</h3>
    </div>
    <div class="row" id="myTable">
        <?php
        $displayquestion = "SELECT * FROM Quiz JOIN UserAccount ON Quiz.CreatorID = UserAccount.UserID ";
        $result = mysqli_query($conn, $displayquestion);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $QuizID = $row['QuizID'];
                $Quiztitle = $row['QuizTitle'];
                $QuizCode = $row['QuizCode'];
                $Author = $row['Username'];
                $image = $row['Image'];
                $play = $row['Play'];
                $Type = $row["Type"];
                $getquestionamount = "SELECT QuestionID from QuizQuestion where QuizID = '$QuizID'";
                $resultt = mysqli_query($conn, $getquestionamount);
                if ($resultt) {
                    $numQues = mysqli_num_rows($resultt);
                }
                echo "<div class='col-xxl-3 col-lg-4 col-md-6 mt-3 text-center ccc'>
                 <div class='card carde'>
                 <input type='text' style='display:none' value='$QuizCode'>
                 <input type='text' style='display:none' value='$QuizID'>
                 <input type='text' style='display:none' value='$numQues'>
                 <input type='text' style='display:none' value='$play'>
                  <div class='image-container'>";
                echo " <img src='$image' class='images' alt=''></div>";
                echo " <div class='card-body'>
                <h5 class='card-title text-start quiztitles'>$Quiztitle</h5>
                <h6 class='text-start mrauthor'>Category: $Type</h6>
                <h6 class='text-start mrauthor'>By $Author</h6>
                <h6 class='plays'>$play plays</h6>
            </div></div></div>";
            }
        } else {
            echo "Error: " . mysqli_error($conn);
        }
        mysqli_close($conn);
        ?>
    </div>
</div>
<br><br><br><br><br>
<script>
    $(document).ready(function() {
        $('.ccc').fadeIn(700)
        $('.carde').click(function() {
            var quizcode = $(this).children().eq(0).val();
            var quizid = $(this).children().eq(1).val();
            var numofques = $(this).children().eq(2).val();
            var image = $(this).children().eq(4).children().eq(0).attr("src");
            var titles = $(this).find('.quiztitles').html();
            var author = $(this).find('.mrauthor').html(); // Corrected line
            var play = $(this).children().eq(3).val();
            console.log(quizcode + quizid + image + titles, author);
            $('.detail').fadeIn(300);
            $('.detail').css('display', 'flex')
            $('.detail .quiztitle').html(titles)
            $('.detail img').attr("src", image)
            $('.detail .authorr').html(author)
            $('.detail .plays').html(play + " plays");
            $('.detail .quizcodee').html("QuizCode : " + "<span id='quizCode'>" + quizcode + "</span>" + " <i class='bi bi-copy' id='copyIcon'></i>")
            $('.detail .numofques').html(numofques + " Questions")
            $('.detail .edit').click(function() {
                var url = "edit.php?quizid=" + quizid;
                window.location.href = url;
            })
            $('.detail .delques').click(function() {
                Swal.fire({
                    title: 'Delete Quiz!',
                    text: 'Do you want to delete this quiz?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    confirmButtonColor: 'red'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'delete_quiz.php',
                            data: {
                                quizId: quizid
                            }, 
                            success: function(response) {
                                location.reload();
                            },
                            error: function(xhr, status, error) {
                                console.error(error);
                            }
                        });
                    } else {
                        // Perform any alternative action or do nothing
                    }
                });
            });
            $('.close').click(function() {
                $('.detail').fadeOut(300);
            })
            function displayNotification(message) {
                var modal = document.getElementById("notificationModal");
                var notificationMessage = document.getElementById("notificationMessage");
                notificationMessage.innerHTML = message;
                modal.style.display = "block";
                var closeButton = document.getElementsByClassName("close")[0];
                closeButton.onclick = function() {
                    modal.style.display = "none";
                }
                setTimeout(function() {
                    modal.style.display = "none";
                }, 1000);
            }
            document.getElementById('copyIcon').addEventListener('click', function() {
                var quizCode = document.getElementById('quizCode');
                var range = document.createRange();
                range.selectNode(quizCode);
                window.getSelection().removeAllRanges();
                window.getSelection().addRange(range);
                document.execCommand('copy');
                window.getSelection().removeAllRanges();
                displayNotification("Quiz code copied");
            });
        });
    });
</script>