<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
class ProductController extends Controller
{
    public function index()
    {
        //  $cars = DB::table('cars')->join('mfs', 'cars.mf_id', '=', 'mfs.id')->get();
        // dd($cars);
        $products = Product::all();
        return view('product-list', compact('products'));
    }

    public function create()
    {
        $categories = Category::pluck('category_name', 'id');
        return view('product-create', compact('categories'));
    }

//     public function store(Request $request)
// {
//     $validateData = $request->validate([
//         'name' => 'required|string',
//         'old_price' => 'required|numeric',
//         'new_price' => 'required|numeric',
//         'description' => 'required|string',
//         'detail_description' => 'required|string',
//         'origin' => 'required|string',
//         'standard' => 'required|string',
//         'image' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:2048',
//         'category_id' => 'required',
//     ]);

//     if ($request->hasFile('image')) {
//         $image = $request->file('image');
//         $imageName = time() . '_' . $image->getClientOriginalName();
//         $image->move(public_path('images'), $imageName);

//         $product = new Product();
//         $product->name = $validateData['name'];
//         $product->old_price = $validateData['old_price'];
//         $product->new_price = $validateData['new_price'];
//         $product->description = $validateData['description'];
//         $product->detail_description = $validateData['detail_description'];
//         $product->origin = $validateData['origin'];
//         $product->standard = $validateData['standard'];
//         $product->image = $imageName;
//         $product->category_id = $validateData['category_id'];

//         $product->save();

//         $msg = "success";
//         Session::flash('message', $msg);

//         return redirect()->route('products.index')->with('success', 'Product created successfully.');
//     }

//     return redirect()->back()->withErrors($validateData)->withInput();
// }


public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string',
            'old_price' => 'required|numeric',
            'new_price' => 'required|numeric',
            'description' => 'required|string',
            'detail_description' => 'required|string',
            'origin' => 'required|string',
            'standard' => 'required|string',
            'image' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required',
        ]);

        // if ($validator->fails()) {
        //     return redirect('products/create')
        //         ->withErrors($validator)
        //         ->withInput();
        // }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $path = 'images/';
            $filename = time() . '.' . $extension;
            $file->move($path, $filename);
        } 
        $product = new Product();
        $product->name = $request->name;
        $product->old_price = $request->old_price;
        $product->new_price = $request->new_price;
        $product->description = $request->description;
        $product->detail_description = $request->detail_description;
        $product->origin = $request->origin;
        $product->standard = $request->standard;
        $product->image = $filename;
        $product->category_id = $request->category_id;
        $product->save();
        
        return redirect('products/create')->with('status', 'Thêm sản phẩm thành công.');
    }
    
    public function edit(int $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::pluck('category_name', 'id');
        //dd($product);
        return view('product-update', compact('product', 'categories'));
    }
    
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);

        $validateData = Validator::make(
            $request->all(),
            [
                'old_price' => 'required',
                'new_price' => 'required',
                'description' => 'required',
                'detail_description' => 'required',
                'origin' => 'required',
                'standard' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            ],
            [
                'name.required' => 'Trường tên sản phẩm là bắt buộc.',
                'old_price.required' => 'Trường giá cũ là bắt buộc.',
                'new_price.required' => 'Trường giá mới là bắt buộc.',
                'description.required' => 'Trường mô tả là bắt buộc.',
                'detail_description.required' => 'Trường mô tả chi tiết là bắt buộc.',
                'origin.required' => 'Trường xuất xứ là bắt buộc.',
                'standard.required' => 'Trường tiêu chuẩn là bắt buộc.',
                'image.required' => 'Trường ảnh là bắt buộc.',
                'image.image' => 'Trường ảnh phải là một tệp hình ảnh.',
                'image.mimes' => 'Trường ảnh phải có định dạng jpeg, png, jpg, gif, hoặc svg.'
            ]
        );

        if ($validateData->fails()) {
            return redirect()->back()->withErrors($validateData)->withInput();
        }

        if ($request->hasFile('image')) {
            $image_path = public_path("images/{$product->image}");

            if (File::exists($image_path)) {
                File::delete($image_path);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
            $product->image = $imageName;
        }

        $product->name = $request->name;
        $product->old_price = $request->old_price;
        $product->new_price = $request->new_price;
        $product->description = $request->description;
        $product->detail_description = $request->detail_description;
        $product->origin = $request->origin;
        $product->standard = $request->standard;
        $product->category_id = $request->category_id;
        $product->save();
        
        return redirect()->back()->with('status', 'Cập nhật sản phẩm thành công.');
    }
    


    public function destroy($id){
        $product = Product::findOrFail($id);

        if (File::exists($product->brand)) {
            File::delete($product->brand);
        }

        $product->delete();
        return redirect()->back()->with('status', 'Car deleted successfully');
    }

    // public function update(Request $request, int $id)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'old_price' => 'required',
    //         'new_price' => 'required',
    //         'description' => 'required',
    //         'detail_description' => 'required',
    //         'origin' => 'required',
    //         'standard' => 'required',
    //         'image' => 'required',
    //     ]);

    //     $product = Product::findOrFail($id);
        
    //     if ($request->has('image')) {
    //         $file = $request->file('image');
    //         $extension = $file->getClientOriginalName();
    //         $path = 'images/';
    //         $filename = time() . '.' . $extension;
    //         $file->move($path, $filename);

    //         if (File::exists($product->image)) {
    //             File::delete($product->image);
    //         }
    //     }
    //     $product->update([
    //         'name' => $request->name,
    //         'old_price' => $request->old_price,
    //         'new_price' => $request->new_price,
    //         'description' => $request->description,
    //         'detail_description' => $request->detail_description,
    //         'origin' => $request->origin,
    //         'standard' => $request->standard,
    //         'image' => $filename,
    //         'category_id' => $request->category_id,
            
    //     ]);

    //     $product->save();

    //     return redirect()->back()->with('status', 'Product updated successfully');
    // }
    



    // public function delete(string $id)
    // {
    //     if (!empty($id)) {
    //         $car = Car::find($id);


    //         if ($car) {
    //             $image_path = public_path("images/{$car->image}");

    //             if (File::exists($image_path)) {
    //                 File::delete($image_path);
    //             }

    //             $car->delete();
    //             return redirect()->route('cars.index')->with('success', 'Car deleted successfully');
    //         } else {
    //             return redirect()->back()->with('error', 'Car not found');
    //         }
    //     } else {
    //         return redirect()->back()->with('error', 'Invalid car ID');
    //     }
    // }

    // public function show(string $id)
    // {
    //     $car = Car::find($id);
    //     $mfsList = Mf::all();

    //     return view('car-detail', compact('car', 'mfsList'));
    // }
    // public function getEdit(string $id)
    // {
    //     $car = Car::find($id);
    //     $mfsList = Mf::all();
    //     return view('car-update', compact('car', 'mfsList'));
    // }

    // public function updateCar(Request $request, string $id)
    // {
    //     $car = Car::find($id);
    //     $msg = 'failed';

    //     if (!empty($car)) {
    //         $validateData = $request->validate([
    //             'model' => 'required|min:5',
    //             'product_on' => 'required|date',
    //             'description' => 'required',
    //             'image' => 'file|image|mimes:jpeg,png,jpg,gif|max:2048',
    //             'mf_id' => 'required',
    //         ], [
    //             'model.required' => 'Trường model là bắt buộc',
    //             'model.min' => 'Trường model có độ dài phải lớn hơn',
    //             'product_on.required' => 'Trường thời gian là bắt buộc',
    //             'product_on.date' => 'Trường thời gian là bắt buộc',
    //             'description.required' => 'Trường mô tả là bắt buộc',
    //             'image.image' => 'Trường này phải là ảnh',
    //             'image.mimes' => 'Trường Kiểu ảnh là kiểu ảnh',
    //             'mf_id.required' => 'Trường hạng là bắt buộc',
    //         ]);

    //         if ($request->hasFile('image')) {
    //             $image_path = public_path("images/{$car->image}");

    //             if (File::exists($image_path)) {
    //                 File::delete($image_path);
    //             }

    //             $image = $request->file('image');
    //             $imageName = time() . '_' . $image->getClientOriginalName();
    //             $image->move(public_path('images'), $imageName);
    //             $car->image = $imageName;
    //         }

    //         $car->model = $validateData['model'];
    //         $car->product_on = $validateData['product_on'];
    //         $car->description = $validateData['description'];
    //         $car->mf_id = $validateData['mf_id'];
    //         $car->save();

    //         if ($car) {
    //             $msg = "success";
    //         }

    //         Session::flash('message', $msg);
    //         return redirect()->back();
    //     }

    //     Session::flash('message', $msg);
    //     return redirect()->back();
    // }

}