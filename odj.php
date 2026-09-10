<?php
 $conn = mysqli_connect("localhost", "root", "", przerzut);
 $id = $_POST['id']
 $query = 'DELETE FROM zlecenia WHERE id = $id';

 mysqli_query($conn, $query);

 header('location: index.php');
?>