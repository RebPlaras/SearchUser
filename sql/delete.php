<?php 
    require_once '../core/dbConfig.php'; 
    require_once '../core/models.php'; 

    if (isset($_GET['teacherID'])) {
        $teacherID = $_GET['teacherID'];
        deleteTeacher($pdo, $teacherID);
        header("Location: search.php");
        exit();
    } else {
        die("Teacher ID is required to delete a record.");
    }
?>
