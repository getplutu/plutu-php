<?php

return [
    'status' => 200,
    'result' => [
        "process_id" => rand(1000000000000, 9999999999999),
        "amount" => rand(1, 100),
        "commission" => 0
    ],
    "message" => "OTP has been sent to your mobile number"
];