<?php



    session_start();

    $messages_history_filename = 'history.json';

    function messages_history_get(){
        global $messages_history_filename;
        $messageHistory=[];
        if(file_exists($messages_history_filename)){
            $fileContent = file_get_contents($messages_history_filename);
            if($fileContent){
                $messageHistory = json_decode($fileContent);
            } 
        }
        return $messageHistory;
    }
    function messages_history_get_as_html(){
        $user=$_POST['user'];
        $html=[];
        foreach(messages_history_get() as $message){
            $html[]='<div class="messaggio '.($message->user === $user ? 'me' : 'other').'">';
            $html[]='<div class="message-content">';
            $html[]='<table>';
            $html[]='<tr>';
            $html[]='<td><small>'.$message->user.'</small></td>';
            $html[]='</tr>';
            $html[]='<tr>';
            $html[]='<td>'.$message->message.'</td>';
            $html[]='<tr>';
            $html[]='</table>';
            $html[]='</div>';
            $html[]='</div>';
        }
        return join('',$html);
    }
    function messages_history_clear(){
        global $messages_history_filename;
        unlink($messages_history_filename);
        return [];
    }
    function message_send($message){
        global $messages_history_filename;
        $messageHistory=messages_history_get();
        $messageHistory[]=$message;
        $f=fopen($messages_history_filename,'w');
        fputs($f,json_encode($messageHistory));
        fclose($f);
    }
    function message_read(){
        $messageFields=['message','user'];
        $message = [];
        foreach($messageFields as $messageField){
            $message[$messageField] = $_POST[$messageField];
        }
        return $message;
    }


    $handlers = [
        'message_send' => function () {
            message_send(message_read());
            echo "SENT";
        },
        'messages_history_clear' => function () {
            messages_history_clear();
            echo "messages cleared !";
        },
        'list_messages' => function () {
            echo json_encode(messages_history_get());
        },
        'list_messages_as_html' => function () {
            echo messages_history_get_as_html();
        },
    ];


    if (isset($_POST['action'])) {
        if (isset($handlers[$_POST['action']])) {
            $handlers[$_POST['action']]();
        }
    }
