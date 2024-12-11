<?php
function getData($url){
  $xmlData = trim(file_get_contents($url));
  $arrayData=simplexml_load_string($xmlData) or die("Error: Cannot create object");
  $return=(is_object($arrayData)?$arrayData:0);
  return $return;
}
function setMessage($MessageID, $Message){
	$CI = & get_instance();
	$sessData = array($MessageID => $Message);
	$CI->session->set_userdata($sessData);
}
function isSuperAdmin(){
	$CI = & get_instance();
	if($CI->session->userdata('sess_agent_type') == 'super-amdin'){
		return true;
	}
	return false;
}

	 function nice_number($n) {
	
        // first strip any formatting;
        $n = (0+str_replace(",", "", $n));

 

        // is this a number?
        if (!is_numeric($n)) return false;

 

        // now filter it;
        if ($n > 1000000000000) return round(($n/1000000000000), 2).' trillion';
        elseif ($n > 1000000000) return round(($n/1000000000), 2).' billion';
        elseif ($n > 1000000) return round(($n/1000000), 2).' million';
        elseif ($n > 1000) return round(($n/1000), 2).' thousand';

 

        return number_format($n);
    }


function selectNavLink($module, $selected_slug, $selected_slug2='', $selected_slug3='', $selected_slug4='', $selected_slug5=''){
	$CI = & get_instance();
	if($module == $selected_slug){
		return 'class="active"';	
	}
	
	if($CI->uri->segment(2) == $selected_slug2 && $selected_slug2 != ''){
		return 'class="active"';	
	}
	if($CI->uri->segment(2) == $selected_slug3 && $selected_slug3 != ''){
		return 'class="active"';	
	}
	if($CI->uri->segment(2) == $selected_slug4 && $selected_slug4 != ''){
		return 'class="active"';	
	}
	if($CI->uri->segment(2) == $selected_slug5 && $selected_slug5 != ''){
		return 'class="active"';	
	}
	return '';
}

function selectNavLinkDd($module, $selected_slug){
	if($module == $selected_slug){
		return 'active';	
	}
	return '';
}

function convert_number_to_arabic($str){
	$western_arabic = array('0','1','2','3','4','5','6','7','8','9');
	$eastern_arabic = array('٠','١','٢','٣','٤','٥','٦','٧','٨','٩');
	$str = str_replace($western_arabic, $eastern_arabic, $str);	
	return $str;
}
function getMessage($MessageID){
	$CI = & get_instance();
	$messageData = '';
	if((bool)($CI->session->userdata($MessageID)) == TRUE){
		$messageData = $CI->session->userdata($MessageID);
		$CI->session->unset_userdata($MessageID);
	}
	return $messageData;
}
function getCurrentDate(){
	date_default_timezone_set('Asia/Dubai');
	return date('Y-m-d H:i:s');
}
function getCurrentTime(){
	date_default_timezone_set('Asia/Dubai');
	return date('h:i A', time());
}
function displayDate($date){
	$date = date_create($date);
	return date_format($date, 'd M, Y h:i A');
}

function displayCallDate($date){
	$date = date_create($date);
	return date_format($date, 'd M h:i a');
}

function displayDateSimple($date){
	$date = date_create($date);
	return date_format($date, 'd M, Y');
}

function displayDateSimpleWithDay($date){
	$date = date_create($date);
	return date_format($date, 'D d, M');
}

function displayDate12hour($date){
	$date = date_create($date);
	return date_format($date, 'd M, Y h:i A');
}
function isNumber($Value){	
	if(preg_match('/^\d+$/',$Value)) {
	  return true;
	}
	return false;
}
function paginationList(){	
	$CI = & get_instance();	
	$perPage = $CI->session->userdata('recordsPerPage');
	$option1 = '';
	$option2 = '';
	$option3 = '';
	$option4 = '';
	$option5 = '';
	switch($perPage){
		case 20:
			$option1 = 'selected';
		break;
		case 50:
			$option2 = 'selected';
		break;
		case 100:
			$option3 = 'selected';
		break;
		case 200:
			$option4 = 'selected';
		break;
		case 500:
			$option5 = 'selected';
		break;
	}
	return '
			<select name="records_per_page" id="records_per_page" style="margin:6px 4px 0 0; border: 1px solid #CCCCCC; width: 57px;" onchange="changeRecordsPerPage();">
				<option value="20" '.$option1.'>20</option>
				<option value="50" '.$option2.'>50</option>
				<option value="100" '.$option3.'>100</option>
				<option value="200" '.$option4.'>200</option>
				<option value="500" '.$option5.'>500</option>
			</select>
			<input type="hidden" id="base___URL"  value="'.base_url().'"/>
			';
}

