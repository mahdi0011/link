<?php 

define('BOT_TOKEN','268199625:AAEBTU-jpjWPXoy4a0VsVlXna_NB8_y75g8');
define('API_TELEGRAM','https://api.telegram.org/bot'.BOT_TOKEN.'/');

$channel = 'https://telegram.me/N_Genius';

$json = file_get_contents('php://input');
$telegram = urldecode($json);
$update = json_decode($telegram);

$chat_id = $update->message->chat->id;
$chat_name = $update->message->chat->first_name;

$user_id = $update->message->from->id;
$user_first_name = $update->message->from->first_name;
$user_last_name = $update->message->from->last_name;
$user_name = $update->message->from->username;

$msg_id = $update->message->message_id;
$msg_text = $update->message->text;

function sendAction($chat_id,$action)
{
  $url = API_TELEGRAM.'sendChatAction?chat_id='.$chat_id.'&action='.$action;
  file_get_contents($url);
}

function sendMessage($chat_id,$msg_text)
{
  $url = API_TELEGRAM.'sendMessage?chat_id='.$chat_id.'&text='.$msg_text;
  file_get_contents($url);
}

if ($msg_text == '/start') 
{
sendAction($chat_id,'typing');
sendMessage($chat_id,'سازنده  : @Ir_Poker ');
}
else
{
$forward_to_channel = API_TELEGRAM.'forwardMessage?chat_id='.$channel.'&from_chat_id='.$chat_id.'&message_id='.$msg_id;
$run_forward_to_channel = file_get_contents($forward_to_channel);
$forward_msg_id1 = urldecode($run_forward_to_channel);
$forward_msg_id2 = json_decode($forward_msg_id1);
$forward_msg_id = $forward_msg_id2->result->message_id;
$forward_to_chat_from_channel = API_TELEGRAM.'forwardMessage?chat_id='.$chat_id.'&from_chat_id='.$channel.'&message_id='.$forward_msg_id;
file_get_contents($forward_to_chat_from_channel);
}

