<?php
defined('BASEPATH') or exit('No direct script access allowed');
class File extends CI_Controller
{

    
    public function show_file()
    {
        
     $array1=['a','b','c'];
     $array2=[1,2,3];
    foreach( $array1 as $a1){
        $arr[]=$a1;
    }
    // $array3= $array1;
       print_r($arr);exit;

    }

}