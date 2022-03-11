<?php

namespace WalkerChiu\MorphAddress\Models\Observers;

class AddressObserver
{
    /**
     * Handle the model "retrieved" event.
     *
     * @param Model  $model
     * @return void
     */
    public function retrieved($model)
    {
        //
    }

    /**
     * Handle the model "creating" event.
     *
     * @param Model  $model
     * @return void
     */
    public function creating($model)
    {
        //
    }

    /**
     * Handle the model "created" event.
     *
     * @param Model  $model
     * @return void
     */
    public function created($model)
    {
        //
    }

    /**
     * Handle the model "updating" event.
     *
     * @param Model  $model
     * @return void
     */
    public function updating($model)
    {
        //
    }

    /**
     * Handle the model "updated" event.
     *
     * @param Model  $model
     * @return void
     */
    public function updated($model)
    {
        //
    }

    /**
     * Handle the model "saving" event.
     *
     * @param Model  $model
     * @return void
     */
    public function saving($model)
    {
        if ($model->is_main) {
            config('wk-core.class.morph-address.address')
                ::withTrashed()
                ->where('morph_type', $model->morph_type)
                ->where('morph_id', $model->morph_id)
                ->where('type', $model->type)
                ->where('id', '<>', $model->id)
                ->update(['is_main' => 0]);
        }
    }

    /**
     * Handle the model "saved" event.
     *
     * @param Model  $model
     * @return void
     */
    public function saved($model)
    {
        //
    }

    /**
     * Handle the model "deleting" event.
     *
     * @param Model  $model
     * @return void
     */
    public function deleting($model)
    {
        //
    }

    /**
     * Handle the model "deleted" event.
     *
     * Its Lang will be automatically removed by database.
     *
     * @param Model  $model
     * @return void
     */
    public function deleted($model)
    {
        if (!config('wk-morph-address.soft_delete')) {
            $model->forceDelete();
        }

        if ($model->isForceDeleting()) {
            $model->langs()->withTrashed()
                           ->forceDelete();
        }

        if ($model->is_main) {
            $address = config('wk-core.class.morph-address.address')
                ->where('morph_type', $model->morph_type)
                ->where('morph_id', $model->morph_id)
                ->where('type', $model->type)
                ->orderBy('updated_at', 'DESC')
                ->first();
            if ($address)
                $address->update(['is_main' => 1]);
        }
    }

    /**
     * Handle the model "restoring" event.
     *
     * @param Model  $model
     * @return void
     */
    public function restoring($model)
    {
        //
    }

    /**
     * Handle the model "restored" event.
     *
     * @param Model  $model
     * @return void
     */
    public function restored($model)
    {
        //
    }
}
