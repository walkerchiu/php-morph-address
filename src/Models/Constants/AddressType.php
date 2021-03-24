<?php

namespace WalkerChiu\MorphAddress\Models\Constants;

/**
 * @license MIT
 * @package WalkerChiu\MorphAddress
 *
 *
 */

class AddressType
{
    public static function getCodes()
    {
        $items = [];
        $types = self::all();
        foreach ($types as $code=>$type) {
            array_push($items, $code);
        }

        return $items;
    }

    public static function options($only_vaild = false)
    {
        $items = $only_vaild ? [] : ['' => trans('php-core::system.null')];

        $types = self::all();
        foreach ($types as $key=>$value) {
            $items = array_merge($items, [$key => trans('php-morph-address::system.addressType.'.$key)]);
        }

        return $items;
    }

    public static function all()
    {
        return [
            'location'     => 'Location',
            'household'    => 'Household Registration',
            'store'        => 'Store',
            'site'         => 'Site',
            'registration' => 'Registration',
            'contact'      => 'Contact',
            'recipient'    => 'Recipient',
            'bill'         => 'Bill',
            'invoice'      => 'Invoice'
        ];
    }
}
