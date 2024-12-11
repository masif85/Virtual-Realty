<?php   
require(APPPATH.'libraries/REST_Controller.php');     
class App extends REST_Controller {    
	/**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
       parent::__construct();
       $this->load->database();
    }       
    /**
     * Get All Data from this method.
     *
     * @return Response
    */

	public function index_get()
	{		
		
		
	$type=@$this->input->get('type');
		
		
		//List that shows general assets detail and topics, title of editions with its id
		if($type=='general_assets'):
		$platform=@$this->input->get('platform');
		if($platform && $type):
		$platform=$this->db->query("SELECT * FROM vr_editions where platform like '%$platform%' and disable=0 and status=1");
		$data=$platform->result();
		foreach($data as $row):
		$assets=$this->db->query("SELECT * FROM vr_assets where edition_id='$row->id'");
		$assetsa=$assets->result();
		foreach($assetsa as $asset):
		$general_assets[]=array("asset_id"=>$asset->id,
							 "recognition_imageUrl"=>"https://vr.khaleejtimes.com/public/editions/pics/$asset->image",
							 "asset_url"=>"https://vr.khaleejtimes.com/public/editions/assets/$asset->asset");
		endforeach;
		endforeach;
		
		foreach($data as $row):
		$topics=array("editions_id"=>$row->id,"editions_name"=>$row->title,"type"=>$row->type,"covers_pic_url"=>"https://vr.khaleejtimes.com/public/editions/pics/$row->cover_picture");	
		$topic[]=array("$row->topics"=>$topics);
		endforeach;
		$timestamp=date("Y-m-d H:i:s");
		$data_final=$data_final=array("status"=>"success",
						 "general_assets"=>$general_assets,
						 "editions"=>$topic,
						"updated_timestamp"=>$timestamp);
		
		
		$this->set_response($data_final, REST_Controller::HTTP_OK);
		endif;
		endif;
		
		//List of all editions under one topics
		
		if($type=="topic_editions"):
		$additional="  where disable=0  and status=1";
		$topic_name=@$this->input->get('topic');		
		if($topic_name && $type):	
		if($topic_name!='all'):
		$additional="where topics = '$topic_name' and disable=0  and status=1";
		endif;
		
		$topics=$this->db->query("SELECT * FROM vr_editions $additional ORDER BY id DESC");
		$data=$topics->result();		
		foreach($data as $row):			
		$editions[]=array("editions_id"=>$row->id,
					"editions_name"=>$row->title,
					"type"=>$row->type,	  
					"topic"=>$row->topics,
					"covers_pic_url"=>"https://vr.khaleejtimes.com/public/editions/pics/$row->cover_picture",
					"description"=>$row->description,
					"fee"=>$row->fee,
					"last_updated"=>$row->last_updated,
					"created_on"=>$row->created_on);
		endforeach;
		$timestamp=date("Y-m-d H:i:s");
		$data_final=$data_final=array("status"=>"success",
						"type"=>$topic_name,
						"editions"=>$editions,
						"updated_timestamp"=>$timestamp);		
		$this->set_response($data_final, REST_Controller::HTTP_OK);		
		endif;		
		endif;
		
		
		if($type=="type_editions"):		
			$additional="  where disable=0  and status=1";
			$type_name=@$this->input->get('topic');

			if($type_name && $type):	
			if($type_name!='all'):
			$additional="where type = '$type_name' and disable=0  and status=1";
		endif;
		
		$topics=$this->db->query("SELECT * FROM vr_editions $additional  ORDER BY id DESC");
		$data=$topics->result();		
		foreach($data as $row):			
		$editions[]=array("editions_id"=>$row->id,
					"editions_name"=>$row->title,
					"type"=>$row->type,	  
					"topic"=>$row->topics,
					"covers_pic_url"=>"https://vr.khaleejtimes.com/public/editions/pics/$row->cover_picture",
					"description"=>$row->description,
					"fee"=>$row->fee,
					"last_updated"=>$row->last_updated,
					"created_on"=>$row->created_on);
		endforeach;
		$timestamp=date("Y-m-d H:i:s");
		$data_final=$data_final=array("status"=>"success",
						"type"=>$type_name,
						"editions"=>$editions,
						"updated_timestamp"=>$timestamp);		
		$this->set_response($data_final, REST_Controller::HTTP_OK);		
		endif;		
		endif;
		
		
		
		
		
		//List of all the recognition image and assets for any editions
		
		if($type=="recognition_image"):		
		$edition_id=@$this->input->get('edition_id');
		if($edition_id && $type):		
		$topics=$this->db->query("SELECT * FROM vr_editions where id = '$edition_id' and disable=0  and status=1");
		$data=$topics->result();	
		$timestamp=date("Y-m-d H:i:s");
		foreach($data as $row):			
		$editions[]=array("title"=>$row->title,	
						  "type"=>$row->type,
						  "covers_pic_url"=>"https://vr.khaleejtimes.com/public/editions/pics/$row->cover_picture",
						  "description"=>$row->description,
						  "topic_name"=>$row->topics);
		
		
		$assets=$this->db->query("SELECT * FROM vr_assets where edition_id='$row->id'");
		$assetsa=$assets->result();
		foreach($assetsa as $asset):
		$general_assets[]=array("asset_id"=>$asset->id,
							 "recognition_imageUrl"=>"https://vr.khaleejtimes.com/public/editions/pics/$asset->image",
							 "asset_url"=>"https://vr.khaleejtimes.com/public/editions/assets/$asset->asset");
		endforeach;
		$data_final=$data_final=array("status"=>"success",
						"title"=>$row->title,	
						"type"=>$row->type,			  
						  "covers_pic_url"=>"https://vr.khaleejtimes.com/public/editions/pics/$row->cover_picture",
						  "description"=>$row->description,
						  "topic_name"=>$row->topics,
						"Contents"	=> $general_assets,		  
						"updated_timestamp"=>$timestamp);		
		endforeach;			
		$this->set_response($data_final, REST_Controller::HTTP_OK);		
		endif;		
		endif;
		
		
		
				//List of all the categories
		
		if($type=="categories"):		
		$name=@$this->input->get('category_name');
		if($name && $type):		
		if($name!='all'):
		$additional="where name = '$name'  and status=1";
		endif;
		
		$topics=$this->db->query("SELECT * FROM vr_categories $additional ORDER BY `order` ASC");
		$data=$topics->result();	
		$timestamp=date("Y-m-d H:i:s");
		foreach($data as $row):			
		$editions[]=array("id"=>$row->id,
						  "type"=>$row->type,
						"name"=>$row->name,
						 "cat_url"=>"https://vr.khaleejtimes.com/public/editions/category_pics/$row->image");
	
		$data_final=$data_final=array("status"=>"success",						
						"Contents"	=> $editions,		  
						"updated_timestamp"=>$timestamp);		
		endforeach;			
		$this->set_response($data_final, REST_Controller::HTTP_OK);		
		endif;		
		endif;
		
		
		/*
        $input = $this->input->post();		
		unset($input['submit']);
        $this->db->insert('items',$input);     
        $this->response(['Item created successfully.'], REST_Controller::HTTP_OK);
		*/
	}
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function index_post()
    {
		$type=@$this->input->post('type');
		echo $type;
    }
	
	
	public function register_post()
	{
		
		$post_data = json_decode(file_get_contents('php://input'),true); 
		/*$fullname=$this->input->post("fullname");
		$email=$this->input->post("email");
		$password=$this->input->post("password");*/
		$fullname=$post_data['fullname'];
		$email=$post_data['email'];
		$mobile=$post_data['mobile'];
		$password=$post_data['password'];		
		if($fullname && $email && $password):
		$check=$this->Main_Model->fetch_row("*","vr_users"," where email='$email'");
		
		if(!$check->id):
		$data=array("fullname"=>$fullname,
					"email"=>$email,
					"mobile"=>$mobile,
					"password"=>$password,
					"status"=>1);
		$id=$this->Main_Model->insert_data("vr_users",$data);
		if($id):
		$datas=array("token"=>md5($id));
		$this->Main_Model->update_data("vr_users",$datas,"id",$id);
		$response=array("status"=>"Success",
			"message"=>"Successfully registered",
			"token"=>md5($id)
			);		
		else:
			$response=array("status"=>"Failure",
			"message"=>"Unknown Error Occurred"			
			);
		endif;
		
		else:
			$response=array("status"=>"Failure",
			"message"=>"User already exists"			
			);
		endif;
		else:
/*		$message=array();
		if(!$fullname):
		$message[]="Full Name cannot be empty ";
		endif;
		if(!$email):
		$message[]="Email cannot be empty ";
		endif;
		if(!$password):
		$message[]="Password cannot be empty ";
		endif;*/
		$response=array("status"=>"Failure",
			"message"=>"Mandatory fields are missing"			
			);
		endif;
		$this->response($response, REST_Controller::HTTP_OK);
	}
	
	
	public function userInfo_post()
	{
		$post_data = json_decode(file_get_contents('php://input'),true);
		$token=$post_data["token"];
		$data=$this->Main_Model->fetch_row("fullname,email,mobile,token","vr_users"," where token='$token' LIMIT 1");
		if($data):
		$response=array("status"=>'Success',
					   "content"=>$data);
		else:
		$response=array("status"=>"Failure",
			"message"=>"Profile not found");	
		endif;
		$this->response($response, REST_Controller::HTTP_OK);
	}
	
	
	public function login_post()
	{
		$post_data = json_decode(file_get_contents('php://input'),true);
		
		$username=$post_data["email"];
		$password=$post_data["password"];
		$data=$this->Main_Model->fetch_row("*","vr_users"," where email='$username' and password='$password' and status=1");
		if($data):
		$response=array("status"=>'Success',
					   "message"=>"Successfully logged in",
					   "token"=>$data->token);
		else:
		$response=array("status"=>"Failure",
			"message"=>"Incorrect email id or password");	
		endif;
		$this->response($response, REST_Controller::HTTP_OK);
		
	}
	
