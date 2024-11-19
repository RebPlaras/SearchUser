<?php 

require_once 'dbConfig.php';

// Insert into teacher records
function insertTeacherRecord($pdo, $first_name, $last_name, $email, $department) {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    $sql = "INSERT INTO teachers (first_name, last_name, email, department) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $isInserted = $stmt->execute([$first_name, $last_name, $email, $department]);

    // check if the insertion was successful
    if ($isInserted) {
        return [
            'message' => 'Teacher added successfully.',
            'statusCode' => 200
        ];
    } else {
        // if the insert fails, return an error message
        return [
            'message' => 'Failed to insert teacher record.',
            'statusCode' => 400
        ];
    }
}


// View all teacher records
function getAllTeachers($pdo) {
    $sql = "SELECT teacherID, first_name, last_name, email, department, created_at FROM teachers";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch a teacher by ID
function getTeacherByID($pdo, $teacherID) {
    $sql = "SELECT * FROM teachers WHERE teacherID = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$teacherID]);
    return $stmt->fetch();
}

// Update a teacher record
function updateTeacher($pdo, $teacherID, $first_name, $last_name, $email, $department) {
    // Prepare the SQL query to update the teacher's information
    $query = "UPDATE teachers SET first_name = ?, last_name = ?, email = ?, department = ? WHERE teacherID = ?";
    $stmt = $pdo->prepare($query);
    $executeQuery = $stmt->execute([$first_name, $last_name, $email, $department, $teacherID]);

    // check if the query was successful and return appropriate response
    if ($executeQuery) {
        return [
            'message' => 'Teacher information has been updated successfully.',
            'statusCode' => 200
        ];
    } else {
        return [
            'message' => 'Failed to update teacher information.',
            'statusCode' => 400
        ];
    }
}

// Delete a teacher record
function deleteTeacher($pdo, $teacherID) {
    $sql = "DELETE FROM teachers WHERE teacherID = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$teacherID]);
}

// Insert a new user
function insertNewUser($pdo, $username, $email, $password) {
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT); 

    $executeQuery = $stmt->execute([$username, $email, $hashedPassword]);

    if ($executeQuery) {
        return [
            'message' => 'User has been inserted successfully.',
            'statusCode' => 200
        ];
    } else {
        return [
            'message' => 'Failed to insert the user.',
            'statusCode' => 400
        ];
    }
}

// Search Teachers Table (checks every column for similarities in :search)
function searchTeachersByDetails($pdo, $search) {
    $query = "SELECT * FROM teachers 
              WHERE first_name LIKE :search 
              OR last_name LIKE :search 
              OR email LIKE :search 
              OR department LIKE :search";
    $stmt = $pdo->prepare($query);
    $searchTerm = "%" . $search . "%";
    $stmt->bindParam(':search', $searchTerm);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
