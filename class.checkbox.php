<?php
/********************************************
class.checkbox.php

info:       Checkbox API
author:     Volodymyr Stroiev (v.v.stoev@gmail.com)


updated:    2021-01-27
*********************************************/

class checkbox {
	
	var $apiurl="https://api.checkbox.in.ua";
	var $cashier_login = "";
	var $cashier_pass = "";
	var $license_key = "";
	
	
	var $client_name = "Project";
	var $client_version = "v1";
	
	var $apikey_array = array();

	function checkbox(){

	}

	function api_query($path, $requestHeader, $requestBody, $method="post", $type="json") {

		$ch = curl_init($this->apiurl.$path);
			
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeader);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		if($method=='post')
			curl_setopt($ch, CURLOPT_POST, 1);
		else
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
		
		curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);
		
		$res = curl_exec($ch);
			
		if(!$res){
			$error = curl_error($ch).'('.curl_errno($ch).')';
			curl_close($ch);
			return $error;
		}
		else{
			curl_close($ch);
			if($type=="json")
				$res = json_decode($res);
			
			return $res;
		}
	}

	// Sign In Cashier
	function getToken(){
		$path = "/api/v1/cashier/signin";
		
		$requestHeader = array(
			'Content-Type: application/json', 
			'X-Client-Name: '.$this->client_name, 
			'X-Client-Version: '.$this->client_version, 
		);

		$requestBody = '{
		"login": "'.$this->cashier_login.'",
		"password": "'.$this->cashier_pass.'"
		}';
		
		$res = $this->api_query($path, $requestHeader, $requestBody);
		if(!empty($res->access_token)) 
			return $res->access_token;
		else
			return 0;
	}
	
	// Get Cashier Profile
	function cashier_Me($token){
		$method = "get";
		$path = "/api/v1/cashier/me";
				
		$requestHeader = array(
			'Authorization: bearer '.$token,
			'X-Client-Name: '.$this->client_name, 
			'X-Client-Version: '.$this->client_version, 
		);
		
		$requestBody = '';
		
		$res = $this->api_query($path, $requestHeader, $requestBody, $method);
		return $res;
	}
	
	// Get Cashier Shift
	function cashier_Shift($token){
		$method = "get";
		$path = "/api/v1/cashier/shift";
				
		$requestHeader = array(
			'Authorization: bearer '.$token,
			'X-Client-Name: '.$this->client_name, 
			'X-Client-Version: '.$this->client_version, 
		);
		
		$requestBody = '';
		
		$res = $this->api_query($path, $requestHeader, $requestBody, $method);
		return $res;
	}
	
	// Create Shift
	function create_Shift($token){
		$path = "/api/v1/shifts";
		
		$requestHeader = array(
			'Authorization: bearer '.$token, 
			'Content-Type: application/json', 
			'X-Client-Name: '.$this->client_name, 
			'X-Client-Version: '.$this->client_version, 
			'X-License-Key: '.$this->license_key);
			
		
		$requestBody = '';
		
		$res = $this->api_query($path, $requestHeader, $requestBody);
		
		return $res;
	}
	
	// Get Shifts 
	function get_Shifts($token, $status="OPENED"){
		$method = "get";
		$path = "/api/v1/shifts?statuses=".$status."&desc=false&limit=25&offset=0";
				
		$requestHeader = array(
			'accept: application/json',
			'Authorization: Bearer '.$token,
			'X-Client-Name: '.$this->client_name, 
			'X-Client-Version: '.$this->client_version, 
		);
		
		$requestBody = '';
		
		$res = $this->api_query($path, $requestHeader, $requestBody, $method);
		return $res;
	}
	
	// Get Shift 
	function get_Shift($token, $shift_id){
		$method = "get";
		$path = "/api/v1/shifts?shift_id=".$shift_id;
				
		$requestHeader = array(
			'Authorization: bearer '.$token, 
			'Content-Type: application/json', 
			'X-Client-Name: '.$this->client_name, 
			'X-Client-Version: '.$this->client_version, 
		);
		
		$requestBody = "";
		
		$res = $this->api_query($path, $requestHeader, $requestBody, $method);
		return $res;
	}
	
	// Close Shift
	function close_Shift($token){
		$path = "/api/v1/shifts/close";
				
		$requestHeader = array(
			'Authorization: bearer '.$token, 
			'Accept: application/json', 
			'X-Client-Name: '.$this->client_name, 
			'X-Client-Version: '.$this->client_version, 
		);
		
		$requestBody = '';
		
		$res = $this->api_query($path, $requestHeader, $requestBody);
		return $res;
	}
	
	// Get Receipts
	function get_Receipts($token){
		$method = "get";
		$path = "/api/v1/receipts?desc=false&limit=25&offset=0";
				
		$requestHeader = array(
			'Authorization: bearer '.$token, 
			'Content-Type: application/json', 
			'X-Client-Name: '.$this->client_name, 
			'X-Client-Version: '.$this->client_version, 
		);
		
		$requestBody = "";
		
		$res = $this->api_query($path, $requestHeader, $requestBody, $method);
		return $res;
	}
	
	// Get Receipt
	function get_Receipt($token, $receipt_id){
		$method = "get";
		$path = "/api/v1/receipts/".$receipt_id;
				
		$requestHeader = array(
			'Authorization: bearer '.$token, 
			'Content-Type: application/json', 
			'X-Client-Name: '.$this->client_name, 
			'X-Client-Version: '.$this->client_version, 
		);
		
		$requestBody = "";
		
		$res = $this->api_query($path, $requestHeader, $requestBody, $method);
		return $res;
	}
	
	
	// Create Receipt
	function create_Receipts($token, $goods, $order_info, $return="false"){
		$method='post';
		$path = "/api/v1/receipts/sell";
				
		$requestHeader = array(
			'Authorization: bearer '.$token, 
			'Accept: application/json', 
			'X-Client-Name: '.$this->client_name, 
			'X-Client-Version: '.$this->client_version, 
		);
		
		$goods_json_array = array();
		
		foreach($goods as $k=>$v){
			$goods_json_array[] = '{
				"good": {
				"code": "'.$v['code'].'",
				"name": "'.$v['name'].'",
				"barcode": "'.$v['barcode'].'",
				"header": "'.$v['header'].'",
				"footer": "'.$v['footer'].'",
				"price": '.$v['price'].',
				"uktzed": "'.$v['uktzed'].'"
				},
			"quantity": '.$v['quantity'].'000,
			"is_return": '.$return.',
			"discounts": []
			}';
		}
		
		$goods_json = implode(",", $goods_json_array); 
				
		$requestBody = iconv("WINDOWS-1251", "UTF-8", '{
			"cashier_name": "'.$order_info['cashier_name'].'",
			"departament": "",
			"goods": [
				'.$goods_json.'
			  ],
			"delivery": {
				'.$order_info['email'].'
			},
			"discounts": [],
			"payments": [
			{
				"type": "'.$order_info['payment_type'].'",
				"value": '.$order_info['payment_value'].',
				"label": "'.$order_info['payment_label'].'"
			}
			],
			"rounding": false,
			"header": "'.$order_info['order_id'].'",
			"footer": "'.$order_info['footer'].'",
			"barcode": "'.$order_info['barcode'].'"
		}');
		//echo "<pre>".print_r($requestBody, 1)."</pre>";
		
		$res = $this->api_query($path, $requestHeader, $requestBody, $method);
		return $res;
	}
	
	function create_Service_Receipt($token, $sum, $label="Готівка"){
		$method='post';
		$path = "/api/v1/receipts/service";
				
		$requestHeader = array(
			'Authorization: bearer '.$token, 
			'Accept: application/json', 
			'X-Client-Name: '.$this->client_name, 
			'X-Client-Version: '.$this->client_version, 
		);
		
		$requestBody = iconv("WINDOWS-1251", "UTF-8", '{
			"payment": {
				"type": "CASH",
				"value": '.$sum.',
				"label": "'.$label.'"
			}
		}');
		
		$res = $this->api_query($path, $requestHeader, $requestBody, $method);
		return $res;
	}
	
	// Get Receipts Html, Pdf, Text, Qr Code Image
	function get_Receipts_print($token, $receipt_id, $type){
		$method = "get";
		$path = "/api/v1/receipts/".$receipt_id."/".$type;
				
		$requestHeader = array(
			'Authorization: bearer '.$token, 
			'Content-Type: application/json', 
			'X-Client-Name: '.$this->client_name, 
			'X-Client-Version: '.$this->client_version, 
		);
		
		$requestBody = "";
		
		$res = $this->api_query($path, $requestHeader, $requestBody, $method, $type);
		return $res;
	}

	
	
	// Get Transactions 
	function get_Transactions($token, $limit=25){
		$method = "get";
		$path = "/api/v1/transactions?limit=".$limit."&offset=0";
				
		$requestHeader = array(
			'Authorization: bearer '.$token, 
			'Content-Type: application/json', 
			'X-Client-Name: '.$this->client_name, 
			'X-Client-Version: '.$this->client_version, 
		);
		
		$requestBody = "";
		
		$res = $this->api_query($path, $requestHeader, $requestBody, $method);
		return $res;
	}
	
	
	// Create X Report
	function create_X_Report($token){
		$path = "/api/v1/reports";
				
		$requestHeader = array(
			'Authorization: bearer '.$token, 
			'Accept: application/json', 
			'X-Client-Name: '.$this->client_name, 
			'X-Client-Version: '.$this->client_version, 
		);
		
		$requestBody = '';
		
		$res = $this->api_query($path, $requestHeader, $requestBody);
		return $res;
	}
	
	// Get Reports
	function get_Reports($token, $limit=25){
		$method = "get";
		$path = "/api/v1/reports?desc=false&limit=".$limit."&offset=0";
				
		$requestHeader = array(
			'Authorization: bearer '.$token, 
			'Content-Type: application/json', 
			'X-Client-Name: '.$this->client_name, 
			'X-Client-Version: '.$this->client_version, 
		);
		
		$requestBody = "";
		
		$res = $this->api_query($path, $requestHeader, $requestBody, $method);
		return $res;
	}
}
?>