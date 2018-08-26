<?php

require_once __DIR__.'/../jobs/sendMessage.php';

use PHPUnit\Framework\TestCase;

final class sendMessageJobTest extends TestCase
{

    /* 
    * Test sending message job
    */
    public function test_ThatMessageJob()
    {
        $message = array (
            "country_code" => "0092",
            "cellphone_number" => "3213674902",
            "message" => "This is test message",
        );

        $messageQueue = new SendMessages;
        $response = $messageQueue->send();

        $this->assertThat($response, $this->equalTo('{"status":"success","message":"Message has been sent successfully."}'));
    }


}
