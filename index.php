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

$content = array('chat_id' => '@Rmn98', 'user_id' => $user_id);
$join_info = $telegram->getChatMember($content);

$join_check = $join_info['ok'];
$join_status = $join_info['result']['status']; // Value => member || left

if(!$join_check || $join_status == 'left') {

    $option = array(
        array(
            $telegram->buildInlineKeyBoardButton(" عضویت در کانال ", $url="https://t.me/joinchat/UNWSodg8AsF4fA1U")
            ),
        array(
            $telegram->buildInlineKeyBoardButton(" عضو شدم ", $url="/start")
            )
        );
        $keyb = $telegram->buildInlineKeyBoard($option);

    $join_content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => 'کاربر عزیز شما عضو کانال ما نیستید و امکان استفاده از ربات را ندارید ⚠️
 
    ⭕️ لطفا در کانال زیر عضو شوید :
    
    🆔 @Rmn98
    
    سپس به ربات برگشته و مجدد امتحان کنید ✔️');

    $telegram->sendMessage($join_content);
}
else {
if(!is_null($text) && !is_null($chat_id)) {
    if($text == 'اطلاعات') {
        $content = array('chat_id' => $chat_id, 'text' => "متن ارسال شما: $text
                            نام کاربری شما: $username
                            *نام شما:* $name
                            فامیلی شما: $family
                            آیدی پیام: $message_id
                            آیدی شما: $user_id
                            آیدی مکان چت (بات یا گروه): $chat_id", 'parse_mode' => Markdown);
        $telegram->sendMessage($content);
                }
    }
}