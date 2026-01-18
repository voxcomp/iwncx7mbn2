<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'amount', 'discount_type', 'active', 'valid_from', 'valid_to', 'last_used', 'used', 'maxuse',
    ];

    /**
     * Tests valid_to field, returns true if coupon is not expired
     */
    public function isExpired(): bool
    {
        return $this->valid_to < time();
    }

    /**
     * Tests valid_from field, returns true if coupon is usable
     */
    public function isValid(): bool
    {
        return $this->valid_from > time();
    }

    /**
     * Tests active field, returns true if coupon is currently active
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * Tests if coupon code is usable, returns true if coupon is usable
     */
    public function isUsable(): bool
    {
        return $this->active && $this->valid_from < time() && $this->valid_to > time() && (($this->maxuse == 0) || ($this->used < $this->maxuse));
    }

    /**
     * Returns cost after coupon value is taken
     */
    public function value(float $charge): bool
    {
        if ($this->discount_type == 'dollar') {
            $charge = $charge - floatval($this->amount);
        } elseif ($this->discount_type == 'percent') {
            $charge = $charge - ($charge * (floatval($this->amount) / 100));
        }

        return ($charge > 0) ? $charge : 0;
    }

    /**
     * Adds to number of uses, deactivate if used more than max times
     */
    public function useCoupon()
    {
        if ($this->isUsable()) {
            $this->used += 1;
            $this->last_used = time();
            if ($this->used > $this->maxuse && $this->maxuse > 0) {
                $this->active = 0;
            }
            $this->save();

            return true;
        } else {
            $this->active = 0;
            $this->save();

            return false;
        }
    }
}
