<?php

namespace Dgharami\Eden\Components\Metrics;

use Carbon\CarbonImmutable;
use DateTime;
use DateTimeZone;
use Dgharami\Eden\Traits\HasDatabaseQueryFilters;
use Dgharami\Eden\Traits\Makeable;

/**
 * @method static make($filter = null)
 */
abstract class MetricValue
{
    use Makeable;
    use HasDatabaseQueryFilters;

    protected mixed $activeFilter = null;

    protected mixed $owner = null;

    protected function __construct($filter = null)
    {
        $owners = debug_backtrace();
        $this->owner = collect($owners)->first(function ($trace) {
            return $trace['function'] == 'calculate';
        })['object'] ?? null;

        $this->activeFilter = $filter ?? $this->owner->filter;
    }

    /**
     * Provide Filter Variable
     *
     * @param mixed $filter
     * @return $this
     */
    public function filter($filter)
    {
        $this->activeFilter = appCall($filter);
        return $this;
    }

    /**
     * Provide User Timezone
     *
     * @return string
     */
    public function getTimezone()
    {
        return config('app.timezone');
    }

    /**
     * @param string $userTimezone
     * @return int|float|double
     */
    public function getTimezoneOffset($userTimezone = null)
    {
        $timezoneOffset = function ($timezone) {
            return (new DateTime(CarbonImmutable::now()->format('Y-m-d H:i:s'), new DateTimeZone($timezone)))->getOffset() / 60 / 60;
        };

        if ($userTimezone) {
            $appOffset = $timezoneOffset(config('app.timezone'));
            $userOffset = $timezoneOffset($userTimezone);

            return $userOffset - $appOffset;
        }

        return 0;
    }

    /**
     * Provide the current date range to Calculate result
     *
     * @param  string|int  $key
     * @param  string  $timezone
     * @return array<CarbonImmutable>
     */
    protected function provideCurrentDateRange($key, $timezone)
    {
        if (!is_null($this->owner) && !is_null($dateRange = $this->owner->currentDateRange($key, $timezone)) && !empty($dateRange)) {
            return $dateRange;
        }

        if (is_numeric($key)) {
            return [
                CarbonImmutable::now($timezone)->subDays($key),
                CarbonImmutable::now($timezone),
            ];
        }

        // Default -> Today
        return [
            CarbonImmutable::now($timezone)->startOfDay(),
            CarbonImmutable::now($timezone)->endOfDay(),
        ];
    }

    /**
     * Provide the previous date range to Calculate result
     *
     * @param  string|int  $key
     * @param  string  $timezone
     * @return array<CarbonImmutable>
     */
    protected function providePreviousDateRange($key, $timezone)
    {
        if (!is_null($this->owner) && !is_null($dateRange = $this->owner->previousDateRange($key, $timezone)) && !empty($dateRange)) {
            return $dateRange;
        }

        if (is_numeric($key)) {
            return [
                CarbonImmutable::now($timezone)->subDays($key * 2),
                CarbonImmutable::now($timezone)->subDays($key)->subSecond(),
            ];
        }

        // Default -> Yesterday
        return [
            CarbonImmutable::now($timezone)->subDay()->startOfDay(),
            CarbonImmutable::now($timezone)->subDay()->endOfDay(),
        ];
    }

    /**
     * Calculate the current date range
     *
     * @param  string|int  $key
     * @param  string  $timezone
     * @return array<CarbonImmutable>
     */
    final public function getCurrentDateRange($key, $timezone = null)
    {
        $timezone = is_null($timezone) ? config('app.timezone') : $timezone;

        if (strtoupper($key) == 'TODAY') {
            return [
                CarbonImmutable::now($timezone)->startOfDay(),
                CarbonImmutable::now($timezone)->endOfDay(),
            ];
        }

        if (strtoupper($key) == 'YESTERDAY') {
            return [
                CarbonImmutable::now($timezone)->subDay()->startOfDay(),
                CarbonImmutable::now($timezone)->subDay()->endOfDay(),
            ];
        }

        if (strtoupper($key) == 'MTD') {
            return [
                CarbonImmutable::now($timezone)->startOfMonth(),
                CarbonImmutable::now($timezone),
            ];
        }

        if (strtoupper($key) == 'QTD') {
            return [
                CarbonImmutable::now($timezone)->startOfQuarter(),
                CarbonImmutable::now($timezone),
            ];
        }

        if (strtoupper($key) == 'YTD') {
            return [
                CarbonImmutable::now($timezone)->startOfYear(),
                CarbonImmutable::now($timezone),
            ];
        }

        return $this->provideCurrentDateRange($key, $timezone);
    }

    /**
     * Calculate the previous date range
     *
     * @param  string|int  $key
     * @param  string  $timezone
     * @return array<CarbonImmutable>
     */
    final public function getPreviousDateRange($key, $timezone = null)
    {
        $timezone = is_null($timezone) ? config('app.timezone') : $timezone;

        if (strtoupper($key) == 'TODAY') {
            return [
                CarbonImmutable::now($timezone)->subDay()->startOfDay(),
                CarbonImmutable::now($timezone)->subDay()->endOfDay(),
            ];
        }

        if (strtoupper($key) == 'YESTERDAY') {
            return [
                CarbonImmutable::now($timezone)->subDays(2)->startOfDay(),
                CarbonImmutable::now($timezone)->subDays(2)->endOfDay(),
            ];
        }

        if (strtoupper($key) == 'MTD') {
            return [
                CarbonImmutable::now($timezone)->subMonthWithoutOverflow()->startOfMonth(),
                CarbonImmutable::now($timezone)->subMonthWithoutOverflow(),
            ];
        }

        if (strtoupper($key) == 'QTD') {
            return [
                CarbonImmutable::now($timezone)->subQuarterWithOverflow()->startOfQuarter(),
                CarbonImmutable::now($timezone)->subQuarterWithOverflow()->subSecond(),
            ];
        }

        if (strtoupper($key) == 'YTD') {
            return [
                CarbonImmutable::now($timezone)->subYear()->startOfYear(),
                CarbonImmutable::now($timezone)->subYear(),
            ];
        }

        return $this->providePreviousDateRange($key, $timezone);
    }

    /**
     * @param array $dateRange
     * @return array
     */
    protected function formatQueryDateBetween(array $dateRange, $timezone)
    {
        return array_map(function ($datetime) use ($timezone) {
            if (! $datetime instanceof \DateTimeImmutable) {
                return $datetime->copy()->timezone($timezone);
            }

            return $datetime->timezone($timezone);
        }, $dateRange);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    abstract public function view();

}
