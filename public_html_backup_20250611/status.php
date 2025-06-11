<?php
// Simple status check
session_start();

$checks = [
    'PHP Version' => phpversion(),
    'Session Active' => session_status() === PHP_SESSION_ACTIVE ? 'Yes' : 'No',
    'App Directory' => is_dir('../app') ? 'Found' : 'Missing',
    'Vendor Directory' => is_dir('../app/vendor') ? 'Found' : 'Missing',
    'Storage Directory' => is_dir('../storage') ? 'Found' : 'Missing',
    'Environment File' => file_exists('../app/.env') ? 'Found' : 'Missing',
    'Your IP' => $_SERVER['REMOTE_ADDR'],
    'Authenticated' => isset($_SERVER['PHP_AUTH_USER']) ? 'Yes (as ' . $_SERVER['PHP_AUTH_USER'] . ')' : 'No'
];
?>
<!DOCTYPE html>
<html>
<head>
    <title>GMPM System Status</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; max-width: 600px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .good { color: green; }
        .bad { color: red; }
    </style>
</head>
<body>
    <h1>GMPM System Status</h1>
    <table>
        <?php foreach($checks as $check => $status): ?>
        <tr>
            <th><?php echo $check; ?></th>
            <td class="<?php echo strpos($status, 'Missing') === false ? 'good' : 'bad'; ?>">
                <?php echo $status; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <p><a href="/">Back to Main Site</a></p>
</body>
</html>
