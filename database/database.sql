
CREATE DATABASE messagebird;

USE messagebird;

CREATE TABLE message_queues (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    cellphone varchar(20) NOT NULL,
    message text NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, 
    executed_at DATETIME ON UPDATE CURRENT_TIMESTAMP, 
    is_executed boolean DEFAULT false NOT NULL,
    ip_address varchar(20),
    PRIMARY KEY (id)
);
