<?php

namespace Avikuloff\QuickFilters\Tests;

use Avikuloff\QuickFilters\QuickFilters;
use Avikuloff\QuickFilters\QuickFiltersServiceProvider;
use Avikuloff\QuickFilters\Tests\Stubs\EmailFilterStub;
use Avikuloff\QuickFilters\Tests\Stubs\NameFilterStub;
use Avikuloff\QuickFilters\Tests\Stubs\UserModelStub;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase;

class QuickFiltersTest extends TestCase
{
    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    /**
     * @var array
     */
    protected $data;

    public function testFilterMacroWithFilters()
    {
        $builder = $this->builder->filter($this->data, [NameFilterStub::class]);
        $value = $builder->getQuery()->wheres[0]['value'];

        $this->assertSame('John Doe', $value);
    }

    public function testFilterMacroWithoutFilters()
    {
        $builder = $this->builder->filter($this->data);
        $name = $builder->getQuery()->wheres[0]['value'];
        $email = $builder->getQuery()->wheres[1]['value'];

        $this->assertSame('John Doe', $name);
        $this->assertSame('johndoe@example.com', $email);
    }

    public function testFilterMacroWithInvalidFilters()
    {
        $builder = $this->builder->filter($this->data, ['invalid', NameFilterStub::class, 'otherInvalidFilter']);
        $value = $builder->getQuery()->wheres[0]['value'];

        $this->assertSame('John Doe', $value);
    }

    public function testFilterExcept()
    {
        $filter = (new QuickFilters($this->builder))->except($this->data, [NameFilterStub::class]);
        $column = $filter->getQuery()->wheres[0]['column'];

        $this->assertArrayNotHasKey(1, $filter->getQuery()->wheres);
        $this->assertNotSame('name', $column);
    }

    public function testFilterExceptWithInvalidFilterKeys()
    {
        $filter = (new QuickFilters($this->builder))
            ->except($this->data, ['invalidFilterKey', NameFilterStub::class, 'otherInvalidFilterKey']);
        $column = $filter->getQuery()->wheres[0]['column'];

        $this->assertArrayNotHasKey(1, $filter->getQuery()->wheres);
        $this->assertNotSame('name', $column);
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->builder = UserModelStub::query();

        $this->data = ['name' => 'John Doe', 'email' => 'johndoe@example.com'];
    }

    protected function getPackageProviders($app)
    {
        return [QuickFiltersServiceProvider::class];
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
            'quickfilters.groups.user_model_stubs.name',
            NameFilterStub::class
        );
        $app['config']->set(
            'quickfilters.groups.user_model_stubs.email',
            EmailFilterStub::class
        );
    }
}
