<?php
    session_start();
    if(!isset($_SESSION['user'])){
        header("Location: /login.php?error=Non sei autorizzato");
    }
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Chat</title>
    <style>
        body{
            margin:0px;
            padding:0px;
            background-color: #000000;
        }
        .messaggi{
            background-color: #000000;
            height: calc( 100vh - 45px );
            overflow: auto;
            color:green;
            background-color: #000000;
        }
        #messaggio{
            padding:10px;
            border: 1px solid #c0c0c0;
            width:100%;
            display: block;
            outline: none;
            font-size: 15px;
            box-sizing: border-box;
            
        }
        .messaggio{
            background-color: #000000;
            width: 100%;
            position: relative;
        }
        .messaggio .message-content{
            background-color: #000000;
            padding:10px;
            font-family: Verdana;
            display: flex;
            justify-content: end;
        }
        .messaggio .message-content small{
            color: #555555;
        }
        .messaggio .message-content table {
            background-color: #fff;
            padding:10px;
            border-radius: 20px;
        }
        .messaggio.me .message-content{
            justify-content: start;
        }
        .messaggio.other .message-content{
            color: green;
        }
        .messaggio small{
            display: flex;
            clear:both;
        }
        #clear_history{
            z-index: 1;
            position: fixed;
            width:100%;
            padding:20px;
            background-color: red;
            color:#fff;
            text-align:center;
            cursor:pointer;
            font-family: Verdana;
        }
        </style>
    <script>
        let me  = '<?php echo $_SESSION['user']; ?>';
        const refresh_messages_every_msecs = 2000;
        window.onload = () => {
            let messaggio = document.querySelector("#messaggio");
            messaggio.addEventListener("keyup",function(e){
                if(e.keyCode === 13){
                    sendMessage(messaggio.value);
                    messaggio.value = '';
                }
            });
            document.querySelector('#clear_history').addEventListener("click",function(e){
                var _this = this;
                _this.innerText="Cancellando..";
                askServer("POST",{action:'messages_history_clear'}).then( _response => {
                    _this.innerText="Cancella cronologia";
                });
            });

            refresh_messages_list();
            setInterval(()=>{
                refresh_messages_list();
            },refresh_messages_every_msecs)
        }   
        function createFormData(obj){
            let formData = [];
            Object.keys(obj).forEach( k => {
                formData.push(k+"="+encodeURIComponent(obj[k]));
            });
            return formData.join("&");
        }
        function askServer(method,payload,onResponse){
            let request_params = {
                method,
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded',
                }
            };
            if(method === "POST" && payload ){
                request_params['body']=createFormData(payload);
            }
            return fetch(
                "/server.php",
                request_params
            ).then( response => {
                response.text().then( response_text => {
                    onResponse(response_text);
                })
            });
        }
        function sendMessage(message){
            let payload = {message,user:me,action:'message_send'};
            askServer("POST",payload,(_response_text)=>{
                if( _response_text === 'SENT'){
                    create_message_element(payload);
                }
            });
        }
        function create_message_element(message){
            let messaggi = document.querySelector('#messaggi');
            let messaggio = document.createElement("div");
            messaggio.classList = "messaggio "+(message.user === me ? 'me' : 'other');
            messaggio.innerHTML=`
                <div class="message-content">
                    <table>
                        <tr>
                            <td><small>${message.user}</small></td>
                        </tr>
                        <tr>
                            <td>${message.message}</td>
                        </tr>                    
                </div>
            `;  

            messaggi.appendChild(messaggio);
        }
        function refresh_messages_list(){
            let messagesDiv = document.querySelector('#messaggi'); 
            askServer('POST',{action:'list_messages_as_html',user:me},(_messagesAsHTML)=>{
                messagesDiv.innerHTML = _messagesAsHTML;
                messagesDiv.scrollTop=9999999999999;
            });
        }
    </script>
</head>
<body>
    <div id="clear_history">Cancella cronologia</div>
    <div id="messaggi" class="messaggi">

    </div>
    <input type="text" name="messaggio" id="messaggio" autocomplete="off"/>
</body>
</html>