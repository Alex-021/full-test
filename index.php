<!DOCTYPE html>
<html>
 <head>
  <title>Full Bot</title>
  <style>
    table {
    border-collapse: collapse;
    width: 100%;
    }

    td, th {
    border: 1px solid #505050;
    text-align: center;
    padding: 8px;
    }

    tr:nth-child(even) {
    background-color: #dddddd;
    }
</style>
</head>
 <body>
  <table>
   <thead>
    <tr>
    <th> User Number </th>
     <th> User ID </th>
     <th> Count Message </th>
     <th> Last Message </th>
    </tr>
   </thead>
   <tbody>
<?php
echo "Hello Dear..."."<br>";
$dsn = "pgsql:"
    . "host=ec2-3-221-100-217.compute-1.amazonaws.com;"
    . "dbname=dasaur93oo75cr;"
    . "user=swbcsfjlkdpmxy;"
    . "port=5432;"
    . "sslmode=require;"
    . "password=230f3b3e18c1b36230767101fb25aea119911c36ba6cc2af15b15822225b1e9a";

$db = new PDO($dsn);
echo "DB Status: ";
if ($db) {
    echo "Connected!"."<br>";
} else {
    echo "Faild..."."<br>";
}
$val = 1718192034;
$sq = "SELECT * FROM user_data WHERE userid = $val";
$res = $db->query($sq);
echo "<br>";
$row = $res->fetch(PDO::FETCH_ASSOC);
    $found = $row["userid"];
    echo "ID Status: ";
    if ($found) {
        echo "Found it! : ";
        echo $found . "<br>";
    }
    else {
        echo "Not Found...".$val."<br>";
        $sql = "INSERT INTO user_data (userid, countmsg) VALUES ($val, 5)";
        $n2 = $db->query($sql);
    }
echo "<br>";

$query = "SELECT * FROM user_data;";
$result = $db->query($query);
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>#" . $row["user_number"] . "</td>";
    echo "<td>" . $row["userid"] . "</td>";
    echo "<td>" . $row["countmsg"] . "</td>";
    echo "<td>" . $row["last_msg"] . "</td>";
    echo "</tr>";
}
$res->closeCursor();
$result->closeCursor();
?>
   </tbody>
  </table>
 </body>
</html>
<?php
include 'Telegram.php';

// Set the bot TOKEN
$bot_id = "2088394503:AAGrodGdGqYOua-DoZrl_31AP6ZFSKXPHss";
$admin_id = "271148667";
// Instances the class
$telegram = new Telegram($bot_id);

$text = $telegram->Text(); // متنی که کاربر ارسال میکنه
$username = $telegram->Username(); // نام کاربری کاربر
$name = $telegram->FirstName();
$family = $telegram->LastName();
$message_id = $telegram->MessageID(); // هر پیغام در تلگرام یک آیدی یکتا دارد
$user_id = $telegram->UserID(); // آیدی یکتای کاربر
$chat_id = $telegram->ChatID(); // آیدی مکانی که چت صورت میگیرد، مثل خود بات یا آیدی گروه
// [ عضویت ](https://t.me/joinchat/UNWSodg8AsF4fA1U/)
$from_chat = $telegram->FromChatID();

