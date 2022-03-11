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
    /**
     * @return Array
     */
    public static function getCodes(): array
    {
        $items = [];
        $types = self::all();
        foreach ($types as $code => $type) {
            array_push($items, $code);
        }

        return $items;
    }

    /**
     * @param Bool  $onlyVaild
     * @return Array
     */
    public static function options($onlyVaild = false): array
    {
        $items = $onlyVaild ? [] : ['' => trans('php-core::system.null')];

        $types = self::all();
        foreach ($types as $key => $value) {
            $items = array_merge($items, [$key => trans('php-morph-address::system.addressType.'.$key)]);
        }

        return $items;
    }

    /**
     * @return Array
     */
    public static function all(): array
    {
        return [
            'bill'         => 'Bill',
            'contact'      => 'Contact',
            'household'    => 'Household Registration',
            'invoice'      => 'Invoice',
            'location'     => 'Location',
            'mailing'      => 'Mailing',
            'registration' => 'Registration',
            'recipient'    => 'Recipient',
            'site'         => 'Site',
            'store'        => 'Store',
            'working'      => 'Working',
        ];
    }
}
