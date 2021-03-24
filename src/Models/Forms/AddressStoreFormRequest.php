<?php

namespace WalkerChiu\MorphAddress\Models\Forms;

use Illuminate\Support\Facades\Request;
use WalkerChiu\MorphAddress\Models\Forms\AddressFormRequest;

class AddressStoreFormRequest extends AddressFormRequest
{
    /**
     * @Override WalkerChiu\MorphAddress\Models\Forms\AddressFormRequest::getValidatorInstance
     */
    protected function getValidatorInstance()
    {
        $request = Request::instance();
        $data = array_merge($this->all(), [
            'morph_type' => config('wk-core.class.site.site'),
            'morph_id'   => $request->route('id'),
            'type'       => 'site'
        ]);
        $this->getInputSource()->replace($data);

        return parent::getValidatorInstance();
    }
}
