<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patrimony extends Model
{
    use HasFactory;

    protected $table = "patrimonies";

    protected $fillable = [
        'type',
        'quantity_account_current',
        'detail_location',
        'estate_quantity',
        'department',
        'city',
        'locality',
        'latitude',
        'longitude',
        'location_address',
        'infrastructure_type',
        'description',
        'registry_number',
        'cadastral_current_account',
        'estate_status',
        'committed_investment',
        'transfer',
        'balance_for_transfer',
        'tenant',
        'rent_amount',
        'rent_amount_period',
        'contract_resolution',
        'contract_number',
        'current_period_start',
        'current_period_end',
        'status_documentation',
        'land_area_mt2',
        'land_area_hectares',
        'land_sub_area',
        'built_area_m2',
        'built_value_gs',
        'property_value_gs',
        'total_value_gs',
        'possession_rent_without_title',
        'main_photo_file',
        'main_photo_file_path',
        'evidence_file',
        'evidence_file_path',
    ];
    
}
