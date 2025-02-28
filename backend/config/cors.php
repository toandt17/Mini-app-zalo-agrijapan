<?php

return [
    'paths' => ['*'], // Áp dụng cho API
    'allowed_methods' => ['*'], // Cho phép tất cả phương thức
    'allowed_origins' => ['*'], // Cho phép tất cả domain truy cập
    'allowed_headers' => ['*'], // Cho phép tất cả headers
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];