function shortDescription($string, $numberOfWords, $readMoreLink){
	$stringtArray = preg_split('//', $string, -1, PREG_SPLIT_NO_EMPTY);
	$arraySize  = sizeof($stringtArray);	
	$newString = '';
	if($arraySize > $numberOfWords){		
		for($i=0; $i<$numberOfWords; $i++){
			$newString.= $stringtArray[$i];	
		}
		return $newString.$readMoreLink;
	}
	return $string;		
}

function file_upload_settings(){
	$CI = & get_instance();
	$imagePath = '../cdn/';
	$config['upload_path'] = realpath(APPPATH . $imagePath);
	$config['allowed_types'] = 'doc|docx|pdf|txt';
	$config['max_size']	=(4*1024);
	$config['max_width']  = 0;
	$config['max_height']  = 0;
	$config['encrypt_name'] = TRUE;
	$CI->upload->initialize($config);
}

function excel_file_upload_settings(){
	$CI = & get_instance();
	$imagePath = '../imported-shipment-files/';
	$config['upload_path'] = realpath(APPPATH . $imagePath);
	$config['allowed_types'] = 'xls';
	$config['max_size']	=(20*1024);
	$config['max_width']  = 0;
	$config['max_height']  = 0;
	$config['encrypt_name'] = TRUE;
	$CI->upload->initialize($config);
}

function remove_picture($imageName){
	error_reporting(0);
	$imagePath = '../cdn/';
	unlink(realpath(APPPATH.$imagePath.'/'.$imageName));
	$extention = explode('.', $imageName);
	$ext = '.'.$extention[sizeof($extention)-1];
	$thumb = str_replace($ext, '', $imageName).'_thumb'.$ext;
	unlink(realpath(APPPATH.$imagePath.'/'.$thumb));
}

function remove_file($fileName){
	error_reporting(0);
	$filePath = '../cdn/';
	unlink(realpath(APPPATH.$filePath.'/'.$fileName));	
}

function message($text = 'Add / Update Successfull.', $type = 'success'){
	$return_msg = '';
	switch($type){
		case 'error':
			$return_msg = '
							<div class="alert alert-danger alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<h4 style="margin:0px;"><i class="icon fa fa-ban"></i> Error! <label style="font-weight:normal; font-size:14px;">'.$text.'</label></h4>								
							</div>
						';
		break;
		case 'success':
			$return_msg = '
							<div class="alert alert-success alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<h4 style="margin:0px;"><i class="icon fa fa-check"></i> Success! <label style="font-weight:normal; font-size:14px;">'.$text.'</label></h4>
							  </div>
							  ';
		break;
		case 'info':
			$return_msg = '
							<div class="alert alert-info alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<h4 style="margin:0px;"><i class="icon fa fa-info"></i> Info! <label style="font-weight:normal; font-size:14px;">'.$text.'</label></h4>
							</div>
			  			';
		break;
		case 'warning':
			$return_msg = '
							<div class="alert alert-warning alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<h4 style="margin:0px;"><i class="icon fa fa-warning"></i> Warning! <label style="font-weight:normal; font-size:14px;">'.$text.'</label></h4>
							</div>
			  			';
		break;
	}
	return $return_msg;
}
function filter_value($field_name, $empty_title = ''){
	$CI = & get_instance();
	if($CI->input->post($field_name)){
		return 	$CI->input->post($field_name);
	}
	return $empty_title;
}
function full_url(){
	/*$s = &$_SERVER;
	$ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
	$sp = strtolower($s['SERVER_PROTOCOL']);
	$protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
	$port = $s['SERVER_PORT'];
	$port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
	$host = isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : $s['SERVER_NAME'];
	
	return $protocol . '://' . $host . $port . $s['REQUEST_URI'];*/
}

