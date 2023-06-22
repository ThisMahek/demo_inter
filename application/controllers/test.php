<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Itinerary_model extends Ci_model
{
    public function set_upload_files($upload_path, $files, $type = "")
    {
        $image_base64 = base64_decode($files);
        if ($type != "" && $type != null) {
            $file = $upload_path . uniqid() . '.' . $type;
        } else {
            $file = $upload_path . uniqid() . '.png';
        }
        file_put_contents($file, $image_base64);
        $image = $file;
        return $image;
    }
    public function get_data($table, $where, $type)
    {
        if ($where) {
            $this->db->where($where);
        }
        if ($type == 'single') {
            $userdata = $this->db->get($table)->row();
        } elseif ($type == 'all') {
            $userdata = $this->db->get($table)->result();
        } elseif ($type == 'no') {
            $userdata = $this->db->get($table)->num_rows();
        }
        return $userdata;
    }
    public function update_data($table, $where, $array)
    {
        return $this->db->where($where)->update($table, $array);
    }
    public function insert_data($table, $array)
    {
        return $this->db->insert($table, $array);
    }
    public function manage_activity($request)
    {
        $type = $request['type'];
        $insert_array['user_id'] = isset($request['user_id']) ? $request['user_id'] : "";
        $insert_array['title'] = isset($request['title']) ? $request['title'] : "";
        $insert_array['start_time'] = isset($request['start_time']) ? $request['start_time'] : "";
        $insert_array['duration'] = isset($request['duration']) ? $request['duration'] : "";
        $insert_array['time_zone'] = isset($request['time_zone']) ? $request['time_zone'] : "";
        $insert_array['details'] = isset($request['details']) ? $request['details'] : "";
        $insert_array['pay_details'] = isset($request['pay_detials']) ? $request['pay_detials'] : "";
        $insert_array['currency'] = isset($request['currency']) ? $request['currency'] : "";
        $insert_array['adults'] = isset($request['adults']) ? $request['adults'] : "";
        $insert_array['kids'] = isset($request['kids']) ? $request['kids'] : "";
        if (isset($request['image']) && !empty($request['image'])) {
            $files = $request['image'];
            $upload_path = 'uploads/activity/';
            $insert_array["image"] = $this->set_upload_files($upload_path, $files, 'png');
        }
        if ($type == 'create') {
            $insert_array['status'] = 1;
            $x = $this->insert_data('activity', $insert_array);
            if ($this->db->affected_rows() == true) {
                $response = 1;
            } else {
                $response = 0;
            }
        } elseif ($type == 'update') {
            $where = array('id' => $request['activity_id']);
            $response = $this->update_data('activity', $where, $insert_array);
            if ($this->db->affected_rows() == true) {
                $response = 1;
            } else {
                $response = 0;
            }
        } elseif ($request['type'] == 'delete') {
            $where = array('id' => $request['activity_id']);
            $updater['status'] = 2;
            $response = $this->update_data('activity', $where, $updater);
            if ($this->db->affected_rows() == true) {
                $response = 1;
            } else {
                $response = 0;
            }
        } elseif ($request['type'] == 'get') {
            $where = array('activity.status' => 1);
            $this->db->select('activity.id,activity.title,ifnull(activity.image,"") as image,activity.start_time,activity.duration,activity.details,time_zone.name as time_zone_name,time_zone.time_zone as time');
            $this->db->join('time_zone', 'time_zone.id=activity.time_zone', 'left');
            $response = $this->db->where($where)->get('activity')->result();
        } elseif ($request['type'] == 'get_single') {
            $where = array('activity.id' => $request['activity_id'], 'activity.status' => 1);
            $this->db->select('activity.id,activity.title,activity.start_time,activity.duration,time_zone.name as time_zone_name,time_zone.time_zone as time,activity.details,ifnull(activity.image,"") as image,ifnull(activity.pay_details,"") as pay_details,ifnull(activity.adults,"") as adults,ifnull(activity.kids,"") as kids,budget.name as currency_name,budget.symbol,activity.currency as currency_id,activity.time_zone as time_zone_id');
            $this->db->join('time_zone', 'time_zone.id=activity.time_zone', 'left')->join('budget', 'budget.id=activity.currency', 'left');
            $response = $this->db->where($where)->get('activity')->row();
        }
        return $response;
    }
    public function manage_flight($request)
    {
        $type = $request['type'];
        $insert_array['user_id'] = isset($request['user_id']) ? $request['user_id'] : "";
        $insert_array['name'] = isset($request['name']) ? $request['name'] : "";
        $insert_array['start_time'] = isset($request['start_time']) ? $request['start_time'] : "";
        $insert_array['duration'] = isset($request['duration']) ? $request['duration'] : "";
        $insert_array['from'] = isset($request['from_destination']) ? $request['from_destination'] : "";
        $insert_array['to'] = isset($request['to_destination']) ? $request['to_destination'] : "";
        $insert_array['time_zone'] = isset($request['time_zone']) ? $request['time_zone'] : "";
        $insert_array['details'] = isset($request['details']) ? $request['details'] : "";
        $insert_array['pay_details'] = isset($request['pay_details']) ? $request['pay_details'] : "";
        $insert_array['currency'] = isset($request['currency']) ? $request['currency'] : "";
        $insert_array['adults'] = isset($request['adults']) ? $request['adults'] : "";
        $insert_array['kids'] = isset($request['kids']) ? $request['kids'] : "";
        if ($type == 'create') {
            $insert_array['status'] = 1;
            $x = $this->insert_data('flight', $insert_array);
            if ($this->db->affected_rows() == true) {
                $response = 1;
            } else {
                $response = 0;
            }
        } elseif ($type == 'update') {
            $where = array('id' => $request['flight_id']);
            $response = $this->update_data('flight', $where, $insert_array);
            if ($this->db->affected_rows() == true) {
                $response = 1;
            } else {
                $response = 0;
            }
        } elseif ($request['type'] == 'delete') {
            $where = array('id' => $request['flight_id']);
            $updater['status'] = 2;
            $response = $this->update_data('flight', $where, $updater);
            if ($this->db->affected_rows() == true) {
                $response = 1;
            } else {
                $response = 0;
            }
        } elseif ($request['type'] == 'get') {
            $where = array('flight.user_id' => $request['user_id'], 'flight.status' => 1);
            $this->db->select('flight.id,flight.name,flight.start_time,flight.duration,flight.details,flight.from,flight.to,time_zone.name as time_zone_name,time_zone.time_zone as time');
            $this->db->join('time_zone', 'time_zone.id=flight.time_zone', 'left');
            $response = $this->db->where($where)->get('flight')->result();
        } elseif ($request['type'] == 'get_single') {
            $where = array('flight.id' => $request['flight_id'], 'flight.status' => 1);
            $this->db->select('flight.id,flight.name,flight.start_time,flight.duration,flight.from,flight.to,time_zone.name as time_zone_name,time_zone.time_zone as time,flight.details,ifnull(flight.pay_details,"") as pay_details,ifnull(flight.adults,"") as adults,ifnull(flight.kids,"") as kids,budget.symbol,flight.currency');
            $this->db->join('time_zone', 'time_zone.id=flight.time_zone', 'left')->join('budget', 'budget.id=flight.currency', 'left');
            $response = $this->db->where($where)->get('flight')->row();
        }
        return $response;
    }
    public function manage_transport($request)
    {
        $type = $request['type'];
        $insert_array['user_id'] = isset($request['user_id']) ? $request['user_id'] : "";
        $insert_array['name'] = isset($request['name']) ? $request['name'] : "";
        $insert_array['contact_no'] = isset($request['contact_no']) ? $request['contact_no'] : "";
        $insert_array['email_id'] = isset($request['email_id']) ? $request['email_id'] : "";
        $insert_array['vehicle_type'] = isset($request['vehicle_type']) ? $request['vehicle_type'] : "";
        $insert_array['city'] = isset($request['city']) ? $request['city'] : "";
        $insert_array['start_time'] = isset($request['start_time']) ? $request['start_time'] : "";
        $insert_array['end_time'] = isset($request['end_time']) ? $request['end_time'] : "";
        $insert_array['vehicle_details'] = isset($request['vehicle_details']) ? $request['vehicle_details'] : "";
        $insert_array['pay_details'] = isset($request['pay_details']) ? $request['pay_details'] : "";
        $insert_array['currency'] = isset($request['currency']) ? $request['currency'] : "";
        $insert_array['adults'] = isset($request['adults']) ? $request['adults'] : "";
        $insert_array['kids'] = isset($request['kids']) ? $request['kids'] : "";
        if (isset($request['image']) && !empty($request['image'])) {
            $files = $request['image'];
            $upload_path = 'uploads/vihicle/';
            $insert_array["image"] = $this->set_upload_files($upload_path, $files, 'png');
        }
        if ($type == 'create') {
            $insert_array['status'] = 1;
            $x = $this->insert_data('transport', $insert_array);
            if ($this->db->affected_rows() == true) {
                $response = 1;
            } else {
                $response = 0;
            }
        } elseif ($type == 'update') {
            $where = array('id' => $request['transport_id']);
            $response = $this->update_data('transport', $where, $insert_array);
            if ($this->db->affected_rows() == true) {
                $response = 1;
            } else {
                $response = 0;
            }
        } elseif ($request['type'] == 'delete') {
            $where = array('id' => $request['transport_id']);
            $updater['status'] = 2;
            $response = $this->update_data('transport', $where, $updater);
            if ($this->db->affected_rows() == true) {
                $response = 1;
            } else {
                $response = 0;
            }
        } elseif ($request['type'] == 'get') {
            $where = array('transport.user_id' => $request['user_id'], 'transport.status' => 1);
            $this->db->select('transport.id,transport.name,transport.contact_no,vehicle_type as vehicle_id,vehicle.name as vehicle_name,vehicle.type as vehicle_type,ifnull(transport.image,"") as image');
            $this->db->join('vehicle', 'vehicle.id=transport.vehicle_type', 'left');
            $response = $this->db->where($where)->get('transport')->result();
        } elseif ($request['type'] == 'get_single') {
            $where = array('transport.id' => $request['transport_id'], 'transport.status' => 1);
            $this->db->select('transport.id,transport.name,transport.email_id,transport.contact_no,transport.vehicle_type as vehicle_id,vehicle.name as vehicle_name,vehicle.type as vehicle_type,transport.city,transport.start_time,transport.end_time,transport.vehicle_details,transport.pay_details,transport.adults,transport.kids,budget.symbol,transport.currency,ifnull(transport.image,"") as image');
            $this->db->join('vehicle', 'vehicle.id=transport.vehicle_type', 'left')->join('budget', 'budget.id=transport.currency', 'left');
            $response = $this->db->where($where)->get('transport')->row();
        }
        return $response;
    }
    public function get_all_type_data($request)
    {
        $response = array();
        $type = $request['type'];
        $response = $this->get_data($type, array('status' => 1), 'all');
        return $response;
    }



    public function manage_hotel($request)
    {
        $type = $request['type'];
        $uid = $request['user_id'];
        $insert_data = array();
        $insert_data['user_id'] = $request['user_id'] ? $request['user_id'] : '';
        $insert_data['name'] = $request['hotel_name'] ? $request['hotel_name'] : '';
        $insert_data['rating'] = $request['star_rating'] ? $request['star_rating'] : '';
        $insert_data['city'] = $request['city'] ? $request['city'] : '';
        $insert_data['country'] = $request['country'] ? $request['country'] : '';
        $insert_data['contact_number'] = $request['contact_number'] ? $request['contact_number'] : '';
        $insert_data['email_id'] = $request['email_id'] ? $request['email_id'] : '';
        $insert_data['check_in_time'] = $request['check_in_time'] ? $request['check_in_time'] : '';
        $insert_data['check_out_time'] = $request['check_out_time'] ? $request['check_out_time'] : '';
        $insert_data['address'] = $request['address'] ? $request['address'] : '';
        $insert_data['nights'] = $request['nights_in_hotel'] ? $request['nights_in_hotel'] : '';
        $insert_data['meal_plan'] = $request['meal_plan'] ? $request['meal_plan'] : '';
        $insert_data['details'] = $request['more_details'] ? $request['more_details'] : '';
        $insert_data['pay_details'] = $request['pay_details'] ? $request['pay_details'] : '';
        $insert_data['currency'] = $request['currency'] ? $request['currency'] : '';
        $insert_data['adults'] = $request['price_for_adult'] ? $request['price_for_adult'] : '';
        $insert_data['kids'] = $request['price_for_kids'] ? $request['price_for_kids'] : '';
        if (isset($request['upload_image']) && !empty($request['upload_image'])) {
            $files = $request['upload_image'];
            $upload_path = 'uploads/hotel/';
            $insert_data["image"] = $this->set_upload_files($upload_path, $files, 'png');
        }
        if ($type == 'create') {
            $insert_data['status'] = 1;
            return $this->db->insert('hotel', $insert_data);
        } else if ($type == 'update') {
            $hotel_id = $request['hotel_id'];
            if (!empty($hotel_id)) {
                return $this->db->where(['id' => $hotel_id])->update('hotel', $insert_data);
            } else {
                return false;
            }
        } else if ($type == 'delete') {
            $hotel_id = $request['hotel_id'];
            $insert_data['status'] = 2;
            if (!empty($hotel_id)) {
                return $this->db->where(['id' => $hotel_id])->update('hotel', $insert_data);
            } else {
                return false;
            }
        } elseif ($type == 'get_single') {
            $hotel_id = $request['hotel_id'];
            if (!empty($hotel_id)) {
                return $this->db->select('h1.id as hid, h1.user_id,h1.name,h1.rating,h1.city,h1.country,h1.contact_number,h1.email_id,h1.image,ifnull(rating.id,"") as rating_id,ifnull(rating.name,"") as rating_name,h1.check_in_time,h1.check_out_time,h1.address,h1.address,h1.nights,h1.meal_plan,h1.details,h1.pay_details,h1.adults,h1.kids,ifnull(h1.image,"") as upload_image,h1.currency,budget.symbol as currency_name')->from('hotel as h1')
                    ->join('rating', 'rating.id= h1.rating', 'left')
                    ->join('budget', 'budget.id= h1.currency', 'left')
                    ->where(['h1.id' => $hotel_id, 'h1.status' => 1])
                    ->get()
                    ->row_array();
            } else {
                return false;
            }
        } elseif ($type == 'get') {
            return $this->db->select('h1.id as hotel_id,h1.name,h1.rating, h1.user_id,h1.city,h1.country,h1.contact_number,h1.email_id,h1.image,ifnull(rating.id,"") as rating_id,ifnull(rating.name,"") as rating_name')->from('hotel as h1')
                ->join('rating', 'rating.id= h1.rating', 'left')
                ->where(['h1.user_id' => $uid, 'h1.status' => 1])
                ->get()
                ->result_array();

        }
    }





    public function manage_train($request)
    {
        $type = $request['type'];
        $uid = $request['user_id'];
        $insert_data = array();
        $insert_data['user_id'] = $request['user_id'] ? $request['user_id'] : '';
        $insert_data['name'] = $request['train_name'] ? $request['train_name'] : '';
        $insert_data['train_number'] = $request['train_number'] ? $request['train_number'] : '';
        $insert_data['start_time'] = $request['start_time'] ? $request['start_time'] : '';
        $insert_data['end_time'] = $request['end_time'] ? $request['end_time'] : '';
        $insert_data['hour'] = $request['duration_hrs'] ? $request['duration_hrs'] : '';
        $insert_data['mins'] = $request['duration_mins'] ? $request['duration_mins'] : '';
        $insert_data['from_destination'] = $request['from_destination'] ? $request['from_destination'] : '';
        $insert_data['to_destination'] = $request['to_destination'] ? $request['to_destination'] : '';
        $insert_data['details'] = $request['more_details'] ? $request['more_details'] : '';
        $insert_data['timezone'] = $request['timezone'] ? $request['timezone'] : '';
        $insert_data['pay_details'] = $request['pay_details'] ? $request['pay_details'] : '';
        $insert_data['currency'] = $request['currency'] ? $request['currency'] : '';
        $insert_data['adults'] = $request['adults'] ? $request['adults'] : '';
        $insert_data['kids'] = $request['kids'] ? $request['kids'] : '';

        if ($type == 'create') {
            $insert_data['status'] = 1;
            return $this->db->insert('train', $insert_data);
        } else if ($type == 'update') {
            $train_id = $request['train_id'];
            if (!empty($train_id)) {
                return $this->db->where(['id' => $train_id, 'user_id' => $uid])->update('train', $insert_data);
            } else {
                return false;
            }
        } else if ($type == 'delete') {
            $train_id = $request['train_id'];
            $insert_data['status'] = 2;
            if (!empty($train_id)) {
                return $this->db->where(['id' => $train_id])->update('train', $insert_data);
            } else {
                return false;
            }
        } elseif ($type == 'get_single') {
            $train_id = $request['train_id'];
            if (!empty($train_id)) {

                return $this->db->select('t1.user_id,t1.id as train_id,t1.name,t1.start_time,t1.end_time,t1.hour as duration,t1.mins,t1.from_destination,t1.to_destination,t1.train_number,t1.timezone,time_zone.name as time_name,time_zone.time_zone,t1.details,t1.pay_details,t1.adults,t1.kids,t1.currency,budget.symbol as currency_name')->from('train as t1')
                    ->join('time_zone', 'time_zone.id = t1.timezone', 'left')
                    ->join('budget', 'budget.id= t1.currency', 'left')
                    ->where(['t1.id' => $train_id, 't1.status' => 1])

                    ->get()
                    ->row_array();
            } else {
                return false;
            }
        } elseif ($type == 'get') {
            return $this->db->select('t1.id as tid,t1.user_id,t1.name,t1.start_time,t1.end_time,t1.hour,t1.mins,t1.train_number,t1.timezone,time_zone.name as time_name ,time_zone.time_zone')->from('train as t1')
                ->join('time_zone', 'time_zone.id = t1.timezone', 'left')
                ->where(['t1.user_id' => $uid, 't1.status' => 1])
                ->get()
                ->result_array();
        }
    }





    public function manage_cruise($request)
    {
        $type = $request['type'];
        $uid = $request['user_id'];
        $insert_data = array();
        $insert_data['user_id'] = $request['user_id'] ? $request['user_id'] : '';
        $insert_data['name'] = $request['name'] ? $request['name'] : '';
        $insert_data['number'] = $request['cruise_number'] ? $request['cruise_number'] : '';
        $insert_data['start_time'] = $request['start_time'] ? $request['start_time'] : '';
        $insert_data['hour'] = $request['duration'] ? $request['duration'] : '';
        $insert_data['from'] = $request['from_destination'] ? $request['from_destination'] : '';
        $insert_data['to'] = $request['to_destination'] ? $request['to_destination'] : '';
        $insert_data['more_details'] = $request['more_details'] ? $request['more_details'] : '';
        $insert_data['time_zone'] = $request['timezone'] ? $request['timezone'] : '';
        $insert_data['pay_details'] = $request['pay_details'] ? $request['pay_details'] : '';
        $insert_data['currency'] = $request['currency'] ? $request['currency'] : '';
        $insert_data['adults'] = $request['adults'] ? $request['adults'] : '';
        $insert_data['kids'] = $request['kids'] ? $request['kids'] : '';
        if ($type == 'create') {
            $insert_data['status'] = 1;
            return $this->db->insert('cruise', $insert_data);
        } else if ($type == 'update') {
            $cruise_id = $request['cruise_id'];
            if (!empty($cruise_id)) {
                return $this->db->where(['id' => $cruise_id])->update('cruise', $insert_data);
            } else {
                return false;
            }
        } else if ($type == 'delete') {
            $cruise_id = $request['cruise_id'];
            $insert_data['status'] = 2;
            if (!empty($cruise_id)) {
                return $this->db->where(['id' => $cruise_id])->update('cruise', $insert_data);
            } else {
                return false;
            }
        } elseif ($type == 'get_single') {
            $cruise_id = $request['cruise_id'];
            if (!empty($cruise_id)) {
                return $this->db->select('c1.id as cruise_id,c1.name as name,c1.number,c1.start_time,c1.hour as duration,c1.from,c1.to,c1.time_zone,time_zone.name as time_name,time_zone.time_zone,c1.more_details,c1.pay_details,c1.adults,c1.kids,c1.currency,budget.symbol as currency_name,c1.user_id')->from('cruise as c1')
                    ->join('time_zone', 'time_zone.id = c1.time_zone', 'left')->join('budget', 'budget.id=c1.currency', 'left')
                    ->where(['c1.id' => $cruise_id, 'c1.status' => 1])
                    ->get()
                    ->row_array();
            } else {
                return false;
            }
        } elseif ($type == 'get') {
            return $this->db->select('c1.id as cruise_id,c1.user_id,c1.name,c1.from,c1.to,c1.start_time,c1.hour as duration,c1.number,c1.time_zone,time_zone.name as time_name ,time_zone.time_zone')->from('cruise as c1')
                ->join('time_zone', 'time_zone.id = c1.time_zone', 'left')
                ->where(['c1.user_id' => $uid, 'c1.status' => 1])
                ->get()
                ->result_array();
        }
    }

    public function create_itenary($request)
    {
        $insert_data = array();
        $type = $request['type'];
        $insert_data['user_id'] = $request['user_id'] ? $request['user_id'] : '';
        $insert_data['title'] = $request['title'] ? $request['title'] : '';
        $insert_data['days'] = $request['day'] ? $request['day'] : '';
        $insert_data['night'] = $request['night'] ? $request['night'] : '';
        $insert_data['location'] = $request['location'] ? $request['location'] : '';
        if (isset($request['image']) && !empty($request['image'])) {
            $upload_path = 'uploads/activity/';
            $files = $request['image'];
            $insert_data["image"] = $this->set_upload_files($upload_path, $files, 'png');
        }
        if ($type == 'create') {
            $insert_data['status'] = 1;
            return $this->db->insert('Itinerary', $insert_data);
        } elseif ($type == 'update') {
            $itenary_id = $request['itenary_id'];
            $res = $this->db->where(['id' => $itenary_id])->update('Itinerary', $insert_data);
            if ($res == true) {
                return true;
            } else {
                return false;
            }
        } elseif ($type == 'delete') {
            $itenary_id = $request['itenary_id'];
            $update_data['status'] = 2;
            $res = $this->db->where(['id' => $itenary_id])->update('Itinerary', $update_data);
            if ($res == true) {
                return true;
            } else {
                return false;
            }
        }
    }
    public function manage_package_description($request)
    {
        $insert_data = array();
        $type = $request['type'];
        $itenary_id = $request['itenary_id'] ? $request['itenary_id'] : '';
        $insert_data['package_description'] = $request['package_description'] ? $request['package_description'] : '';
        $insert_data['location_description'] = $request['location_description'] ? $request['location_description'] : '';
        if ($type == 'create') {
            return $this->db->where(['id' => $itenary_id])->update('Itinerary', $insert_data);
        } else if ($type == 'get') {

            return $this->db->select('package_description,location_description')->from('Itinerary')->where(['id' => $itenary_id, 'status' => 1])->get()->row_array();

        }
    }


    public function manage_day_wise_plan($request)
    {

        $type = isset($request['type']) ? $request['type'] : "";
        $day_id = isset($request['day_id']) ? $request['day_id'] : "";
        $itenary_id = isset($request['itenary_id']) ? $request['itenary_id'] : "";
        $insert_array['itenary_id'] = isset($request['itenary_id']) ? $request['itenary_id'] : "";
        $insert_array['user_id'] = isset($request['user_id']) ? $request['user_id'] : "";
        $insert_array['day'] = isset($request['day']) ? $request['day'] : "";
        $insert_array['day_title'] = isset($request['day_title']) ? $request['day_title'] : "";
        $insert_array['details'] = isset($request['details']) ? $request['details'] : "";
        $insert_array['hotel_id'] = isset($request['hotel']) ? $request['hotel'] : "";
        $insert_array['check_in_date'] = isset($request['check_in_date']) ? $request['check_in_date'] : "";
        $insert_array['check_out_date'] = isset($request['check_out_date']) ? $request['check_out_date'] : "";
        $insert_array['check_in_time'] = isset($request['check_in_time']) ? $request['check_in_time'] : "";
        $insert_array['check_out_time'] = isset($request['check_out_time']) ? $request['check_out_time'] : "";
        $insert_array['nights'] = isset($request['nights']) ? $request['nights'] : "";
        $insert_array['transport_id'] = isset($request['transport_id']) ? $request['transport_id'] : "";
        $insert_array['trans_date'] = isset($request['date']) ? $request['date'] : "";
        $insert_array['trans_time'] = isset($request['time']) ? $request['time'] : "";
        $insert_array['train_id'] = isset($request['train_id']) ? $request['train_id'] : "";
        $insert_array['cruise_id'] = isset($request['cruise_id']) ? $request['cruise_id'] : "";
        $insert_array['flight_id'] = isset($request['flight_id']) ? $request['flight_id'] : "";
        $insert_array['activity_id'] = isset($request['activity_id']) ? $request['activity_id'] : "";
        $insert_array['cruise_id'] = isset($request['cruise_id']) ? $request['cruise_id'] : "";
        $insert_array['flight_id'] = isset($request['flight_id']) ? $request['flight_id'] : "";
        $insert_array['activity_id'] = isset($request['activity_id']) ? $request['activity_id'] : "";
        if ($type == 'create') {
            if (!empty($itenary_id)) {
                $itenary_exists = $this->db->get_where('Itinerary', ['id' => $itenary_id, 'status' => 1])->num_rows();
                if ($itenary_exists > 0) {
                    return $this->insert_data('day_wise_plan', $insert_array);
                }
            } else {
                return false;
            }
        } elseif ($type == 'update') {
            $where = array('id' => $request['day_id']);
            if (!empty($itenary_id)) {
                $itenary_exists = $this->db->get_where('Itinerary', ['id' => $itenary_id, 'status' => 1])->num_rows();
                if ($itenary_exists > 0) {
                    return $this->update_data('day_wise_plan', $where, $insert_array);
                }
            } else {
                return false;
            }
        }

    }
    public function get_itenary_dashboard()
    {
        $my_itenary = $this->db->select('Itinerary.id,Itinerary.title,Itinerary.days,Itinerary.night,Itinerary.location,ifnull(Itinerary.image,"") as image')->where('Itinerary.status', 1)->get('Itinerary')->result();
        foreach ($my_itenary as $row) {
            $day_data = $this->db->select('id,day_title')->where(['status' => 1, 'itenary_id' => $row->id])->get('day_wise_plan')->result();
            $arr[] = array(
                'id' => $row->id,
                'name' => $row->title,
                'day' => $row->days,
                'night' => $row->night,
                'location' => $row->location,
                'image' => $row->image,
                'is_hotel' => $this->db->where(['status' => 1, 'itenary_id' => $row->id, 'hotel_id!=' => ""])->get('day_wise_plan')->num_rows(),
                'is_transport' => $this->db->where(['status' => 1, 'itenary_id' => $row->id, 'transport_id!=' => ""])->get('day_wise_plan')->num_rows(),
                'is_train' => $this->db->where(['status' => 1, 'itenary_id' => $row->id, 'train_id!=' => ""])->get('day_wise_plan')->num_rows(),
                'is_flight' => $this->db->where(['status' => 1, 'itenary_id' => $row->id, 'flight_id!=' => ""])->get('day_wise_plan')->num_rows(),
                'is_curise' => $this->db->where(['status' => 1, 'itenary_id' => $row->id, 'cruise_id!=' => ""])->get('day_wise_plan')->num_rows(),
                'is_activity' => $this->db->where(['status' => 1, 'itenary_id' => $row->id, 'activity_id!=' => ""])->get('day_wise_plan')->num_rows(),
                'days' => $day_data,
            );
        }
        $response = array(
            'libaray' => $this->get_data('library', array('status' => 1), 'all'),
            'my_itenary' => $arr,
        );
        return $response;
    }
    public function get_day_plan_by_id($request)
    {
        $itenary_id = $request['itenary_id'];
        $day_id = $request['day_id'];
        $day_data = $this->db->select('id,day_title,day,details')->where(['status' => 1, 'itenary_id' => $itenary_id, 'id' => $day_id])->get('day_wise_plan')->row();
        $hotel_data = $this->db->select('hotel.id,hotel.name,ifnull(hotel.image,"") as image,hotel.	email_id,hotel.contact_number,hotel.city,hotel.country,day_wise_plan.check_in_date,day_wise_plan.check_out_date,day_wise_plan.check_in_time,day_wise_plan.check_out_time,day_wise_plan.nights')->join('hotel', 'hotel.id=day_wise_plan.hotel_id', 'right')->where(['day_wise_plan.status' => 1, 'day_wise_plan.itenary_id' => $itenary_id, 'day_wise_plan.id' => $day_id, 'hotel.status' => 1])->get('day_wise_plan')->row();
        $transport_data = $this->db->select('transport.id,transport.name,ifnull(transport.image,"") as image,day_wise_plan.trans_time,day_wise_plan.trans_date')->join('transport', 'transport.id=day_wise_plan.transport_id', 'right')->where(['day_wise_plan.status' => 1, 'day_wise_plan.itenary_id' => $itenary_id, 'day_wise_plan.id' => $day_id, 'transport.status' => 1])->get('day_wise_plan')->row();
        $train_data = $this->db->select('train.id,train.name,train.start_time,train.hour,train.mins,train.from_destination,train.to_destination,train.train_number,train.timezone,time_zone.name as time_name,time_zone.time_zone')->join('train', 'train.id=day_wise_plan.train_id', 'right')->join('time_zone', 'time_zone.id = train.timezone', 'right')->where(['day_wise_plan.status' => 1, 'day_wise_plan.itenary_id' => $itenary_id, 'day_wise_plan.id' => $day_id, 'train.status' => 1])->get('day_wise_plan')->row();
        $cruise_data = $this->db->select('cruise.id,cruise.name,cruise.start_time, cruise.hour,cruise.from,cruise.to,cruise.number,cruise.time_zone,time_zone.name as time_name,time_zone.time_zone')->join('cruise', 'cruise.id=day_wise_plan.cruise_id', 'right')->join('time_zone', 'time_zone.id = cruise.time_zone', 'right')->where(['day_wise_plan.status' => 1, 'day_wise_plan.itenary_id' => $itenary_id, 'day_wise_plan.id' => $day_id, 'cruise.status' => 1])->get('day_wise_plan')->row();
        $flight_data = $this->db->select('flight.id,flight.name,flight.start_time,flight.duration,flight.from,flight.to,flight.time_zone,time_zone.name as time_name,time_zone.time_zone')->join('flight', 'flight.id=day_wise_plan.flight_id', 'right')->join('time_zone', 'time_zone.id = flight.time_zone', 'right')->where(['day_wise_plan.status' => 1, 'day_wise_plan.itenary_id' => $itenary_id, 'day_wise_plan.id' => $day_id, 'flight.status' => 1])->get('day_wise_plan')->row();
        $activity_data = $this->db->select('activity.id, activity.title,activity.start_time,activity.duration,ifnull(activity.image,"") as imahe,activity.time_zone,time_zone.name as time_name,time_zone.time_zone,activity.details')->join('activity', 'activity.id=day_wise_plan.activity_id', 'right')->join('time_zone', 'time_zone.id = activity.time_zone', 'right')->where(['day_wise_plan.status' => 1, 'day_wise_plan.itenary_id' => $itenary_id, 'day_wise_plan.id' => $day_id, 'activity.status' => 1])->get('day_wise_plan')->row();
        $arr = array(
            'id' => $day_data->id,
            'day_title' => $day_data->day_title,
            'day' => $day_data->day,
            'details' => $day_data->details,
            'hotel' => !empty($hotel_data) ? $hotel_data : (object) array(),
            'transport' => !empty($transport_data) ? $transport_data : (object) array(),
            'train' => !empty($train_data) ? $train_data : (object) array(),
            'cruise' => !empty($cruise_data) ? $cruise_data : (object) array(),
            'flight' => !empty($flight_data) ? $flight_data : (object) array(),
            'activity' => !empty($activity_data) ? $activity_data : (object) array(),
        );
        return $arr;
    }
    public function mange_package_price($request)
    {

        $itenary_id = $request['itenary_id'];
        $price_for_id = $request['price_for_id'];
        $insert_data['itinerary_id'] = $itenary_id;
        $insert_data['price_for_id'] = $price_for_id;
        $pricedata = (isset($request['pricedata'])) ? $request['pricedata'] : array();
        $remove_id = (isset($request['remove_id'])) ? json_decode($request['remove_id']) : array();
        if (!empty($pricedata)) {
            foreach ($pricedata as $post_data) {
                $insert_data['validity'] = $post_data['validity'];
                $insert_data['price_for_id'] = $post_data['validity'];
                $insert_data['price_type'] = $post_data['price_type'];
                $insert_data['currency'] = $post_data['currency'];
                $insert_data['price'] = $post_data['price'];
                $insert_data['details'] = $post_data['details'];
                $check_id = isset($post_data['id']) ? $post_data['id'] : '';
                if ($check_id == null || $check_id == " ") {
                    $x = $this->insert_data('itinerary_price', $insert_data);
                }
                $x = $this->update_data('itinerary_price', $insert_data);
            }
        }
        return $x;
    }
}