	public function sendactivation_post()
	{
		$post_data = json_decode(file_get_contents('php://input'),true);
		$username=$post_data["email"];
		$data=$this->Main_Model->fetch_row("*","vr_users"," where email='$username' and status=1");
		if($data):
		$act_code = random_int(10000, 99999);
		$shuffled = str_shuffle($act_code.$data->id);
		$datas=array("user_id"=>$data->id,
					"email"=>$data->email,
					"act_code"=>$shuffled,
					"date"=>date("Y-m-d"),
					"status"=>0);
		$updata=array("status"=>1);
		$this->Main_Model->update_data("tbl_reset_pwd",$updata,"user_id",$data->id);
		$id=$this->Main_Model->insert_data("tbl_reset_pwd",$datas);	
		
		$msg_body="Kindly use actiation code: <h2>$shuffled</h2>";
		$email_data_admin=array("from"=>'noreply@competitions.khaleejtimes.com',
							"to"=>$data->email,														
							"subject"=>'AR - Khaleej Times - Forgot Password',
							"msg_body"=>$msg_body);
			send_mail($email_data_admin);		
		$response=array("status"=>'Success',
						"act_code"=>$shuffled,
					   "message"=>"code successfully sent to your email id");
		else:
		$response=array("status"=>"Failure",
			"message"=>"Email address not registered");	
		endif;
		$this->response($response, REST_Controller::HTTP_OK);
	}
	
