<?php

namespace WalkerChiu\MorphAddress\Models\Entities;

trait UserTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function addresses($type = null)
    {
        return $this->morphMany(config('wk-core.class.morph-address.address'), 'morph')
                    ->when($type, function ($query, $type) {
                                return $query->where('type', $type);
                            });
    }

    /**
     * @param Array         $types
     * @param String|Array  $codes
     * @return Array
     */
    public function showAddresses(array $types, $codes): array
    {
        $data = [];
        foreach ($types as $type) {
            if (!isset($data[$type])) {
                $data = array_merge($data, [$type => []]);
            }

            foreach ($data as $key => $value) {
                $records = $this->addresses($key)->get();
                foreach ($records as $record) {
                    if (is_string($codes)) {
                        $data[$key] = [
                            'id'            => $record->id,
                            'type'          => $record->type,
                            'phone'         => $record->phone,
                            'email'         => $record->email,
                            'area'          => $record->area,
                            'address_line1' => $record->findLang($codes, 'address_line1'),
                            'address_line2' => $record->findLang($codes, 'address_line2'),
                            'guide'         => $record->findLang($codes, 'guide'),
                            'is_main'       => $record->is_main
                        ];
                    } elseif (is_array($codes)) {
                        foreach ($codes as $code) {
                            $address = [
                                'id'            => $record->id,
                                'type'          => $record->type,
                                'phone'         => $record->phone,
                                'email'         => $record->email,
                                'area'          => $record->area,
                                'address_line1' => $record->findLang($code, 'address_line1'),
                                'address_line2' => $record->findLang($code, 'address_line2'),
                                'guide'         => $record->findLang($code, 'guide'),
                                'is_main'       => $record->is_main
                            ];

                            if (isset($data[$key][$code])) {
                                array_push($data[$key][$code], $address);
                            } else {
                                $data[$key] = array_merge($data[$key], [$code => [$address]]);
                            }
                        }
                    }
                }
            }
        }

        return $data;
    }
}
