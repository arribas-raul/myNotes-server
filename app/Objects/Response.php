<?php

namespace App\Objects;

use App\Helpers\LogHelper;

class Response
{
     const RESPONSE_OK = 'OK';
     const RESPONSE_KO = 'KO';

     protected $state;
     protected $msg;

     public function __construct( $state, $msg ) 
     {
          $this->state = $state;
          $this->msg = $msg;
     }

     public function isOk()
     {
           return $this->state == 'ok';
     }

     public function getMsg()
     {
          return $this->msg;
     }

     static public function getArrayResponse( $state, $msg )
     {
		return [
			'state' => $state,
			'msg' => $msg
		];
     }

     static public function getArrayResponseOK($msg)
     {
		return [
			'state' => self::RESPONSE_OK,
			'msg' => $msg
		];
     }

     static public function getArrayResponseKO($msg)
     {
		return [
			'state' => self::RESPONSE_KO,
			'msg' => $msg
		];
     }

     static public function isArrayResponseOK($response){
		return $response['state'] == self::RESPONSE_OK;
     }

     static public function responseKO($class, $function, $error, $msg = null)
     {
		LogHelper::printError($class, $function, $error );
		
		return 
		[
			'state' => self::RESPONSE_KO,
			'msg'   => $msg ? $msg : \Lang::get( 'api.error' )
		];
     }
     
}