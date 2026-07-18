<?php
// download.php
require_once 'includes/db.php';

$app_id = isset($_GET['app_id']) ? intval($_GET['app_id']) : 0;

if ($app_id > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM applications WHERE id = ?");
        $stmt->execute([$app_id]);
        $app = $stmt->fetch();

        if ($app) {
            // Log download
            function get_client_ip() {
                $ipaddress = '';
                if (isset($_SERVER['HTTP_CLIENT_IP']))
                    $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
                else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                    $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
                else if(isset($_SERVER['HTTP_X_FORWARDED']))
                    $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
                else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
                    $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
                else if(isset($_SERVER['HTTP_FORWARDED']))
                    $ipaddress = $_SERVER['HTTP_FORWARDED'];
                else if(isset($_SERVER['REMOTE_ADDR']))
                    $ipaddress = $_SERVER['REMOTE_ADDR'];
                else
                    $ipaddress = 'UNKNOWN';
                return $ipaddress;
            }

            $ip = get_client_ip();
            $device_info = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown';
            
            // Note: MAC Address cannot be accurately obtained over HTTP without client-side native code
            // So we mock it or extract if local
            $mac_address = 'Unavailable over HTTP';

            $log_stmt = $pdo->prepare("INSERT INTO app_downloads (app_id, ip_address, mac_address, device_info) VALUES (?, ?, ?, ?)");
            $log_stmt->execute([$app_id, $ip, $mac_address, $device_info]);

            // Redirect to the actual file
            header("Location: " . $app['file_url']);
            exit;
        } else {
            echo "Error: Application not found.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Database error.";
        exit;
    }
} else {
    echo "Invalid application ID.";
    exit;
}
?>
