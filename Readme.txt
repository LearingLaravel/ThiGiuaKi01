1. Xây dựng CSDL
1.1. Tạo Migration cho Bảng Phân Loại (Categories):
php artisan make:migration create_categories_table
- mở migration và chỉnh sửa nó để tạo bảng categories với các trường:
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->timestamps();
});
1.2. Tạo Migration cho Bảng Sản Phẩm (Products):
php artisan make:migration create_products_table
-Mở Product migration 
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->float('old_price');
    $table->float('new_price');
    $table->text('description');
    $table->text('detail_description');
    $table->string('origin');
    $table->string('standard');
    $table->string('image');
    $table->unsignedBigInteger('category_id');
    $table->foreign('category_id')->references('id')->on('categories');
    $table->timestamps();
});

1.3. Chạy migration: php artisan migrate


------------------------
2. Xây dựng dữ liệu mẫu
2.1. Tạo dữ liệu mẫu cho bảng Categories
2.2.1. Chạy Category Seeder
php artisan make:seeder CategorySeeder
2.2.2. Chạy Category Factory
php artisan make:factory CategoryFactory
2.2.3. Định nghĩa CategoryFactory trong file Database/Factory/CategoryFactory
use App\Models\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});
2.2.4. Quay lại file CategorySeeder để gọi lại Factory để sinh ra dữ liệu mẫu
public function run(){
    Category::factory ->count(10)->create();
}
2.2.5. Chạy seeder
php artisan db:seed
2.2.6. Kiểm tra Database



2.3. Tạo dữ liệu mẫu cho bảng Product
2.3.1. Chạy Product Seeder
php artisan make:seeder ProductSeeder
2.3.2. Chạy Category Factory
php artisan make:factory ProductFactory
2.3.3. Định nghĩa ProductFactory trong file Database/Factory/CategoryFactory
use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'old_price' => $faker->randomFloat(2, 1, 100),
        'new_price' => $faker->randomFloat(2, 1, 100),
        'description' => $faker->sentence,
        'detail_description' => $faker->paragraph,
        'origin' => $faker->country,
        'standard' => $faker->word,
        'image' => $faker->imageUrl(),
        'category_id' => function () {
            return factory(App\Models\Category::class)->create()->id;
        },
    ];
});

2.3.4. Quay lại file ProductSeeder để gọi lại Factory để sinh ra dữ liệu mẫu
public function run(){
    Product::factory ->count(10)->create();
}
2.3.5. Chạy seeder
php artisan db:seed
2.3.6. Kiểm tra Database


---------------------------





