<?php

if(php_sapi_name() === 'cli'){
    $sql = "ALTER table users ADD provider SMALLINT(2) UNSIGNED;
            ALTER table users ADD social_id VARCHAR(128);
            CREATE TABLE IF NOT EXISTS providers (
                id SMALLINT(2) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(30) NOT NULL
            ) ENGINE=InnoDB;
            INSERT INTO providers(name) VALUES ('vk'),('fb'),('ok'),('gp');
            ALTER TABLE users ADD INDEX provider (provider);
            ALTER TABLE providers ADD INDEX id (id);
            ALTER table users ADD CONSTRAINT fk_provider FOREIGN KEY (provider) REFERENCES providers(id) ON UPDATE CASCADE
          ON DELETE RESTRICT;
            ALTER TABLE users MODIFY email VARCHAR(100) DEFAULT NULL;";

    if ($conn->multi_query($sql) === TRUE) {
        echo "Update success";
    } else {
        echo "Fail";
    }
}