<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\SubjectNoteModel;
use App\Helpers\LogHelper;

class SubjectNoteController extends Controller
{
    public function get(Request $request){

        $id = $request->id;

        try{
            $data = SubjectNoteModel::getForId($id);

            if (empty($data) ){
                $msg = \Lang::get( 'api.objectEmpty' );

                return response()->json(compact('msg'), 406);                
				
			}else{
                $msg = \Lang::get( 'api.getSuccess' );

                return response()->json(compact('msg', 'data'), 200); 
			}

        }catch (\Exception $e){
            LogHelper::printError(__CLASS__, __FUNCTION__, $e );

            return response()->json(['error' => \Lang::get( 'api.error' )], $e->getStatusCode());     
					
		}catch (\PDOException $e){
            LogHelper::printError(__CLASS__, __FUNCTION__, $e );

            return response()->json(['error' => \Lang::get( 'api.error' )], $e->getStatusCode());
		} 
    }

    public function list(Request $request){

        $user       = $request->user;
        $id_subject = $request->id_subject;

        try{
            $data = SubjectNoteModel::list($id_subject);

            if (empty($data) || count($data) == 0 ){
                $msg = \Lang::get( 'api.dataEmpty' );

                return response()->json(compact('msg'), 200);           
                
            }else{
                $msg = \Lang::get( 'api.getSuccess' );

                return response()->json(compact('msg', 'data'), 200); 
            }

        }catch (\Exception $e){
            LogHelper::printError(__CLASS__, __FUNCTION__, $e );

            return response()->json(['error' => \Lang::get( 'api.error' )], $e->getStatusCode());       
                    
        }catch (\PDOException $e){
            LogHelper::printError(__CLASS__, __FUNCTION__, $e );

            return response()->json(['error' => \Lang::get( 'api.error' )], $e->getStatusCode());    
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
            $msg = $validator->errors();

            return response()->json(compact('msg'), 406); 
        }

        $user        = $request->user;
        $name        = $request->name;
        $note        = $request->note;
        $id_subject  = $request->id_subject;

        try{
            $data = SubjectNoteModel::createObject($id_subject, $name, $note);

            if (empty($data) ){
                $msg = \Lang::get( 'api.dataExist' );

                return response()->json(compact('msg'), 406);          
                
            }else{
                $msg = \Lang::get( 'api.createSuccess' );

                return response()->json(compact('msg', 'data'), 201); 
            }

        }catch (\Exception $e){
            LogHelper::printError(__CLASS__, __FUNCTION__, $e );

            return response()->json(['error' => \Lang::get( 'api.error' )], $e->getStatusCode());      
                    
        }catch (\PDOException $e){
            LogHelper::printError(__CLASS__, __FUNCTION__, $e );

            return response()->json(['error' => \Lang::get( 'api.error' )], $e->getStatusCode());
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
            $msg = $validator->errors();

            return response()->json(compact('msg'), 406); 
        }

        $user        = $request->user;
        $id          = $request->id;
        $name        = $request->name;
        $note        = $request->note;

        try{
            $data = SubjectNoteModel::getForId($id);

            if(empty($data)){
                $msg = \Lang::get( 'api.dataEmpty' );

                return response()->json(compact('msg'), 406);
            }

            $data = SubjectNoteModel::updateObject($id, $name, $note);

            if (empty($data) ){
                $msg = \Lang::get( 'api.error' );

                return response()->json(compact('msg'), 500);               
                
            }else{
                $msg = \Lang::get( 'api.updateSuccess' );

                return response()->json(compact('msg', 'data'), 200); 
            }

        }catch (\Exception $e){    
            LogHelper::printError(__CLASS__, __FUNCTION__, $e );

            return response()->json(['error' => \Lang::get( 'api.error' )], $e->getStatusCode());     
                    
        }catch (\PDOException $e){
            LogHelper::printError(__CLASS__, __FUNCTION__, $e );

            return response()->json(['error' => \Lang::get( 'api.error' )], $e->getStatusCode());
        }
    }

    /**Delete functions ********************************/
    public function delete(Request $request){

        $user = $request->user;
        $id   = $request->id;

        try{
            $model = SubjectNoteModel::getForId($id);

            if(empty($model)){
                $msg = \Lang::get( 'api.dataEmpty' );

                return response()->json(compact('msg'), 406);
            }

            $delete = SubjectNoteModel::deleteObject($id);

            if(isset($delete)){
                $msg = \Lang::get( 'api.deleteSuccess' );

                return response()->json(compact('msg', 'data'), 200); 

            }else{
                $msg = \Lang::get( 'api.error' );

                return response()->json(compact('msg'), $e->getStatusCode()); 
            }  

        }catch (\Exception $e){ 
            LogHelper::printError(__CLASS__, __FUNCTION__, $e );

            return response()->json(['error' => \Lang::get( 'api.error' )], $e->getStatusCode());      
                    
        }catch (\PDOException $e){
            LogHelper::printError(__CLASS__, __FUNCTION__, $e );

            return response()->json(['error' => \Lang::get( 'api.error' )], $e->getStatusCode());
        }
    }
}
