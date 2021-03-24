<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateWkMorphAddressTable extends Migration
{
    public function up()
    {
        Schema::create(config('wk-core.table.morph-address.addresses'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('morph');
            $table->string('type', 15);
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->char('area', 3)->default('TWN');
            $table->boolean('is_main')->default(0);

            $table->timestampsTz();
            $table->softDeletes();

            $table->index(['morph_type', 'morph_id', 'type']);
            $table->index('type');
            $table->index('phone');
            $table->index('email');
            $table->index('area');
        });
        if (!config('wk-morph-address.onoff.core-lang_core')) {
            Schema::create(config('wk-core.table.morph-address.addresses_lang'), function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->morphs('morph');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('code');
                $table->string('key');
                $table->text('value')->nullable();
                $table->boolean('is_current')->default(1);

                $table->timestampsTz();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')
                    ->on(config('wk-core.table.user'))
                    ->onDelete('set null')
                    ->onUpdate('cascade');
            });
        }
    }

    public function down() {
        Schema::dropIfExists(config('wk-core.table.morph-address.addresses_lang'));
        Schema::dropIfExists(config('wk-core.table.morph-address.addresses'));
    }
}
