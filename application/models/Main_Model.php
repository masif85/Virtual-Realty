<?php
class Main_Model extends CI_Model {
        public function __construct()
        {
        $this->load->database();
		$this->load->library('session');			
	    }
 public function check_login($data){
	 $db = get_instance()->db->conn_id;	 
	 $username=mysqli_real_escape_string($db,$data['username']);	 
	 $password=mysqli_real_escape_string($db,$data['password']);	
	 $check=$this->db->query("SELECT * FROM tbl_users where username='$username' AND password='$password'");
	 if ($check->num_rows() > 0) {		
		if($check->row('status')==0)
		{
			redirect(base_url('Login/notactive')); 
		}		
		  $user_data = array(
			'username' => $check->row('username'),
			'user_type' => $check->row('user_type'),
			'full_name' => $check->row('first_name')." ".$check->row('last_name'),
			'contactno' => $check->row('contactno'),			
			'status' => $check->row('status'),	
			'profile_pic'=>$check->row('profile_pic'),
			'email' => $check->row('email'),
			'default_page' =>  $check->row('default_page'),
			'id' => $check->row('id'),			
			'sess_user_role' => $check->row('user_role'));			 
			$this->session->set_userdata($user_data);
			$return = array("status"=>1,"redirect"=>$check->row('default_page'),"msg"=>"");
		 $login_details=array("last_login"=>date("Y-m-d H:i:s"));
		 $this->update_data("tbl_users", $login_details,"id",$check->row('id'));
			} 
		else 
		{			
			$return =array("status"=>0,"redirect"=>"login","msg"=>"Invalid Username or Password");
		}	
        return $return;	
 }
	


public function fetch_row($columns="",$table="",$where="")
{
	
	$Q=$this->db->query("SELECT $columns FROM $table $where");
		if ($Q->num_rows() > 0) {
		$return = $Q->row();		
		} 
		else {			
		$return ='0';
			}
        return $return;
}
 
	
public function fetch_row_counts($fields="",$table="",$where="")
{
	
	$Q=$this->db->query("SELECT $fields FROM $table $where");
		if ($Q->num_rows() > 0) {
		$return = $Q->row();		
		} 
		else {			
		$return ='0';
			}
        return $return;
}	

	public function fetch_rows($table="",$where="")
{
	
	$Q=$this->db->query("SELECT * FROM $table $where");
		if ($Q->num_rows() > 0) {
		$return = $Q->result();		
		} 
		else {			
		$return ='0';
			}
        return $return;
}
 

	
	public function fetch_rows_joins($joins,$table="",$where="")
{

	$Q=$this->db->query("SELECT $joins FROM $table $where");
		if ($Q->num_rows() > 0) {
		$return = $Q->result();		
		} 
		else {			
		$return ='0';
			}
        return $return;
}

public function gen_query($query="")
{
	$this->db->query($query); 
	return 1;
}
 
 public function insert_data($table="",$form_data="")
 {
	 	$this->db->insert($table, $form_data);
		if ($this->db->affected_rows() == '1')
		{
			$order_data=$this->db->order_by('id',"desc")->limit(1)->get($table)->row();
			$return =$order_data->id;
			//$this->db->insert_id();			
		} 
		else {			
		$return =0;
			}
        return $return; 
 }
 
 
 
 public function update_data($table,$data,$idname,$id)
 {
	$this->db->where($idname, $id);
	$query = $this->db->update($table,$data);
	return $this->db->affected_rows(); 
 }

	public function single_value($whereClause, $requiredFieldName, $table) {         
                                $this->db->select($requiredFieldName);
                                $this->db->from($table);
                                $this->db->where($whereClause);                                
                                $resultSet = $this->db->get();
                                //echo $this->db->last_query();exit;
                                if ($resultSet->num_rows() > 0) {
                                                $returnValue = $resultSet->row();
                                                $returnValue = $returnValue->$requiredFieldName;
                                } else {
                                                $returnValue = 0;
                                }
                                $resultSet->free_result();
                                return $returnValue;
                }

 
 
