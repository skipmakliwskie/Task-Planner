<?php
require_once('../config.php');
Class Users extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function save_users(){
		if(empty($_POST['password']))
			unset($_POST['password']);
		else
		$_POST['password'] = md5($_POST['password']);
		extract($_POST);
		$data = '';
		foreach($_POST as $k => $v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=" , ";
				$data .= " {$k} = '{$v}' ";
			}
		}
		if(empty($id)){
			$qry = $this->conn->query("INSERT INTO users set {$data}");
			if($qry){
				$id=$this->conn->insert_id;
				$this->settings->set_flashdata('success','User Details successfully saved.');
				foreach($_POST as $k => $v){
					if($k != 'id'){
						if(!empty($data)) $data .=" , ";
						if($this->settings->userdata('id') == $id)
						$this->settings->set_userdata($k,$v);
					}
				}
				if(!empty($_FILES['img']['tmp_name'])){
					if(!is_dir(base_app."uploads/avatars"))
						mkdir(base_app."uploads/avatars");
					$ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
					$fname = "uploads/avatars/$id.png";
					$accept = array('image/jpeg','image/png');
					if(!in_array($_FILES['img']['type'],$accept)){
						$err = "Image file type is invalid";
					}
					if($_FILES['img']['type'] == 'image/jpeg')
						$uploadfile = imagecreatefromjpeg($_FILES['img']['tmp_name']);
					elseif($_FILES['img']['type'] == 'image/png')
						$uploadfile = imagecreatefrompng($_FILES['img']['tmp_name']);
					if(!$uploadfile){
						$err = "Image is invalid";
					}
					$temp = imagescale($uploadfile,200,200);
					if(is_file(base_app.$fname))
					unlink(base_app.$fname);
					$upload =imagepng($temp,base_app.$fname);
					if($upload){
						$this->conn->query("UPDATE `users` set `avatar` = CONCAT('{$fname}', '?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$id}'");
						if($this->settings->userdata('id') == $id)
						$this->settings->set_userdata('avatar',$fname."?v=".time());
					}

					imagedestroy($temp);
				}
				return 1;
			}else{
				return 2;
			}

		}else{
			$qry = $this->conn->query("UPDATE users set $data where id = {$id}");
			if($qry){
				$this->settings->set_flashdata('success','User Details successfully updated.');
				foreach($_POST as $k => $v){
					if($k != 'id'){
						if(!empty($data)) $data .=" , ";
						if($this->settings->userdata('id') == $id)
							$this->settings->set_userdata($k,$v);
					}
				}
				if(!empty($_FILES['img']['tmp_name'])){
					if(!is_dir(base_app."uploads/avatars"))
						mkdir(base_app."uploads/avatars");
					$ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
					$fname = "uploads/avatars/$id.png";
					$accept = array('image/jpeg','image/png');
					if(!in_array($_FILES['img']['type'],$accept)){
						$err = "Image file type is invalid";
					}
					if($_FILES['img']['type'] == 'image/jpeg')
						$uploadfile = imagecreatefromjpeg($_FILES['img']['tmp_name']);
					elseif($_FILES['img']['type'] == 'image/png')
						$uploadfile = imagecreatefrompng($_FILES['img']['tmp_name']);
					if(!$uploadfile){
						$err = "Image is invalid";
					}
					$temp = imagescale($uploadfile,200,200);
					if(is_file(base_app.$fname))
					unlink(base_app.$fname);
					$upload =imagepng($temp,base_app.$fname);
					if($upload){
						$this->conn->query("UPDATE `users` set `avatar` = CONCAT('{$fname}', '?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$id}'");
						if($this->settings->userdata('id') == $id)
						$this->settings->set_userdata('avatar',$fname."?v=".time());
					}

					imagedestroy($temp);
				}

				return 1;
			}else{
				return "UPDATE users set $data where id = {$id}";
			}
			
		}
	}
	public function delete_users(){
		extract($_POST);
		$qry = $this->conn->query("DELETE FROM users where id = $id");
		if($qry){
			$this->settings->set_flashdata('success','User Details successfully deleted.');
			if(is_file(base_app."uploads/avatars/$id.png"))
				unlink(base_app."uploads/avatars/$id.png");
			return 1;
		}else{
			return false;
		}
	}
	function registration(){
		$_POST['password'] = md5($_POST['password']);
		extract($_POST);
		$data = "";
		$check = $this->conn->query("SELECT * FROM `users` where username = '{$username}' ".($id > 0 ? " and id!='{$id}'" : "")." ")->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = 'Username already exists.';
			return json_encode($resp);
		}
		foreach($_POST as $k => $v){
			$v = $this->conn->real_escape_string($v);
			if(!in_array($k, ['id']) && !is_array($_POST[$k])){
				if(!empty($data)) $data .= ", ";
				$data .= " `{$k}` = '{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `users` set {$data} ";
		}else{
			$sql = "UPDATE set `users` set {$data} where id = '{$id}' ";
		}
		$save = $this->conn->query($sql);
		if($save){
			$uid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			if(!empty($id))
				$resp['msg'] = 'User Details has been updated successfully';
			else
				$resp['msg'] = 'Your Account has been created successfully';

			if(!empty($_FILES['img']['tmp_name'])){
				if(!is_dir(base_app."uploads/avatars"))
					mkdir(base_app."uploads/avatars");
				$ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
				$fname = "uploads/avatars/$uid.png";
				$accept = array('image/jpeg','image/png');
				if(!in_array($_FILES['img']['type'],$accept)){
					$resp['msg'] = "Image file type is invalid";
				}
				if($_FILES['img']['type'] == 'image/jpeg')
					$uploadfile = imagecreatefromjpeg($_FILES['img']['tmp_name']);
				elseif($_FILES['img']['type'] == 'image/png')
					$uploadfile = imagecreatefrompng($_FILES['img']['tmp_name']);
				if(!$uploadfile){
					$resp['msg'] = "Image is invalid";
				}
				$temp = imagescale($uploadfile,200,200);
				if(is_file(base_app.$fname))
				unlink(base_app.$fname);
				$upload =imagepng($temp,base_app.$fname);
				if($upload){
					$this->conn->query("UPDATE `users` set `avatar` = CONCAT('{$fname}', '?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$uid}'");
				}
				imagedestroy($temp);
			}
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = $this->conn->error;
			$resp['sql'] = $sql;
		}
		if($resp['status'] == 'success' && isset($resp['msg']))
		$this->settings->set_flashdata('success', $resp['msg']);
		return json_encode($resp);
	}
	function update_user_meta(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k=>$v){
			if(!is_array($_POST[$k]) && !in_array($k,['individual_id'])){
				if(!empty($data)) $data .= ", ";
				$data .= "('{$individual_id}', '{$k}', '{$this->conn->real_escape_string($v)}')";
			}
		}
		$this->conn->query("DELETE FROM `individual_meta` where individual_id = '{$individual_id}' and meta_field in ('".(implode("','", array_keys($_POST)))."') ");
		$sql = "INSERT INTO `individual_meta` (`individual_id`, `meta_field`, `meta_value`) VALUES {$data}";
		$save = $this->conn->query($sql);
		if($save){
			$resp['status'] = 'success';
			$resp['msg'] = "Your Information for Verification has been updated successfully";
			if(isset($_FILES['vaccine_card_path']) && !empty($_FILES['vaccine_card_path']['tmp_name'])){
				$filename = "uploads/vaccines/{$individual_id}.png";
				if(!is_dir(base_app."uploads/vaccines/"))
					mkdir(base_app."uploads/vaccines/");
				$type = mime_content_type($_FILES['vaccine_card_path']['tmp_name']);
				if(!in_array($type, ['image/jpeg', 'image/png'])){
					$resp['msg'] .= ' Vaccine Card Image has failed to upload due to invalid type.';
				}else{
					if($type == 'image/png'){
						$img = imagecreatefrompng($_FILES['vaccine_card_path']['tmp_name']);
					}else{
						$img = imagecreatefromjpeg($_FILES['vaccine_card_path']['tmp_name']);
					}
					list($width, $height) = getimagesize($_FILES['vaccine_card_path']['tmp_name']);
					if($width > 640){
						$perc = ($width - 640) / $width;
						$width = 640;
						$height = $height - ($height * $perc);
					}
					if($height > 640){
						$perc = ($height - 640) / $height;
						$height = 640;
						$width = $width - ($width * $perc);
					}
					$img = imagescale($img, $width, $height);
					if(is_file(base_app.$filename))
						unlink(base_app.$filename);
					$upload = imagepng($img, base_app.$filename, 6);
					if($upload){
						$this->conn->query("DELETE FROM `individual_meta` where individual_id = '{$individual_id}' and meta_field = 'vaccine_card_path' ");
						$this->conn->query("INSERT INTO `individual_meta` set meta_field = 'vaccine_card_path', meta_value = CONCAT('{$filename}?v=',unix_timestamp(CURRENT_TIMESTAMP)), individual_id = '{$individual_id}' ");
					}else{
						$resp['msg'] .= ' Vaccine Card Image has failed to upload due to unknown reason.';
					}
				}
			}
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = $this->conn->error;
			$resp['sql'] = $sql;
		}
		if($resp['status'] && isset($resp['msg']) && !empty($resp['msg']))
			$this->settings->set_flashdata('fixed_success',$resp['msg']);
		return json_encode($resp);
	}

	function update_individual_status(){
		extract($_POST);
		$update = $this->conn->query("UPDATE `users` set `status` = '{$status}' where id = '{$id}' ");
		if($update){
			$resp['status'] = 'success';
			$resp['msg'] = 'Individual\'s Status has been updated successfully.';
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = $this->conn->error;
		}
		if($resp['status'])
		$this->settings->set_flashdata('success', $resp['msg']);
		return json_encode($resp);
	}
}

$users = new users();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
	case 'save':
		echo $users->save_users();
	break;
	case 'registration':
		echo $users->registration();
	break;
	case 'delete':
		echo $users->delete_users();
	break;
	case 'update_user_meta':
		echo $users->update_user_meta();
	break;
	case 'update_individual_status':
		echo $users->update_individual_status();
	break;
	case 'save_individual':
		echo $users->save_individual();
	break;
	case 'delete_individual':
		echo $users->delete_individual();
	break;
	default:
		// echo $sysset->index();
		break;
}