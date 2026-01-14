<?php

return [
    'user' => [
        ['name' => 'gender', 'value' => '', 'context' => 'user'],
        ['name' => 'contact', 'value' => '', 'context' => 'user'],
        ['name' => 'address', 'value' => '', 'context' => 'user'],
        ['name' => 'date_of_birth', 'value' => '', 'context' => 'user'],
    ],
    'app' => [
        ['name' => 'company_name', 'value' => env('APP_NAME', 'Salex'), 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'company_logo', 'value' => '/images/logo.png', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'company_icon', 'value' => '/images/icon.png', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'company_banner', 'value' => '/images/default-banner.png', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'layout', 'value' => 'ltr', 'context' => 'app', 'autoload' => 0, 'public' => 1],

        ['name' => 'invoice_logo', 'value' => '/images/invoice-logo.png', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'language', 'value' => 'en', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'date_format', 'value' => 'd-m-Y', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'time_format', 'value' => 'h', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'time_zone', 'value' => 'Asia/Dhaka', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'currency_symbol', 'value' => '$', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'currency_code', 'value' => 'usd', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'decimal_separator', 'value' => '.', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'thousand_separator', 'value' => ',', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'number_of_decimal', 'value' => '2', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'currency_position', 'value' => 'prefix_with_space', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        [
            'name' => 'country',
            'value' => 'Afghanistan,Albania,Algeria,American Samoa,Andorra,Angola,Anguilla,Antarctica,Antigua and Barbuda,Argentina,Armenia,Aruba,Australia,Austria,Azerbaijan,Bahamas,Bahrain,Bangladesh,Barbados,Belarus,Belgium,Belize,Benin,Bermuda,Bhutan,Bolivia,Bosnia and Herzegovina,Botswana,Bouvet Island,Brazil,British Indian Ocean Territory,Brunei Darussalam,Bulgaria,Burkina Faso,Burundi,Cambodia,Cameroon,Canada,Cape Verde,Cayman Islands,Central African Republic,Chad,Chile,China,Christmas Island,Cocos (Keeling Islands),Colombia,Comoros,Congo,Cook Islands,Costa Rica,Cote D\'Ivoire (Ivory Coast),Croatia (Hrvatska,Cuba,Cyprus,Czech Republic,Denmark,Djibouti,Dominica,Dominican Republic,East Timor,Ecuador,Egypt,El Salvador,Equatorial Guinea,Eritrea,Estonia,Ethiopia,Falkland Islands (Malvinas),Faroe Islands,Fiji,Finland,France,Metropolitan,French Guiana,French Polynesia,French Southern Territories,Gabon,Gambia,Georgia,Germany,Ghana,Gibraltar,Greece,Greenland,Grenada,Guadeloupe,Guam,Guatemala,Guinea,Guinea-Bissau,Guyana,Haiti,Heard and McDonald Islands,Honduras,Hong Kong,Hungary,Iceland,India,Indonesia,Iran,Iraq,Ireland,Israel,Italy,Jamaica,Japan,Jordan,Kazakhstan,Kenya,Kiribati,Korea (North),Korea (South),Kuwait,Kyrgyzstan,Laos,Latvia,Lebanon,Lesotho,Liberia,Libya,Liechtenstein,Lithuania,Luxembourg,Macau,Macedonia,Madagascar,Malawi,Malaysia,Maldives,Mali,Malta,Marshall Islands,Martinique,Mauritania,Mauritius,Mayotte,Mexico,Micronesia,Moldova,Monaco,Mongolia,Montserrat,Morocco,Mozambique,Myanmar,Namibia,Nauru,Nepal,Netherlands,Netherlands Antilles,New Caledonia,New Zealand,Nicaragua,Niger,Nigeria,Niue,Norfolk Island,Northern Mariana Islands,Norway,Oman,Pakistan,Palau,Panama,Papua New Guinea,Paraguay,Peru,Philippines,Pitcairn,Poland,Portugal,Puerto Rico,Qatar,Reunion,Romania,Russian Federation,Rwanda,Saint Kitts and Nevis,Saint Lucia,Saint Vincent and The Grenadines,Samoa,San Marino,Sao Tome and Principe,Saudi Arabia,Senegal,Seychelles,Sierra Leone,Singapore,Slovak Republic,Slovenia,Solomon Islands,Somalia,South Africa,S. Georgia and S. Sandwich Isls.,Spain,Sri Lanka,St. Helena,St. Pierre and Miquelon,Sudan,Suriname,Svalbard and Jan Mayen Islands,Swaziland,Sweden,Switzerland,Syria,Taiwan,Tajikistan,Tanzania,Thailand,Togo,Tokelau,Tonga,Trinidad and Tobago,Tunisia,Turkey,Turkmenistan,Turks and Caicos Islands,Tuvalu,Uganda,Ukraine,United Arab Emirates,United Kingdom (Britain / UK),United States of America (USA),US Minor Outlying Islands,Uruguay,Uzbekistan,Vanuatu,Vatican City State (Holy See),Venezuela,Viet Nam,Virgin Islands (British),Virgin Islands (US),Wallis and Futuna Islands,Western Sahara,Yemen,Yugoslavia,Zaire,Zambia,Zimbabwe',
            'context' => 'app',
            'autoload' => 0,
            'public' => 1
        ],
        ['name' => 'sales_invoice_logo', 'value' => '/images/logo.png', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'sales_invoice_prefix', 'value' => 'POS', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'sales_invoice_suffix', 'value' => 'sales', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'sales_invoice_auto_generate', 'value' => '1', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'sales_invoice_auto_email', 'value' => '1', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'sales_invoice_starts_from', 'value' => '10000', 'context' => 'app', 'autoload' => 0, 'public' => 1],

        ['name' => 'return_invoice_logo', 'value' => '/images/logo.png', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'return_invoice_prefix', 'value' => 'POS', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'return_invoice_suffix', 'value' => 'return', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'return_invoice_auto_generate', 'value' => '0', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'return_invoice_auto_email', 'value' => '0', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'return_invoice_starts_from', 'value' => '10000', 'context' => 'app', 'autoload' => 0, 'public' => 1],


        ['name' => 'account_sid', 'value' => 'XXXXXXXX', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'auth_token', 'value' => 'XXXXXXXX', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'sms_sent_from', 'value' => '+017xxxxxxx', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'sms_driver', 'value' => 'twilio', 'context' => 'app', 'autoload' => 0, 'public' => 1],
        ['name' => 'send_auto_sms', 'value' => '0', 'context' => 'app', 'autoload' => 0, 'public' => 1],

    ],
    'brand' => [
        ['name' => 'avatar', 'value' => null, 'context' => 'brand'],
        ['name' => 'address', 'value' => '', 'context' => 'brand'],
    ],
    'context' => [
        'app',
        'subscriber',
        'brand',
        'role',
        'template'
    ],
    'time_format' => [
        'h',
        'H'
    ],
    'layouts' => [
        'ltr',
        'rtl'
    ],
    'currency_position' => [
        'prefix_only',
        'prefix_with_space',
        'suffix_only',
        'suffix_with_space'
    ],
    'amazon_ses' => [
        'hostname' => '',
        'access_key_id' => '',
        'secret_access_key' => '',
    ],
    'mailgun' => [
        'domain_name' => '',
        'api_key' => '',
        'webhook_key' => ''
    ],
    'mail_configs' => [
        'context' => '',
        'from_email' => '',
        'from_name' => ''
    ],
    'date_format' => [
        'd-m-Y',
        'm-d-Y',
        'Y-m-d',
        'm/d/Y',
        'd/m/Y',
        'Y/m/d',
        'm.d.Y',
        'd.m.Y',
        'Y.m.d'
    ],

    'decimal_separator' => [
      '.',
      ','
    ],

    'thousand_separator' => [
        '.',
        ',',
        ' '
    ],
    'number_of_decimal' => [
        '0',
        '2'
    ],
    'supported_mail_services' => [
        'smtp' => 'SMTP',
        'amazon_ses' => 'Amazon SES',
        'mailgun' => 'Mailgun',
        'sendmail' => 'Sendmail'
    ],
    'corn-job-context' => 'corn-job',
    'brand_default_prefix' => [
        'amazon_ses' => 'brand_default_amazon_ses',
        'mailgun' => 'brand_default_mailgun',
        'privacy' => 'brand_default_privacy',
        'notification' => 'brand_default_notification',
    ],
    'warranty_duration_type' => [
        'hour_s',
        'day_s',
        'month_s',
        'year_s',
    ],

    'default_mail_setup' => [
        'from_name' => 'Salex',
        'from_email' => 'salex@gainhq.com',
        'provider' => 'sendmail',
        'default_mail' => 'sendmail',
        'context' => 'app',
    ]
];
