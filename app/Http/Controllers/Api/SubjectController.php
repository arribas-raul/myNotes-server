<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\SubjectModel;
use App\Models\SubjectNoteModel;

use App\Objects\Response;

class SubjectController extends Controller
{
    /**Select functions ********************************/
    public function get(Request $request){

        $id = $request->id;

        try{
            $model = SubjectModel::getForId($id);

            if (empty($model) ){
				return Response::getArrayResponseKO(\Lang::get( 'api.objectEmpty' ));                
				
			}else{
                
				$array = Response::getArrayResponseOK(\Lang::get( 'api.getSuccess' ));
				$array['data'] = $model;
					
				return $array;
			}

        }catch (\Exception $e){
			return Response::responseKO(__CLASS__, __FUNCTION__, $e);            
					
		}catch (\PDOException $e){
			return Response::responseKO(__CLASS__, __FUNCTION__, $e);            
		} 
    }

    public function list(Request $request){

        $user = $request->user;

        try{
            $data = SubjectModel::list($user->id);

            if (empty($data) || count($data) == 0 ){
                return Response::getArrayResponseKO(\Lang::get( 'api.dataEmpty' ));                
                
            }else{
                $array = Response::getArrayResponseOK(\Lang::get( 'api.getSuccess' ));
                $array['data'] = $data;
                    
                return $array;
            }

        }catch (\Exception $e){
            return Response::responseKO(__CLASS__, __FUNCTION__, $e);            
                    
        }catch (\PDOException $e){
            return Response::responseKO(__CLASS__, __FUNCTION__, $e);            
        }
    } 

    /**Create functions ********************************/
    public function create(Request $request){

        $validator = Validator::make(
            $request->all(), 
            [
                'name' => 'required|string|min:2|max:100'
            ]);

        if($validator->fails()){
            return Response::getArrayResponseKO($validator->errors());
        }

        $user        = $request->user;
        $name        = $request->name;
        $description = $request->description;

        try{
            $model = SubjectModel::getForName($user->id, $name);

            if(isset($model)){
                return Response::getArrayResponseOK(\Lang::get( 'api.dataExist' ));
            }

            $model = SubjectModel::createObject($user->id, $name, $description);

            if (empty($model) ){
                return Response::getArrayResponseKO(\Lang::get( 'api.error' ));                
                
            }else{
                $array = Response::getArrayResponseOK(\Lang::get( 'api.createSuccess' ));
                $array['data'] = $model;
                    
                return $array;
            }

        }catch (\Exception $e){
            return Response::responseKO(__CLASS__, __FUNCTION__, $e);            
                    
        }catch (\PDOException $e){
            return Response::responseKO(__CLASS__, __FUNCTION__, $e);
        }
    }           
       
    /**Update functions ********************************/
    public function update(Request $request){

        $validator = Validator::make(
            $request->all(), 
            [
                'id'   => 'required',
                'name' => 'required|string|min:2|max:100'
            ]);

        if($validator->fails()){
            return Response::getArrayResponseKO($validator->errors());
        }

        $user        = $request->user;
        $id          = $request->id;
        $name        = $request->name;
        $description = $request->description;

        try{
            $model = SubjectModel::getForId($id);

            if(empty($model)){
                return Response::getArrayResponseOK(\Lang::get( 'api.objectEmpty' ));
            }

            $model = SubjectModel::updateObject($id, $name, $description);

            if (empty($model) ){
                return Response::getArrayResponseKO(\Lang::get( 'api.error' ));                
                
            }else{
                $array = Response::getArrayResponseOK(\Lang::get( 'api.updateSuccess' ));
                $array['data'] = $model;
                    
                return $array;
            }

        }catch (\Exception $e){
            return Response::responseKO(__CLASS__, __FUNCTION__, $e);            
                    
        }catch (\PDOException $e){
            return Response::responseKO(__CLASS__, __FUNCTION__, $e);
        }
    }

    /**Delete functions ********************************/
    public function delete(Request $request){
        $validator = Validator::make(
            $request->all(), 
            [
                'id' => 'required'
            ]);

        if($validator->fails()){
            return Response::getArrayResponseKO($validator->errors());
        }

        $user = $request->user;
        $id   = $request->id;

        try{
            $model = SubjectModel::getForId($id);

            if(empty($model)){
                return Response::getArrayResponseOK(\Lang::get( 'api.objectEmpty' ));
            }

            $transaction = DB::transaction(function() use ( $user, $id )
            {
                $result = SubjectNoteModel::deleteForIdSubject($id);
                $result += SubjectModel::deleteObject($id);

                return $result;
            });

            return $transaction > 0 ?
                Response::getArrayResponseOK(\Lang::get( 'api.deleteSuccess' )) : 
                Response::getArrayResponseKO(\Lang::get( 'api.error' ));
               

        }catch (\Exception $e){
            return Response::responseKO(__CLASS__, __FUNCTION__, $e);            
                    
        }catch (\PDOException $e){
            return Response::responseKO(__CLASS__, __FUNCTION__, $e);
        }
    }
}
