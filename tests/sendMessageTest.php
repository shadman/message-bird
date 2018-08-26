<?php

require_once __DIR__.'/../services/message.php';

use PHPUnit\Framework\TestCase;

final class sendMessageTest extends TestCase
{

    /* 
    * Test short message
    */
    public function test_ThatSimpleMessageQueued()
    {
        $message = array (
            "country_code" => "0092",
            "cellphone_number" => "3213674902",
            "message" => "This is test message",
        );

        $messageQueue = new Message;
        $response = $messageQueue->send($message, 1);

        $this->assertThat($response, $this->equalTo('sent'));
    }


    /* 
    * Test long message
    */
    public function test_ThatLongMessageQueued()
    {
        $message = array (
            "country_code" => "0092",
            "cellphone_number" => "3213674902",
            "message" => "This is test message This is test message This is test message This is test message This is test message This is test message This is test message This is test message This is test messageThis is test message This is test message ",
        );

        $messageQueue = new Message;
        $response = $messageQueue->send($message, 1);

        $this->assertThat($response, $this->equalTo('sent'));
    }


    /* 
    * Test Validation with wrong number
    */
    public function test_ThatWrongNumber()
    {
        $message = array (
            "country_code" => "0092",
            "cellphone_number" => "321367",
            "message" => "This is test message ",
        );

        $messageQueue = new Message;
        $response = $messageQueue->send($message, 1);

        $this->assertThat($response, $this->equalTo('Invalid cellphone number found'));
    }


    /* 
    * Test Validation with wrong message
    */
    public function test_ThatWrongMessage()
    {
        $message = array (
            "country_code" => "0092",
            "cellphone_number" => "3213674902",
            "message" => "This ",
        );

        $messageQueue = new Message;
        $response = $messageQueue->send($message, 1);

        $this->assertThat($response, $this->equalTo('Invalid message found. Message length should be in between 10 and 320'));
    }

}
