<?php namespace Tests\Repositories;

use App\Models\Test;
use Tests\TestCase;

class TestRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\TestRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\TestRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $tests = factory(Test::class, 3)->create();
        $testIds = $tests->pluck('id')->toArray();

        /** @var  \App\Repositories\TestRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\TestRepositoryInterface::class);
        $this->assertNotNull($repository);

        $testsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(Test::class, $testsCheck[0]);

        $testsCheck = $repository->getByIds($testIds);
        $this->assertEquals(3, count($testsCheck));
    }

    public function testFind()
    {
        $tests = factory(Test::class, 3)->create();
        $testIds = $tests->pluck('id')->toArray();

        /** @var  \App\Repositories\TestRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\TestRepositoryInterface::class);
        $this->assertNotNull($repository);

        $testCheck = $repository->find($testIds[0]);
        $this->assertEquals($testIds[0], $testCheck->id);
    }

    public function testCreate()
    {
        $testData = factory(Test::class)->make();

        /** @var  \App\Repositories\TestRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\TestRepositoryInterface::class);
        $this->assertNotNull($repository);

        $testCheck = $repository->create($testData->toFillableArray());
        $this->assertNotNull($testCheck);
    }

    public function testUpdate()
    {
        $testData = factory(Test::class)->create();

        /** @var  \App\Repositories\TestRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\TestRepositoryInterface::class);
        $this->assertNotNull($repository);

        $testCheck = $repository->update($testData, $testData->toFillableArray());
        $this->assertNotNull($testCheck);
    }

    public function testDelete()
    {
        $testData = factory(Test::class)->create();

        /** @var  \App\Repositories\TestRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\TestRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($testData);

        $testCheck = $repository->find($testData->id);
        $this->assertNull($testCheck);
    }

}
