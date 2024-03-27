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
use Illuminate\Http\Response;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::all();
        

        // $query = Product::query();
        // $category = $request->category_filter;

        // switch ($category) {
        //     case "Hoa quả":
        //         $query->where('category_name', 'Hoa quả');
        //         break;
        //     case "Thực phẩm khô":
        //         $query->where('category_name', 'Thực phẩm khô');
        //         break;
        //     case "Reu hữu cơ":
        //         $query->where('category_name', 'Rau hữu cơ');
        //         break;
        // }
        // $products = $query->get();

        return view('admin/product-list', compact('products'));
    }

    public function create()
    {
        $categories = Category::pluck('category_name', 'id');
        return view('admin/product-create', compact('categories'));
    }

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
        return view('admin/product-update', compact('product', 'categories'));
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


    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if (File::exists($product->brand)) {
            File::delete($product->brand);
        }

        $product->delete();
        return redirect()->back()->with('status', 'Thêm sản phẩm thành công');
    }



    public function search(Request $request)
    {
        $search = $request->search;
        $products = Product::where(function ($query) use ($search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('old_price', 'like', "%$search%")
                ->orWhere('new_price', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%")
                ->orWhere('detail_description', 'like', "%$search%")
                ->orWhere('origin', 'like', "%$search%")
                ->orWhere('standard', 'like', "%$search%");
        })
            ->orWhereHas('category', function ($query) use ($search) {
                $query->where('category_name', 'like', "%$search");
            })
            ->get();
        return view('admin/product-list', compact('products', 'search'));
    }
}