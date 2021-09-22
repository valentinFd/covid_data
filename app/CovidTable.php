<?php

namespace App;

use League\Csv\Reader;
use League\Csv\Statement;

class CovidTable
{
    private const FILE = "https://data.gov.lv/dati/dataset/5d0c9a64-b7b2-494e-b77d-22d48225791b/resource/8ea0ee31-1bea-4336-bbe4-2e66ccdadd1b/download/covid_19_valstu_saslimstibas_raditaji.csv";

    private array $data;

    public function getData(): array
    {
        return $this->data;
    }

    public function __construct()
    {
        $this->data = [];
    }

    public function load(): void
    {
        $csv = Reader::createFromString(file_get_contents(self::FILE), "r");
        $csv->setHeaderOffset(0);
        $csv->setDelimiter(';');
        $records = Statement::create()->process($csv);
        foreach ($records as $record)
        {
            $this->data[] = new CountryCovidDataRow(...array_values($record));
        }
    }

    public function search(string $input, string $column): array
    {
        $searchResults = [];
        if (in_array($column, CountryCovidDataRow::getPropertyNames()))
        {
            $getProperty = "get" . $column;
            foreach ($this->data as $row)
            {
                if (strtoupper($input) === strtoupper($row->$getProperty()))
                {
                    $searchResults[] = $row;
                }
            }
        }
        return $searchResults;
    }

    public function print(array $data): void
    {
        echo "<table class='table'>";
        echo "<thead>";
        echo "<tr>";
        foreach (CountryCovidDataRow::getPropertyNames() as $propertyName)
        {
            echo "<th>";
            $propertyName = strlen($propertyName) > 26 ? substr($propertyName, 0, 24) . ".." : $propertyName;
            echo $propertyName;
            echo "</th>";
        }
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($data as $row)
        {
            if (is_a($row, "App\CountryCovidDataRow"))
            {
                echo "<tr>";
                foreach ((array)$row as $element)
                {
                    echo "<td>";
                    $element = strlen($element) > 26 ? substr($element, 0, 24) . ".." : $element;
                    echo $element;
                    echo "</td>";
                }
                echo "</tr>";
            }
        }
        echo "</tbody>";
        echo "</table>";
    }
}
