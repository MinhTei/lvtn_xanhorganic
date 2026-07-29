<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use HasFactory;
    protected $fillable=['name','email','phone','password','remember_token','status','role_id','activation_token'];

    public function role(){
        return $this->belongsTo(Role::class,'role_id');
    }
    public function addresses(){
        return $this->hasMany(UserAddress::class,'user_id');
    }
     public function notifications(){
        return $this->hasMany(Notification::class,'user_id');
    }
    public function coupons(){
        return $this->hasMany(Coupon::class,'created_by');
    }
    public function couponUsages(){
        return $this->hasMany(CouponUsage::class,'user_id');
    }
    public function carts(){
        return $this->hasMany(Cart::class,'user_id');
    }
     public function orders(){
        return $this->hasMany(Order::class,'user_id');
    }
    public function wishlists(){
        return $this->hasMany(Wishlist::class,'user_id');
    }
    public function products(){
        return $this->belongsToMany(Product::class,'wishlists','user_id','product_id');
    }
    public function productReviews(){
        return $this->hasMany(ProductReview::class,'user_id');
    }

    //CheckStatus
    public function isPending(){
        return $this->status==='pending';
    }
     public function isActive(){
        return $this->status==='active';
    }
     public function isBlocked(){
        return $this->status==='blocked';
    }
     // ---- Admin helpers ----
    public function isAdmin(): bool
    {
        return $this->role?->name === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role?->name === 'staff';
    }

    /** Admin panel: admin/staff hoặc role tùy chỉnh có ít nhất 1 quyền manage_* */
    public function canAccessAdmin(): bool
    {
        if ($this->status !== 'active' || !$this->role) {
            return false;
        }

        if (in_array($this->role->name, ['admin', 'staff'], true)) {
            return true;
        }

        // Role tùy chỉnh: vào được admin nếu có quyền
        return $this->role->permissions()->exists();
    }

    /** Kiểm tra quyền theo tên permission (vd: manage_products) */
    public function hasPermission(string $permission): bool
    {
        if (!$this->role) {
            return false;
        }

        // Admin: full quyền
        if ($this->isAdmin()) {
            return true;
        }

        // Cache danh sách quyền trên instance (sidebar gọi nhiều lần)
        if (!isset($this->permissionNamesCache)) {
            $this->permissionNamesCache = $this->role->permissions()->pluck('name')->all();
        }

        return in_array($permission, $this->permissionNamesCache, true);
    }
}
