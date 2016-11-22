<?php

if(php_sapi_name() === 'cli'){
    $sql = "ALTER table users ADD phone_confirm TINYINT(1) AFTER phone;";

    if ($conn->multi_query($sql) === TRUE) {
        echo "Update success";
    } else {
        echo "Fail";
    }
}