function single_value($whereClause, $requiredFieldName, $table) {	
	$CI = & get_instance();
	$CI->db->select($requiredFieldName);
	$CI->db->from($table);
	$CI->db->where($whereClause);
	
	$resultSet = $CI->db->get();
	if ($resultSet->num_rows() > 0) {
		$returnValue = $resultSet->row();
		$returnValue = $returnValue->$requiredFieldName;
	} else {
		$returnValue = 0;
	}
	$resultSet->free_result();
	return $returnValue;
}

function single_row($whereClouse, $table, $limit = 0, $start = 0, $orderBy = '', $orderType = '') {
	$CI = & get_instance();
	$CI->db->select('*');
	$CI->db->from($table);
	$CI->db->where($whereClouse);
			
	if($limit > 0){
		$CI->db->limit($limit, $start);
	}	
	if($orderBy != ''){
		$CI->db->order_by($orderBy, $orderType);
	}
	
	$resultSet = $CI->db->get();
		
	if ($resultSet->num_rows() > 0) {		
		return $resultSet->row();	
	} 
	else {		
		return 0;
	}
}

function fetchDataAll($whereClouse, $table, $limit, $start, $orderBy, $orderType) {
	$CI = & get_instance();
	$CI->db->select('*');
	$CI->db->from($table);
	$CI->db->where($whereClouse);
			
	if($limit > 0){
		$CI->db->limit($limit, $start);
	}
	
	$CI->db->order_by($orderBy, $orderType);	
	$resultSet = $CI->db->get();
		
	if ($resultSet->num_rows() > 0) {		
		return $resultSet;	
	} 
	else {		
		return 0;
	}
}

function total_rows($whereClouse, $table) {
	$CI = & get_instance();
	$CI->db->select('*');
	$CI->db->from($table);
	$CI->db->where($whereClouse);				
	$resultSet = $CI->db->get();
		
	return $resultSet->num_rows();
}

function module_enabled($module_name, $permission_type, $agent_id) {	
	$CI = & get_instance();
	$agent_type = single_value("agent_id = '$agent_id'", 'agent_type', 'tbl_agents');
	if($agent_type == 'super-admin'){
		return true;	
	}
	
	$CI->db->select($permission_type);
	$CI->db->from('tbl_permissions');
	$CI->db->where('agent_id', $agent_id);
	$CI->db->where('module_name', $module_name);
	$CI->db->where($permission_type, 'YES');
	
	$resultSet = $CI->db->get();
	if ($resultSet->num_rows() > 0) {
		return true;
	}
	$resultSet->free_result();
	return false;
}

function valid_agent(){
	$CI = & get_instance();
	$agent_company = $CI->session->userdata('gis_dm_ses_company_id');
	$agent_type = $CI->session->userdata('gis_dm_ses_agent_type');
	$agent_id = $CI->session->userdata('gis_dm_ses_agent_id');
	if(single_value("company_id = '$agent_company' and agent_type = '$agent_type' and agent_id = '$agent_id' and agent_status = 'YES' and removed = 'NO'", 'agent_id', 'tbl_agents')){
		return 	1;
	}
	return 0;
}

function time_difference($from_time, $to_time, $type = 'm'){
	$return_value = '';
	switch($type){
		case 'd':
			$date1=date_create($to_time);
			$date2=date_create($from_time);
			$diff=date_diff($date1,$date2);
			$return_value = $diff->format("%a");
		break;
		
		case 'h':
			$to_time = strtotime($to_time);
			$from_time = strtotime($from_time);
			$minutes = round(abs($to_time - $from_time) / 60,2);
			$return_value = round($minutes/60, 0);
		break;
		
		case 'm':
			$to_time = strtotime($to_time);
			$from_time = strtotime($from_time);
			$return_value = round(abs($to_time - $from_time) / 60,2);
		break;
		
		case 's':
			$to_time = strtotime($to_time);
			$from_time = strtotime($from_time);
			$return_value = round(abs($to_time - $from_time),2);
		break;
	}
	return $return_value;
}

