<?php

namespace Pastock\Pawork;

class Pawork
{
    /**
     * @var array
     */
    private $workingDay = [];

    /**
     * @var array
     */
    private $holiday= [];

    /**
     * @var bool
     */
    private $withoutDatabase;

    public function __construct(bool $withoutDatabase = false)
    {
        $this->withoutDatabase = $withoutDatabase;
    }

    /**
     * @param string[] $date YYYY-MM-DD style
     * @return static
     */
    public function addHoliday(string ...$date): Pawork
    {
        foreach ($date as $item) {
            $this->holiday[] = $item;
        }

        return $this;
    }

    /**
     * @param string[] $date YYYY-MM-DD style
     * @return static
     */
    public function addWorkingDay(string ...$date): Pawork
    {
        foreach ($date as $item) {
            $this->workingDay[] = $item;
        }

        return $this;
    }

    /**
     * @param string $date YYYY-MM-DD style
     * @return bool
     */
    public function isHoliday(string $date): bool
    {
        $result = false;

        // Check default database
        if (!$this->withoutDatabase && static::findInArray($date, Database::all())) {
            $result = true;
        }

        // Check custom holiday list.
        if (self::findInArray($date, $this->holiday)) {
            $result = true;
        }

        // Check list to exclude
        if (self::findInArray($date, $this->workingDay)) {
            $result = false;
        }

        return $result;
    }

    public function isWorkingDay(string $date): bool
    {
        return !$this->isHoliday($date);
    }

    /**
     * Clean all customize
     */
    public function clean()
    {
        $this->workingDay = [];
    }

    /**
     * @param string $date
     * @return bool
     */
    private static function findInArray(string $date, array $items): bool
    {
        return array_search($date, $items, true) !== false;
    }
}
