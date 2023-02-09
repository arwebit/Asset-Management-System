<?php

include './api/common/global_functions.php';
include './api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {    
    $input_data[] = array("session_token" => $_SESSION['asset_token'], "session_user" => $_SESSION['asset_member']);
    $recv_data = json_encode($input_data);
    $getData = json_decode(callAPI($recv_data, $logout_access_api));
    if ($getData->message == "Success") {
        unset($_SESSION['asset_member']);
        unset($_SESSION['asset_token']);

        if (isset($_SERVER['HTTP_REFERER'])) {
            ?>
            <script type="text/javascript">
                window.location.href = "index.html";
            </script>
            <?php

        } else {
            ?>
            <script type="text/javascript">
                window.location.href = "index.html";
            </script>
            <?php

        }
        exit;
    } else if ($getData->message == "Failed") {
        unset($_SESSION['asset_member']);
        unset($_SESSION['asset_token']);

        if (isset($_SERVER['HTTP_REFERER'])) {
            ?>
            <script type="text/javascript">
                window.location.href = "index.html";
            </script>
            <?php

        } else {
            ?>
            <script type="text/javascript">
                window.location.href = "index.html";
            </script>
            <?php

        }
        exit;
    }else{
         unset($_SESSION['asset_member']);
        unset($_SESSION['asset_token']);
        ?>
            <script type="text/javascript">
                window.location.href = "index.html";
            </script>
            <?php
    }
}
?>