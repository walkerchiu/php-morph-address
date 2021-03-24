<?php

namespace WalkerChiu\MorphAddress\Models\Entities;

use WalkerChiu\Core\Models\Entities\Lang;

class AddressLang extends Lang
{
    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = array())
    {
        $this->table = config('wk-core.table.morph-address.addresses_lang');

        parent::__construct($attributes);
    }
}
