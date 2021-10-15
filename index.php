<?php
include 'Telegram.php';

// Set the bot TOKEN
$bot_id = "2088394503:AAE-lF3-HYFf4FZH5GVlsJvg7j_6C3jiAoU";
// Instances the class
$telegram = new Telegram($bot_id);

$text = $telegram->Text(); // متنی که کاربر ارسال میکنه
$username = $telegram->Username(); // نام کاربری کاربر
$name = $telegram->FirstName();
$family = $telegram->LastName();
$message_id = $telegram->MessageID(); // هر پیغام در تلگرام یک آیدی یکتا دارد
$user_id = $telegram->UserID(); // آیدی یکتای کاربر
$chat_id = $telegram->ChatID(); // آیدی مکانی که چت صورت میگیرد، مثل خود بات یا آیدی گروه

if(!is_null($text) && !is_null($chat_id)){
    if($text == 'اطلاعات') {
        $content = array('chat_id' => $chat_id, 'text' => "'متن ارسال شما: '$text
                            'نام کاربری شما: '$username
                            'نام شما: '$name
                            'فامیلی شما: '$family
                            'آیدی پیام: '$message_id
                            'آیدی شما: '$user_id
                            'آیدی مکان چت (بات یا گروه): '$chat_id");
        $telegram->sendMessage($content);
                }
    if($text == 'info') {
        $content = array('chat_id' => $chat_id, 'text' => 'متن ارسال شما:'.$text.
                            'نام کاربری شما: '.$username.
                            'نام شما: '.$name.
                            'فامیلی شما: '.$family.
                            'آیدی پیام: '.$message_id.
                            'آیدی شما: '.$user_id.
                            'آیدی مکان چت (بات یا گروه): '.$chat_id);
        $telegram->sendMessage($content);
                }
    }