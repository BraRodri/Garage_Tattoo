<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as Capsule;


class Products extends Model
{
    use HasFactory;
    protected $table = 'products';

    protected $fillable = [
        'id',
        'brands_id',
        'slug',
        'types_id',
        'type',
        'sku',
        'title',
        'normal_price',
        'offer_price',
        'stock_control',
        'stock',
        'minimum_amount',
        'discount',
        'start_date',
        'end_date',
        'weight',
        'lenght',
        'width',
        'height',
        'general_description',
        'technical_description',
        'shipping_description',
        'guarantee_description',
        'video_description',
        'model',
        'tags',
        'offer',
        'featured',
        'new',
        'visits_number',
        'sales_number',
        'points',
        'position',
        'shipping_active',
        'office_shipping_active',
        'shipping_free',
        'attribute_active',
        'meta_title',
        'meta_description',
        'meta_keyword',
        'meta_author',
        'meta_robots',
        'archive',
        'chilecompracode',
        'active',
        'author',
        'code_cache',
        'pedregal_id',
        'talla',
        'color',
        'medida',
        'certificado'
    ];


    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function brand()
    {
        return $this->belongsTo(Brands::class, 'brands_id');
    }

    public function type()
    {
        return $this->belongsTo(Types::class, 'types_id');
    }

    public function categories()
    {
        return $this->hasMany(ProductsCategories::class, 'products_id');
    }

    public function galleries()
    {
        return $this->hasMany(ProductsGalleries::class, 'products_id');
    }

    public function archives()
    {
        return $this->hasMany(ProductsArchives::class, 'products_id');
    }

    public function relations()
    {
        return $this->hasMany(ProductsRelations::class, 'products_id');
    }

    public static function getProduct($product_id = 0)
    {
        $product = Capsule::table('products')
            ->join('products_categories', 'products_categories.products_id', '=', 'products.id')
            ->join('categories', 'products_categories.categories_id', '=', 'categories.id')
            ->join('brands', 'products.brands_id', '=', 'brands.id', 'LEFT OUTER')
            ->select([
                'products.*',
                'brands.title AS brands_name',
                'categories.id AS categories_id',
                'categories.title AS categories_name',
                'categories.discount AS category_discount',
                'categories.start_date AS categories_start_date',
                'categories.end_date AS categories_end_date'
            ])
            ->where([
                'products.id' => $product_id,
                'products.active' => 1,
            ])
            ->get();
        return $product;
    }

    public static function getProducts($filters = array(), $order = '')
    {
        $products = Capsule::table('products')
            ->join('products_categories', 'products_categories.products_id', '=', 'products.id')
            ->join('categories', 'products_categories.categories_id', '=', 'categories.id')
            ->join('brands', 'products.brands_id', '=', 'brands.id', 'LEFT OUTER')
            ->select([
                'products.*',
                'brands.title AS brands_name',
                'categories.id AS categories_id',
                'categories.title AS categories_name',
                'categories.discount AS categories_discount',
                'categories.start_date AS categories_start_date',
                'categories.end_date AS categories_end_date',
            ])
            ->where('products.active', 1)
            ->where('categories.active', 1);

        if (count($filters) > 0) {
            $products = $products->where($filters);
        }

        switch ($order) {
            case 1:
                $products = $products->orderBy('products.featured', 'DESC');
                break;
            case 2:
                $products = $products->orderBy('products.title', 'ASC');
                break;
            case 3:
                $products = $products->orderBy('products.title', 'DESC');
                break;
            case 4:
                $products = $products->orderBy('products.id', 'DESC');
                break;
            case 5:
                $products = $products->orderByRaw('CASE WHEN products.offer_price > 0 THEN products.offer_price ELSE products.normal_price END ASC');
                break;
            case 6:
                $products = $products->orderByRaw('CASE WHEN products.offer_price > 0 THEN products.offer_price ELSE products.normal_price END DESC');
                break;
            default:
                $products = $products->orderByRaw('CASE WHEN products.offer_price > 0 THEN products.offer_price ELSE products.normal_price END ASC');
        }

        return $products;
    }

    public static function getProductsFeatured($number_per_page = 1)
    {
        $featureds = Capsule::table('products')
            ->join('products_categories', 'products_categories.products_id', '=', 'products.id')
            ->join('categories', 'products_categories.categories_id', '=', 'categories.id')
            ->join('brands', 'products.brands_id', '=', 'brands.id', 'LEFT OUTER')
            ->select([
                'products.*',
                'brands.title AS brands_name',
                'categories.id AS categories_id',
                'categories.title AS categories_name',
                'categories.discount AS category_discount',
                'categories.start_date AS categories_start_date',
                'categories.end_date AS categories_end_date'
            ])
            ->where('categories.active', 1)
            ->where('products.active', 1)
            ->where('products.featured', 1)
            ->where('products.offer', 0)
            ->orderByRaw("RAND()")
            ->limit($number_per_page)
            ->get();
        return $featureds;
    }

