<?php

namespace WalkerChiu\MorphAddress\Models\Services;

use Illuminate\Support\Facades\App;
use WalkerChiu\Core\Models\Services\CheckExistTrait;

class AddressService
{
    use CheckExistTrait;

    protected $repository;

    public function __construct()
    {
        $this->repository = App::make(config('wk-core.class.morph-address.addressRepository'));
    }
}
