<?php

namespace WalkerChiu\MorphAddress\Models\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use WalkerChiu\Core\Models\Entities\LangTrait;
use WalkerChiu\Core\Models\Entities\UuidModel;

class Address extends UuidModel
{
    use LangTrait;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var Array
     */
    protected $fillable = [
        'morph_type', 'morph_id',
        'type',
        'phone', 'email',
        'area',
        'is_main'
    ];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var Array
	 */
    protected $hidden = [
        'deleted_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var Array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var Array
     */
    protected $casts = [
        'is_main' => 'boolean'
    ];



    /**
     * Create a new instance.
     *
     * @param Array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('wk-core.table.morph-address.addresses');

        parent::__construct($attributes);
    }

    /**
     * Get it's lang entity.
     *
     * @return Lang
     */
    public function lang()
    {
        if (
            config('wk-core.onoff.core-lang_core')
            || config('wk-morph-address.onoff.core-lang_core')
        ) {
            return config('wk-core.class.core.langCore');
        } else {
            return config('wk-core.class.morph-address.addressLang');
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function langs()
    {
        if (
            config('wk-core.onoff.core-lang_core')
            || config('wk-morph-address.onoff.core-lang_core')
        ) {
            return $this->langsCore();
        } else {
            return $this->hasMany(config('wk-core.class.morph-address.addressLang'), 'morph_id', 'id');
        }
    }

    /**
     * Get the owning morph model.
     */
    public function morph()
    {
        return $this->morphTo();
    }
}
