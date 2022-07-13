<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $foods = Food::all();
        return view('showFood', ['food' => $foods]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('food-create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = '';

        $this->validate($request, [
            'name' => 'required',
            'price' => 'required|numeric',
            'old_price' => 'required|numeric',
            'description' => 'required',
            'categories_id' => 'required',
            'image' => 'required|mimes:jpg,png,gif,jpeg|max: 2048',
        ], [
            'name.required' => 'Please input name',
            'description.required' => 'Please input description',
            'price.required' => 'Please input price',
            'old_price.required' => 'Please input old price',
            'price.numeric' => 'Please input number',
            'old_price.numeric' => 'Please input number',
            'categories_id.required' => 'Please select category',
            'price.required' => 'Please input old price',
            'image.required' => 'Please input image',
            'image.mimes' => 'Please input file image',
            'image.max' => 'Please choose image file has 2Mb'
        ]);
        $file = $request->file(('image'));
        $name = time() . '_' . $file->getClientOriginalName();
        $destinationPath = public_path('images');
        $file->move($destinationPath, $name);

        $food = new Food();
        $food->name = $request->name;
        $food->price = $request->price;
        $food->old_price = $request->old_price;
        $food->description = $request->description;
        $food->categories_id = $request->categories_id;
        $food->image = $name;
        $food->save();

        return redirect()->route('food.index')->with('success', 'Bạn đã thêm thành công');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $food = Food::find($id);
        return view('detailFood', ['food' => $food]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function all() {
        $foods = Food::join('categories', 'foods.categories_id', 'categories.id')->select('foods.*', "categories.name as category_name")->get();
        return response()->json(["success" => true, "message" => "car record created successfully", "data" => $foods]);
    }

    public function searchFood(Request $res) {
        $food_rearch = DB::table('foods')->where('name', 'like','%'.$res->name.'%')->get();
        if(count($food_rearch) > 0){
            return response()->json(["success" => true, "message" => "car record created successfully", "data" => $food_rearch]);
        }else{
            return ["Error" => "Not found"];
        }
    }
}
