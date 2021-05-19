<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use JWTAuth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CsvDataImport;
use App\Validators\ProductValidator;
use Prettus\Validator\Exceptions\ValidatorException;
use Dingo;

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
            $products = app(Product::class)
                            ->select('product_name','price','sku_number','description')
                            ->where('users_id',$user['users_id'])
                            ->paginate(10);
            return $products;
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
            if ($request->hasFile('file')) 
            {
                $data = $request->all();
                $data['users_id'] = $user['users_id'];
                $file_extension = $data['file']->clientExtension();

                if(!isset($file_extension))
                {
                    $file_extension = $data['file']->guessExtension();
                }
                if($request->file('file')->getSize() > 1000000)
                {
                    abort(412,'Expected file size less than 1 MB');
                }
                if(!in_array($file_extension, ['csv'])) 
                {
                    throw new \Exception('File extension not accepted.');
                }
                $user_array = Excel::toArray(new CsvDataImport, $request->file('file'));
                ini_set('memory_limit', '-1');
                set_time_limit(300);
                $keys = array();
                if( isset($user_array[0]) ) {
                    if( isset($user_array[0][0]) ){
                        $keys = $user_array[0][0];
                    }
                }
                if(isset($keys) && $keys[0] == 'Product Name' && $keys[1] == 'Price' && $keys[2] == 'SKU Number' && $keys[3] == 'Description')
                {
                    unset($user_array[0][0]);
                    if(isset($user_array[0]) && !empty($user_array[0])){
                        foreach( $user_array[0] as $user_data ){
                            $data_for_validate = [
                                'product_name'  => $user_data[0],
                                'price'         => $user_data[1],
                                'sku_number'    => $user_data[2],
                                'description'   => $user_data[3]
                            ];
                            $validation_rule = 'create-rule';
                            app(ProductValidator::class)->with( $data_for_validate )->passesOrFail($validation_rule);
                            $data_for_validate['users_id'] = $data['users_id'];
                            app(Product::class)->create($data_for_validate);
                        }
                        return ['data' => ['message' => 'successfully uploaded product details']];
                    }
                    else
                        abort(412,'Upload document with product details.');
                }
                else
                {
                    abort(412,'Please Upload a valid CSV file');
                }
            }
        }
        catch (ValidatorException $e) {
            throw new Dingo\Api\Exception\StoreResourceFailedException('Unable to create product ', $e->getMessageBag());
        }
        catch (\Exception $e)
        {
            throw $e;
        }   
    }
}
