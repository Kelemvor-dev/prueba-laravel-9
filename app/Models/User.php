<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 *
 * @property $id
 * @property $category_id
 * @property $name
 * @property $lastname
 * @property $identification
 * @property $email
 * @property $country
 * @property $address
 * @property $mobile
 * @property $email_verified_at
 * @property $password
 * @property $remember_token
 * @property $created_at
 * @property $updated_at
 *
 * @property Category $category
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class User extends Model
{
    
    static $rules = [
      'category_id'=> 'required',
      'name' => 'required|regex:/^[a-zA-Z ]+$/|max:100|min:5',
      'lastname' => 'required|regex:/^[a-zA-Z ]+$/|max:100',
      'identification' => 'required|unique:users,identification',
      'email' => 'required|email|unique:users,email|max:150',
      'country' => 'required',
      'address' => 'required|max:180',
      'mobile' => 'required|numeric|min:10',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['category_id','name','lastname','identification','email','country','address','mobile'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category()
    {
        return $this->hasOne('App\Models\Category', 'id', 'category_id');
    }
    

}
