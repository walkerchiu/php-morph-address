<?php

namespace WalkerChiu\MorphAddress\Models\Repositories;

use Illuminate\Support\Facades\App;
use WalkerChiu\Core\Models\Forms\FormTrait;
use WalkerChiu\Core\Models\Repositories\Repository;
use WalkerChiu\Core\Models\Repositories\RepositoryTrait;

class AddressRepository extends Repository
{
    use FormTrait;
    use RepositoryTrait;

    protected $entity;

    public function __construct()
    {
        $this->entity = App::make(config('wk-core.class.morph-address.address'));
    }

    /**
     * @param String $code
     * @param Array  $data
     * @param Int    $page
     * @param Int    $nums per page
     * @return Array
     */
    public function list(String $code, Array $data, $page = null, $nums = null)
    {
        $this->assertForPagination($page, $nums);

        $entity = $this->entity;

        $data = array_map('trim', $data);
        $records = $entity->with(['langs' => function ($query) use ($code) {
                                $query->ofCurrent()
                                      ->ofCode($code);
                             }])
                            ->when($data, function ($query, $data) {
                                return $query->unless(empty($data['id']), function ($query) use ($data) {
                                            return $query->where('id', $data['id']);
                                        })
                                        ->unless(empty($data['morph_type']), function ($query) use ($data) {
                                            return $query->where('morph_type', $data['morph_type']);
                                        })
                                        ->unless(empty($data['morph_id']), function ($query) use ($data) {
                                            return $query->where('morph_id', $data['morph_id']);
                                        })
                                        ->unless(empty($data['type']), function ($query) use ($data) {
                                            return $query->where('type', $data['type']);
                                        })
                                        ->unless(empty($data['phone']), function ($query) use ($data) {
                                            return $query->where('phone', $data['phone']);
                                        })
                                        ->unless(empty($data['email']), function ($query) use ($data) {
                                            return $query->where('email', $data['email']);
                                        })
                                        ->unless(empty($data['area']), function ($query) use ($data) {
                                            return $query->where('area', $data['area']);
                                        })
                                        ->unless(empty($data['name']), function ($query) use ($data) {
                                            return $query->whereHas('langs', function($query) use ($data) {
                                                $query->ofCurrent()
                                                      ->where('key', 'name')
                                                      ->where('value', $data['name']);
                                            });
                                        })
                                        ->unless(empty($data['address_line1']), function ($query) use ($data) {
                                            return $query->whereHas('langs', function($query) use ($data) {
                                                $query->ofCurrent()
                                                      ->where('key', 'address_line1')
                                                      ->where('value', 'LIKE', $data['address_line1']."%");
                                            });
                                        })
                                        ->unless(empty($data['address_line2']), function ($query) use ($data) {
                                            return $query->whereHas('langs', function($query) use ($data) {
                                                $query->ofCurrent()
                                                      ->where('key', 'address_line2')
                                                      ->where('value', 'LIKE', $data['address_line2']."%");
                                            });
                                        })
                                        ->unless(empty($data['guide']), function ($query) use ($data) {
                                            return $query->whereHas('langs', function($query) use ($data) {
                                                $query->ofCurrent()
                                                      ->where('key', 'guide')
                                                      ->where('value', 'LIKE', $data['guide']."%");
                                            });
                                        });
                            })
                            ->orderBy('updated_at', 'DESC')
                            ->get()
                            ->when(is_integer($page) && is_integer($nums), function ($query) use ($page, $nums) {
                                return $query->forPage($page, $nums);
                            });
        $list = [];
        foreach ($records as $record) {
            $data = $record->toArray();
            array_push($list,
                array_merge($data, [
                    'name'          => $record->findLangByKey('name'),
                    'address_line1' => $record->findLangByKey('address_line1'),
                    'address_line2' => $record->findLangByKey('address_line2'),
                    'guide'         => $record->findLangByKey('guide')
                ])
            );
        }

        return $list;
    }

    /**
     * @param Address $entity
     * @param Array|String $code
     * @return Array
     */
    public function show($entity, $code)
    {
    }
}
