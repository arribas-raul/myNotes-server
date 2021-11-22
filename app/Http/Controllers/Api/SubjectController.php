<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\SubjectModel;
use App\Models\SubjectNoteModel;
use App\Helpers\LogHelper;

class SubjectController extends Controller
{
    /**Select functions ********************************/
    public function get(Request $request){

        $id = $request->id;

        try{
            $data = SubjectModel::getForId($id);

            if (empty($data) ){
                return response()->json(['error' => \Lang::get( 'api.objectEmpty' )], 406);
				
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

        $user = $request->user;

        try{
            $data = SubjectModel::list($user->id);

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
                'name' => 'required|string|min:2|max:100'
            ]);

        if($validator->fails()){
            $msg = $validator->errors();

            return response()->json(compact('msg'), 406); 
        }

        $user        = $request->user;
        $name        = $request->name;
        $description = $request->description;

        try{
            $data = SubjectModel::getForName($user->id, $name);

            if(isset($model)){
                $msg = \Lang::get( 'api.dataExist' );

                return response()->json(compact('msg'), 406);    
            }

            $data = SubjectModel::createObject($user->id, $name, $description);

            if (empty($data) ){
                $msg = \Lang::get( 'api.error' );

                return response()->json(compact('msg'), 500);             
                
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
                'name' => 'required|string|min:2|max:100'
            ]);

        if($validator->fails()){
            $msg = $validator->errors();

            return response()->json(compact('msg'), 406); 
        }

        $user        = $request->user;
        $id          = $request->id;
        $name        = $request->name;
        $description = $request->description;

        try{
            $data = SubjectModel::getForId($id);

            if(empty($data)){
                $msg = \Lang::get( 'api.dataEmpty' );

                return response()->json(compact('msg'), 406);
            }

            $data = SubjectModel::updateObject($id, $name, $description);

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
            $model = SubjectModel::getForId($id);

            if(empty($model)){
                $msg = \Lang::get( 'api.dataEmpty' );

                return response()->json(compact('msg'), 406);
            }

            $transaction = DB::transaction(function() use ( $user, $id )
            {
                $result = SubjectNoteModel::deleteForIdSubject($id);
                $result += SubjectModel::deleteObject($id);

                return $result;
            });

            if($transaction > 0){
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
