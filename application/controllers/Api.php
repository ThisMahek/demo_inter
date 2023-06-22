<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Api_model', 'AM');
  }
  //soap apis
  function getdata()
  {
    $response = array();
    $result = $this->AM->get_data();
    if (!empty($result)) {
      $response['data'] = $result;
      $response['reponse'] = true;
      $response['message'] = "Data found successfully";
    } else {
      $response['data'] = array();
      $response['reponse'] = false;
      $response['message'] = "No data found";
    }
    echo json_encode($response);
  }
  function insert_data()
  {
    $response = array();
    $result = $this->AM->insert_admin_data();
    if ($result) {
      $response['message'] = "Data saved successfully";
      $response['success'] = true;
    } else {
      $response['message'] = "Unable to save data";
      $response['success'] = true;
    }
    echo json_encode( $response);
  }

}