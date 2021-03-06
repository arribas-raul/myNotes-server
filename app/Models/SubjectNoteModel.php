<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\CustomModel;

class SubjectNoteModel extends CustomModel
{
    protected $table = 'subject_note';
	protected $fillable = [ 'id_subject', 'name', 'note', 'is_deleted' ];

    /**Select functions ********************************/
    static public function getForId($id){
        return parent::find($id);
    }

    static public function list($id_subject){
        return parent::where('id_subject', $id_subject)
        ->where('is_deleted', false)
        ->get();
    }

    /**Create functions ********************************/
    static public function createObject( $id_subject, $name, $note ){
        $data = 
		[
			'id_subject' => $id_subject,
            'name'       => $name,
			'note'       => $note
		];

        \Log::info(['data' => $data]);

		return parent::create( $data );
    }

    /**Update functions ********************************/
    static public function updateObject( $id, $name, $note ){
        $data = 
		[
            'name' => $name,
			'note' => $note,
		];

		return parent::find($id)->update( $data );
    }

    /*Delete functions******************************************************************************/
    static public function deleteForIdSubject( $id_subject ){
        $data = 
		[
			'is_deleted' => true
		];

		return parent::where('id_subject', $id_subject)->update($data);
    }
}
