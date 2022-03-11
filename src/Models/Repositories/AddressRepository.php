<?php

namespace WalkerChiu\MorphAddress\Models\Repositories;

use Illuminate\Support\Facades\App;
use WalkerChiu\Core\Models\Forms\FormTrait;
use WalkerChiu\Core\Models\Repositories\Repository;
use WalkerChiu\Core\Models\Repositories\RepositoryTrait;
use WalkerChiu\Core\Models\Services\PackagingFactory;

class AddressRepository extends Repository
{
    use FormTrait;
    use RepositoryTrait;

    protected $instance;



    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->instance = App::make(config('wk-core.class.morph-address.address'));
    }

    /**
     * @param String  $code
     * @param Array   $data
     * @param Bool    $auto_packing
     * @return Array|Collection|Eloquent
     */
    public function list(string $code, array $data, $auto_packing = false)
    {
        $instance = $this->instance;

        $data = array_map('trim', $data);
        $repository = $instance->with(['langs' => function ($query) use ($code) {
                                    $query->ofCurrent()
                                          ->ofCode($code);
                                }])
                                ->whereHas('langs', function ($query) use ($code) {
                                    return $query->ofCurrent()
                                                 ->ofCode($code);
                                })
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
                                                return $query->whereHas('langs', function ($query) use ($data) {
                                                    $query->ofCurrent()
                                                          ->where('key', 'name')
                                                          ->where('value', $data['name']);
                                                });
                                            })
                                            ->unless(empty($data['address_line1']), function ($query) use ($data) {
                                                return $query->whereHas('langs', function ($query) use ($data) {
                                                    $query->ofCurrent()
                                                          ->where('key', 'address_line1')
                                                          ->where('value', 'LIKE', $data['address_line1']."%");
                                                });
                                            })
                                            ->unless(empty($data['address_line2']), function ($query) use ($data) {
                                                return $query->whereHas('langs', function ($query) use ($data) {
                                                    $query->ofCurrent()
                                                          ->where('key', 'address_line2')
                                                          ->where('value', 'LIKE', $data['address_line2']."%");
                                                });
                                            })
                                            ->unless(empty($data['guide']), function ($query) use ($data) {
                                                return $query->whereHas('langs', function ($query) use ($data) {
                                                    $query->ofCurrent()
                                                          ->where('key', 'guide')
                                                          ->where('value', 'LIKE', $data['guide']."%");
                                                });
                                            });
                                })
                                ->orderBy('updated_at', 'DESC');

        if ($auto_packing) {
            $factory = new PackagingFactory(config('wk-morph-address.output_format'), config('wk-morph-address.pagination.pageName'), config('wk-morph-address.pagination.perPage'));
            $factory->setFieldsLang(['name', 'address_line1', 'address_line2', 'guide']);
            return $factory->output($repository);
        }

        return $repository;
    }

    /**
     * @param Address       $instance
     * @param Array|String  $code
     * @return Array
     */
    public function show($instance, $code): array
    {
    }
}
