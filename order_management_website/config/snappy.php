<?php

return array(


    'pdf' => array(
        'enabled' => true,
        'binary'  => env('PDF_BIN_PATH'),
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),
    'image' => array(
        'enabled' => true,
        'binary'  => env('IMAGE_BIN_PATH'),
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),


);
