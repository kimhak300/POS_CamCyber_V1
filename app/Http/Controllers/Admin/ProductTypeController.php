<?php
namespace App\Http\Controllers\Admin;

// ============================================================================>> Core Library
use Illuminate\Http\Request; // For Getting requested Data from Client
use Illuminate\Http\Response; // For Responsing data back to Client

// ============================================================================>> Third Library
use Tymon\JWTAuth\Facades\JWTAuth; // Get Current Logged User

// ============================================================================>> Core Library
// Controller
use App\Http\Controllers\MainController;
class ProductTypeController extends MainController
{
    public function getData(){
        
        $data = Type::select("id","name") -> get();
        return $data;

    }
    public function create(Request $req){
        
        // ===>> Check Validation
        $this->validate($req, [
            'name'      => 'required'
        ]);

        // ===>> Save Data to DB
        $data = new Type;
        $data->name = $req->name;
        $data->save();
    
        // ===>> Response back to Client
        return response()->json([
            'data'         => $data,
            'message'       => 'Data has been successfully created.'
        ], Response::HTTP_OK);
    
    
    
    }

    public function update(Request $req, $id){
        // ===>> Check Validation
        $this->validate($req, [
            'name'      => 'required'
        ]);

        // ===>> Find Record In Db requested from client
        $data = Type::find($id);

        // ===>> Check If Data is correct
        if ($data){// Yes

            // ===>> Save Data to DB
            $data->name = $req->name;
            $data->save();

            // ===>> Success Response back to client
            return response()->json([
                'data'         => $data,
                'message'       => 'Data has been successfully updated.'
            ], Response::HTTP_OK);

         } else{ // No
            
            // ===>> Failed Response Back to Client
            return response()->json([
                'status'    => 'បរាជ័យ',
                'message'   => 'ទិន្នន័យដែលផ្តល់ឲ្យមិនត្រូវទេ',
            ], Response::HTTP_BAD_REQUEST);

      }
        
    }

    public function delete(Request $req, $id){
        // ===>> Check Validation
        $this->validate($req, [
            'name'      => 'required'
        ]);

        // ===>> Find eecord in DB requested from client
        $data = Type::find($id);

        // ===>> Check if data is correct
        if ($data){// Yes

            // ===>> Delete data from DB
            $data->name = $req->name;
            $data->delete();

            // ===>> Success response back to client
            return response()->json([
                'data'         => $data,
                'message'       => 'Data has been successfully deleted.'
            ], Response::HTTP_OK);

         } else{ // No
            
            // ===>> Failed Response Back to Client
            return response()->json([
                'status'    => 'បរាជ័យ',
                'message'   => 'ទិន្នន័យដែលផ្តល់ឲ្យមិនត្រូវទេ',
            ], Response::HTTP_BAD_REQUEST);

      }
        
    }

}