function valid_captcha(){
	$CI = & get_instance();	
	$captcha = $CI->input->post('g-recaptcha-response');
	$secretKey = "6LcLrgcUAAAAAFyGV6O-fnfqd3oTt-2f2V9y3maS";
	$ip = $_SERVER['REMOTE_ADDR'];
	$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
	$responseKeys = json_decode($response,true);			
	 if(intval($responseKeys["success"]) !== 1) {
		return 0;
	}
	return 1;
}


function crypto_rand_secure($min, $max)
{
    $range = $max - $min;
    if ($range < 1) return $min; // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd > $range);
    return $min + $rnd;
}

function get_token($length = 28){
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    $max = strlen($codeAlphabet); // edited

    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, $max-1)];
    }

    return $token;
}

function generateRandNumber($size) {
	$alpha_key = '';
	$keys = range('A', 'Z');
	for ($i = 0; $i < 2; $i++) {
		$alpha_key .= $keys[array_rand($keys)];
	}
	$length = $size - 2;
	$key = '';
	$keys = range(0, 9);
	for ($i = 0; $i < $length; $i++) {
		$key .= $keys[array_rand($keys)];
	}
	return $alpha_key . $key;
}

function isAgentLogin(){
	$CI = & get_instance();
	if($CI->session->userdata('gis_dm_ses_agent_login')){
		$agent_id = $CI->session->userdata('gis_dm_ses_agent_id');
		$company_id = $CI->session->userdata('gis_dm_ses_company_id');
		$email_address = $CI->session->userdata('gis_dm_ses_agent_email');
		if(single_value("agent_id = '$agent_id' and company_id = '$company_id' and agent_email = '$email_address' and agent_status = 'YES'", 'agent_id', 'tbl_agents')){
			return 1;
		}
		return 0;
	}
	return 0;
}

function status($Key){
	$return_val = '';
	
	switch($Key){
		case '0':
			$return_val = '<span class="label label-danger">In-Active</span>';
		break;	
		case '1':
			$return_val = '<span class="label label-success">Active</span>';
		break;	
	}
	
	return $return_val;
}

function booking_status($Key){
	$return_val = '';
	$return_val = '<span class="label label-primary">'.$Key.'</span>';
	
	switch($Key){
		case 'PreBooked':
			$return_val = '<span class="label label-danger">'.$Key.'</span>';
		break;
		
		case 'None / Pending':
			$return_val = '<span class="label label-danger">'.$Key.'</span>';
		break;
		
		case 'Cancelled':
			$return_val = '<span class="label label-danger">'.$Key.'</span>';
		break;
		
		case 'Delivery Failed':
			$return_val = '<span class="label label-danger">'.$Key.'</span>';
		break;
		
		case 'Confirmed':
			$return_val = '<span class="label label-success">'.$Key.'</span>';
		break;
		
		case 'Delivered Success':
			$return_val = '<span class="label label-success">'.$Key.'</span>';
		break;
	}
	return $return_val;
}

function shipment_status_title($shipment_id){
	if(!$shipment_id){
		return 'All Bookings';
	}
	return single_value("status_id = '$shipment_id'", 'status_title', 'tbl_booking_status');
}
function get_image_thumbnail($imageName){
	$extention = explode('.', $imageName);
	$ext = '.'.$extention[sizeof($extention)-1];
	$thumb = str_replace($ext, '', $imageName).'_thumb'.$ext;
	return $thumb;
}


