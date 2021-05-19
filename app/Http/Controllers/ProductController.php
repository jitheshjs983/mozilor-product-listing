<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use JWTAuth;
class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard(Request $request)
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
            }
            } catch (\Exception $e){
                throw $e;
            }
            $products = app(Product::class)->where('users_id',$user['users_id'])->get();
            return response()->json(compact('products'));
    }
    public function upload_document(Request $request)
    {
        try 
        {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
            }
        } 
        catch (\Exception $e)
        {
            throw $e;
        }
        try
        {
            if ($request->hasFile('file')) {
                $data = $request->all();
                $data['users_id'] = $user['users_id'];
                $file_extension = $data['file']->clientExtension();

                if(!isset($file_extension))
                {
                    $file_extension = $data['file']->guessExtension();
                }

                if(!in_array($file_extension, ['csv'])) 
                {
                    throw new \Exception('File extension not accepted.');
                }
                $customerArr = $this->csvToArray($data['file']);
                if(sizeof($customerArr))
                {
                    for ($i = 0; $i < count($customerArr); $i ++)
                    {
                        dd($customerArr[$i]);
                        User::firstOrCreate($customerArr[$i]);
                    }
                }
                else
                {
                    abort(412,'Empty CSV file');
                }
            }
        }
        catch (\Exception $e)
        {
            throw $e;
        }   
    }
    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }
}
