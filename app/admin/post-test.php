<?php
echo "Request method: " . $_SERVER['REQUEST_METHOD'] . "<br>";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "POST is working!<br>";
    echo "Username: " . ($_POST['username'] ?? 'not set') . "<br>";
    echo "Password: " . (isset($_POST['password']) ? 'received' : 'not set') . "<br>";
}
?>
<form method="POST" action="">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <button type="submit">Test POST</button>
</form>
