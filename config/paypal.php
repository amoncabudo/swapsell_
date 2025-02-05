<?php

return [
    'mode'      => env('PAYPAL_MODE', 'sandbox'),
    'client_id' => env('PAYPAL_CLIENT_ID', 'AQ54dqpTpwUqqwMSE8LL9Mnds4f5HrqSE_c4EqZv9piafk5Q1xyCANCbgALxmWtxjjjspcfhpaC1_IoO'),
    'secret'    => env('PAYPAL_SECRET', 'EMhFu-zVuaxfNRQqS7sxJNPsxLbwJk6LpkaF5Uo3m4piw724S5I8fJ0HikeTOSCzadCkM8Ty8tvtgNVp'),
    'currency'  => 'EUR'  // Cambiado a EUR ya que estás en Europa
];
