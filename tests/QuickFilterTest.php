<?php

namespace Avikuloff\QuickFilter\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase;

class EloquentBuilderFilterMacroTest extends TestCase
{
    protected $data;

    public function testFilterMacroWithFilters()
    {
        $builder = UserModelStub::query();
        $builder = $builder->filter($this->data, ['name']);
        $value = $builder->getQuery()->wheres[0]['value'];

        $this->assertSame('John Doe', $value);
    }

    public function testFilterMacroWithoutFilters()
    {
        $builder = UserModelStub::query();
        $builder = $builder->filter($this->data);
        $value = $builder->getQuery()->wheres[0]['value'];

        $this->assertSame('John Doe', $value);
    }

    public function testFilterMacroWithInvalidFilterKey()
    {
        $builder = UserModelStub::query();
        $builder = $builder->filter($this->data, ['invalidFilterKey', 'name']);
        $value = $builder->getQuery()->wheres[0]['value'];

        $this->assertSame('John Doe', $value);
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->data = ['name' => 'John Doe'];
    }

    protected function getPackageProviders($app)
    {
        return ['Avikuloff\QuickFilter\QuickFilterServiceProvider'];
    }

    /**
     * Define environment setup.
     *
     * @param Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set(
            'quickfilter.groups.Avikuloff\QuickFilter\Tests\UserModelStub.name',
            'Avikuloff\QuickFilter\Tests\NameFilterStub'
        );
    }
}
