<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function delete_img(){
		extract($_POST);
		if(is_file($path)){
			if(unlink($path)){
				$resp['status'] = 'success';
			}else{
				$resp['status'] = 'failed';
				$resp['error'] = 'failed to delete '.$path;
			}
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = 'Unkown '.$path.' path';
		}
		return json_encode($resp);
	}
	function save_category(){
		if(empty($_POST['id'])){
			$_POST['user_id'] = $this->settings->userdata('id');
		}else{
			$_POST['user_id'] = $this->conn->query("SELECT user_id from `category_list` where id = '{$_POST['id']}' ")->fetch_array()[0];
		}
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$v = $this->conn->real_escape_string($v);
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `category_list` where `name` = '{$name}' and `user_id` = '{$user_id}' and delete_flag = 0 ".($id > 0 ? " and id != '{$id}' " : '')." ")->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = 'Category Name already exists.';
			return json_encode($resp);
		}
		if(empty($id)){
			$sql = "INSERT INTO `category_list` set {$data} ";
		}else{
			$sql = "UPDATE `category_list` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$aid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			$resp['aid'] = $aid;

			if(empty($id))
				$resp['msg'] = "New Category successfully saved.";
			else
				$resp['msg'] = " Category successfully updated.";
			
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}
	function delete_category(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `category_list` set delete_flag = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Category successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_schedule(){
		if(empty($_POST['id'])){
			$_POST['user_id'] = $this->settings->userdata('id');
		}else{
			$_POST['user_id'] = $this->conn->query("SELECT user_id from `schedule_list` where id = '{$_POST['id']}' ")->fetch_array()[0];
		}
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$v = $this->conn->real_escape_string($v);
				$data .= " `{$k}`='{$v}' ";
			}
		}
		// $check = $this->conn->query("SELECT * FROM `schedule_list` where `name` = '{$name}' and category_id = '{$category_id}' and delete_flag = 0 ".($id > 0 ? " and id != '{$id}' " : '')." ")->num_rows;
		// if($check > 0){
		// 	$resp['status'] = 'failed';
		// 	$resp['msg'] = 'schedule Name already exists on the selected category.';
		// 	return json_encode($resp);
		// }
		if(empty($id)){
			$sql = "INSERT INTO `schedule_list` set {$data} ";
		}else{
			$sql = "UPDATE `schedule_list` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$aid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			$resp['aid'] = $aid;

			if(empty($id))
				$resp['msg'] = "New Schedule successfully saved.";
			else
				$resp['msg'] = " Schedule successfully updated.";
			
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}
	function delete_schedule(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `schedule_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Scheduled Task has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_student(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$v = $this->conn->real_escape_string($v);
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `student_list` where `code` = '{$code}' and delete_flag = 0 ".($id > 0 ? " and id != '{$id}' " : '')." ")->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = 'Student Code already exists.';
			return json_encode($resp);
		}
		if(empty($id)){
			$sql = "INSERT INTO `student_list` set {$data} ";
		}else{
			$sql = "UPDATE `student_list` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$sid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			$resp['sid'] = $sid;

			if(empty($id))
				$resp['msg'] = "New Student has been saved successfully.";
			else
				$resp['msg'] = " Student Details has been updated successfully.";
			
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}
	function delete_student(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `student_list` set delete_flag = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Student has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_account(){
		if(empty($_POST['id'])){
			$prefix = date("Ymd");
			$code = sprintf("%'.04d", 1);
			while(true){
				$check = $this->conn->query("SELECT * FROM `account_list` where code = '{$prefix}{$code}' and delete_flag = 0 ")->num_rows;
				if($check > 0){
					$code = sprintf("%'.04d", abs($code) + 1);
				}else{
					$_POST['code'] = $prefix.$code;
					break;
				}
			}
		}
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k, ['id'])){
				if(!empty($data)) $data .=",";
				$v = $this->conn->real_escape_string($v);
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `account_list` set {$data} ";
		}else{
			$sql = "UPDATE `account_list` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$aid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			$resp['aid'] = $aid;

			if(empty($id))
				$resp['msg'] = "New Account successfully saved.";
			else
				$resp['msg'] = " Account successfully updated.";
			
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}
	function delete_account(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `accounts` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Account successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function update_account_status(){
		extract($_POST);
		$update = $this->conn->query("UPDATE `accounts` set `status` = '{$status}' where id = '{$id}' ");
		if($update){
			$resp['status'] = 'success';
			$resp['msg'] = 'Rental Status has been updated successfully.';
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = $this->conn->error;
		}
		if($resp['status'])
		$this->settings->set_flashdata('success', $resp['msg']);
		return json_encode($resp);
	}
	function save_payment(){
		extract($_POST);
		$data = "";
		$check = $this->conn->query("SELECT * FROM `payment_list` where `account_id` = '{$account_id}' and month_of = '{$month_of}' ".($id > 0 ? " and id != '{$id}' " : '')." ")->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = 'The Account already have a payment details on the choosen month.';
			return json_encode($resp);
		}
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id')) && !is_array($_POST[$k])){
				if(!empty($data)) $data .=",";
				$v = $this->conn->real_escape_string($v);
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `payment_list` set {$data} ";
		}else{
			$sql = "UPDATE `payment_list` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$sid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			$resp['sid'] = $sid;

			if(empty($id))
				$resp['msg'] = "New Payment has been saved successfully.";
			else
				$resp['msg'] = " Payment Details has been updated successfully.";
			
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		// if($resp['status'] == 'success')
		// 	$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}
	function delete_payment(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `payment_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$resp['msg'] = " Payment Details has been deleted successfully.";
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'delete_img':
		echo $Master->delete_img();
	break;
	case 'save_category':
		echo $Master->save_category();
	break;
	case 'delete_category':
		echo $Master->delete_category();
	break;
	case 'save_schedule':
		echo $Master->save_schedule();
	break;
	case 'delete_schedule':
		echo $Master->delete_schedule();
	break;
	case 'save_student':
		echo $Master->save_student();
	break;
	case 'delete_student':
		echo $Master->delete_student();
	break;
	case 'save_account':
		echo $Master->save_account();
	break;
	case 'delete_account':
		echo $Master->delete_account();
	break;
	case 'update_account_status':
		echo $Master->update_account_status();
	break;
	case 'save_payment':
		echo $Master->save_payment();
	break;
	case 'delete_payment':
		echo $Master->delete_payment();
	break;
	case 'save_student_transactions':
		echo $Master->save_student_transactions();
	break;
	case 'delete_student_transactions':
		echo $Master->delete_student_transactions();
	break;
	case 'update_student_transcation_status':
		echo $Master->update_student_transcation_status();
	break;
	default:
		// echo $sysset->index();
		break;
}