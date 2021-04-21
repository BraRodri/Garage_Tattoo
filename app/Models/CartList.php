<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class CartList extends Model
{
    use HasFactory;
    protected $table = 'cart_list';
    private $subtotal = 0;
    private $despacho = 0;

    protected $fillable = [
        'clients_id',
        'cart_name'
    ];

    public function client(){
        return $this->belongsTo(Clients::class, 'clients_id');
    }

    public function details(){
        return $this->hasMany(CartListDetail::class);
    }

    public static function findOrCreatebySessionId($shopping_cart_id){

        if ($shopping_cart_id) {
            # code...
            return CartList::find($shopping_cart_id);
        } else {
            # code...
            return CartList::create();
        }
        
    }

    public function total_productos(){

        return $this->details->sum('quantity');
    }

    public function total_subtotal(){

        

        foreach($this->details as $key => $details){
            $this->subtotal += Products::find($details->products_id)->offer_price * $details->quantity;
        }

        return $this->subtotal;
    }

    public function total_despacho(){

        return $this->despacho;
    }

    public function total(){

        return $this->subtotal+$this->despacho;
    }

    public static function get_session_cart_list(){
        $session_name = 'shopping_cart_id';
        $shopping_cart_id = Session::get($session_name);
         $cart_list = self::findOrCreatebySessionId($shopping_cart_id);
         return $cart_list;
    }

}
