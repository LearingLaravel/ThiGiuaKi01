<!doctype html>
<html lang="en">
  <head>
    <title>Admin</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>
  <div class="container">
    <div class=" table-responsive container">
        <h2 class="text-center mt-4">Danh sách sản phẩm</h2>
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ url('products/create') }}" class="btn btn-primary">Thêm sản phẩm</a>
          
            <form class="form-inline" method="get" action="{{ url('/search') }}">
              <input name='search' value="{{isset($search) ? $search : ''}}" class="form-control mr-2" type="search" placeholder="Tìm kiếm" aria-label="Tìm kiếm">
              <button class="btn btn-outline-success" type="submit">Tìm kiếm</button>
            </form>

          </div>

        <table class="table mt-4 ">
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Tên sản phẩm</th>
                <th>Gía cũ</th>
                <th>Gía ưu đãi</th>
                <th>Mô tả</th>
                <th>Mô tả chi tiết</th>
                <th>Nguồn gốc</th>
                <th>Tiêu chuẩn</th>
                <th>Phân loại</th>
                <th>Hành động</th>
            </tr>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td ><img src="{{ asset('images/'.$product->image) }}" alt="Brand Image" class="img-thumbnail" style=""></td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->old_price}}</td>
                        <td>{{ $product->new_price}}</td>
                        <td>{{ $product->description }}</td>
                        <td>{{ $product->detail_description }}</td>
                        <td>{{ $product->origin }}</td>
                        <td>{{ $product->standard }}</td>  
                        <td>{{ $product->category->category_name }}</td>            
                        <td>
                            <div aria-label="Car Actions">
                            <a href="{{url('products/'.$product->id.'/edit')}}" class="btn btn-warning">Sửa</a>
                            <a href="{{url('products/'.$product->id.'/delete')}}" onclick="return confirm ('Are you sure?')" class="btn btn-danger">Xóa</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>