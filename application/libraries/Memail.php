<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH."/third_party/phpmailer/PHPMailerAutoload.php";

class Memail extends PHPMailer {
    public function __construct() { parent::__construct(); }
} 
 
?>