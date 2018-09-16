<?php  namespace Tests\Controllers\Admin;

use Tests\TestCase;

class TestControllerTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Http\Controllers\Admin\TestController $controller */
        $controller = \App::make(\App\Http\Controllers\Admin\TestController::class);
        $this->assertNotNull($controller);
    }

    public function setUp()
    {
        parent::setUp();
        $authUser = \App\Models\AdminUser::first();
        $this->be($authUser, 'admins');
    }

    public function testGetList()
    {
        $response = $this->action('GET', 'Admin\TestController@index');
        $this->assertResponseOk();
    }

    public function testCreateModel()
    {
        $this->action('GET', 'Admin\TestController@create');
        $this->assertResponseOk();
    }

    public function testStoreModel()
    {
        $test = factory(\App\Models\Test::class)->make();
        $this->action('POST', 'Admin\TestController@store', [
                '_token' => csrf_token(),
            ] + $test->toArray());
        $this->assertResponseStatus(302);
    }

    public function testEditModel()
    {
        $test = factory(\App\Models\Test::class)->create();
        $this->action('GET', 'Admin\TestController@show', [$test->id]);
        $this->assertResponseOk();
    }

    public function testUpdateModel()
    {
        $faker = \Faker\Factory::create();

        $test = factory(\App\Models\Test::class)->create();

        $name = $faker->name;
        $id = $test->id;

        $test->name = $name;

        $this->action('PUT', 'Admin\TestController@update', [$id], [
                '_token' => csrf_token(),
            ] + $test->toArray());
        $this->assertResponseStatus(302);

        $newTest = \App\Models\Test::find($id);
        $this->assertEquals($name, $newTest->name);
    }

    public function testDeleteModel()
    {
        $test = factory(\App\Models\Test::class)->create();

        $id = $test->id;

        $this->action('DELETE', 'Admin\TestController@destroy', [$id], [
                '_token' => csrf_token(),
            ]);
        $this->assertResponseStatus(302);

        $checkTest = \App\Models\Test::find($id);
        $this->assertNull($checkTest);
    }

}
