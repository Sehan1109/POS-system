<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'barcode',
        'cost_price',
        'selling_price',
        'stock_quantity',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the sale items associated with this product.
     */
    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Get purchase order items associated with this product.
     */
    public function purchaseOrderItems(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    /**
     * Check if product is low on stock
     */
    public function isLowStock(): bool
    {
        return $this->stock_quantity <= 10 && $this->stock_quantity > 0;
    }

    /**
     * Check if product is out of stock
     */
    public function isOutOfStock(): bool
    {
        return $this->stock_quantity <= 0;
    }

    /**
     * Decrease stock when product is sold
     */
    public function decreaseStock(int $quantity): bool
    {
        if ($this->stock_quantity >= $quantity) {
            $this->stock_quantity -= $quantity;
            $this->save();
            
            // Log activity for stock decrease
            ActivityLog::record(
                'stock_decrease',
                "Stock decreased by {$quantity} units for product: {$this->name}. New stock: {$this->stock_quantity}",
                $this
            );
            
            return true;
        }
        return false;
    }

    /**
     * Increase stock when purchase order is received
     */
    public function increaseStock(int $quantity): bool
    {
        $this->stock_quantity += $quantity;
        $this->save();
        
        // Log activity for stock increase
        ActivityLog::record(
            'stock_increase',
            "Stock increased by {$quantity} units for product: {$this->name}. New stock: {$this->stock_quantity}",
            $this
        );
        
        return true;
    }
}