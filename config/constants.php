<?php

return [


    'bank_accounts_ids' => json_decode(env('BANK_ACCOUNTS_IDS', '[]')),

    'invoice_minimum_amount' => (float)env('INVOICE_MINIMUM_AMOUNT', 0),

   
];
