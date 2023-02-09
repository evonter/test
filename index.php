<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start();
require_once __DIR__ . '/vendor/autoload.php';

$data = [];

$accepted_lang = ['ru', 'en'];

if(!empty($_GET['lang']) && in_array($_GET['lang'], $accepted_lang)) {
   $lang = $_GET['lang'];
   $_SESSION['lang'] = $_GET['lang'];
} elseif(!empty($_SESSION['lang']) && in_array($_SESSION['lang'], $accepted_lang)) {
   $lang = $_SESSION['lang'];
} else {
   $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
   $lang = in_array($lang, $accepted_lang) ? $lang : 'ru';
}

require_once __DIR__ ."/lang/{$lang}.php";

$data['lang'] = $lang;

   
if($_SERVER['REQUEST_METHOD'] == 'POST') {

   $json = [];

   $db = new MysqliDb('localhost', 'usr', 'ghjq4jgG', 'db');

   if(empty($_POST['name']) || mb_strlen($_POST['name']) > 64) {
      $json['error']['fields']['name'] = $data['error_name'];
   }

   if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($_POST['email']) > 64) {
      $json['error']['fields']['email'] = $data['error_email'];
   }

   if(empty($_POST['phone']) || mb_strlen($_POST['phone']) < 5 || mb_strlen($_POST['phone']) > 64) {
      $json['error']['fields']['phone'] = $data['error_phone'];
   }
   
   if(!empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $db->where("email", $_POST['email']);
      $customer = $db->getOne("customers");
      if($customer) {
         $json['error']['fields']['email'] = $data['error_email_exist'];
      }
   }

   if(!empty($_POST['phone']) && mb_strlen($_POST['phone']) >= 5 && mb_strlen($_POST['phone']) <= 64) {
      $db->where("phone", $_POST['phone']);
      $customer = $db->getOne("customers");
      if($customer) {
         $json['error']['fields']['phone'] = $data['error_phone_exist'];
      }
   }

   if(!$json) {

      $customer_id = $db->insert('customers', ['name' => $_POST['name'],'email' => $_POST['email'],'phone' => $_POST['phone']]);
      
      if($customer_id) {
         $json['success'] = $data['success'];
      } else {
         $json['error'] = $data['error_request'];
         file_put_contents(__DIR__ .'error.log',$db->getLastError(),FILE_APPEND);
      }
      
   }

   header('Content-Type: application/json', true);
   echo json_encode($json);
   exit;
}

header('Content-Type: text/html; charset=utf-8', true);
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ .'/view');
$twig = new \Twig\Environment($loader);
echo $twig->render('form.twig', $data);
exit;