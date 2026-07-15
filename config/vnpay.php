<?php

return [
    /*
    |--------------------------------------------------------------------------
    | VNPay (Sandbox demo — đổi sang merchant thật khi lên production)
    | Tài liệu: https://sandbox.vnpayment.vn/apis/docs/huong-dan-tich-hop/
    |--------------------------------------------------------------------------
    */
    'tmn_code' => env('VNPAY_TMN_CODE', 'DEMOV210'),
    'hash_secret' => env('VNPAY_HASH_SECRET', 'RAOEXHYVSDDNIYVSTDITKZWAXARPUMUL'),
    'url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'return_url' => env('VNPAY_RETURN_URL', null), // null → route('vnpay.return')
    'api_url' => env('VNPAY_API_URL', 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction'),
];
