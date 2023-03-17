<?php

    session_start();
    $accounts = require_once("account.php");

    if(isset($_POST['login'])){
        $form_username = $_POST['username'];
        $form_password = $_POST['password'];
        
        if(isset($accounts[$form_username])){
            if($accounts[$form_username] === $form_password ){
                $_SESSION['user'] = $form_username;
                header("Location:chat.php");
                exit;
            } 
        } 
        else {
            header("Location:login.php?error=user o password errati!");
            exit;
        }
    } 

    header("Location:login.php?error=Devi prima loggarti");







?>