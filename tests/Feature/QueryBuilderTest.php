<?php

namespace Tests\Feature;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class QueryBuilderTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        DB::delete('delete from products');
        DB::delete('delete from categories');
    }

    public function testInsert(): void
    {
        DB::table('categories')->insert([
            "id" => "GADGET",
            "name" => "Gadget",
            "description" => "Gadget Category",
            "created_at" => "2020-10-10 10:10:10"
        ]);
        DB::table('categories')->insert([
            "id" => "FOOD",
            "name" => "Food",
            "description" => "Food Category",
            "created_at" => "2020-10-10 10:10:10"
        ]);
        $result= DB::select('select count(id) total from categories ');
        self::assertEquals(2, $result[0]->total);
    }
    public function testSelect(){
        $this->testInsert();
        $collection = DB::table('categories')->select(['id','name'])->get();
        self::assertNotNull($collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }
    public function insertCategories(){
        DB::table('categories')->insert([
            "id" => "SMARTPHONE",
            "name" => "Smartphone",
            "description" => "Smartphone Category",
            "created_at" => "2020-10-10 10:10:10"
        ]);
        DB::table('categories')->insert([
            "id" => "FOOD",
            "name" => "Food",
            "description" => "Food Category",
            "created_at" => "2020-10-10 10:10:10"
        ]);
        DB::table('categories')->insert([
            "id" => "LAPTOP",
            "name" => "laptop",
            "description" => "laptop Category",
            "created_at" => "2020-10-10 10:10:10"
        ]);
        DB::table('categories')->insert([
            "id" => "FASHION",
            "name" => "Fashion",
            "description" => "Fashion Category",
            "created_at" => "2020-10-10 10:10:10"
        ]);
    }
    public function testWhere(){
        $this->insertCategories();
        $collection = DB::table('categories')->Where(function (Builder $builder){
            $builder->where('id','=','SMARTPHONE');
            $builder->orWhere('id','=','FOOD');
        })->get();
        self::assertCount(2,$collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }
    public function testWhereBetween(){
        $this->insertCategories();
        $collection = DB::table('categories')->whereBetween('created_at',[
            '2020-9-10 10:10:10',
            '2020-11-10 10:10:10'
        ])->get();
        self::assertCount(4,$collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });

    }
    public function testWhereInMethod(){
        $this->insertCategories();
        $collection = DB::table('categories')->whereIn('id',[
            'SMARTPHONE','FOOD'
        ])->get();
        self::assertCount(2,$collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }
    public function testWhereNotNull(){
        $this->insertCategories();
        $collection = DB::table('categories')->
        whereNotNull('description')->get();
        self::assertCount(4,$collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }
    public function testWhereDate(){
        $this->insertCategories();
        $collection = DB::table('categories')->
        whereDate('created_at','2020-10-10' )->get();
        self::assertCount(4,$collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }
    public function testUpdate(){
        $this->insertCategories();
         DB::table('categories')->where('id','=','SMARTPHONE')->update([
            'name' => 'Handphone'
        ]);
        $collection = DB::table('categories')->where('name','=','Handphone')->get();
        self::assertCount(1,$collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }
    public function testUpdateOrInsert(){
        $this->insertCategories();
        DB::table('categories')->updateOrInsert([
            'id' => 'SMARTPHONE'
        ],[
            'name' => 'Handphone'
        ]);
        $collection = DB::table('categories')->where('name','=','Handphone')->get();
        self::assertCount(1,$collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }
    public function testIncrement(){
        DB::table('counter')->where('id', '=', 'sample')->increment('counter', 1);

        $collection = DB::table('counter')->where('id','=','sample')->get();
        self::assertCount(1,$collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }
    public function testDelete(){
        $this->insertCategories();
        DB::table('categories')->where('id','=','SMARTPHONE')->delete();
        $collection = DB::table('categories')->where('id','=','SMARTPHONE')->get();
        self::assertCount(0,$collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }
    public function insertProducts(){
        $this->insertCategories();
        DB::table('products')->insert([
           'id' => '1',
           'name' => 'Samsung Galaxy S20',
           'description' => 'Samsung Galaxy S20',
            'category_id' => 'SMARTPHONE',
            'price' => 14000000,
        ]);
        DB::table('products')->insert([
            'id' => '2',
            'name' => 'Samsung Galaxy S10',
            'description' => 'Samsung Galaxy S10',
            'category_id' => 'SMARTPHONE',
            'price' => 12000000,
        ]);
        DB::table('products')->insert([
            'id' => '3',
            'name' => 'iphone 11',
            'description' => 'iphone 11',
            'category_id' => 'SMARTPHONE',
            'price' => 15000000,
        ]);
        DB::table('products')->insert([
            'id' => '4',
            'name' => 'Macbook Pro 2020',
            'description' => 'Macbook Pro 2020',
            'category_id' => 'LAPTOP',
            'price' => 25000000,
        ]);
    }
    public function testJoin(){
        $this->insertProducts();
        $collection = DB::table('products')
            ->join('categories','categories.id','=','products.category_id')
            ->select('products.id','categories.name as category_name', 'products.price')
            ->get();
        self::assertCount(4,$collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }
    public function testOrdering(){
        $this->insertProducts();
        $collection = DB::table('products')
            ->join('categories','categories.id','=','products.category_id')
            ->select('products.id','products.name','categories.name as category_name', 'products.price')
            ->orderBy('products.price','desc')
            ->orderBy('categories.name','desc')
            ->get();
        self::assertCount(4,$collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });

    }
    public function testPanging(){
        $this->insertCategories();
        $collection = DB::table('categories')
            ->skip(2)
            ->take(2)
            ->get();
        self::assertCount(2,$collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }
    public function insertManyCategories(){
        for ($i=0;$i<100;$i++){
            DB::table('categories')->insert([
                "id" => "SMARTPHONE".$i,
                "name" => "Smartphone".$i,
                "description" => "Smartphone Category".$i,
                "created_at" => "2020-10-10 10:10:10"
            ]);
        }
    }
    public function testChunk(){
        $this->insertManyCategories();
        DB::table('categories')->orderBy('id')->chunk(10,function ($collection){
            self::assertCount(10,$collection);
            self::assertNotNull($collection);
            $collection->each(function ($item){
                Log::info(json_encode($item));
            });
        });
    }
    public function testLazy()
    {
        $this->insertManyCategories();
        $collection = DB::table('categories')->orderBy('id')->lazy(10)->take(3);
        self::assertNotNull($collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }
    public function testCursor()
    {
        $this->insertManyCategories();
        $collection = DB::table('categories')->orderBy('id')->cursor();
        self::assertNotNull($collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }
    public function testAggregate(){
        $this->insertProducts();
        $collection = DB::table('products')
            ->count('id');
        self::assertEquals(4,$collection);
        $collection = DB::table('products')
            ->min('price');
        self::assertEquals(12000000,$collection);
        $collection = DB::table('products')
            ->max('price');
        self::assertEquals(25000000,$collection);
        $collection = DB::table('products')
            ->sum('price');
        self::assertEquals(66000000,$collection);
    }
    public function testQueryBuilderRaw(){
        $this->insertProducts();
        $collection = DB::table('products')
            ->select(
                DB::raw('count(id) as total'),
                DB::raw('sum(price) as total_price'),
                DB::raw('min(price) as min_price'),
                DB::raw('max(price) as max_price')
            )->get();
        self::assertEquals(4,$collection[0]->total);
        self::assertEquals(66000000,$collection[0]->total_price);
        self::assertEquals(12000000,$collection[0]->min_price);
        self::assertEquals(25000000,$collection[0]->max_price);

    }
    public function insertProductFood(){
        DB::table('products')->insert([
            'id' => '5',
            'name' => 'bakso',
            'category_id' => 'FOOD',
            'price' => 12000,
        ]);
        DB::table('products')->insert([
            'id' => '6',
            'name' => 'Mie Ayam',
            'category_id' => 'FOOD',
            'price' => 15000,
        ]);
    }
    public function testGrupBy()
    {
        $this->insertProducts();
        $this->insertProductFood();
        $collection = DB::table('products')
            ->select('category_id',DB::raw('count(id) as total'))
            ->groupBy('category_id')
            ->get();
        self::assertCount(3,$collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }
    public function testGrupByHaving()
    {
        $this->insertProducts();
        $this->insertProductFood();
        $collection = DB::table('products')
            ->select('category_id',DB::raw('count(id) as total'))
            ->groupBy('category_id')
            ->having(DB::raw('count(id)'),'>',2)
            ->get();
        self::assertCount(1,$collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }
    public function testLocking()
    {
        $this->insertProducts();
        DB::transaction(function (){
            $collection = DB::table('products')
                ->where('id','1')
                ->lockForUpdate()
                ->get();
            self::assertCount(1,$collection);
            $collection->each(function ($item){
                Log::info(json_encode($item));
            });
        });
    }
    public function testPagination(){
        $this->insertCategories();
        $paginate = DB::table('categories')->paginate(2, page: 1);
        self::assertEquals(1, $paginate->currentPage());
        self::assertEquals(2, $paginate->perPage());
        self::assertEquals(2, $paginate->lastPage());
        self::assertEquals(4, $paginate->total());
        $collection = $paginate->items();
        self::assertCount(2, $collection);
        foreach ($collection as $item){
            Log::info(json_encode($item));
        }
    }
    public function testIterationPagination(){
        $this->insertCategories();
        $page = 1;
        while(true){
            $paginate = DB::table('categories')->paginate(2, page: $page);
            if($paginate->isEmpty()){
                break;
            }else{
                $collection = $paginate->items();
                self::assertCount(2, $collection);
                foreach ($collection as $item){
                    Log::info(json_encode($item));
                }
            }
            $page++;


        }

    }
    public function testCursorPagination(){
        $this->insertCategories();
        $cursor = 'id';
        while(true){
            $paginate = DB::table('categories')->orderBy('id')->cursorPaginate(2, cursor: $cursor);
            foreach ($paginate->items() as $item){
                self::assertNotNull($item);
                Log::info(json_encode($item));
            }
            $cursor = $paginate->nextCursor();
            if ($cursor === null){
                break;
            }
        }
    }
}
