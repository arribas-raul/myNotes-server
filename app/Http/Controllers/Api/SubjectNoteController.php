<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\SubjectNoteModel;
use App\Objects\Response;

class SubjectNoteController extends Controller
{
    public function get(Request $request){

        $id = $request->id;

        try{
            $model = SubjectNoteModel::getForId($id);

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

        $user       = $request->user;
        $id_subject = $request->id_subject;

        try{
            $data = SubjectNoteModel::list($id_subject);

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
                'id_subject' => 'required',
                'name'       => 'required|string|min:2|max:100',
                'note'       => 'required|string|min:0|max:1000'
            ]);

        if($validator->fails()){
            return Response::getArrayResponseKO($validator->errors());
        }

        $user        = $request->user;
        $name        = $request->name;
        $note        = $request->note;
        $id_subject  = $request->id_subject;

        try{
            $model = SubjectNoteModel::createObject($id_subject, $name, $note);

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
                'name' => 'required|string|min:2|max:100',
                'note' => 'required|string|min:2|max:1000'
            ]);

        if($validator->fails()){
            return Response::getArrayResponseKO($validator->errors());
        }

        $user        = $request->user;
        $id          = $request->id;
        $name        = $request->name;
        $note        = $request->note;

        try{
            $model = SubjectNoteModel::getForId($id);

            if(empty($model)){
                return Response::getArrayResponseOK(\Lang::get( 'api.objectEmpty' ));
            }

            $model = SubjectNoteModel::updateObject($id, $name, $note);

            if (empty($model) ){
                return Response::getArrayResponseKO(\Lang::get( 'api.error' ));                
                
            }else{
                $array['response'] = Response::getArrayResponseOK(\Lang::get( 'api.updateSuccess' ));
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

        $user = $request->user;
        $id   = $request->id;

        try{
            $model = SubjectNoteModel::getForId($id);

            if(empty($model)){
                return Response::getArrayResponseOK(\Lang::get( 'api.objectEmpty' ));
            }

            $delete = SubjectNoteModel::deleteObject($id);

            return isset($delete) ?
                Response::getArrayResponseOK(\Lang::get( 'api.deleteSuccess' )) : 
                Response::getArrayResponseKO(\Lang::get( 'api.error' ));
               

        }catch (\Exception $e){
            return Response::responseKO(__CLASS__, __FUNCTION__, $e);            
                    
        }catch (\PDOException $e){
            return Response::responseKO(__CLASS__, __FUNCTION__, $e);
        }
    }
}
