<?php

// Add this to your existing config/app.php array:
// 'admin_key' => env('ADMIN_KEY', 'GREENORDER_ADMIN_2024'),

// Full config/app.php reference entry:
return [
    // ... other Laravel config ...

    /*
    |--------------------------------------------------------------------------
    | GreenOrder Admin Registration Key
    |--------------------------------------------------------------------------
    | This secret key is required to register as an admin account.
    | Change this in your .env file:  ADMIN_KEY=your_secret_key_here
    */
    'admin_key' => env('ADMIN_KEY', 'GREENORDER_ADMIN_2024'),
];
