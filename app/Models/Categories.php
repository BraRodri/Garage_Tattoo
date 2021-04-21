<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as Capsule;


class Categories extends Model
{
    use HasFactory;
    protected $table = 'categories';

    protected $fillable = [
        'parent_id',
        'slug',
        'level',
        'title',
        'description',
        'main_image',
        'secondary_image',
        'offer_image',
        'discount',
        'start_date',
        'end_date',
        'position',
        'featured',
        'active',
        'author'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function category(){
        return $this->belongsTo(Categories::class, 'parent_id');
    }

    public function products(){
        return $this->hasMany(ProductsCategories::class);
    }

    public static function getCategories($parent_id = 0, $order_categories = 'categories.title')
    {
        $query = "
        SELECT
        categories.id,
        categories.parent_id,
        categories.title,
        categories.description,
        categories.main_image,
        categories.secondary_image,
        categories.offer_image
        FROM
        products_categories
        INNER JOIN products ON products_categories.products_id = products.id
        INNER JOIN categories ON products_categories.categories_id = categories.id
        WHERE
        categories.active = 1
        AND categories.parent_id = {$parent_id}
        AND products.active = 1
        GROUP BY
        categories.id,
        categories.parent_id,
        categories.title,
        categories.description,
        categories.main_image,
        categories.secondary_image
        ORDER BY
        {$order_categories} ASC
        ";
        $categories = Capsule::select($query);
        return $categories;
    }

    public static function getCategoriesByProduct($id_product = 0, $order = 'ASC')
    {
        $query = "
        SELECT
        products_categories.products_id,
        products_categories.categories_id,
        products.sku,
        products.title,
        categories.title AS categories_name,
        categories.discount,
        categories.start_date,
        categories.end_date
        FROM
        products_categories
        INNER JOIN products ON products_categories.products_id = products.id
        INNER JOIN categories ON products_categories.categories_id = categories.id
        WHERE
        categories.active = 1
        AND products_categories.products_id = :products_id
        ORDER BY
        categories.id {$order} LIMIT 1
        ";
        $categories = Capsule::select($query, [':products_id' => $id_product]);
        return $categories;
    }

    public static function getCategoriesWithDiscountByProduct($id_product = 0)
    {
        $query = "
        SELECT
        products_categories.categories_id,
        categories.title AS categories_name,
        categories.discount,
        categories.start_date,
        categories.end_date
        FROM
        products_categories
        INNER JOIN products ON products_categories.products_id = products.id
        INNER JOIN categories ON products_categories.categories_id = categories.id
        WHERE
        categories.active = 1
        AND products_categories.products_id = :products_id
        AND categories.parent_id = 0
        AND categories.discount > 0
        AND categories.end_date >= NOW()
        AND categories.start_date <= NOW()
        ORDER BY
        categories.discount DESC LIMIT 1
        ";
        $categories = Capsule::select($query, [':products_id' => $id_product]);
        return $categories;
    }

    public static function getCategoriesProductsRelations($id_product = 0)
    {
        $categories = Capsule::select('
        SELECT
        products_categories.categories_id,
        categories.title,
        categories.secondary_image
        FROM
        products_categories
        INNER JOIN categories ON products_categories.categories_id = categories.id
        WHERE
        products_categories.products_id = :products_id
        AND categories.active = 1
        ORDER BY
        categories.parent_id ASC
        ', [':products_id' => $id_product]);
        return $categories;
    }
}
