<?php
/*
IMPORTANTE!!!!

Este código PHP ES SUMAMENTE IMPORTANTE!!!!!
Es el código que se utiliza para que el usuario pueda cerrar sesión

@author Schimpf Tadeo
@version 1.0
@date 11/06/2025
*/
  session_start(); 

  session_unset();

  session_destroy();

  header('Location: /php-login');
?>