	public function forgetpassword_post()
	{
		$post_data = json_decode(file_get_contents('php://input'),true);
		$activation_code=$post_data["activation_code"];
			
		$check=	$this->Main_Model->fetch_row("*","tbl_reset_pwd"," where act_code='$activation_code' and status=0");
		if($check):
			$data=$this->Main_Model->fetch_row("*","vr_users"," where id='$check->user_id' and status=1");
			if($data):
				$act_code = random_int(10000, 99999);
				$shuffled = str_shuffle($act_code.$data->id);
				$datam=array("status"=>1);
				$id=$this->Main_Model->update_data("tbl_reset_pwd",$datam,"id",$check->id);
				$datas=array("fullname"=>$data->fullname,
						   "email"=>$data->email
						   );
				$response=array("status"=>'Success',
							   "content"=>$datas);
			else:
				$response=array("status"=>"Failure",
				"message"=>"User Information not found");	
			endif;
			else:
			$response=array("status"=>"Failure",
				"message"=>"Activation code not matching");	
		endif;
		$this->response($response, REST_Controller::HTTP_OK);
	}
	
	
	public function updatepassword_post()
	{
		$post_data = json_decode(file_get_contents('php://input'),true);
		$email=$post_data["email"];
		$password=$post_data["password"];		
			
	
			$data=$this->Main_Model->fetch_row("*","vr_users"," where email='$email' and status=1");
			if($data):				
				$datam=array("password"=>$password);
				$id=$this->Main_Model->update_data("vr_users",$datam,"id",$data->id);			
				$response=array("status"=>'Success',
								"message"=>"Password successfully updated",
							   "token"=>md5($data->id));
			else:
				$response=array("status"=>"Failure",
				"message"=>"Error while updating password");	
			endif;
			
		$this->response($response, REST_Controller::HTTP_OK);
	}
	
	
	public function savetoken_post()
	{
		$post_data = json_decode(file_get_contents('php://input'),true);
		$email=$post_data["email"];
		$token=$post_data["token"];		
			
	
			$data=$this->Main_Model->fetch_row("*","vr_users"," where email='$email' and status=1 and token_push_notification is not null");
			if($data):				
				$datam=array("token_push_notification"=>$token);
				$id=$this->Main_Model->update_data("vr_users",$datam,"id",$data->id);			
				$response=array("status"=>'Success',
								"message"=>"Token Saved successfully");
			else:
				$response=array("status"=>"Failure",
				"message"=>"Error while trying to save token");	
			endif;
			
		$this->response($response, REST_Controller::HTTP_OK);
	}
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function index_put()
    {
		$table="";
		$key = $this->_args['X-API-KEY'];
		$table=$this->rest->target_table;		
		$id=$this->input->get("id");
		$ukey=$this->input->get("uniquekey");
		$message="";		
		//$table="tbl_vouchers2";
        //$input = $this->put();
		$data_final = array(
			'ResponseCode'=>0,
			'Response'=>"Failure",
			'Description'=>$message
			);	
		
		
		$this->response($data_final, REST_Controller::HTTP_OK);
    }     
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function index_delete($id)
    {
		/*
        $this->db->delete('tbl_vouchers', array('id'=>$id));
       
        $this->response(['Item deleted successfully.'], REST_Controller::HTTP_OK);
		*/
    }    	
}