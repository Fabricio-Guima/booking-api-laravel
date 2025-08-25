<?php

namespace App\Models;

use App\Observers\PropertyObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

#[ObservedBy([PropertyObserver::class])]
class Property extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'owner_id',
        'name',
        'city_id',
        'address_street',
        'address_postcode',
        'lat',
        'long',
    ];

    public function address(): Attribute
    {
        return new Attribute(
            get: fn () => $this->address_street
                 . ', ' . $this->address_postcode
                 . ', ' . $this->city->name
        );
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function apartments()
    {
        return $this->hasMany(Apartment::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->width(800);
    }
}
