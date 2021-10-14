<?php

namespace Tests\Benchmark;

use MilesChou\TwnicIp\TwnicIp;
use Pastock\Pawork\Pawork;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\Revs;

class TwnicIpBench
{
    /**
     * @Revs(10000)
     * @Iterations(5)
     */
    public function benchIsTaiwanForNiceCase()
    {
        $target = new Pawork();
        $target->isHoliday('2017-01-01');
    }

    /**
     * @Revs(10000)
     * @Iterations(5)
     */
    public function benchIsTaiwanForWorstCase()
    {
        $target = new Pawork();
        $target->isHoliday('2030-01-01');
    }
}
