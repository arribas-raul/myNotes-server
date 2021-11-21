<?php

namespace App\Objects;

use App\Helpers\LogHelper;

class Response
{
     const RESPONSE_OK = 'OK';
     const RESPONSE_KO = 'KO';

     protected $status;
     protected $msg;

     public function __construct( $status, $msg ) 
     {
          $this->status = $status;
          $this->msg = $msg;
     }

     public function isOk()
     {
           return $this->status == 'OK';
     }

     public function getMsg()
     {
          return $this->msg;
     }

     static public function getArrayResponse( $status, $msg )
     {
		return [
			'status' => $status,
			'msg' => $msg
		];
     }

     static public function getArrayResponseOK($msg)
     {
		return [
			'status' => self::RESPONSE_OK,
			'msg' => $msg
		];
     }

     static public function getArrayResponseKO($msg)
     {
		return [
			'status' => self::RESPONSE_KO,
			'msg' => $msg
		];
     }

     static public function isArrayResponseOK($response){
		return $response['status'] == self::RESPONSE_OK;
     }

     static public function responseKO($class, $function, $error, $msg = null)
     {
		LogHelper::printError($class, $function, $error );
		
		return 
		[
			'status' => self::RESPONSE_KO,
			'msg'   => $msg ? $msg : \Lang::get( 'api.error' )
		];
     }
     
}