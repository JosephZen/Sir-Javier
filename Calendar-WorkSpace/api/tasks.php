<?php
require '../config.php';
require '../includes/functions.php';  // For createClickUpTask
header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) die(json_encode(['error' => 'Unauthorized']));

$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action === 'get') {
    // ... existing code unchanged ...
} elseif ($action === 'create') {
    $data = [
        'title' => $_POST['title'],
        'start_time' => $_POST['start_time'],
        'end_time' => $_POST['end_time'],
        'progress' => $_POST['progress'] ?? 0,
        'status' => $_POST['status'] ?? 'ongoing',
        'clickup_id' => null,  // Will be set if ClickUp succeeds
        'created_by' => $user_id
    ];

    // Insert task first
    $stmt = $pdo->prepare("INSERT INTO tasks (title, start_time, end_time, progress, status, clickup_id, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt->execute(array_values($data))) {
        echo json_encode(['success' => false, 'error' => 'Failed to create task']);
        exit;
    }

    $task_id = $pdo->lastInsertId();

    // Add creator as assignee
    $pdo->prepare("INSERT INTO task_assignments (task_id, user_id) VALUES (?, ?)")->execute([$task_id, $user_id]);

    // ClickUp Integration
    $clickup_id = null;
    if (isset($_POST['create_clickup']) && $_POST['create_clickup'] === 'true') {
        $description = "Created from Team Calendar. Start: {$_POST['start_time']}, End: {$_POST['end_time']}, Assigned: {$_SESSION['username']}. Progress: {$_POST['progress']}%. Status: {$_POST['status']}";
        $priority = ($_POST['status'] === 'help-requested') ? 1 : 3;  // Urgent for help requests
        $clickup_id = createClickUpTask($_POST['title'], $description, $priority);
        
        if ($clickup_id) {
            // Update our DB with ClickUp ID
            $pdo->prepare("UPDATE tasks SET clickup_id = ? WHERE id = ?")->execute([$clickup_id, $task_id]);
            $data['clickup_id'] = $clickup_id;
        } else {
            // Still succeed, but notify
            echo json_encode(['success' => true, 'id' => $task_id, 'clickup_error' => 'Task created, but ClickUp sync failed. Check logs.']);
            exit;
        }
    }

    echo json_encode(['success' => true, 'id' => $task_id, 'clickup_id' => $clickup_id]);
} elseif ($action === 'update') {
    // ... existing code unchanged ...
    // Optionally add ClickUp update here (PUT to /task/{taskId}), but for now, just local update
} elseif ($action === 'add_collaborator') {
    // ... existing code unchanged ...
} elseif ($action === 'delete') {
    // ... existing code unchanged ...
    // Optionally delete from ClickUp if clickup_id exists (DELETE to /task/{taskId})
}
?>