<?php


namespace App\Services;


use App\Models\DicModel;

class DicService
{
    /**
     * @var DicModel
     */
    private $model;

    /**
     * DicServiceImpl constructor.
     * @param DicModel $model
     */
    public function __construct(DicModel $model)
    {
        $this->model = $model;
    }

    public function all(): array
    {
        return \Cache::get('dic') ?? $this->refresh();
    }

    public function refresh(): array
    {
        $dic = $this->getByDb();
        \Cache::forever('dic', $dic);
        return $dic;
    }

    public function getByDb(): array
    {
        return $this->model::query()->get()->toArray();
    }

    public function getDefaultEntry(string $en_name): array
    {
        foreach ($this->getEntry($en_name) as $entry) {
            if ($entry['is_default']) return $entry;
        }

        return [];
    }

    public function getDefaultEntryIndex(string $en_name): ?int
    {
        return $this->getDefaultEntry($en_name)['index'] ?? null;
    }

    public function getEntry(string $en_name, string $key = null): array
    {
        foreach ($this->all() as $dic) {
            if ($dic['en_name'] !== $en_name) continue;
            if (!$key) return $dic['entry'];
            foreach ($dic['entry'] as $entry) {
                if ($entry['key'] === $key) return $entry;
            }
        }

        return [];
    }

    public function getEntryIndex(string $en_name, string $key): ?int
    {
        return $this->getEntry($en_name, $key)['index'] ?? null;
    }

    public function IsExistEntry(string $en_name, int $index)
    {
        $entry = $this->getEntry($en_name);
        foreach ($entry as $item) {
            if ($item['index'] === $index) return true;
        }
        return false;
    }
}