<?php
/**
 * GIT DEPLOYMENT SCRIPT
 *
 * @package Inheart
 */

$allowed_ips = array(
    '140.82.115.',
    '207.97.227.',
    '50.57.128.',
    '108.171.174.',
    '50.57.231.',
    '204.232.175.',
    '192.30.252.', // GitHub
    '195.37.139.',
    '193.174.' // VZG
);
$allowed = false;

$headers = apache_request_headers();

if (@$headers["X-Forwarded-For"]) {
    $ips = explode(",", $headers["X-Forwarded-For"]);
    $ip = $ips[0];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

foreach ($allowed_ips as $allow) {
    if (stripos($ip, $allow) !== false) {
        $allowed = true;
        break;
    }
}

if (!$allowed) {
    header('HTTP/1.1 403 Forbidden');
    echo "<span style=\"color: #ff0000\">Sorry, no hamster - better convince your parents!</span>\n";
    echo "</pre>\n</body>\n</html>";
    exit;
}

flush();

$commands = array(
    'echo $PWD',
    'whoami',
    'git clean  -d  -f .',
    'git pull',
    'git status',
);

$output = PHP_EOL;

$log = "####### " . date('Y-m-d H:i:s') . " #######\n";

foreach ($commands as $command) {
    $tmp = shell_exec("$command 2>&1");
    $output .= $command . PHP_EOL;
    $output .= htmlentities(trim($tmp)) . PHP_EOL;

    $log .= "\$ $command" . PHP_EOL . trim($tmp) . PHP_EOL;
}

$log .= PHP_EOL;

file_put_contents('deploy-log.txt', $log, FILE_APPEND);

echo $output;
