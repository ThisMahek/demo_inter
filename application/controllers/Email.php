<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Email extends CI_Controller
{

   public function test()
   {
      //  $config['protocol'] = 'SMTP';
      // $config['mailpath'] = '/usr/sbin/sendmail';
      // $config['charset'] = 'iso-8859-1';
      // $config['mailtype'] = 'html';
      // $config['wordwrap'] = TRUE;
      // $this->email->initialize($config);
      // $this->load->library('email');
      // $this->email->from('your@example.com', 'Your Name');
      // $this->email->to('vermamahak44@gmail.com');
      // $this->email->cc('another@another-example.com');
      // $this->email->bcc('them@their-example.com');

      // $this->email->subject('Email Test');
      // $this->email->message('Testing the email class.');
      // $x = $this->email->send();
      // print_r($x);exit;
// $this->email->send();
// $email_config = Array(
//    'protocol'  => 'smtp',
//    'smtp_host' => 'ssl://smtp.googlemail.com',
//    'smtp_port' => '465',
//    'smtp_user' => 'someuser@gmail.com',
//    'smtp_pass' => 'password',
//    'mailtype'  => 'html',
//    'starttls'  => true,
//    'newline'   => "\r\n"
// );
$from_email="officalmahek7379@gmail.com";
$to_email="vermamahak44@gmail.com";
$this->load->library('email');
$this->email->from($from_email, 'invoice');
$this->email->to($to_email);
$this->email->subject('Invoice');
$this->email->message('Test');
$x=$this->email->send();
      if ($x)
         $this->session->set_flashdata("email_sent", "Congragulation Email Send Successfully.");
      else
         $this->session->set_flashdata("email_sent", "You have encountered an error");
      $this->load->view('test');
   }
}
?>