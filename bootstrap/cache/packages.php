<?php return array (
  'adldap2/adldap2-laravel' => 
  array (
    'providers' => 
    array (
      0 => 'Adldap\\Laravel\\AdldapServiceProvider',
      1 => 'Adldap\\Laravel\\AdldapAuthServiceProvider',
    ),
    'aliases' => 
    array (
      'Adldap' => 'Adldap\\Laravel\\Facades\\Adldap',
    ),
  ),
  'barryvdh/laravel-snappy' => 
  array (
    'providers' => 
    array (
      0 => 'Barryvdh\\Snappy\\ServiceProvider',
    ),
    'aliases' => 
    array (
      'PDF' => 'Barryvdh\\Snappy\\Facades\\SnappyPdf',
      'SnappyImage' => 'Barryvdh\\Snappy\\Facades\\SnappyImage',
    ),
  ),
  'beyondcode/laravel-credentials' => 
  array (
    'providers' => 
    array (
      0 => 'BeyondCode\\Credentials\\CredentialsServiceProvider',
    ),
  ),
  'beyondcode/laravel-dump-server' => 
  array (
    'providers' => 
    array (
      0 => 'BeyondCode\\DumpServer\\DumpServerServiceProvider',
    ),
  ),
  'fideloper/proxy' => 
  array (
    'providers' => 
    array (
      0 => 'Fideloper\\Proxy\\TrustedProxyServiceProvider',
    ),
  ),
  'genealabs/laravel-caffeine' => 
  array (
    'providers' => 
    array (
      0 => 'GeneaLabs\\LaravelCaffeine\\Providers\\Service',
    ),
  ),
  'laravel-notification-channels/webpush' => 
  array (
    'providers' => 
    array (
      0 => 'NotificationChannels\\WebPush\\WebPushServiceProvider',
    ),
  ),
  'laravel/tinker' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Tinker\\TinkerServiceProvider',
    ),
  ),
  'laravelcollective/html' => 
  array (
    'providers' => 
    array (
      0 => 'Collective\\Html\\HtmlServiceProvider',
    ),
    'aliases' => 
    array (
      'Form' => 'Collective\\Html\\FormFacade',
      'Html' => 'Collective\\Html\\HtmlFacade',
    ),
  ),
  'maatwebsite/excel' => 
  array (
    'providers' => 
    array (
      0 => 'Maatwebsite\\Excel\\ExcelServiceProvider',
    ),
    'aliases' => 
    array (
      'Excel' => 'Maatwebsite\\Excel\\Facades\\Excel',
    ),
  ),
  'mavinoo/laravel-batch' => 
  array (
    'providers' => 
    array (
      0 => 'Mavinoo\\Batch\\BatchServiceProvider',
    ),
    'aliases' => 
    array (
      'Batch' => 'Mavinoo\\Batch\\BatchFacade',
    ),
  ),
  'nesbot/carbon' => 
  array (
    'providers' => 
    array (
      0 => 'Carbon\\Laravel\\ServiceProvider',
    ),
  ),
  'nunomaduro/collision' => 
  array (
    'providers' => 
    array (
      0 => 'NunoMaduro\\Collision\\Adapters\\Laravel\\CollisionServiceProvider',
    ),
  ),
  'spatie/laravel-activitylog' => 
  array (
    'providers' => 
    array (
      0 => 'Spatie\\Activitylog\\ActivitylogServiceProvider',
    ),
  ),
  'spatie/laravel-backup' => 
  array (
    'providers' => 
    array (
      0 => 'Spatie\\Backup\\BackupServiceProvider',
    ),
  ),
  'spatie/laravel-permission' => 
  array (
    'providers' => 
    array (
      0 => 'Spatie\\Permission\\PermissionServiceProvider',
    ),
  ),
  'yajra/laravel-datatables-buttons' => 
  array (
    'providers' => 
    array (
      0 => 'Yajra\\DataTables\\ButtonsServiceProvider',
    ),
  ),
  'yajra/laravel-datatables-editor' => 
  array (
    'providers' => 
    array (
      0 => 'Yajra\\DataTables\\EditorServiceProvider',
    ),
  ),
  'yajra/laravel-datatables-fractal' => 
  array (
    'providers' => 
    array (
      0 => 'Yajra\\DataTables\\FractalServiceProvider',
    ),
  ),
  'yajra/laravel-datatables-html' => 
  array (
    'providers' => 
    array (
      0 => 'Yajra\\DataTables\\HtmlServiceProvider',
    ),
  ),
  'yajra/laravel-datatables-oracle' => 
  array (
    'providers' => 
    array (
      0 => 'Yajra\\DataTables\\DataTablesServiceProvider',
    ),
    'aliases' => 
    array (
      'DataTables' => 'Yajra\\DataTables\\Facades\\DataTables',
    ),
  ),
  'yajra/laravel-oci8' => 
  array (
    'providers' => 
    array (
      0 => 'Yajra\\Oci8\\Oci8ServiceProvider',
    ),
  ),
);