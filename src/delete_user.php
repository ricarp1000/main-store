<?php
/*
Use this endpoint to test request
http://localhost/main-store/src/delete_user.php?userID=ANY - ID-number
*/

include ("../settings/database.php");

//Get data 
$user_id = $_GET['userID'];

//PREPARE QUERY
$sql_delete_user = "DELETE FROM users WHERE id = '$user_id'";
$res = pg_query($local_conn, $sql_delete_user);

if($res){
echo "<script>alert('Usuario eliminado correctamente');</script>";
header('Refresh:1; url=list_users.php');

}else{

echo "<script>alert('Error al eliminar usuario');</script>";
header('Refresh:0; url=list_users.php');
}