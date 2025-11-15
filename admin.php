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
$currentPage = "admin.php";
include_once "dashboard.php";
?>
<script>
    $(document).ready(function() {
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#myTable .meow").filter(function() {
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
    });
</script>
<style>
    .users img {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 3px #CE0037 solid;
    }

    .users .name {
        font-size: 20px;
        margin-top: 10px;
        text-align: start;
        font-weight: 700;
    }

    .users .totalquiz {
        font-size: 15px;
        text-align: start;
    }

    .users {
        margin: 10px;
        padding: 20px;
        box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;
        border-radius: 15px;
    }

    .useramount {
        padding: 30px;
        padding-bottom: 10px;
    }

    .diable {
        background-color: #F0F34C;
        margin-right: 15px;
        padding: 5px 10px;
        border-radius: 30px;
        border: none;
        box-shadow: 0 0 0.3rem black;
        font-weight: 700;
        transition: 0.3s;
    }

    .diable:hover {
        background-color: black;
        color: #F0F34C;
        transition: 0.3s;
    }

    .delete {
        background-color: #CE0037;
        color: white;
        font-weight: 700;
        padding: 5px 10px;
        border: none;
        border-radius: 30px;
        box-shadow: 0 0 0.3rem black;
        transition: 0.3s;
    }

    .delete:hover {
        background-color: white;
        color: #CE0037;
        transition: 0.3s;
    }

    .disabled {
        background-color: greenyellow;
        color: black;
    }

    .disabled:hover {
        background-color: greenyellow;
        color: black;
    }

    .search {
        padding-bottom: 10px;
        padding-left: 40px;
    }

    .search input {
        width: 40%;
        background-color: #D9D9D9;
        padding: 10px 20px;
        border: none;
        border-radius: 10px;
        font-size: 20px;
    }

    @media screen and (max-width:670px) {

        .search input {
            width: 80%;
        }
    }

    @media screen and (max-width:1000px) {

        .search input {
            width: 80%;
        }
    }

    .no-results {
        display: none;
        color: red;
    }
</style>

<div class="container josefin-sans">
    <div class="row" id="myTable">
        <?php
        $countuser = "SELECT COUNT(*) AS user_count FROM UserAccount where Role = 'User'";
        $getuseramount = mysqli_query($conn, $countuser);
        if ($getuseramount) {
            $rows = mysqli_fetch_assoc($getuseramount);
            $user_count = $rows['user_count'];
            echo "<div class='col-12 useramount'><h2>Total Users : $user_count</h2></div>";
        }
        ?>
        <div class="col-12 search">
            <input id="myInput" type="text" placeholder="Search..">
        </div>
        <div class="no-results p-3 ps-5">
            <h3>No results found for "<span id="searchTerm"></span>"</h3>
        </div>
        <?php
        $getuser = "SELECT * FROM UserAccount where Role != 'Admin' and Role !='Guest'";
        $result = mysqli_query($conn, $getuser);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $userid = $row['UserID'];
                $countquiz = "SELECT COUNT(*) AS quiz_count FROM Quiz WHERE CreatorID = $userid";
                $getquizamount = mysqli_query($conn, $countquiz);
                if ($getquizamount) {
                    $rows = mysqli_fetch_assoc($getquizamount);
                    $quiz_count = $rows['quiz_count'];
                }
                $username = $row['Username'];
                $profile = $row['Profile'];
                $email = $row['Email'];
                $status = $row['Status'];
                echo "<div class='col-xxl-3 col-lg-4 col-md-6 col-12 text-center meow'><div class='users'>
                <img src='$profile'>
                <h2 class='name'>$username</h2>
                <h2 class='totalquiz'>$email</h2>
                <h2 class='totalquiz'>Quiz created: $quiz_count</h2>
                <input type='text' style='display:none;' name='userid' value='$userid'>
                 <button class='diable $status disableButton'>Disable User</button><button class='delete'>Delete User</button>
                </div>
                </div>";
            }
        }
        if ($user_count <= 0) {
            echo "<div class='col-12 p-3'><h3>No user found !</h3></div>";
        }
        ?>
    </div>
</div>
<script>
    $(document).ready(function() {
        const buttons = document.getElementsByClassName("disableButton");
        for (let i = 0; i < buttons.length; i++) {
            const button = buttons[i];
            if (button.classList.contains("disabled")) {
                button.textContent = "Enable User";
            }
        }
        $('.delete').click(function() {
            var userid = $(this).siblings().eq(4).val();
            Swal.fire({
                title: 'Delete User!',
                text: 'Do you want to delete this user?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                confirmButtonColor: 'red'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'delete_user.php',
                        data: {
                            userid: userid
                        },
                        success: function(response) {
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                } 
            });
        })
        $('.diable').click(function() {
            var userid = $(this).siblings().eq(4).val();
            console.log(userid);
            $.ajax({
                url: 'disableuser.php',
                method: 'GET',
                data: {
                    userid: userid
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('An error occurred while fetching questions. Please try again later.');
                }
            });
        })
    })
</script>