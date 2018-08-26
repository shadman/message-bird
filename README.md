# Message Bird API Integration

## Prerequisites
- Apache / Nginx
- PHP 7.1 or greater
- MySQL 5.0 or greater


## Deployment Steps
1. Extract all code from ZIP file
2. Paste extracted directory into your Apache root
	ex: `/var/www/html/message-bird/`
3. Create database, with a name of `messagebird`
4. Execute migrations scripts, can find script here: `database/database.sql`
5. Update database configuration and sms service inside: `config/config.php`
6. Now you are ready to use, Message Bird Code Challenge


## Setup Scheduller Job
1. Open your crontab by using below command 

>  crontab -e

2. Add your jobs by adding below line by using example URL: `http://localhost/message-bird/job.php?action=send_message`

>  */5 * * * * /usr/bin/curl "http://localhost/message-bird/job.php?action=send_message" 2>/dev/null

3. Now messages will be sent automatically, from a queue

## Execute Tests:

> ./phpunit.phar tests/


## Other Information:
- Only 10 Messages per IP are allowed, which is configurable from here: `config/config.php`
- We are tracking IP addresses for security and daily limit count.
- We are using Queues to save all message request, without lossing them.
- We are getting max 320 message limit, to make things better and secure.
- In background we are using job, to execute all messages time to time.
- Added 5 phpunit tests, to test all scenarios with including background job.

Cheers !

