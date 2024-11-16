<?php

namespace WalkerChiu\MorphAddress;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use WalkerChiu\Core\Models\Constants\CountryZone;
use WalkerChiu\MorphAddress\Models\Constants\AddressType;
use WalkerChiu\MorphAddress\Models\Entities\Address;
use WalkerChiu\MorphAddress\Models\Forms\AddressFormRequest;

class AddressFormRequestTest extends \Orchestra\Testbench\TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        //$this->loadLaravelMigrations(['--database' => 'mysql']);
        $this->loadMigrationsFrom(__DIR__ .'/../migrations');
        $this->withFactories(__DIR__ .'/../../src/database/factories');

        $this->request  = new AddressFormRequest();
        $this->rules    = $this->request->rules();
        $this->messages = $this->request->messages();
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
     * Unit test about Authorize.
     *
     * For WalkerChiu\MorphAddress\Models\Forms\AddressFormRequest
     * 
     * @return void
     */
    public function testAuthorize()
    {
        $this->assertEquals(true, 1);
    }

    /**
     * Unit test about Rules.
     *
     * For WalkerChiu\MorphAddress\Models\Forms\AddressFormRequest
     * 
     * @return void
     */
    public function testRules()
    {
        $faker = \Faker\Factory::create();


        // Give
        $attributes = [
            'type'          => $faker->randomElement(AddressType::getCodes()),
            'phone'         => $faker->phoneNumber,
            'email'         => $faker->email,
            'area'          => $faker->randomElement(CountryZone::getCodes()),
            'name'          => $faker->name,
            'address_line1' => $faker->address
        ];
        // When
        $validator = Validator::make($attributes, $this->rules, $this->messages); $this->request->withValidator($validator);
        $fails = $validator->fails();
        // Then
        $this->assertEquals(false, $fails);

        // Give
        $attributes = [
            'type'          => $faker->slug,
            'phone'         => $faker->phoneNumber,
            'email'         => $faker->email,
            'area'          => $faker->randomElement(CountryZone::getCodes()),
            'name'          => $faker->name,
            'address_line1' => $faker->address
        ];
        // When
        $validator = Validator::make($attributes, $this->rules, $this->messages); $this->request->withValidator($validator);
        $fails = $validator->fails();
        // Then
        $this->assertEquals(true, $fails);
    }
}
