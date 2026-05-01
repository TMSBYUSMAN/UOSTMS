<?php
include('db_connect.php');

// 1. Students table mein Email, Password aur Status add karne ke liye
mysqli_query($conn, "ALTER TABLE students ADD COLUMN email VARCHAR(100) AFTER contact");
mysqli_query($conn, "ALTER TABLE students ADD COLUMN password VARCHAR(255) AFTER email");
mysqli_query($conn, "ALTER TABLE students ADD COLUMN status VARCHAR(20) DEFAULT 'Pending' AFTER password");

// 2. Routes table mein City ka column add karne ke liye
mysqli_query($conn, "ALTER TABLE routes ADD COLUMN city VARCHAR(100) AFTER route_name");

// 3. Delete Errors khatam karne ke liye Foreign Keys ko update karna (ON DELETE CASCADE)
mysqli_query($conn, "ALTER TABLE fees DROP FOREIGN KEY fees_ibfk_1");
mysqli_query($conn, "ALTER TABLE fees ADD CONSTRAINT fees_ibfk_1 FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE");

mysqli_query($conn, "ALTER TABLE maintenance DROP FOREIGN KEY maintenance_ibfk_1");
mysqli_query($conn, "ALTER TABLE maintenance ADD CONSTRAINT maintenance_ibfk_1 FOREIGN KEY (bus_id) REFERENCES buses(id) ON DELETE CASCADE");

mysqli_query($conn, "ALTER TABLE students DROP FOREIGN KEY students_ibfk_1");
mysqli_query($conn, "ALTER TABLE students ADD CONSTRAINT students_ibfk_1 FOREIGN KEY (route_id) REFERENCES routes(id) ON DELETE CASCADE");

echo "<h3>Database successfully updated!</h3>";
echo "<ul>
        <li>Students table updated (Email, Password, Status added)</li>
        <li>Routes table updated (City added)</li>
        <li>Delete constraints fixed (Cascade Delete enabled)</li>
      </ul>";
?>