<?php

return [
    'session_key'       => 'vanilo_cart', // The session key where the cart id gets saved
    'auto_destroy'      => false, // Whether to immediately delete carts with 0 items
    'auto_set_user_id'  => true // Whether to automatically set the user_id on new carts (based on Auth::user())
];
