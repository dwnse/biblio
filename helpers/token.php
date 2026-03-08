<?php
// helpers/token.php

function generate_simple_token($userId, $email) {
    // Token simple: base64_encode(userId:email:timestamp:random)
    $data = $userId . ':' . $email . ':' . time() . ':' . bin2hex(random_bytes(8));
    return base64_encode($data);
}
