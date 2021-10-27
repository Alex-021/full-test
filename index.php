<!DOCTYPE html>
<html>
 <head>
  <title>Full Bot</title>
  <style>
    table {border-collapse: collapse;width: 100%;}
    td, th {border: 1px solid #505050;text-align: center;padding: 8px;}
    tr:nth-child(even) {background-color: #dddddd;}
</style>
</head>
 <body>
  <table>
   <thead>
    <tr>
    <th> User Number </th>
     <th> User ID </th>
     <th> First Name </th>
     <th> Last Name </th>
    </tr>
   </thead>
   <tbody>
<?php
include 'db.php';
echo "Hello Dear..."."<br>";
$val = 2718192035;
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
        $sql = "INSERT INTO user_data (userid, fname) VALUES ($val, 'ALI')";
        $db->query($sql);
    }
echo "<br>";

$query = "SELECT * FROM user_data;";
$result = $db->query($query);
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>#" . $row["number"] . "</td>";
    echo "<td>" . $row["userid"] . "</td>";
    echo "<td>" . $row["fname"] . "</td>";
    echo "<td>" . $row["lname"] . "</td>";
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
$bot_id = "2088394503:AAHzRy9RPDKQWLaRgr5Ytfd6-qeweTKJPTQ";
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

// if (!is_null($text) && !is_null($chat_id)) {
if ($user_id != $admin_id) { // Is Not ADMIN //

    $join_channel = array('chat_id' => '@Rmn98', 'user_id' => $user_id);
    $join_info = $telegram->getChatMember($join_channel);
    $join_status = $join_info['result']['status']; // Value => member || left
    $join_check = $join_info['ok'];
    if (!$join_check || $join_status == 'left') { // Is Not Join to Channel! //
        if ($text == '/start') {
            $found = searchId($db, $user_id);
            if (!$found) {
                insertUser($db, $user_id, $name, $family);
            }
            $post = array('chat_id' => $admin_id, 'from_chat_id' => $chat_id, 'message_id' => $message_id);
            $telegram->forwardMessage($post); // TRUE FORWARD Message with Quote.
            $content = array('chat_id' => $admin_id, 'text' => " 
            نام: $name
            نام خانوادگی: $family
            نام کاربری: $username
            آیدی عددی کاربر: $user_id
            متن ارسالی: $text
            آیدی پیام: $message_id
            آیدی محل چت: $chat_id
            از کجا ارسال شده: $from_chat
            ", 'parse_mode' => "Markdown");
        $telegram->sendMessage($content);
        }
        $del_msg = array('chat_id' => $chat_id, 'message_id' => $message_id);
        $telegram->deleteMessage($del_msg);
        $option = array(
            array(
                $telegram->buildInlineKeyBoardButton(" عضویت در کانال ", $url="https://t.me/joinchat/UNWSodg8AsF4fA1U")
            ),
            array(
                $telegram->buildInlineKeyBoardButton(" عضو شدم ", "", $callback_data = "confirm")
                )
            );
            $keyb = $telegram->buildInlineKeyBoard($option);

        $join_content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "کاربر عزیز شما عضو کانال ما نیستید و امکان استفاده از ربات را ندارید ⚠️
    
        ⭕️ لطفا در کانال زیر عضو شوید :
        
        🆔 @Rmn98
        
        سپس به ربات برگشته و مجدد امتحان کنید ✔️", 'parse_mode' => "Markdown");
        $telegram->sendMessage($join_content);
    }
    else { # Channel Ok!
        $t_start = "/start";
        $confirm = "confirm";
        $t_back = "➡️ برگشت";
        $t_info = "💡 راهنما";
        $t_desc = "📌 توضیحات";
        $t_buy = "💳 خرید شماره مجازی";
        $t_contact = "💬 ارتباط با پشتیبانی";
        switch ($text) {
            case $t_start:
            case $confirm:
                $del_msg = array('chat_id' => $chat_id, 'message_id' => $message_id);
                $telegram->deleteMessage($del_msg);
                $option = array(array($telegram->buildKeyboardButton($t_buy)),
                    array($telegram->buildKeyboardButton($t_info),$telegram->buildKeyboardButton($t_desc)),
                    array($telegram->buildKeyboardButton($t_contact)));
                $keyb = $telegram->buildKeyBoard($option, $onetime=true, $resize=true, $selective=false, $placeholder="یک گزینه را انتخاب کنید");
                $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "
                *خوش امدید*
                
                ربات شماره مجازی:
                ابتدا توضیحات و راهنما را مطالعه کنید.
    
                ", 'parse_mode' => "Markdown");
                $telegram->sendMessage($content);
                break;
            case $t_back:
                $option = array(array($telegram->buildKeyboardButton($t_buy)),
                    array($telegram->buildKeyboardButton($t_info),$telegram->buildKeyboardButton($t_desc)),
                    array($telegram->buildKeyboardButton($t_contact)));
                $keyb = $telegram->buildKeyBoard($option, $onetime=true, $resize=true, $selective=false, $placeholder="یک گزینه را انتخاب کنید");
                $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "
                *به منوی اصلی بازگشتید*
                
                ربات شماره مجازی:
                لطفا یک مورد را انتخاب نمایید.
    
                ", 'parse_mode' => "Markdown");
                $telegram->sendMessage($content);
                break;
            case $t_desc:
                $option = array(array($telegram->buildKeyboardButton($t_back)));
                $keyb = $telegram->buildKeyBoard($option, $onetime=true, $resize=true, $selective=false);
                $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "
                
                متن توضیحات
                .
                . 
                . 
                . 
                . 
                . 
                . 
                پایان متن توضیحات ربات
    
                ", 'parse_mode' => "Markdown");
                $telegram->sendMessage($content);
                break;
            case $t_info:
                $option = array(array($telegram->buildKeyboardButton($t_back)));
                $keyb = $telegram->buildKeyBoard($option, $onetime=true, $resize=true, $selective=false);
                $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "
                
                متن راهنمای ربات
                . 
                . 
                . 
                . 
                . 
                . 
                . 
                . 
                پایان متن راهنمای ربات
    
                ", 'parse_mode' => "Markdown");
                $telegram->sendMessage($content);
                break;
            case $t_contact:
                $option = array(array($telegram->buildKeyboardButton($t_back)));
                $keyb = $telegram->buildKeyBoard($option, $onetime=true, $resize=true, $selective=false);
                $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "

                جهت تماس با ادمین به آیدی:
    
                @ALI021m
    
                پیام بدید.
    
                ", 'parse_mode' => "Markdown");
                $telegram->sendMessage($content);
                break;
            case $t_buy:
                $option = array(
                    array($telegram->buildInlineKeyBoardButton("Telegram | تلگرام", "", $callback_data = "telegram")),
                    array($telegram->buildInlineKeyBoardButton("اینستاگرام | Instagram", "", $callback_data = "instagram")),
                    array($telegram->buildInlineKeyBoardButton("واتساپ | WhatsApp", "", $callback_data = "whatsapp"),
                          $telegram->buildInlineKeyBoardButton("اسکایپ | Skype", "", $callback_data = "skype")),
                    array($telegram->buildInlineKeyBoardButton("نتفلیکس | Netflix", "", $callback_data = "netflix"), 
                          $telegram->buildInlineKeyBoardButton("دیسکورد | DISCORD", "", $callback_data = "discord")),
                    array($telegram->buildInlineKeyBoardButton("ایمو | IMO", "", $callback_data = "imo"), 
                          $telegram->buildInlineKeyBoardButton("فیسبوک | Facebook", "", $callback_data = "facebook")),
                    array($telegram->buildInlineKeyBoardButton("وابر | Viber", "", $callback_data = "viber"), 
                          $telegram->buildInlineKeyBoardButton("لاین | Line", "", $callback_data = "line")),
                    array($telegram->buildInlineKeyBoardButton("استیم | Steam", "", $callback_data = "steam"), 
                          $telegram->buildInlineKeyBoardButton("توییتر | Twitter", "", $callback_data = "twitter")),
                    array($telegram->buildInlineKeyBoardButton("یاهو | Yahoo", "", $callback_data = "yahoo"), 
                          $telegram->buildInlineKeyBoardButton("لینکدین | LinkedIn", "", $callback_data = "linkedin")),
                    array($telegram->buildInlineKeyBoardButton("اپل | Apple", "", $callback_data = "apple"), 
                          $telegram->buildInlineKeyBoardButton("آمازون | Amazon", "", $callback_data = "amazon")),
                    array($telegram->buildInlineKeyBoardButton("اسنپچت | Snapchat", "", $callback_data = "snapchat"), 
                          $telegram->buildInlineKeyBoardButton("سیگنال | Signal", "", $callback_data = "signal")),
                    array($telegram->buildInlineKeyBoardButton("تیک تاک | TikTok", "", $callback_data = "tiktok"), 
                          $telegram->buildInlineKeyBoardButton("لایکی | Likee", "", $callback_data = "likee")),
                    array($telegram->buildInlineKeyBoardButton("جیمیل | Gmail", "", $callback_data = "gmail"), 
                          $telegram->buildInlineKeyBoardButton("شیائومی | Xiaomi", "", $callback_data = "xiaomi")),
                    array($telegram->buildInlineKeyBoardButton("مایکروسافت و سایر برنامه‌ها", "", $callback_data = "others")),
                );
                $keyb = $telegram->buildInlineKeyBoard($option);
    
                $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "
                
                شماره مجازی برای چه برنامه‌ای لازم دارید؟
    
                ", 'parse_mode' => "Markdown");
                $telegram->sendMessage($content);
                break;
            case "telegram":
                $option = array(array($telegram->buildKeyboardButton("خرید شماره روسیه")));
                    $keyb = $telegram->buildKeyBoard($option, $onetime=false, $resize=true, $selective=true);
                    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "
                    آیا از خرید شماره مجازی روسیه
                     برای تلگرام مطمئن هستید؟
                    ", 'parse_mode' => "Markdown");
                    $telegram->sendMessage($content);
                break;
            case "instagram":
                $option = array(array($telegram->buildKeyboardButton("خرید شماره آلمان")));
                    $keyb = $telegram->buildKeyBoard($option, $onetime=false, $resize=true, $selective=true);
                    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "
                    آیا از خرید شماره مجازی المان
                        برای اینستاگرام مطمئن هستید؟
                    ", 'parse_mode' => "Markdown");
                    $telegram->sendMessage($content);
                break;
            case "نام":
                $option = array(array($telegram->buildKeyboardButton($t_back)));
                $keyb = $telegram->buildKeyBoard($option, $onetime=false, $resize=true, $selective=true);
                $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "
                نام شما: $name
                ", 'parse_mode' => "Markdown");
                $telegram->sendMessage($content);
                break;
            case "value":
                # code...
                break;
            default:
                $option = array(array($telegram->buildKeyboardButton($t_back)));
                $keyb = $telegram->buildKeyBoard($option, $onetime=false, $resize=true, $selective=true);
                $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "
                این بخش درحال تکمیل می‌باشد...
                ", 'parse_mode' => "Markdown");
                $telegram->sendMessage($content);
                break;
        }
    }
}
else { // Is ADMIN //
    switch ($text) {
        case "/start":
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
            break;
        case "کاربران":
            $option = getList($db, $telegram);
            $keyb = $telegram->buildInlineKeyBoard($option);
            $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => '
            لیست کاربران: 
            ', 'parse_mode' => "Markdown");
            $telegram->sendMessage($content);
            break;
        case "من کی هستم؟":
            $query = "SELECT * FROM user_data;";
            $result = $db->query($query);
            $content = array('chat_id' => $chat_id, 'text' => "
            شما ادمین ربات هستید!
            ", 'parse_mode' => "Markdown");
            $telegram->sendMessage($content);
            break;
        
        default:
            $found = searchId($db, $text);
            if (!$found) {
                $content = array('chat_id' => $chat_id, 'text' => "
                دستور ناشناخته: $text
                ", 'parse_mode' => "Markdown");
                $telegram->sendMessage($content);
            }
            else {
                // $name_info = getInfo($db, $found, "fname");
                $option = array(
                    array($telegram->buildKeyboardButton("ارسال پیام"),$telegram->buildKeyboardButton("قطع ارتباط")),
                    array($telegram->buildKeyboardButton("✏️ ویرایش"),$telegram->buildKeyboardButton("❌ حذف"))
                );
                $keyb = $telegram->buildKeyBoard($option, $onetime=true, $resize=true, $selective=true);
                $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "
                کاربر $name_info انتخاب شد.
    
                ", 'parse_mode' => "Markdown");
                $telegram->sendMessage($content);
            }
            break;
    }
}

//////// Functions Section ////////
function getList($db, $telegram) {
    $query = "SELECT * FROM user_data;";
    $result = $db->query($query);
    $i = 1;
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $t_id = $row["userid"];
        $t_fname = $row["fname"];
        $num = $row["number"];
        $colsArr[] = $telegram->buildInlineKeyBoardButton("کاربر #$num: $t_fname","", "$t_id");
        if ($i % 2 == 0) {
            $rowsArr[] = $colsArr;
            unset($colsArr);
        }
        $i++;
    }
    if ($i % 2 == 0)
    $rowsArr[] = $colsArr;
    $result->closeCursor();
    return $rowsArr;
}
// function getInfo($db, $user_id, $col_name) {
//     $sql = "SELECT * FROM user_data WHERE userid = $user_id";
//     $result = $db->query($sql);
//     $row = $result->fetch(PDO::FETCH_ASSOC);
//     $result->closeCursor();
//     $data = $row[$col_name];
//     return $data;
// }
function searchId($db, $user_id) {
    $sql = "SELECT * FROM user_data WHERE userid = $user_id";
    $result = $db->query($sql);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $result->closeCursor();
    $u_id = $row["userid"];
    return $u_id;
}
function insertUser($db, $user_id, $name, $family) {
    $sql = "INSERT INTO user_data (userid, fname, lname) VALUES ($user_id, '$name', '$family')";
    $insert = $db->query($sql);
    $insert->closeCursor();
    $content = array('chat_id' => $admin_id, 'text' => " 
    کاربر: $name به لیست اضافه شد.
    ", 'parse_mode' => "Markdown");
    $telegram->sendMessage($content);
}
function deleteUser($db, $user_id) {
    // $name_info = getInfo($db, $user_id, "fname");
    $sql = "DELETE FROM user_data WHERE userid = $user_id";
    $delete = $db->query($sql);
    $delete->closeCursor();
    $content = array('chat_id' => $admin_id, 'text' => " 
    کاربر: $name_info از لیست حذف شد.
    ", 'parse_mode' => "Markdown");
    $telegram->sendMessage($content);
}
