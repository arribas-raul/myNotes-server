<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomModel extends Model
{
	/*Delete functions******************************************************************************/
	static public function deleteObject ($id)
	{
		$data = 
		[
			'is_deleted' => true
		];

		return parent::find($id)->update($data);
	}   
}
