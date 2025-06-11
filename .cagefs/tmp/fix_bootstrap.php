<?php
$file = '/home/gmpmus/app/src/bootstrap.php';
$content = file_get_contents($file);
$content = preg_replace('/define\(\'(.*?)\',/', '!defined(\'$1\') && define(\'$1\',', $content);
file_put_contents($file, $content);
