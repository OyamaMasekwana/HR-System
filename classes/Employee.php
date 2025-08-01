<?php
class Employee {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function getAll() {
        $query = "SELECT e.*, d.departmentName as department 
                  FROM Employee e 
                  LEFT JOIN Department d ON e.departmentId = d.departmentId";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT e.*, d.departmentName as department 
                                     FROM Employee e 
                                     LEFT JOIN Department d ON e.departmentId = d.departmentId 
                                     WHERE e.employeeId = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function create($data) {
        // First get departmentId
        $deptStmt = $this->conn->prepare("SELECT departmentId FROM Department WHERE departmentName = ?");
        $deptStmt->bind_param("s", $data['department']);
        $deptStmt->execute();
        $deptResult = $deptStmt->get_result();
        
        if ($deptResult->num_rows === 0) {
            return false;
        }
        
        $dept = $deptResult->fetch_assoc();
        $departmentId = $dept['departmentId'];
        
        $stmt = $this->conn->prepare("INSERT INTO Employee (name, position, salary, contact, departmentId) 
                                     VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdsi", $data['name'], $data['position'], $data['salary'], $data['contact'], $departmentId);
        return $stmt->execute();
    }
    
    public function update($id, $data) {
        // Similar to create but with update
        $deptStmt = $this->conn->prepare("SELECT departmentId FROM Department WHERE departmentName = ?");
        $deptStmt->bind_param("s", $data['department']);
        $deptStmt->execute();
        $deptResult = $deptStmt->get_result();
        
        if ($deptResult->num_rows === 0) {
            return false;
        }
        
        $dept = $deptResult->fetch_assoc();
        $departmentId = $dept['departmentId'];
        
        $stmt = $this->conn->prepare("UPDATE Employee 
                                     SET name=?, position=?, salary=?, contact=?, departmentId=?
                                     WHERE employeeId=?");
        $stmt->bind_param("ssdsii", $data['name'], $data['position'], $data['salary'], $data['contact'], $departmentId, $id);
        return $stmt->execute();
    }
    
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM Employee WHERE employeeId = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    public function getDepartmentStats() {
        $query = "SELECT d.departmentName as department, COUNT(*) as count 
                  FROM Employee e 
                  JOIN Department d ON e.departmentId = d.departmentId 
                  GROUP BY d.departmentName";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public function searchEmployees($searchTerm) {
    $stmt = $this->conn->prepare("
        SELECT e.*, d.departmentName as department 
        FROM Employee e 
        LEFT JOIN Department d ON e.departmentId = d.departmentId
        WHERE e.name LIKE ? OR e.position LIKE ? OR d.departmentName LIKE ?
    ");
    $searchParam = "%$searchTerm%";
    $stmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function searchAttendance($searchTerm) {
    $stmt = $this->conn->prepare("
        SELECT a.*, e.name 
        FROM Attendance a 
        JOIN Employee e ON a.employeeId = e.employeeId
        WHERE e.name LIKE ? OR a.status LIKE ?
        ORDER BY a.attendanceDate DESC
    ");
    $searchParam = "%$searchTerm%";
    $stmt->bind_param("ss", $searchParam, $searchParam);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
   public function getEmployeeId($userId) {
    $stmt = $this->conn->prepare("SELECT employeeId FROM Employee WHERE userId = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['employeeId'] ?? null;
}
}
?>