if (!is_null($text) && !is_null($chat_id)) {
    if ($user_id != $admin_id) {

        $join_channel = array('chat_id' => '@Rmn98', 'user_id' => $user_id);
        $join_info = $telegram->getChatMember($join_channel);
        $join_check = $join_info['ok'];
        $join_status = $join_info['result']['status']; // Value => member || left
        
        // $content = array('chat_id' => 271148667, 'from_chat_id' => $chat_id, 'message_id' => $message_id);
        // $telegram->forwardMessage($content); // TRUE FORWARD Message as a Copy.

        $post = array('chat_id' => 271148667, 'from_chat_id' => $chat_id, 'message_id' => $message_id);
        $telegram->forwardMessage($post); // TRUE FORWARD Message with Quote.

        // $content = array('chat_id' => $chat_id, 'reply_to_message_id' => $message_id, 'text' => "دریافت شد!");
        // $telegram->sendMessage($content); // TRUE Reply to Message users.

        if (!$join_check || $join_status == 'left') {
            $option = array(
                array(
                    $telegram->buildInlineKeyBoardButton(" عضویت در کانال ", $url="https://t.me/joinchat/UNWSodg8AsF4fA1U")
                ),
                array(
                    $telegram->buildInlineKeyBoardButton(" عضو شدم ", "", $callback_data = "/start")
                    )
                );
                $keyb = $telegram->buildInlineKeyBoard($option);

            $join_content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => 'کاربر عزیز شما عضو کانال ما نیستید و امکان استفاده از ربات را ندارید ⚠️
        
            ⭕️ لطفا در کانال زیر عضو شوید :
            
            🆔 @Rmn98
            
            سپس به ربات برگشته و مجدد امتحان کنید ✔️', 'parse_mode' => "Markdown");

            $telegram->sendMessage($join_content);
        }
        elseif ($text == '/start') {

            $option = array(
                array($telegram->buildKeyboardButton("💡 راهنما"),$telegram->buildKeyboardButton("📌 توضیحات"))
            );
            $keyb = $telegram->buildKeyBoard($option, $onetime=true, $resize=true, $selective=true);

            $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "
            *خوش امدید*
            
            ربات شماره مجازی:
            ابتدا توضیحات و راهنما را مطالعه کنید.

            ", 'parse_mode' => "Markdown");
            $telegram->sendMessage($content);
        }
        elseif ($text == '📌 توضیحات') {

            $option = array(
                array($telegram->buildKeyboardButton("اطلاعات")),
                array($telegram->buildKeyboardButton("💡 راهنما"))
            );
            $keyb = $telegram->buildKeyBoard($option, $onetime=false, $resize=true, $selective=true);

            $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "
            *خوش امدید*
            
            اطلاعات رو بزن ببین چه باحالع :))

            ", 'parse_mode' => "Markdown");
            $telegram->sendMessage($content);
        }
        elseif ($text == '💡 راهنما') {

            $option = array(
                array(
                    $telegram->buildKeyboardButton("📌 توضیحات")
                ),
                array(
                    $telegram->buildKeyboardButton("برگشت")
                    )
            );
            $keyb = $telegram->buildKeyBoard($option, $onetime=false, $resize=true, $selective=true);

            $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "
            *خوش امدید*
            
            الان حسش نیست راهنمایی کنم 🤦🏻‍♂️😅

            ", 'parse_mode' => "Markdown");
            $telegram->sendMessage($content);
        }
        elseif ($text == 'برگشت') {
            $option = array(
                array(
                    $telegram->buildInlineKeyBoardButton(" درخواست شماره مجازی ", "", $callback_data = "روسیه")
                ),
                array(
                    $telegram->buildInlineKeyBoardButton(" درخواست شماره فضایی😐 ", "", $callback_data = "فضایی")
                    )
                );
                $keyb = $telegram->buildInlineKeyBoard($option);

            $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => '
            گزینه مناسب را انتخاب کنید:
            ', 'parse_mode' => "Markdown");
            $telegram->sendMessage($content);
        }
        elseif ($text == 'روسیه') {
            $option = array(
                array(
                    $telegram->buildInlineKeyBoardButton(" درخواست شماره مجازی ", "", $callback_data = "روسیه")
                ),
                array(
                    $telegram->buildInlineKeyBoardButton(" درخواست شماره فضایی😐 ", "", $callback_data = "فضایی")
                    )
                );
                $keyb = $telegram->buildInlineKeyBoard($option);
                $content = array('chat_id' => $chat_id, 'text' => "فعلا علی خسته شدع بقیه رباتو بعدا میسازع");
                $telegram->sendMessage($content);
            $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => '
            گزینه مناسب را انتخاب کنید:
            ', 'parse_mode' => "Markdown");
            $telegram->sendMessage($content);
        }
        elseif ($text == 'فضایی') {
            $content = array('chat_id' => $chat_id, 'text' => "ناموصا اینو زدی ببینی شماره فضایی دارع؟ 😐");
            $telegram->sendMessage($content);
        }
        elseif ($text == 'اطلاعات') {
            $content = array('chat_id' => $chat_id, 'text' => "متن ارسال شما: $text
                                نام کاربری شما: $username
                                *نام شما:* $name
                                فامیلی شما: $family
                                آیدی پیام: $message_id
                                آیدی شما: $user_id
                                آیدی مکان چت (بات یا گروه): $chat_id", 'parse_mode' => "Markdown");
            $telegram->sendMessage($content);
        }
    }
    else {
        if ($text == '/start') {
            $option = array(
                array($telegram->buildKeyboardButton("من کی هستم؟"),$telegram->buildKeyboardButton("کاربران"))
            );
            $keyb = $telegram->buildKeyBoard($option, $onetime=true, $resize=true, $selective=true);
            $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "
            *خوش امدید*
            
            ربات شماره مجازی:
            اختیار داری داداش :))
            شما ادمین ربات هستی😉

            ", 'parse_mode' => "Markdown");
            $telegram->sendMessage($content);
        }
        elseif ($text == 'کاربران') {
            $query = "SELECT * FROM user_data;";
            $result = $db->query($query);
            $i = 1;
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $t_id = $row["userid"];
                $colsArr[] = $telegram->buildInlineKeyBoardButton("کاربر: #$i","", "$t_id");
                if ($i % 2 == 0) {
                    $rowsArr[] = $colsArr;
                    unset($colsArr);
                }
                $i++;
            }
            if ($i % 2 == 0)
            $rowsArr[] = $colsArr;
            $option = $rowsArr;
            $result->closeCursor();
            $keyb = $telegram->buildInlineKeyBoard($option);
            $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => '
            لیست کاربران: 
            ', 'parse_mode' => "Markdown");
            $telegram->sendMessage($content);
        }
        elseif ($text == 'من کی هستم؟') {

            $query = "SELECT * FROM user_data;";
            $result = $db->query($query);

            $content = array('chat_id' => $chat_id, 'text' => "
            شما ادمین ربات هستید!
            ", 'parse_mode' => "Markdown");
            $telegram->sendMessage($content);
        }
        else {
            $content = array('chat_id' => $chat_id, 'text' => "
            مقدار وارد شده: $text
            ", 'parse_mode' => "Markdown");
            $telegram->sendMessage($content);
        }
    }
}