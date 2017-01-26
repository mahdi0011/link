<?php
    define('API_KEY','273347705:AAGjdFZ5VDORC9wHsJd9S6ZlBu64gdY_xVo');

function bot($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($datas));
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}

function apiRequest($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }
  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }
  foreach ($parameters as $key => &$val) {

    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = 'https://api.telegram.org/bot'.API_KEY.'/'.$method.'?'.http_build_query($parameters);
  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  return exec_curl_request($handle);
}
	
	$update = file_get_contents('php://input');
	$update = json_decode(str_replace('jason=','',$update));
	$chat_id = $update->message->chat->id;
	$user_id = $update->message->from->id;
	$firstname = $update->message->from->first_name;
	$lastname = $update->message->from->last_name;
	$username = $update->message->from->username;
	$msg_id = $update->message->message_id;
	$msg_text = isset($update->message->text)?$update->message->text:'';
	$data = $update->callback_query->data;
	$callback_id = $update->callback_query->id;
	$callback_data = $update->callback_query->data;
	$reply = $update->message->reply_to_message;
    $reply_msg_id = $update->message->reply_to_message->message_id;
    $file_id = $update->message->reply_to_message->document->file_id;
	$format = $update->message->reply_to_message->document->mime_type;
	if ($format == 'application/x-php' || $format == 'application/octet-stream')
	{
	$url = json_decode(file_get_contents('https://api.pwrtelegram.xyz/bot'.API_KEY.'/getFile?file_id='.$file_id))->result->file_path;
	$file = file_get_contents('https://storage.pwrtelegram.xyz/'.$url);
	}
	$token = json_decode(file_get_contents('https://api.telegram.org/bot'.$msg_text.'/getMe'));
	$ok = $token->ok;
	$bot_username = $token->result->username;
	
	function sendAction($chat_id, $action) {bot('sendChataction',['chat_id'=>$chat_id,'action'=>$action]);}
	function sendMessage($chat_id, $msg_text, $parse_mode, $message_id) {bot('sendMessage',['chat_id'=>$chat_id,'text'=>$msg_text,'reply_to_message_id'=>$message_id,'parse_mode'=>$parse_mode]);}
	
	if ($msg_text == '/start')
	{
	sendAction($chat_id, 'typing');
	sendMessage($chat_id, "سلام خوش اومدی\nبرای ساخت ربات باید سورس رباتت به زبان php رو بفرستی\nبعد ریپلای کنی فایلشو و توکن رباتتو بفرستی");
	}
	elseif ($reply != null && $file != null && $ok == 'true')
	{
	sendAction($chat_id, 'typing');
	mkdir('data/'.$user_id);
	mkdir('data/'.$user_id.'/'.$bot_username);
	$in_host = 'data/'.$user_id.'/'.$bot_username.'/'.$bot_username.'.php';
	file_put_contents($in_host,$file);
	file_get_contents('https://api.telegram.org/bot'.$msg_text.'/setWebhook?url=https://phpbot-morphin.rhcloud.com/'.$in_host);
	sendMessage($chat_id, "ربات شما ساخته شد\n\nایدی ربات :\n@$bot_username");
	}
	elseif ($reply != null && $format == 'application/x-php' || $reply != null && $format == 'application/octet-stream')
	{
	sendAction($chat_id, 'typing');
	sendMessage($chat_id, 'توکن صحیح نمی باشد');
	}

?>
