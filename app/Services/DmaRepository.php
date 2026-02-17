<?php

namespace App\Services;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class DmaRepository
{
    public const SETTING_KEY = 'dma_mapping';

    public function getMapping(): array
    {
        return Setting::getValue(self::SETTING_KEY, $this->defaultMapping());
    }

    public function saveMapping(array $mapping): void
    {
        if (isset($mapping['status_active_values']) && is_array($mapping['status_active_values'])) {
            $mapping['status_active_values'] = implode(',', $mapping['status_active_values']);
        }

        Setting::setValue(self::SETTING_KEY, $mapping);
    }

    public function validateMapping(array $mapping): array
    {
        $mapping = array_map(function ($value) {
            if (is_array($value)) {
                return implode(',', $value);
            }

            return is_string($value) ? trim($value) : $value;
        }, $mapping);

        $rules = [
            'table' => ['required', 'string', 'regex:/^[A-Za-z0-9_]+$/'],
            'username_column' => ['required', 'string', 'regex:/^[A-Za-z0-9_]+$/'],
            'phone_column' => ['required', 'string', 'regex:/^[A-Za-z0-9_]+$/'],
            'expiration_column' => ['required', 'string', 'regex:/^[A-Za-z0-9_]+$/'],
            'status_column' => ['nullable', 'string', 'regex:/^[A-Za-z0-9_]+$/'],
            'status_active_values' => ['nullable', 'string'],
        ];

        validator($mapping, $rules)->validate();

        $table = $mapping['table'];

        if (! Schema::connection('dma')->hasTable($table)) {
            throw ValidationException::withMessages(['table' => 'Table not found in DMA database.']);
        }

        $columns = Schema::connection('dma')->getColumnListing($table);
        $expected = [
            $mapping['username_column'],
            $mapping['phone_column'],
            $mapping['expiration_column'],
        ];

        if (! empty($mapping['status_column'])) {
            $expected[] = $mapping['status_column'];
        }

        foreach ($expected as $column) {
            if (! in_array($column, $columns, true)) {
                throw ValidationException::withMessages(['columns' => "Column {$column} not found in DMA table."]);
            }
        }

        $mapping['status_active_values'] = $this->normalizeActiveValues($mapping['status_active_values'] ?? null);

        return $mapping;
    }

    public function testSample(int $limit = 10): Collection
    {
        $mapping = $this->validateMapping($this->getMapping());

        $rows = $this->baseQuery($mapping)
            ->limit($limit)
            ->get();

        return $this->normalizeRows($rows, $mapping);
    }

    public function fetchExpiringOnDate(Carbon $date): Collection
    {
        $mapping = $this->validateMapping($this->getMapping());

        $rows = $this->baseQuery($mapping)
            ->whereDate($mapping['expiration_column'], '=', $date->toDateString())
            ->get();

        return $this->normalizeRows($rows, $mapping);
    }

    public function connectionHealthy(): bool
    {
        try {
            DB::connection('dma')->select('select 1');

            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    protected function baseQuery(array $mapping): Builder
    {
        return DB::connection('dma')->table($mapping['table'])->select($this->selectColumns($mapping));
    }

    protected function selectColumns(array $mapping): array
    {
        $columns = [
            $mapping['username_column'],
            $mapping['phone_column'],
            $mapping['expiration_column'],
        ];

        if (! empty($mapping['status_column'])) {
            $columns[] = $mapping['status_column'];
        }

        return $columns;
    }

    protected function normalizeRows(Collection $rows, array $mapping): Collection
    {
        return $rows->map(function ($row) use ($mapping) {
            $row = (array) $row;

            $username = Arr::get($row, $mapping['username_column']);
            $phone = Arr::get($row, $mapping['phone_column']);
            $expiryRaw = Arr::get($row, $mapping['expiration_column']);
            $statusRaw = $mapping['status_column'] ? Arr::get($row, $mapping['status_column']) : null;

            $expirationDate = $expiryRaw ? Carbon::parse($expiryRaw)->startOfDay() : null;

            $status = 'active';
            if ($mapping['status_column']) {
                $status = $this->isActiveStatus($statusRaw, $mapping['status_active_values']) ? 'active' : 'inactive';
            }

            return [
                'username' => $username,
                'phone' => $phone,
                'expiration_date' => $expirationDate,
                'status' => $status,
            ];
        })->filter(fn ($row) => ! empty($row['username']) && ! empty($row['phone']) && $row['expiration_date']);
    }

    protected function isActiveStatus(mixed $statusRaw, array $activeValues): bool
    {
        if (empty($activeValues)) {
            return (string) $statusRaw !== '' && (string) $statusRaw !== '0';
        }

        return in_array((string) $statusRaw, $activeValues, true);
    }

    protected function normalizeActiveValues(?string $values): array
    {
        if (! $values) {
            return [];
        }

        return collect(explode(',', $values))
            ->map(fn ($value) => trim($value))
            ->filter()
            ->values()
            ->all();
    }

    protected function defaultMapping(): array
    {
        return [
            'table' => 'rm_users',
            'username_column' => 'username',
            'phone_column' => 'phone',
            'expiration_column' => 'expiration',
            'status_column' => null,
            'status_active_values' => '',
        ];
    }
}
