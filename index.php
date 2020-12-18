<?php
header('Content-Type: application/json;charset=UTF-8');

require('controllers/contacts.php');

$contact = new Contacts();

if (isset($_GET['add']) || isset($_GET['edit'])) {
	echo($contact->saveContact());
}
else if (isset($_GET['del'])){
	echo($contact->deleteContact());
}
else if (isset($_GET['load_contact'])){
	echo($contact->loadContact());
}
else if (isset($_GET['list_contacts'])){
	echo($contact->listContacts());
}
else{
	echo("Hello World");
}

