<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmerDetails extends Model
{
    use HasFactory;

    //protected $with = ['farm_lands'];
    protected $table = 'farmer_details';
    protected $appends = ['avatar_url', 'id_proof_photo_url'];

    protected $fillable = [
        'staff_id',
        'user_id',
        'enrollment_date',
        'enrollment_place',
        'full_name',
        'phone_number',
        'identity_proof',
        'country',
        'province',
        'district',
        'commune',
        'village',
        'lng',
        'lat',
        'proof_no',
        'gender',
        'farmer_code',
        'dob',
        'farmer_photo',
        'id_proof_photo',
        'is_online',
        'srp_certification',
    ];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
    
    public function farm_lands()
    {
        return $this->hasMany(FarmLand::class, 'farmer_id', 'id');
    }
    public function cultivation_crop()
    {
        return $this->hasManyThrough(
            Cultivations::class,
            FarmLand::class,
            'farmer_id', // Foreign key on the Farm Land table...
            'farm_land_id', // Foreign key on the Crops table...
            'id', // Local key on the projects table...
            'id' // Local key on the environments table...
        );
    }
    public function family_info()
    {
    	return $this->hasOne(FamilyInfo::class,'farmer_id', 'id');
    }
    public function asset_info()
    {
    	return $this->hasOne(AssetInfo::class,'farmer_id', 'id');
    }
    public function bank_info()
    {
    	return $this->hasMany(BankInfo::class,'farmer_id', 'id');
    }
    public function finance_info()
    {
    	return $this->hasOne(FinanceInfo::class,'farmer_id', 'id');
    }
    public function insurance_info()
    {
    	return $this->hasMany(InsuranceInfo::class,'farmer_id', 'id');
    }
    public function animal_husbandry()
    {
    	return $this->hasMany(AnimalHusbandry::class,'farmer_id', 'id');
    }
    public function certificate_info()
    {
    	return $this->hasOne(CertificateInformation::class,'farmer_id', 'id');
    }
    public function farm_equipment()
    {
    	return $this->hasMany(FarmEquipment::class,'farmer_id', 'id');
    }

    public function countryRelation()
    {
        return $this->belongsTo(Country::class,'country', 'id');
    }

    public function provinceRelation()
    {
        return $this->belongsTo(Province::class,'province', 'id');
    }

    public function districtRelation()
    {
        return $this->belongsTo(District::class,'district', 'id');
    }

    public function communeRelation()
    {
        return $this->belongsTo(Commune::class, 'commune', 'id');
    }

    public function thumbnail()
    {
        return $this->belongsTo(Uploads::class,'farmer_photo', 'id');
    }
    
    public function getAvatarUrlAttribute()
    {
        if (!empty($this->thumbnail)) {
            return asset($this->thumbnail->file_name);
        }
        
        return asset('assets/img/avatars/1.png');
    }

    public function getIdProofPhotoUrlAttribute()
    {
        $photoIds = explode(',', $this->id_proof_photo);
        $url = [];
        foreach ($photoIds as $photoId) {
            $upload = Uploads::find($photoId);
            if ($upload) {
                $url[] = asset($upload->file_name);
            }
        }

        return $url;
    }
}
