<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */
    'functional' => [
        'save' => '儲存訂單',
        'cancel' => '取消儲存',
        'update' => '修改訂單',
        'delete' => '刪除訂單',
        'pdf' => '產生PDF',
        'search' => '搜尋'
    ],
    'fields' => [
        'name' => '訂購人',
        'phone' => '聯絡電話',
        'name_backup' => '備用聯絡人',
        'phone_backup' => '備用聯絡電話',
        'email' => '電子信箱',
        'deposit' => '訂金',
        'extra_fee' => '額外費用',
        'engaged_date' => '訂婚日期',
        'married_date' => '結婚日期',
        'card_required' => '結婚小卡',
        'wood_required' => '準備木盛',
        'remark' => '備註',
        'final_paid' => '尾款'
    ],
    'placeholder' => [
        'name' => '請輸入訂購人',
        'phone' => '請輸入聯絡電話',
        'name_backup' => '請輸入備用聯絡人',
        'phone_backup' => '請輸入備用聯絡電話',
        'email' => '請輸入電子信箱',
        'deposit' => '請輸入訂金',
        'extra_fee' => '請輸入額外費用',
        'engaged_date' => '請點選訂婚日期',
        'married_date' => '請點選結婚日期',
        'remark' => '請輸入備註事項'
    ],
    'replace_string' => [
        'required' => [
            'yes' => '要',
            'no' => '不要'
        ],
        'paid' => [
            'yes' => '已付',
            'no' => '未付'
        ]
    ],
    'unit' => [
        'dollar' => '元'
    ],
    'notification' => [
        'empty_case' => '尚未選擇禮盒內容',
        'empty_filter_package' => '尚未有該類別包裹'
    ],
    'section' => [
        'info' => '基本資料',
        'case' => '禮盒資料',
        'package' => '包裹資料',
        'final_paid' => '尾款狀態'
    ],

];
