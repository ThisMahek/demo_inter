<?php
class  Api_model extends CI_Model{
public function get_data(){
    return $this->db->get('category')->result();
}
public function insert_admin_data(){
    $insert_data=array(
     'district_id'=>$this->input->post('district_id'),
     'name'=>$this->input->post('name'),
    );
    return $this->db->insert('block',$insert_data);

}
}
