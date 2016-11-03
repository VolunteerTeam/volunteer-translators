<?php

if(php_sapi_name() === 'cli'){
    $sql = "CREATE TABLE IF NOT EXISTS test (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            firstname VARCHAR(30) NOT NULL,
            lastname VARCHAR(30) NOT NULL,
            email VARCHAR(50),
            reg_date TIMESTAMP
            )";

    if ($conn->query($sql) === TRUE) {
        echo "Table test created successfully";
    } else {
        $conn->query("DROP TABLE IF EXISTS test");
        echo "undo changes";
    }
}