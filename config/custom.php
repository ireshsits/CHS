<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Custom Configurations
    |--------------------------------------------------------------------------
    |
    | This file is for storing custom parameters for features
    | => Only report purpose complaints (Ex:- NSC)
    |
    */

    'report_purpose_complaint' => [
        'areas' => env('REPORT_PURPOSE_COMPLAINT_AREAS', ''),
        'branch_departments' => env('REPORT_PURPOSE_COMPLAINT_BRANCH_DEPARTMENTS', ''),
    ],

];
