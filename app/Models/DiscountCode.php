<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; 

class DiscountCode extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'discount_value',
        'valid',
        'unique_code',
    ];

    public static function retrieveUsersDiscountCode(string $discountCode,int $userId)
    {
        return self::where('unique_code', $discountCode)
            ->where('user_id', $userId)
            ->where('valid', true)
            ->first();
    }

    public static function deactivateDiscountCode(int $discountCodeId)
    {
        return self::where('id', $discountCodeId)
            ->update(['valid' => false]);
    }

    private static function generateUniqueCode()
    {
        $code = Str::random(20);
        while (self::where('unique_code', $code)->exists()) 
        {
            $code = Str::random(20); 
        }
        return $code;
    }


    public static function createDiscountCode(int $userId)
    {
        return self::create([
            'user_id' => $userId,
            'discount_value' => 5,
            'valid' => true,
            'unique_code' => self::generateUniqueCode()
        ]);
    }


}