 public function delete_query($id,$table,$idname)
 {
	$this->db->query("DELETE FROM $table where $idname='$id'"); 
	return 1;
 }
	


public function send_email($email,$name)
{
	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->CharSet = 'UTF-8';
	$mail->clearAllRecipients();
	$mail->SMTPDebug = 0;
	$mail->Debugoutput = 'html';
	$mail->Host = "mail.upp.ae";
	$mail->SMTPAutoTLS = false;
	$mail->Port = 25;
	$mail->SMTPAuth = false;
	$mail->Username = "admin.it";
	$mail->Password = "UPP@2020";
	//$mail->addAttachment('إجراءات التسليم.jpg', 'إجراءات التسليم.jpg');	
	$mail->setFrom('noreply@upp.ae', 'MOE Bookstore');
	$mail->addAddress($email,$name);
	$mail->addBCC('Mohamed.Abdulfattah@upp.ae','Muhammad Abudlfattah');
	$mail->addBCC('muhammad.asif@upp.ae','Muhammad Asif');
	$mail->Subject = 'Activate Your Account, Moe Bookstore';
	$msg_body="Dear $name, <br />
	Thank you for registering with MOE Bookstore, <br />
	In order to access your Store kindly click on <a href='".base_url()."Register/activate_email/".md5($email)."'>Activate</a> or copy below link to activate <br />
	<a href='".base_url()."Register/activate_email/".md5($email)."'>".base_url()."Register/activate_email/".md5($email)."</a>
	";
	$mail->msgHTML($msg_body);
		if (!$mail->send()) {
			return 0;
		} else {
			return 1;
		}		
}
	
public function act_email($emails){
	 $check=$this->db->query("SELECT * FROM tbl_users where md5(user_email)='$emails'");
	 if ($check->num_rows() == 1) {
		$id=$check->row('id');
		$contactno=$check->row('contactno');
		// echo "UPDATE tbl_users set status=1 where type_id='$id' AND status=0"; exit;
		$this->db->query("UPDATE tbl_users set status=1 where id='$id' AND status=0");
		return array("verified"=>1,"id"=>$id,"contactno"=>$contactno,"is_updated"=>$this->db->affected_rows());
	 }
	else
	{
		//echo "noooo";exit;
		return array("verified"=>0);
	}
}	
public function sms_api($otp,$contactno){
	//echo $otp,$contactno;exit;
	 $data_string = '{
                                                                                "userName":"UPP",
                                                                                "priority":0,
                                                                                "referenceId":"124154324",
                                                                                "dlrUrl":null,
                                                                                "msgType":0,
                                                                                "senderId":"Book Store",
                                                                                "message":"Thank you for Registration Kindly use this OTP to activate your Account:'.$otp.'",
                                                                                "mobileNumbers":{
                                                                                "messageParams":[
                                                                                {
                                                                                "mobileNumber":"'.$contactno.'"
                                                                                }
                                                                                ]
                                                                                },
                                                                                "password":"Up@123" 
                                                                                }';
                                                                               // Syanpse Rest API URL  
                                                                                
	
		$your_url='http://api.me.synapselive.com/v1/multichannel/messages/sendsms';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $your_url);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						'Content-Type: application/json;charset=utf-8',
						'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);
																				//print_r($result);exit;
         curl_close($ch);
	return 1;
}
	public function get_user_info(){
		return $this->session->userdata('id');
	}
public function create_sms($id="",$contactno="")
{	
	if($id=="" && $contactno=="")
	{
	$id=$this->session->userdata('id');
	$contactno=$this->session->userdata('contactno');		
		
	}
	
	$today=date("Y-m-d H:i:s");
	$currentDate = strtotime($today);
	$futureDate = $currentDate+(60*5);
	$next_hit = date("Y-m-d H:i:s", $futureDate);
	$otp=substr($id.rand ( 1000 , 9999 ), 0, 5);	
	
	$check_usr=$this->db->query("SELECT * FROM tbl_users where id='$id' AND status=1");	
	//echo "SELECT * FROM tbl_users where id='$id' AND status=1"; exit;
	//echo "<pre>";print_r($check_usr); exit;
	if($check_usr->num_rows() == 1){
	$check=$this->db->query("SELECT * FROM tbl_otp where user_id='$id' and contact_no='$contactno' and act_status=0");	
	 if ($check->num_rows() == 1) {		 
		
		 if($today>=$check->row('next_hit'))
		 {	 
			
			$this->db->query("UPDATE tbl_otp SET otp='$otp',next_hit='$next_hit' WHERE id='".$check->row('id')."' AND user_id='$id' AND contact_no='$contactno'");	
			//return array("otp"=>$otp,"is_updated"=>$this->db->affected_rows(),"next_hit"=>$check->row('next_hit'));
			if($this->db->affected_rows()==1)
				{				
				$this->sms_api($otp,$check->row('contact_no'));					                			
				return array("otp"=>$otp,"is_updated"=>$this->db->affected_rows(),"next_hit"=>$next_hit);
				//$this->load->view("activate_sms");					
				}
				else
				{	
				$timer=array();
				$to_time=strtotime($next_hit);				
				$from_time = strtotime(date("Y-m-d H:i:s"));
				$timer['time']= round(abs($to_time - $from_time) / 60,2);				
				return array("otp"=>0,"is_updated"=>0,"next_hit"=>$check->row('next_hit'));
				//$this->load->view("create_sms",$timer);
				}
		 }
		 else
		 {
			return array("otp"=>0,"is_updated"=>0,"next_hit"=>$check->row('next_hit'));
		 }		 
	 }
		else{	

		$this->db->query("INSERT INTO tbl_otp (otp,user_id,contact_no,next_hit) VALUES('".$otp."','$id','$contactno','')");
		$this->create_sms();
		return array("otp"=>0,"is_updated"=>0,"next_hit"=>$next_hit);
		}
	}
	//echo "abc";exit;
	//$this->load->view('".$this->session->userdata('default_page')."');
	//echo 'sds';exit;
	redirect(base_url().'tmp_page');
	
	
	return false;
}
public function update_info($data_array, $where_clause, $table){
		$this->db->where($where_clause);
		$result_array = array_map('trim', $data_array);		
		if($this->db->update($table, $result_array)){
			return 1;
		}
	
		return 0;
	}	
}
?>