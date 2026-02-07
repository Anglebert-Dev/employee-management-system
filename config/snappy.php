<?php

return [
    'pdf' => [
        'enabled' => true,
        'binary'  => env('WKHTML_PDF_BINARY', base_path('vendor/wemersonjanuario/wkhtmltopdf-windows/bin/64bit/wkhtmltopdf.exe')),
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],
    
    'image' => [
        'enabled' => true,
        'binary'  => env('WKHTML_IMG_BINARY', base_path('vendor/wemersonjanuario/wkhtmltopdf-windows/bin/64bit/wkhtmltoimage.exe')),
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],
];