<?php

namespace WalkerChiu\MorphAddress;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use WalkerChiu\MorphAddress\Models\Entities\Address;

class AddressTest extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ .'/../migrations');
        $this->withFactories(__DIR__ .'/../../src/database/factories');
    }

    /**
     * To load your package service provider, override the getPackageProviders.
     *
     * @param \Illuminate\Foundation\Application  $app
     * @return Array
     */
    protected function getPackageProviders($app)
    {
        return [\WalkerChiu\Core\CoreServiceProvider::class,
                \WalkerChiu\MorphAddress\MorphAddressServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
    }

    /**
     * A basic functional test on Address.
     *
     * For WalkerChiu\MorphAddress\Models\Entities\Address
     * 
     * @return void
     */
    public function testMorphAddress()
    {
        // Config
        Config::set('wk-core.onoff.core-lang_core', 0);
        Config::set('wk-morph-address.onoff.core-lang_core', 0);
        Config::set('wk-core.lang_log', 1);
        Config::set('wk-morph-address.lang_log', 1);
        Config::set('wk-core.soft_delete', 1);
        Config::set('wk-morph-address.soft_delete', 1);

        // Give
        $db_morph_1 = factory(Address::class)->create();
        $db_morph_2 = factory(Address::class)->create();

        // Get records after creation
            // When
            $records = Address::all();
            // Then
            $this->assertCount(2, $records);

        // Delete someone
            // When
            $db_morph_2->delete();
            $records = Address::all();
            // Then
            $this->assertCount(1, $records);

        // Resotre someone
            // When
            Address::withTrashed()
                   ->find($db_morph_2->id)
                   ->restore();
            $record_2 = Address::find($db_morph_2->id);
            $records = Address::all();
            // Then
            $this->assertNotNull($record_2);
            $this->assertCount(2, $records);
    }
}
