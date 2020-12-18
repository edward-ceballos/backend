<?php

include('models/contacts.php');

$data = array("_status" => 0, "_msj" => "", "_data" => array(), "_action" => NULL);
class Contacts{


	//Create and edit Contacts
	public function saveContact(){
		global $data;

		if (isset($_POST) && !empty($_POST)) {

			$validate = array('name', 'lastname', 'email', 'phone');
			$input = $this->stripslashes_array($_POST);

			if (is_array($input['phone'])) {
				$input['phone'] = array_map(function ($value) {
					return valid_tel($value);
				}, $input['phone']);
				$input['phone'] = implode(",", array_filter($input['phone']));
			}
			else{
				$input['phone'] = valid_tel($input['phone']);
			}

			if ($r = required($validate, $input)) {
				return $r; 
			}
			$contact = new Contacts_model();
			$result = $contact->saveContact($input);

			if ($result) {
				$data['_status'] = 1;
				$data['_msj'] = isset($_POST['id']) && intval($_POST['id']) > 0 ? "update" : 'save';
			}
			else{
				$data['_msj'] = 'Operation failed';
			}
			
		}else{
			$data['_msj'] = 'Missing required parameters';
		}

		return json_encode(utf8ize($data));
	}

	//Get a contact
	public function loadContact(){
		global $data;

		if (isset($_POST['id']) && intval($_POST['id'])) {
			$contact = new Contacts_model();

			$result = $contact->loadContact(intval($_POST['id']));

			if ($result) {
				$data['_status'] = 1;
				$result['phone'] = explode(",", $result['phone']);
				$data['_data'] = $result;
			}
			else{
				$data['_msj'] = 'Operation failed';
			}
		}
		else{
			$data['_msj'] = "The prop 'ID' doesn't exist or is incorrect.";
		}

		return json_encode(utf8ize($data));
	}

	//delete contact
	public function deleteContact(){
		global $data;

		if (isset($_POST['id']) && intval($_POST['id'])) {
			$contact = new Contacts_model();

			$result = $contact->deleteContact(intval($_POST['id']));

			if ($result) {
				$data['_status'] = 1;
				$data['_msj'] = 'deleted';
			}
			else{
				$data['_msj'] = 'Operation failed';
			}
		}
		else{
			$data['_msj'] = "The prop 'ID' doesn't exist or is incorrect.";
		}
		return json_encode(utf8ize($data));
	}

	//list all contacts
	public function listContacts(){
		global $data;

		$start = isset($_POST['start']) && intval($_POST['start']) ? intval($_POST['start']) : 0;
		$count = isset($_POST['count']) && intval($_POST['count']) ? intval($_POST['count']) : 3;

		$contact = new Contacts_model();

		$result = $contact->listContacts(trim($start), trim($count));
		// return json_encode(utf8ize($result));

		if ($result) {
			$data['_status'] = 1;
			$data['_data'] = $result;
		}
		else{
			$data['_msj'] = 'Operation failed';
		}
		return json_encode(utf8ize($data));
	}

	//zanitate array
	protected function stripslashes_array($arr) {

		$array = array_map(function ($value) {
			$allowed_tags = "<b><br><em><hr><i><li><ol><p><s><span><table><tr><td><u><ul>";
			if (is_array($value)) {
				return $this->stripslashes_array($value);
			}else{

				return strip_tags($value, $allowed_tags);
			}

		}, $arr);

		return $array;
	}
}

function utf8ize($d) {

	if (is_array($d)) {
		foreach ($d as $k => $v) {
			$d[$k] = utf8ize($v);
		}
	} else if (is_string ($d)) {
		return utf8_encode($d);
	}
	return $d;
}

function required($array, $compare) {
	
	foreach($array as $val) {
		if(!isset($compare[$val]) || empty($compare[$val])){
			return json_encode("The prop '{$val}' doesn't exist or is incorrect.");
		}
	}
	return false;
}

function valid_tel($number_phone) {
	$num = preg_replace("/[^0-9]/", '', $number_phone); 

	$n = substr($num, 0, 3);
	if ($n != '809' && $n != '849' && $n != '829') {
		return false;
	}
	else if (strlen($num) != 10) {
		return false;
	}
	else {
		return $num;
	}
}