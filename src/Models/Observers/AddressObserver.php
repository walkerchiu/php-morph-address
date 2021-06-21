<?php

namespace WalkerChiu\MorphAddress\Models\Observers;

class AddressObserver
{
    /**
     * Handle the entity "retrieved" event.
     *
     * @param  $entity
     * @return void
     */
    public function retrieved($entity)
    {
        //
    }

    /**
     * Handle the entity "creating" event.
     *
     * @param  $entity
     * @return void
     */
    public function creating($entity)
    {
        //
    }

    /**
     * Handle the entity "created" event.
     *
     * @param  $entity
     * @return void
     */
    public function created($entity)
    {
        //
    }

    /**
     * Handle the entity "updating" event.
     *
     * @param  $entity
     * @return void
     */
    public function updating($entity)
    {
        //
    }

    /**
     * Handle the entity "updated" event.
     *
     * @param  $entity
     * @return void
     */
    public function updated($entity)
    {
        //
    }

    /**
     * Handle the entity "saving" event.
     *
     * @param  $entity
     * @return void
     */
    public function saving($entity)
    {
        if ($entity->is_main) {
            config('wk-core.class.morph-address.address')::withTrashed()
                ->where('morph_type', $entity->morph_type)
                ->where('morph_id', $entity->morph_id)
                ->where('type', $entity->type)
                ->where('id', '<>', $entity->id)
                ->update(['is_main' => 0]);
        }
    }

    /**
     * Handle the entity "saved" event.
     *
     * @param  $entity
     * @return void
     */
    public function saved($entity)
    {
        //
    }

    /**
     * Handle the entity "deleting" event.
     *
     * @param  $entity
     * @return void
     */
    public function deleting($entity)
    {
        //
    }

    /**
     * Handle the entity "deleted" event.
     *
     * Its Lang will be automatically removed by database.
     *
     * @param  $entity
     * @return void
     */
    public function deleted($entity)
    {
        if (!config('wk-morph-address.soft_delete')) {
            $entity->forceDelete();
        }

        if ($entity->isForceDeleting()) {
            $entity->langs->withTrashed()->forceDelete();
        }

        if ($entity->is_main) {
            $address = config('wk-core.class.morph-address.address')
                ->where('morph_type', $entity->morph_type)
                ->where('morph_id', $entity->morph_id)
                ->where('type', $entity->type)
                ->orderBy('updated_at', 'DESC')
                ->first();
            if ($address)
                $address->update(['is_main' => 1]);
        }
    }

    /**
     * Handle the entity "restoring" event.
     *
     * @param  $entity
     * @return void
     */
    public function restoring($entity)
    {
        //
    }

    /**
     * Handle the entity "restored" event.
     *
     * @param  $entity
     * @return void
     */
    public function restored($entity)
    {
        //
    }
}
