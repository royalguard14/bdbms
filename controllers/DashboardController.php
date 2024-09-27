<?php 
require_once 'connect.php';
$data = json_decode(file_get_contents('php://input'), true);
if ($data !== null && isset($data['action'])) {
  $action = $data['action'];




  if ($action=='store') {

  }

  if ($action=='edit') {

  }

  if ($action=='Update') {

  }

  if ($action=='delete') {

  }

/*end of if (functions)*/} else {
//index


}
?>