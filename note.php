<?php
// DATABASE CONNECTION
include 'config.php';
session_start();

if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // SHOW THE FIRST NAME OF THE USER BASE ON WHO LOGIN
    $query1 = "SELECT firstName, email FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query1);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $firstName = $row['firstName'];
        $email = $row['email'];
    }

    // SHOW NOTES/DATA OF USERS
    $query2 = "SELECT * FROM notess WHERE user_id = ?";
    $stmt2 = $conn->prepare($query2);
    $stmt2->bind_param("i", $user_id);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    $notes = array();

    if($result2->num_rows > 0) {
        while ($row = $result2->fetch_assoc()) {
            $notes[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Techtilanotes</title>
    <link rel="icon" type="image/x=icon" href="./assets/ibon.png">
    <!-- STYLE CSS -->
    <link rel="stylesheet" href="./styles/note.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- SCRIPT FOR LOGOUT -->
    <script src="./script/window.js"></script>
    <!-- LINK FOR ICONS -->
    <script src="https://kit.fontawesome.com/535a9c8dda.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
</head>
<body>

    <!-- SIDE NAV BAR -->
    <nav class="fix-position">
        <ul>
            <li>
                <a href="" class="user">
                    <img src="./assets/ibon.png" alt="" class="logo">
                    <div>
                        <p class="bold"><?php echo $firstName; ?></p>
                        <p><?php echo $email; ?></p>
                    </div>
                </a>
            </li>
            <li class="menu">
                <a href="logout.php" class="logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span class="nav-item">Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- NOTES -->
    <!-- IF ADD BUTTON CLICKED THE FORM WILL SHOW -->
    <div class="popup-box">
        <div class="popup">
            <div class="content">
                <header>
                    <p></p>
                    <i class="uil uil-times"></i>
                </header>
                <!-- ADD AND EDIT FORM -->
                <form id="noteForm" action="" method="POST">
                    <div class="row title">
                        <label>Title</label>
                        <input type="text" spellcheck="false" name="title" id="title">
                    </div>
                    <div class="row description">
                        <label>Notes</label>
                        <textarea spellcheck="false" name="content" id="content"></textarea>
                    </div>
                    <!-- BUTTON FOR ADD AND UPDATE-->
                    <input type="hidden" name="note_id" id="note_id" >
                    <input id="add_update" type="submit" value="" name="">
                </form>
            </div>
        </div>
    </div>
    <div class="wrapper">

        <!-- ADD BUTTON -->
        <li class="add-box">
            <div class="chicken">
                <div class="comb1"></div>
                <div class="comb2"></div>
                <div class="eye"></div>
                <div class="beak"></div>
                <div class="wattle"></div>
            </div>
            <p>Add new note</p>
        </li>

        <!-- SHOW ALL THE DATA/NOTE OF THE USER -->
        <?php foreach ($notes as $note) : ?>
            <li class="note">

                <!-- CLICK THE NOTE TO EDIT -->
                <div class="note-content" onclick="updateNote(<?php echo $note['note_id']; ?>, '<?php echo $note['title']; ?>', '<?php echo htmlspecialchars(nl2br($note['content']) ? str_replace(array("\r\n"), '<br/>', addslashes($note['content'])) : '', ENT_QUOTES); ?>')">
                    <div class="details">
                        <p><?php echo $note['title']; ?></p>
                        <span><?php echo nl2br($note['content']); ?></span>
                    </div>
                </div>
                <div class="bottom-content">
                    <span><?php echo $note['currentDate']; ?></span>
                    <div class="settings">
                        <i onclick="showMenu(this)" class="fa-solid fa-feather-pointed"></i>
                        <ul class="menu">
                            
                            <!-- DELETE BUTTON -->
                            <form action="./query/delete.php" method="POST">
                                <li>
                                    <i class="fa-regular fa-trash-can"></i>
                                    <input type="hidden" name="note_id" value="<?php echo $note['note_id']; ?>">
                                    <input type="submit" name="delete" value="delete">
                                </li>
                            </form>
                        </ul>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </div>

    <script src="./script/script.js"></script>

</body>
</html>