<?php

namespace App\Models;

use App\Models\CustomModel;

class SubjectModel extends CustomModel
{
    protected $table = 'subject';
	protected $fillable = [ 'id_user', 'name', 'description', 'is_deleted' ];

    /**Select functions ********************************/
    static public function getForId($id){
        return parent::find($id);
    }

    static public function getForName($id_user, $name){
        return parent::where('id_user', $id_user)
        ->where('name', $name)
        ->where('is_deleted', false)
        ->first();
    }

    static public function list($id_user){
        return parent::where('id_user', $id_user)
        ->where('is_deleted', false)
        ->get();
    }

    /**Create functions ********************************/
    static public function createObject( $id_user, $name, $description ){
        $data = 
		[
			'id_user'     => $id_user,
			'name'        => $name,
			'description' => $description
		];

		return parent::create( $data );
    }

    /**Update functions ********************************/
    static public function updateObject( $id, $name, $description ){
        $data = 
		[
			'name'        => $name,
			'description' => $description
		];

		return parent::find($id)->update( $data );
    }
}
