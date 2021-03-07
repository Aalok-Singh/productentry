<?php

namespace App\Http\Controllers;

use App\Exports\ProductExport;
use App\Exports\ProductsExport;
use App\Imports\ProductImport;
use App\Models\products;
use Exception;
use Illuminate\Http\Request;
use DataTables;
use App\Imports\UsersImport;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;


class ProductController extends Controller
{
    public function index()
    {
        return view('productlist');
    }

    public function productCreate()
    {
        return view('product');
    }
  
    public function productData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>"required",
            'title' => "required",
            'description' => 'required',
            'price' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['Error_Message' => $validator->messages()]);
            return response()->json(array(
                'success' => false,
                'errors' => $validator->messages()->toArray()
        
            ), 400); 
        }
        try{
        if($request->ajax()){
            products::create($request->all());
        }
        return response()->json(["Success" => "Product stored"]);
        }catch(\Exception  $exception){
        return response()->json(["Error" => "Product not stored"]);
        }
       
    }

    public function getProducts(Request $request)
    {
        if ($request->ajax()) {
            $data = products::latest()->get();
       
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<a data-id='.$row->id.' data-name='.$row->name.' data-title='.$row->title.' data-description='.$row->description.' data-price='.$row->price.' class="edit btn btn-success btn-sm">Edit</a> <a data-id='.$row->id.' class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    public function productUpdate(Request $request)
    {
        
        try{
            if($request->ajax()){
                products::where('id', '=', $request->id)->update([
                    'name' => isset($request->name) ? $request->name : 'default value',
                    'title' => isset($request->title) ? $request->title : 'default value',
                    'description' => isset($request->description) ? $request->description : 'default value',
                    'price' => isset($request->price) ? $request->price : 'default value',
                ]);
            }
            return response()->json(["Success" => "Product updated"]);
            }catch(\Exception  $exception){
            return response()->json(["Error" => "Product not updated"]);
            }
    }
    public function productDelete(Request $request)
    {
        try{
            if($request->ajax()){
                products::where('id', '=', $request->id)->delete();
            }
            return response()->json(["Success" => "Product deleted"]);
            }catch(\Exception  $exception){
            return response()->json(["Error" => "Product not deleted"]);
            }
    }

    public function productImport() 
    {
        $validator = Validator::make(
            [
                'file'      => request()->file('file'),
                'extension' => strtolower(request()->file('file')->getClientOriginalExtension()),
            ],
            [
                'file'          => 'required',
                'extension'      => 'required|in:csv',
            ]
        );
        if ($validator->fails()) {
            return response()->json(["Error" => $validator->messages()]);
        }
        Excel::import(new ProductImport,request()->file('file'));
        
        return response()->json(["Success" => "Product imported"]);
    }

    public function productExport() 
    {
        return Excel::download(new ProductExport(), 'products.xlsx');
    //    return back();
    }
}
