<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Affiliate extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'affiliate_id',
        'name',
        'company_name',
        'address',
        'phone',
        'status',
    ];

    /** 
     * Interact with the Company's name attribute. 
     * 
     * @param string $value 
     * @return \Illuminate\Database\Eloquent\Casts\Attribute 
     */
    protected function companyName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => strtolower($value),
        );
    }

    /** 
     * Interact with the name attribute. 
     * 
     * @param string $value 
     * @return \Illuminate\Database\Eloquent\Casts\Attribute 
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => strtolower($value),
        );
    }

    public function subAffiliates()
    {
        return $this->hasMany(Affiliate::class, 'affiliate_id', 'id');
    }

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class, 'affiliate_id', 'id');
    }

    public function keys()
    {
        return $this->hasMany(ApiKey::class, 'affiliate_id', 'id');
    }

    public function activeControl($label = false, $ajax = false, $print = true)
    {
        $id = "active-{$this->id}";
        $html = '';
        $disabled = $this->deleted_at ? 'disabled' : '';
        if ($this->status || old('status')) {
            $data_url = $ajax ? 'data-url="' . route('affiliate.status',  base64_encode($this->id)) . '" data-method="get"' : '';
            $html .= <<<HTML
                    <input type="checkbox" name="status" value="1" {$disabled} checked="checked" class="switchery" {$data_url} id="{$id}">
                HTML;
        } else {
            $data_url = $ajax ? 'data-url="' . route('affiliate.status', base64_encode($this->id)) . '" data-method="get"' : '';
            $html .= <<<HTML
                        <input type="checkbox" name="status" value="0"  {$disabled}  class="switchery" {$data_url} id="{$id}">
                HTML;
        }

        if ($label) {
            $html = <<<HTML
                    <label for="{$id}" style="display: block;">Active? </label>
                    {$html}
                HTML;
        }

        if ($print) {
            echo $html;
        } else {
            return $html;
        }
    }
}
