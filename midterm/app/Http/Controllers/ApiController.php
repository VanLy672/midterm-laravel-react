<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $food = Food::all();
        if (count($food) > 0) {
            return response()->json(["status" =>"200", "success" => true, "count" => count($food), "data" => $food]);
        } else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! no record found"]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        {
            $validation = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'price' => 'required',
                    'old_price' => 'required',
                    'description' => 'required',
                    'images' => 'mimes:jpeg,jpg,png,gif|max:10000'
                ]
            );

            if ($validation->fails()) {
                $response = array('status' => 'error', 'errors' => $validation->errors()->toArray());
                return response()->json($response);
            }
            //nếu dùng $this->validate() thì lấy về lỗi: $errors = $validate->errors();

            //kiểm tra file tồn tại
            $name = '';

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $name = time() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('image'); //project\public\car, //public_path(): trả về đường dẫn tới thư mục public
                $file->move($destinationPath, $name); //lưu hình ảnh vào thư mục public/images/cars
            }

            $food = new Food();
            $food->name = $request->name;
            $food->price = $request->price;
            $food->old_price = $request->old_price;
            $food->decription = $request->decription;
            // $car -> name = $request->name;
            $food->images = $name;
            $food->save();
            if ($food) {
                return response()->json(["status" => "200", "success" => true, "message" => "food record created successfully", "data" => $food]);
            } else {
                return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! failed to create."]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        {
            $validation = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'price' => 'required',
                    'old_price' => 'required',
                    'description' => 'required',
                    'images' => 'mimes:jpeg,jpg,png,gif|max:10000'
                ]
            );

            if ($validation->fails()) {
                $response = array('status' => 'error', 'errors' => $validation->errors()->toArray());
                return response()->json($response);
            }
            //nếu dùng $this->validate() thì lấy về lỗi: $errors = $validate->errors();

            //kiểm tra file tồn tại
            $name = '';

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $name = time() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('image'); //project\public\car, //public_path(): trả về đường dẫn tới thư mục public
                $file->move($destinationPath, $name); //lưu hình ảnh vào thư mục public/images/cars
            }

            $food = Food::find($id);
            $food->decription = $request->decription;
            $food->model = $request->model;
            $food->produced_on = $request->produced_on;
            // $car -> name = $request->name;
            $food->images = $name;
            $food->save();
            if ($food) {
                return response()->json(["status" => "200", "success" => true, "message" => "car record created successfully", "data" => $food]);
            } else {
                return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! failed to create."]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Food::where('id', $id)->delete();
        return redirect("/food")->with('status', 'Delete success');
    }
}
