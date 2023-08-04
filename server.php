<?php
include 'dbconfig.php';

    $sql = "SELECT * FROM keyboard_clicks WHERE click_time >= NOW() - INTERVAL 120 SECOND and is_delete = '0'";
    $query = mysqli_query($connection, $sql);

    if ($query) {
        $data = array();

        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
    
        print_r(json_encode($data));

    } else {
        echo "Error executing the query: " . mysqli_error($connection);
    }
    mysqli_close($connection);


?>