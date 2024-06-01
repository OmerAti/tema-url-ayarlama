<?php
function server_info_check() {
    $web_server = $_SERVER['SERVER_SOFTWARE'];
    if (strpos($web_server, 'Apache') !== false || strpos($web_server, 'nginx') !== false || strpos($web_server, 'LiteSpeed') !== false) {
        return '<span style="color:green;"> ' . $web_server . '</span>';
    } else {
        return '<span style="color:red;">Desteklenmeyen Sunucu</span>';
    }
}
function disk_space_check() {
    $disk_space = disk_total_space('/');
    if ($disk_space >= 1073741824) { 
        return '<span style="color:green;">Yeterli</span>';
    } else {
        return '<span style="color:red;">Yetersiz</span>';
    }
}
function php_version_check() {
    $php_version = phpversion();
    if (version_compare($php_version, '8.0', '>=')) {
        return '<span style="color:green;">' . $php_version . '</span>';
    } else {
        return '<span style="color:red;">' . $php_version . '</span>';
    }
}
function database_check() {
    global $wpdb;
    $database_version = $wpdb->db_version();
    if (version_compare($database_version, '5.015', '>=')) {
        return '<span style="color:green;">MySQL ' . $database_version . '</span>';
    } else {
        return '<span style="color:red;">MySQL ' . $database_version . '</span>';
    }
}
function ram_check() {
    $ram = shell_exec('free -m');
    if (preg_match('/\d+/', $ram, $matches)) {
        $ram_in_mb = intval($matches[0]);
        if ($ram_in_mb >= 512) {
            return '<span style="color:green;">En az 512 MB</span>';
        } else {
            return '<span style="color:red;">' . $ram_in_mb . ' MB</span>';
        }
    } else {
        return '<span style="color:red;">Bilgi Bulunamadı</span>';
    }
}

function https_support_check() {
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        return '<span style="color:green;">Destekleniyor</span>';
    } else {
        return '<span style="color:red;">Desteklenmiyor</span>';
    }
}
?>
