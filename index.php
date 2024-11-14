<?php
$conn = new mysqli("localhost", "root", "", "mytodolist");
if ($conn->connect_error) {
    die("Connection Failed " . $conn->connect_error);
}

if (isset($_POST["addtask"])) {
    $task = $_POST["task"];
    $deadline = $_POST["deadline"];
    $conn->query("INSERT INTO tasks (task, deadline) VALUES ('$task', '$deadline')");
    header("Location: index.php");
}

if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $conn->query("DELETE FROM tasks WHERE id = '$id'");
    header("Location: index.php");
}

if (isset($_GET["complete"])) {
    $id = $_GET["complete"];
    $conn->query("UPDATE tasks SET status ='selesai' WHERE id = '$id'");
    header("Location: index.php");
}

if (isset($_GET["reset"])) {
    $id = $_GET["reset"];
    $conn->query("UPDATE tasks SET status ='belum selesai' WHERE id = '$id'");
    header("Location: index.php");
}

$result = $conn->query("SELECT * FROM tasks ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<head>
    <title>My To-do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>My To-do List</h1>
        <form action="index.php" method="post">
            <input type="text" name="task" placeholder="Enter new task:" required>
            <input type="datetime-local" name="deadline" required>
            <button type="submit" name="addtask">Add Task</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Created At</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr class="<?php echo ($row['status'] == 'selesai') ? 'completed' : ''; ?>">
                        <td><?php echo htmlspecialchars($row["task"]); ?></td>
                        <td><?php echo date('Y-m-d H:i', strtotime($row["created_at"])); ?></td>
                        <td><?php echo date('Y-m-d H:i', strtotime($row["deadline"])); ?></td>
                        <td>
                            <?php echo ucfirst($row["status"]); ?>
                        </td>
                        <td>
                            <?php if ($row["status"] == 'belum selesai'): ?>
                                <a href="index.php?complete=<?php echo $row['id']; ?>">Complete</a>
                            <?php else: ?>
                                <a href="index.php?reset=<?php echo $row['id']; ?>">Reset</a>
                            <?php endif; ?>
                            <a href="index.php?delete=<?php echo $row['id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
