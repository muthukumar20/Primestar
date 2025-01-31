<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Rajkamal
* Description: Full Stock Model class
*/
Class Regular_Booking_Model extends CI_Model {

    public function add_booking_details($data) {

        $query = $this->db->insert('booking', $data);
        if ($query) {
            return $this->db->insert_id();
            //return true;
        } else {
            return false;
        }
    }
    
    public function update_booking_details($data, $id) {
        $this->db->where('id', $id);
        $query = $this->db->update('booking', $data);
        $this->db->last_query();
        $query = $this->db->affected_rows();		
        if ($query) {
            return true;
        } else {
            return false;
        }
    }
    
    public function add_bookingslot_details($data) {

        $query = $this->db->insert('bookingslot', $data);
        if ($query) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }
    
    public function update_wallet_amount($data, $id) {
        $this->db->where('custid', $id);
        $query = $this->db->update('wallet', $data);
        $this->db->last_query();
        $query = $this->db->affected_rows();		
        if ($query) {
            return true;
        } else {
            return false;
        }
    }   

    public function show_booking_timeslot($data){

        $this->db->select('pr.id, pr.cid, ct.courtname, ct.from_time, ct.to_time');
        $this->db->from('pricing as pr');
        $this->db->join('court as ct', 'ct.id = pr.cid', 'left');  
        if($data['sid'] != ''){
            $this->db->where('pr.sid', $data['sid'] );
        }
        if($data['lid'] != ''){
            $this->db->where('pr.lid', $data['lid'] );
        }
        $this->db->where('pr.delete_status !=', 1);
        $this->db->order_by('pr.id','ASC');
        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            $row = $query->result_array();
            return $row;
        }else{
            return false;
        }
    }
    
    public function get_dayslist($dayname){

        $this->db->select('*');
        $this->db->from('dayname_list');
        if($dayname != ''){
            $this->db->where('dayname', $dayname);
        }
        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            $row = $query->row_array();
            return $row;
        }else{
            return false;
        }
    }
    
    public function get_courtid($cname){
        $this->db->select('id');
        $this->db->from('court'); 
        if($cname != ''){
            $this->db->where('courtname', $cname );
        } 
        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            $row = $query->row_array();
            return $row;
        }else{
            return false;
        }
    } 
    
    public function check_timeslot_exist($cid, $fromtime, $totime, $day_id, $holiday_id){
         
            $this->db->select('pst.id, pr.holiday_id');
            $this->db->from('pricingslot as pst');
            $this->db->join('pricing as pr', 'pr.id = pst.pid', 'left');
            if($cid != ''){
                $this->db->where('pr.cid', $cid );
            }
            if($fromtime != ''){
                $this->db->where('pst.fromtime <=', $fromtime);
            }
            if($totime != ''){
                $this->db->where('pst.totime >=', $totime);
            }

            $where = "( CASE WHEN pr.day_type='1' THEN pr.fromday <= '$day_id' AND pr.today >= '$day_id' WHEN pr.day_type='0' THEN pr.fromday = '$day_id' ELSE pr.holiday_id = '$holiday_id' END )";
            $this->db->where($where);
            $this->db->where('pr.delete_status !=', 1);
//            echo $this->db->_compile_select();
//            die();
            $query = $this->db->get();
            if ( $query->num_rows() > 0 )
            {
                $row = $query->result_array();
                return $row;
            }else{
                return false;
            }
       
    }
    
    public function check_bookedslot_exist($cid, $fromtime, $totime, $date,$day_id){
        $this->db->select('bst.id, bst.bid, bk.btype, bk.booked_by, bk.blocked_status, cust.name as customer_name');
        $this->db->from('bookingslot as bst');
        $this->db->join('booking as bk', 'bk.id = bst.bid', 'left');
        $this->db->join('customer as cust', 'cust.id = bk.customerid', 'left');
        if($cid != ''){
            $this->db->where('bst.courtid', $cid );
        } 
        if($fromtime != ''){
            $this->db->where('bst.booking_fromtime <=', $fromtime);
        }
        if($totime != ''){
            $this->db->where('bst.booking_totime >=', $totime);
        }
        if($date != ''){
            $this->db->where('bst.fromdate <=', $date);
            $this->db->where('bst.todate >=', $date);            
            $this->db->where('bst.days', $day_id);
        }
        $this->db->where('bk.bstatus', 1);
        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            $row = $query->row_array();
            return $row;
        }else{
            return false;
        }
    }
    
    public function get_booking_details($booked_slotid){
        $this->db->select('bst.id, bst.bid, bst.booking_fromtime, bst.booking_totime, bst.fromdate, bst.todate, bst.days, cust.name as customer_name, cust.mobile as customer_mobile, bk.bookedon, bk.paystatus, bk.customerid, bk.booking_no, bk.totamt as gross_amount, bk.net_total as net_amount, bk.btype as booking_type, bk.discount_amount, bk.advance_amount, bk.balamt as balance_amount, bk.paidamt as paid_amount_old, bst.amount as paid_amount, bk.remarks, ct.courtname ');
        $this->db->from('bookingslot as bst');
        $this->db->join('booking as bk', 'bk.id = bst.bid', 'left');
        $this->db->join('customer as cust', 'cust.id = bk.customerid', 'left');
        $this->db->join('court as ct', 'ct.id = bst.courtid', 'left');  
        if($booked_slotid != ''){
            $this->db->where('bst.id', $booked_slotid );
        }        
        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            $row = $query->row_array();
            return $row;
        }else{
            return false;
        }
    }
    
       
    public function view_booking_details($booking_id){
        $this->db->select('bst.id, bst.bid, bst.booking_fromtime, bst.booking_totime, bst.fromdate, bst.todate, bst.amount, dlt.dayname, sp.sportsname, loc.location, bk.booking_no, ct.courtname ');
        $this->db->from('bookingslot as bst');
        $this->db->join('booking as bk', 'bk.id = bst.bid', 'left');
        $this->db->join('customer as cust', 'cust.id = bk.customerid', 'left');
        $this->db->join('court as ct', 'ct.id = bst.courtid', 'left'); 
        $this->db->join('sports as sp', 'sp.id = bst.sid', 'left');
        $this->db->join('location_booking as loc', 'loc.id = bst.lid', 'left');
        $this->db->join('dayname_list as dlt', 'dlt.dayid = bst.days', 'left');
        if($booking_id != ''){
            $this->db->where('bst.bid', $booking_id );
        }        
        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            $row = $query->result_array();
            return $row;
        }else{
            return false;
        }
    }
    
    public function show_timeslot_details($data){

        $this->db->select('pst.id, pst.pid, ct.courtname, pr.cid, pr.sid, sp.sportsname, pst.fromtime, pst.totime, pst.cost, pr.lid, loc.location');
        $this->db->from('pricing as pr');
        $this->db->join('court as ct', 'ct.id = pr.cid', 'left'); 
        $this->db->join('sports as sp', 'sp.id = pr.sid', 'left'); 
        $this->db->join('location_booking as loc', 'loc.id = pr.lid', 'left'); 
        $this->db->join('pricingslot as pst', 'pst.pid = pr.id', 'left'); 
        if($data['id'] != ''){
            $this->db->where('pst.id', $data['id'] );
        }
        $this->db->where('pr.delete_status !=', 1);
        $this->db->order_by('pr.id','DESC');
        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            $row = $query->result_array();
            return $row;
        }else{
            return false;
        }
    }
    
    
    
    public function get_sportslist(){
        $this->db->from('sports');
        $this->db->where('status', 1 );
        $this->db->order_by('id','DESC');
        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            $row = $query->result_array();
            return $row;
        }else{
            return false;
        }
    }
    
    public function get_customerDetails($id){
        
        $this->db->select('cust.id,cust.email,cust.name,wal.amount');
        $this->db->from('customer as cust');
        $this->db->join('wallet as wal', 'wal.custid = cust.id', 'left');  
        if($id != ''){
            $this->db->where('custid', $id );
        }
        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            $row = $query->row_array();
            return $row;
        }else{
            return false;
        }
    }

    public function get_courtlist($data){
        $this->db->from('court');  
        if($data['sports_id'] != ''){
            $this->db->where('sid', $data['sports_id'] );
        }
        if($data['location_id'] != ''){
            $this->db->where('lid', $data['location_id'] );
        }
        $this->db->where('status', 1 );
        $this->db->order_by('id','DESC');
        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            $row = $query->result_array();
            return $row;
        }else{
            return false;
        }
    }
  
    public function get_holidayslist(){
        $this->db->select(" id, DATE_FORMAT(holidaydate,'%d-%m-%Y') as holidaydate", FALSE);
        $this->db->from('holidays');
        $this->db->order_by('id','ASC');
        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            $row = $query->result_array();
            return $row;
        }else{
            return false;
        }
    }

    public function get_customer_booking_details($customer_id){

        $this->db->select('SUM(bk.totamt) as total_deductable_amount');
        $this->db->from('booking as bk');
        $this->db->join('customer as cust', 'cust.id = bk.customerid', 'left');
        $this->db->where('bk.customerid', $customer_id );
        $this->db->where('bk.blocked_status', '0');
        $this->db->where('bk.booked_by', $customer_id);
        //$this->db->order_by('bk.id','ASC');
        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            $row = $query->row_array();
            return $row;
        }else{
            return false;
        }
    }
    
    public function getCustomerid($customer_email){

        $this->db->select('*');
        $this->db->from('customer');
        $this->db->where('email', $customer_email );
        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            $row = $query->row_array();
            return $row;
        }else{
            return false;
        }
    }
    
    public function get_locationlist($data){
        $this->db->select('loc.location, loc.id as location_id');
        $this->db->from('court as ct');  
        $this->db->join('location_booking as loc', 'loc.id = ct.lid', 'left');  
        if($data['sports_id'] != ''){
            $this->db->where('ct.sid', $data['sports_id'] );
        }
        $this->db->where('loc.status', 1 );
        $this->db->group_by('ct.lid');
        $this->db->order_by('ct.id','DESC');
        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            $row = $query->result_array();
            return $row;
        }else{
            return false;
        }
    }

}

?>