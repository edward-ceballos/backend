<?php

include('library/connection.php');

$conn = Connection::getInstance();

//Class for Contacts
class Contacts_model{
	

	//Create and edit Contacts
	public function saveContact($contact){

		global $conn;
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if (is_array($contact)) {
			if (!isset($contact['id']) || $contact['id'] == 0) {	

				$sql = "INSERT INTO `contacts` (`name`, `lastname`, `email`, `phone`) 
				VALUES (:name, :lastname, :email, :phone)";

				$stmt = $conn->prepare($sql);
				$stmt->bindParam(':name', $contact['name'], PDO::PARAM_STR);
				$stmt->bindParam(':lastname', $contact['lastname'], PDO::PARAM_STR);
				$stmt->bindParam(':email', $contact['email'], PDO::PARAM_STR);
				$stmt->bindParam(':phone', $contact['phone'], PDO::PARAM_STR);
				$stmt->execute();
				return $stmt->rowCount();
			}
			else{
				$sql = "UPDATE `contacts` SET `name` = :name, `lastname` = :lastname, `email` = :email, `phone` = :phone WHERE `id` = :id";

				$stmt = $conn->prepare($sql);
				$stmt->bindParam(':name', $contact['name'], PDO::PARAM_STR);
				$stmt->bindParam(':lastname', $contact['lastname'], PDO::PARAM_STR);
				$stmt->bindParam(':email', $contact['email'], PDO::PARAM_STR);
				$stmt->bindParam(':phone', $contact['phone'], PDO::PARAM_STR);
				$stmt->bindParam(':id', $contact['id'], PDO::PARAM_INT);
				$stmt->execute();
				return $stmt->rowCount();
			}
		}
		else{
			return false;
		}
	}

	//Get a contact
	public function loadContact($id){ 
		global $conn;

		$sql = "SELECT * FROM `contacts` WHERE `id` = :id";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':id', $id, PDO::PARAM_STR);
		$stmt->execute(); 

		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			return $row;
		}
		else{
			return $stmt->rowCount();
		}
	}

	//delete contcat
	public function deleteContact($id){

		global $conn;

		$sql = "DELETE FROM `contacts` WHERE `id` = :id"; 
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':id', $id, PDO::PARAM_STR);
		$stmt->execute(); 
		return $stmt->rowCount();
	}

	//list all contacts
	public function listContacts($start = 0, $count = 3){

		global $conn;
		$data = array();
		$sql = "SELECT * FROM `contacts` LIMIT {$start}, {$count};";
		$stmt = $conn->query($sql);
		
		if ($stmt) {
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$row['phone'] = explode(',', $row['phone']);
				$data[] = $row;
			}
			return $data;
		}
		else{
			return 0;
		}

	}

}




