<?php

declare(strict_types=1);

/**
 * Contains the TestsDatabasePerformance trait.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-19
 *
 */

namespace Vanilo\Links\Tests;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

trait TestsDatabasePerformance
{
    protected array $modelsHydrated = ['*' => 0];

    protected array $queryCounter = ['default' => 0];

    private ?Dispatcher $dispatcher = null;

    protected function assertTotalModelsHydratedEquals(int $count): void
    {
        $this->assertEquals($count, $this->modelsHydrated['*']);
    }

    protected function assertTotalModelsHydratedIsLessThan(int $count): void
    {
        $this->assertLessThan($count, $this->modelsHydrated['*']);
    }

    protected function assertModelsHydratedEquals(int $count, string|array $models): void
    {
        $this->assertEquals($count, $this->hydratedModelCount($models));
    }

    protected function assertModelsHydratedIsLessThan(int $count, string|array $models): void
    {
        $this->assertLessThan($count, $this->hydratedModelCount($models));
    }

    protected function assertDbQueryCountWasExactly(int $count, string $counter = 'default'): void
    {
        $this->assertEquals($count, $this->queryCounter[$counter]);
    }

    protected function assertDbQueryCountWasLessThan(int $count, string $counter = 'default'): void
    {
        $this->assertLessThan($count, $this->queryCounter[$counter]);
    }

    protected function startCountingDBQueries(string $counter = 'default'): void
    {
        $this->queryCounter[$counter] = 0;
        DB::flushQueryLog();
        DB::enableQueryLog();
    }

    protected function stopCountingDBQueries(string $counter = 'default'): void
    {
        $this->queryCounter[$counter] = count(DB::getQueryLog());
        DB::disableQueryLog();
    }

    protected function startCountingModels(): void
    {
        // Reset the counter
        $this->modelsHydrated = ['*' => 0];

        $this->dispatcher()->listen('eloquent.retrieved:*', function ($event, $models) {
            foreach (array_filter($models) as $model) {
                $class = get_class($model);
                $this->modelsHydrated[$class] = ($this->modelsHydrated[$class] ?? 0) + 1;
                $this->modelsHydrated['*']++;
            }
        });
    }

    protected function stopCountingModels(): void
    {
        $this->dispatcher()->forget('eloquent.retrieved:*');
    }

    protected function hydratedModelCount(string|array $models): int
    {
        $result = 0;
        foreach (Arr::wrap($models) as $model) {
            $result += $this->modelsHydrated[$model] ?? 0;
        }

        return $result;
    }

    protected function dispatcher(): Dispatcher
    {
        return $this->dispatcher ?? $this->dispatcher = $this->app->make(Dispatcher::class);
    }
}
