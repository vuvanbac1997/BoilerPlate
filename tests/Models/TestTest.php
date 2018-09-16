<?php namespace Tests\Models;

use App\Models\Test;
use Tests\TestCase;

class TestTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Test $test */
        $test = new Test();
        $this->assertNotNull($test);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Test $test */
        $testModel = new Test();

        $testData = factory(Test::class)->make();
        foreach( $testData->toFillableArray() as $key => $value ) {
            $testModel->$key = $value;
        }
        $testModel->save();

        $this->assertNotNull(Test::find($testModel->id));
    }

}
