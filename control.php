<?php
function acquireControl($user) {

}

function releaseControl($user) {

}


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    print_r($_POST);

    if (isset($_POST["user"]) && is_numeric($_POST["user"])) {
        $user = intval($_POST["user"]);
        
        acquireControl($user);
        echo json_encode(['status' => 'success']);

        if(isset($_POST["type"])){       
            if($_POST["type"] === 'insert' ){
                insert_rec($_POST);
                // update($_POST);
            }
            
        }
        
    } else {
        
        echo json_encode(['status' => 'error', 'message' => 'Invalid user parameter']);
    }
}


if ($_SERVER["REQUEST_METHOD"] === "GET") {

    if (isset($_GET["user"]) && is_numeric($_GET["user"])) {
        $user = intval($_GET["user"]);


        releaseControl($user);


        echo json_encode(['status' => 'success']);
    } else {

        echo json_encode(['status' => 'error', 'message' => 'Invalid user parameter']);
    }
}

function insert_rec($data)
{
    include 'dbconfig.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['type'] == 'insert') {
        $userid = $_POST['user']; 
        $color = $_POST['color'];
        $key_Number = $_POST['keyNumber'];
        $sql = "INSERT INTO keyboard_clicks (id, user_id, key_number, color, click_time) VALUES ('', '$userid', '$key_Number', '$color', current_timestamp())";
       
        if (mysqli_query($connection, $sql)) {
            echo "Data inserted successfully!";
        } else {
            echo "Error inserting data into the database!";
        }
    }
    mysqli_close($connection);
}
function update($data){
    include 'dbconfig.php';
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $key_number = isset($_POST['keyNumber']) ? intval($_POST['keyNumber']) : 0;

    $sql = "SELECT * FROM keyboard_clicks WHERE key_number = $key_number AND click_time >= NOW() - INTERVAL 120 SECOND";
    $result = mysqli_query($connection, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    print_r(json_encode($data));

    if ($result && mysqli_num_rows($result) > 0) {
        $updateSql = "UPDATE keyboard_clicks SET is_delete = CASE WHEN is_delete = 1 THEN 0 WHEN is_delete = 0 THEN 1 END WHERE id IN ( SELECT id FROM keyboard_clicks WHERE key_number = $key_number AND click_time >= NOW() - INTERVAL 120 SECOND );";
        $updateResult = mysqli_query($connection, $updateSql);
    } else {
        echo "Record not found.";
    }
    mysqli_close($connection);
}
?>