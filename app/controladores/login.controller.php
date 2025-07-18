<?php

require_once "app/modelos/login.model.php";

class LoginController{

    public static function ctrVerifyUser(){
    
        if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"], $_POST["password"])){
            $value = $_POST["email"];
            $password = $_POST ["password"];

            $response = LoginModel::mdlVerifyUser($value);

            if(password_verify($password, $response["user_password"])){
            // if($response && $_POST["password"] === $response["user_password"]){


                $idUser = $response["pk_id_user"];

                $responseUserRole = LoginModel::mdlVerifyRole($idUser);
                
                  $fkIdRole = $responseUserRole["fk_id_role"];
                  $responseRoleName = LoginModel::mdlVerifyNameRole($fkIdRole);

                session_start();
                $_SESSION["authenticated"] = "ok";
                $_SESSION["user_name"] = $response["user_name"];
                $_SESSION["USER_ID"] = $response["pk_id_user"];
                $_SESSION["ROLE_ID"] = $responseUserRole["fk_id_role"];
                $_SESSION["ROLE_NAME"] = $responseRoleName["role_name"];
                header("Location: index.php");
            }else{
                 session_start();
                $_SESSION["login_error"] = "credenciales incorrectas";
                header("location: index.php?router=login");
                exit;
            }
           }else{
                 session_start();
                $_SESSION["login_error"] = "por favor, completa todos los campos";
                header("location: index.php?router=login");
                exit;
            

        }


    }
    public static function ctrLogout() {
            session_start();
            session_unset();
            session_destroy();

            // Mensaje para mostrar en plantilla
            session_start(); // iniciar de nuevo para guardar el mensaje
            $_SESSION["message"] = "Sesión cerrada correctamente";
            $_SESSION["message_type"] = "success";

            header("Location: index.php?route=login");
            exit;
        }

}