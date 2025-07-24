<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'country',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relacje
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'address_id');
    }

    // Akcesory
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getFullAddressAttribute(): string
    {
        return "{$this->address}, {$this->postal_code} {$this->city}, {$this->country}";
    }

    public function getFormattedAddressAttribute(): string
    {
        return implode('<br>', [
            $this->full_name,
            $this->address,
            "{$this->postal_code} {$this->city}",
            $this->country
        ]);
    }

    // Statyczne metody
    public static function createFromOrderData(array $data, int $userId): self
    {
        return self::create([
            'user_id' => $userId,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'city' => $data['city'],
            'postal_code' => $data['postal_code'],
            'country' => $data['country'] ?? 'Polska',
            'is_default' => false
        ]);
    }

    public static function findOrCreateFromOrderData(array $data, int $userId): self
    {
        // Spróbuj znaleźć istniejący adres
        $existingAddress = self::where('user_id', $userId)
            ->where('first_name', $data['first_name'])
            ->where('last_name', $data['last_name'])
            ->where('address', $data['address'])
            ->where('city', $data['city'])
            ->where('postal_code', $data['postal_code'])
            ->first();

        if ($existingAddress) {
            // Zaktualizuj email i telefon jeśli się zmieniły
            $existingAddress->update([
                'email' => $data['email'],
                'phone' => $data['phone'],
                'country' => $data['country'] ?? 'Polska'
            ]);

            return $existingAddress;
        }

        // Utwórz nowy adres
        return self::createFromOrderData($data, $userId);
    }

    public function setAsDefault(): void
    {
        // Usuń domyślny status z innych adresów użytkownika
        self::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);

        // Ustaw ten adres jako domyślny
        $this->update(['is_default' => true]);
    }
}