    public static function getProductsRecents($ids_products = array(), $number_per_page = 1)
    {
        $recents = Capsule::table('products')
            ->join('products_categories', 'products_categories.products_id', '=', 'products.id')
            ->join('categories', 'products_categories.categories_id', '=', 'categories.id')
            ->join('brands', 'products.brands_id', '=', 'brands.id', 'LEFT OUTER')
            ->select([
                'products.*',
                'brands.title AS brands_name',
                'categories.id AS categories_id',
                'categories.title AS categories_name',
                'categories.discount AS category_discount',
                'categories.start_date AS categories_start_date',
                'categories.end_date AS categories_end_date'
            ])
            ->where('categories.active', 1)
            ->where('products.active', 1)
            ->where('products.featured', 0)
            ->where('products.offer', 0)
            ->where('products.new', 1)
            ->orderByRaw("RAND()")
            ->limit($number_per_page);

        if (count($ids_products) > 0) {
            $recents = $recents->whereNotIn('products.id', $ids_products);
        }

        $recents = $recents->orderByRaw("RAND()")
            ->limit($number_per_page)
            ->get();

        return $recents;
    }

    public static function getProductsOffers($start_date = 'NOW()', $end_date = 'NOW()', $ids_products = array(), $number_per_page = 1)
    {
        $offers = Capsule::table('products')
            ->join('products_categories', 'products_categories.products_id', '=', 'products.id')
            ->join('categories', 'products_categories.categories_id', '=', 'categories.id')
            ->join('brands', 'products.brands_id', '=', 'brands.id', 'LEFT OUTER')
            ->select([
                'products.*',
                'brands.title AS brands_name',
                'categories.id AS categories_id',
                'categories.title AS categories_name',
                'categories.discount AS category_discount',
                'categories.start_date AS categories_start_date',
                'categories.end_date AS categories_end_date'
            ])
            ->where('categories.active', 1)
            ->where('products.active', 1)
            ->where(function ($q) use ($start_date, $end_date) {
                $q->where('categories.discount', '>', 0)
                    ->orWhere('categories.end_date', '>=', $end_date)
                    ->orWhere('categories.start_date', '<=', $start_date)
                    ->orWhere('products.offer', 1)
                    ->orWhere('products.offer_price', '>', 0);
            });

        if (count($ids_products) > 0) {
            $offers = $offers->whereNotIn('products.id', $ids_products);
        }

        $offers = $offers->orderByRaw("RAND()")
            ->limit($number_per_page)
            ->get();
        return $offers;
    }

    public static function getProductsRelations($id_product = 0, $number_per_page = 1, $category_id = 0)
    {
        $productsRelations = Capsule::table('products')
            ->join('products_categories', 'products_categories.products_id', '=', 'products.id')
            ->join('categories', 'products_categories.categories_id', '=', 'categories.id')
            ->join('brands', 'products.brands_id', '=', 'brands.id', 'LEFT OUTER')
            ->join('products_relations', 'products_relations.relation_id', '=', 'products.id')
            ->select([
                'products.*',
                'brands.title AS brands_name',
                'categories.id AS categories_id',
                'categories.title AS categories_name'
            ])
            ->where([
                'products_relations.products_id' => $id_product,
                'products.active' => 1,
                'categories.active' => 1
            ])
            ->groupBy('products.id');

        $productsRelationsNumberTotal = $productsRelations->get()->count();

        $ids_products = array();
        if ($productsRelationsNumberTotal > 0) {
            $idProductsRelations = $productsRelations->orderByRaw("RAND()")->limit($number_per_page)->get();
            if (count($idProductsRelations) > 0) {
                foreach ($idProductsRelations as $idProductRelation) {
                    $ids_products[] = $idProductRelation->id;
                }
            }
        }

        $relations = Capsule::table('products')
            ->join('products_categories', 'products_categories.products_id', '=', 'products.id')
            ->join('categories', 'products_categories.categories_id', '=', 'categories.id')
            ->join('brands', 'products.brands_id', '=', 'brands.id', 'LEFT OUTER')
            ->select([
                'products.*',
                'brands.title AS brands_name',
                'categories.id AS categories_id',
                'categories.title AS categories_name'
            ])
            ->where([
                'products.active' => 1,
                'categories.active' => 1
            ])
            ->groupBy('products.id');

        if (count($ids_products) > 0) {
            $relations = $relations
                ->whereIn('products.id', $ids_products)
                ->orderByRaw("RAND()")
                ->limit($number_per_page)
                ->get();
        } else {
            $relations = $relations
                ->where('products.id', '<>', $id_product)
                ->where('products_categories.categories_id', $category_id)
                ->orderByRaw("RAND()")
                ->limit($number_per_page)
                ->get();
        }

        return $relations;
    }

    public static function getCategoriesByProduct($product_id = 0, $level)
    {
        $products = Capsule::table('products')
            ->join('products_categories', 'products_categories.products_id', '=', 'products.id')
            ->join('categories', 'products_categories.categories_id', '=', 'categories.id')
            ->join('brands', 'products.brands_id', '=', 'brands.id', 'LEFT OUTER')
            ->select([
                'categories.id AS categories_id',
                'categories.title AS categories_name',
                'brands.id AS brands_id',
                'brands.title AS brands_name',
            ])
            ->where([
                'categories.active' => 1,
                'brands.active' => 1,
                'products.active' => 1,
                'products.id' => $product_id,
            ]);

        switch ($level) {
            case 1:
                $products = $products->groupBy(['categories.id']);
                break;
            case 2:
                $products = $products->groupBy(['brands.id']);
                break;
            default:
                $products = $products->groupBy(['categories.id']);
        }

        $products = $products->get();
        return $products;
    }
}
