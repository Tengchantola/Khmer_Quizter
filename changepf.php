<?php
include "database/database.php";
session_start();
if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit;
}
if ($_SESSION['Role'] === "Admin") {
    header("Location: admin.php");
    exit;
}
$currentPage = 'home.php';
include_once 'nav.php';
?>
<link rel="stylesheet" href="styles/avatar.css">
<div class="container welcome mt-5">
    <div class="row">
        <div class="col-xxl-2 col-12 text-start">
            <button class="back"><i class="bi bi-backspace-fill"></i> Back</button>
        </div>
        <div class="col-xxl-8 col-12 text-center imageicon josefin-sans">
            <img src="<?php echo $_SESSION['Profile'] ?>" class="img-fluid" style="width: 170px; height: 170px; object-fit: cover; border-radius: 50%; border: 4px solid #ff0000ff;">
            <h1 class="mt-2">Your current Avatar</h1>
            <h2>Choose your new Avatar</h2>
        </div>
    </div>
    <div class="row">
        <?php
        $icon = array(
            "Naruto" => "https://i.postimg.cc/B6CCGGN9/9adf46e3c4aba54e5381d5f6f10b69c5.jpg",
            "Goku" => "https://i.postimg.cc/8c0M1k6b/4cec13a6450152d07c148abb7c5dc79c.jpg",
            "Luffy" => "https://i.postimg.cc/8zn3qLcL/3e7b76dbafbc491605e5b1fccd3ed7b3.jpg",
            "Saitama" => "https://i.postimg.cc/vZWgKt3k/Saitama.jpg",
            "Levi" => "https://i.postimg.cc/RVtN3T5J/Levi.jpg",
            "Eren Yeager" => "https://i.postimg.cc/Mp8mPDy8/Eren-Yeager.jpg",
            "Light Yagami" => "https://i.postimg.cc/rp1bZs08/Light-Yagami.jpg",
            "Pain" => "https://i.postimg.cc/TPDkFSZX/b965790f246ad40f21d3a275bb4cad35.jpg",
            "Spike Spiegel" => "https://i.postimg.cc/MpKvpfwR/Spike-Spiegel.jpg",
            "Edward Elric" => "https://i.postimg.cc/nhPcVd0x/Edward-Elric.jpg",
            "Killua" => "https://i.postimg.cc/kX5mVQVB/Killua.jpg",
            "Gon" => "https://i.postimg.cc/HnRBk1cz/Gon.jpg",
            "Vegeta" => "https://i.postimg.cc/vBmz7Z5f/Vegeta.jpg",
            "Sasuke" => "https://i.postimg.cc/R0S889c0/Sasuke.jpg",
            "Kakashi" => "https://i.postimg.cc/bw8FD8VT/Kakashi.jpg",
            "Itachi" => "https://i.postimg.cc/vH70dk8t/Itachi.jpg",
            "Zoro" => "https://i.postimg.cc/7ZsMHyFL/Zoro.jpg",
            "Sanji" => "https://i.postimg.cc/bvSbqSHj/Sanji.jpg",
            "Ichigo" => "https://i.postimg.cc/NM7K06yw/Ichigo.jpg",
            "Rukia" => "https://i.postimg.cc/prSRVrJf/Rukia.jpg",
            "Lelouch" => "https://i.postimg.cc/2SGm30Gh/Lelouch.jpg",
            "L" => "https://i.postimg.cc/W4xy4ZBS/L.jpg",
            "Mikasa" => "https://i.postimg.cc/bNLFt4Xw/Mikasa.jpg",
            "Armin" => "https://i.postimg.cc/90b1ywnd/Armin.jpg",
            "Tanjiro" => "https://i.postimg.cc/Xv58QzP9/Tanjiro.jpg",
            "Nezuko" => "https://i.postimg.cc/2Ssdbx5f/Nezuko.jpg",
            "Zenitsu" => "https://i.postimg.cc/m2qCJSCC/Zenitsu.jpg"
        );
        foreach ($icon as $key => $value) {
            echo "<div class='col-xxl-2 col-lg-3 col-md-4 col-6 imageicon text-center mt-3'> 
                    <form action='changepf.php' method='post'>
                        <input type='hidden' name='agent_name' value='" . $value . "'>
                        <img src='" . $value . "' class='img-fluid' style='width: 140px; height: 140px; object-fit: cover;'><br>
                        <input type='submit' value='" . $key . "' class='selectagent mt-3 josefin-sans'>
                    </form>
                </div>";
        }
        ?>
    </div>
</div>
<script src="javascripts/avatar.js"></script>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['agent_name'])) {
        $agentName = $_POST['agent_name'];
        $updateQuery = "UPDATE UserAccount SET Profile = '$agentName' WHERE UserID = '{$_SESSION['UserID']}'";

        if ($conn->query($updateQuery) === TRUE) {
            $_SESSION['Profile'] = $agentName;
            echo "<script>window.location.reload(); window.location.href = 'home.php';</script>";
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "Agent name is not received.";
    }
} else {
}
$conn->close();
?>