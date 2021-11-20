<?php

namespace App\Helpers;

class LogHelper
{    
     static public function printError( $tag, $function, $error ) 
     {
          \Log::error($tag, ['function' => $function, 'error' => $error]);
     }
}