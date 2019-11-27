<?php


namespace App\Imports;


use Maatwebsite\Excel\Concerns\ToArray;

class TopicImport implements ToArray
{
    public function array(array $array)
    {
        return $array;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}