function gen_voucher($id="")
	{
$CI = & get_instance();	
$currentterm=$CI->Main_Model->fetch_row("tbl_term","where status='1'");
$checkdup=	$CI->Main_Model->fetch_row("tbl_vouchers","where order_id='$id' and term_id='$currentterm->id'");
	if($checkdup==0)
	{		
	$vextract=$CI->Main_Model->fetch_rows_joins("tbl_orders.id as 'order_id',o.bundle_id,o.qty,
	CONCAT(FLOOR( 1000 + ( RAND( ) *8999 ) ),
	LPAD(tbl_orders.id,5,concat(
				substring('ABCDEFGHIJKLMNPQRSTUVWXYZ', rand()*36+1, 1),
				substring('ABCDEFGHIJKLMNPQRSTUVWXYZ', rand()*36+1, 1),
				substring('ABCDEFGHIJKLMNPQRSTUVWXYZ', rand()*36+1, 1),
				substring('ABCDEFGHIJKLMNPQRSTUVWXYZ', rand()*36+1, 1),
				substring('ABCDEFGHIJKLMNPQRSTUVWXYZ', rand()*36+1, 1),
				substring('ABCDEFGHIJKLMNPQRSTUVWXYZ', rand()*36+1, 1),
				substring('ABCDEFGHIJKLMNPQRSTUVWXYZ', rand()*36+1, 1),
				substring('ABCDEFGHIJKLMNPQRSTUVWXYZ', rand()*36+1, 1)
				)),
	LPAD(o.bundle_id,3,concat(
				substring('ABCDEFGHIJKLMNPQRSTUVWXYZ', rand()*36+1, 1),
				substring('ABCDEFGHIJKLMNPQRSTUVWXYZ', rand()*36+1, 1),
				substring('ABCDEFGHIJKLMNPQRSTUVWXYZ', rand()*36+1, 1),
				substring('ABCDEFGHIJKLMNPQRSTUVWXYZ', rand()*36+1, 1),
				substring('ABCDEFGHIJKLMNPQRSTUVWXYZ', rand()*36+1, 1),
				substring('ABCDEFGHIJKLMNPQRSTUVWXYZ', rand()*36+1, 1),
				substring('ABCDEFGHIJKLMNPQRSTUVWXYZ', rand()*36+1, 1),
				substring('ABCDEFGHIJKLMNPQRSTUVWXYZ', rand()*36+1, 1)
				)),
	LPAD(n,4,concat(
				substring('ABCDEFGHIJKLMNPQRSTUVWXYZ', rand()*36+1, 1),
				substring('ABCDEFGHIJKLMNPQRSTUVWXYZ', rand()*36+1, 1),
				substring('ABCDEFGHIJKLMNPQRSTUVWXYZ', rand()*36+1, 1),
				substring('ABCDEFGHIJKLMNPQRSTUVWXYZ', rand()*36+1, 1),
				substring('ABCDEFGHIJKLMNPQRSTUVWXYZ', rand()*36+1, 1),
				substring('ABCDEFGHIJKLMNPQRSTUVWXYZ', rand()*36+1, 1),
				substring('ABCDEFGHIJKLMNPQRSTUVWXYZ', rand()*36+1, 1),
				substring('ABCDEFGHIJKLMNPQRSTUVWXYZ', rand()*36+1, 1)
				))) AS 'voucher'","tbl_orders_items  o"," join
		 (select (@rn := @rn + 1) as n
		  from tbl_orders o cross join (select @rn := 0) vars
		 ) n
		 on n.n <= o.qty
	INNER JOIN tbl_orders ON o.order_id=tbl_orders.id
	WHERE tbl_orders.id='$id' AND tbl_orders.payment_status_text='Paid'");
		$term=$CI->Main_Model->fetch_row("tbl_term","where status=1");
			foreach($vextract as $vdata)
			{

				$data=array("vcode"=>$vdata->voucher,
						   "bundle_id"=>$vdata->bundle_id,
							"term_id"=>$term->id,
							"order_id"=>$vdata->order_id
						   );
				$check=$CI->Main_Model->insert_data("tbl_vouchers",$data);
			}		
		
	}
		return $check;	
}

function send_mail($data="")
{
	
	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->CharSet = 'UTF-8';
	$mail->clearAllRecipients();
	$mail->SMTPDebug = 0;
	$mail->Debugoutput = 'html';
	$mail->Host = "smtp.elasticemail.com";
	$mail->SMTPAutoTLS = false;
	$mail->Port = 587;
	$mail->SMTPAuth = true;
	$mail->Username = "2245FD0DCD7AF9518A417B54E57EB0D29345FD589CF67D88C31D3DCE4926C4EA9E960917AAF6DF93EFA6DB46FAFC46E4";
	$mail->Password = "2245FD0DCD7AF9518A417B54E57EB0D29345FD589CF67D88C31D3DCE4926C4EA9E960917AAF6DF93EFA6DB46FAFC46E4";
	//$mail->addAttachment(FCPATH.'attachments/'.$main_order->order_number.'.pdf', $main_order->order_number.'.pdf');	
	if(array_key_exists("attachment",$data)):
	//$mail->addAttachment($data['attachment']);
	$mail->AddStringAttachment($data['attachment'], $data['file_name'].'.pdf', 'base64', 'application/pdf');
	endif;
	$mail->setFrom($data['from']);		
	$mail->addAddress($data['to']);	
	if(array_key_exists("bcc",$data)):	
	$mail->addBCC($data['bcc']);	
	endif;
	if(array_key_exists("cc",$data)):	
	$mail->addCC($data['cc']);	
	endif;
	$mail->Subject =$data['subject'];
	$msg_body = $data['msg_body'];
		$mail->msgHTML($msg_body);
		if (!$mail->send()) {			
			return 0;
		} 
		else
		{
			return 1;
		}
}

function gen_invoice($html='',$file_name=""){	
	
		$CI = & get_instance();	
		$CI->load->library('Pdf');
		$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Galadari Brothers LLC');
		$pdf->SetTitle('Proforma');
		$pdf->SetSubject('Proforma');
		$pdf->SetKeywords('Proforma');
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->AddPage();
		
		// set default header data
$pdf->SetHeaderData('','', ' Khaleej Times Subscriptions', 'KT', array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		
		$pdf->writeHTML($html, true, false, true, false, '');
		//$pdf->Output($main_order->order_number.'.pdf', 'D');
		$attachment = $pdf->Output($file_name.'.pdf', 'S');
		return $attachment;
		}	


function gen_invoice_file($html='',$file_name=""){	
	
		$CI = & get_instance();	
		$CI->load->library('Pdf');
		$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Galadari Brothers LLC');
		$pdf->SetTitle('Proforma');
		$pdf->SetSubject('Proforma');
		$pdf->SetKeywords('Proforma');
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->AddPage();
		
		// set default header data
$pdf->SetHeaderData('','', ' Khaleej Times Subscriptions', 'KT', array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		
		$pdf->writeHTML($html, true, false, true, false, '');
		$attachment = $pdf->Output($file_name.'.pdf', 'D');
		//$attachment = $pdf->Output($file_name.'.pdf', 'S');
		return $attachment;
		}	

function encrypt($plainText,$key)
	{
		$key = hextobin(md5($key));
		$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
		$openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
		$encryptedText = bin2hex($openMode);
		return $encryptedText;
	}

	function decrypt($encryptedText,$key)
	{
		$key = hextobin(md5($key));
		$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
		$encryptedText = hextobin($encryptedText);
		$decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
		return $decryptedText;
	}
	//*********** Padding Function *********************

	 function pkcs5_pad ($plainText, $blockSize)
	{
	    $pad = $blockSize - (strlen($plainText) % $blockSize);
	    return $plainText . str_repeat(chr($pad), $pad);
	}

	//********** Hexadecimal to Binary function for php 4.0 version ********

	function hextobin($hexString) 
   	 { 
        	$length = strlen($hexString); 
        	$binString="";   
        	$count=0; 
        	while($count<$length) 
        	{       
        	    $subString =substr($hexString,$count,2);           
        	    $packedString = pack("H*",$subString); 
        	    if ($count==0)
		    {
				$binString=$packedString;
		    } 
        	    
		    else 
		    {
				$binString.=$packedString;
		    } 
        	    
		    $count+=2; 
        	} 
  	        return $binString; 
    	  } 

?>