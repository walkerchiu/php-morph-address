<?php

namespace WalkerChiu\MorphAddress\Models\Observers;

class AddressLangObserver
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
        $query =
            config('wk-core.class.morph-address.addressLang')
                ::withTrashed()
                ->where('morph_type', $model->morph_type)
                ->where('morph_id', $model->morph_id)
                ->where('code', $model->code)
                ->where('key', $model->key)
                ->where('id', '<>', $model->id);

        if (
            config('wk-morph-address.soft_delete')
            && (
                config('wk-core.lang_log')
                || config('wk-morph-address.lang_log')
            )
        ) {
            $query->update(['is_current' => 0]);
        } else {
            $query->forceDelete();
        }
    }

    /**
     * Handle the model "updating" event.
     *
     * History should not be modify.
     *
     * @param Model  $model
     * @return void
     */
    public function updating($model)
    {
        return false;
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
        //
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
     * @param Model  $model
     * @return void
     */
    public function deleted($model)
    {
        //
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
