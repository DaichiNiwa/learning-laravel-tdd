<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\VacancyLevel;

class VacancyLevelTest extends TestCase
{
    /**
     * @dataProvider dataMark
     * @param int $remainingCount
     * @param string $expectedMark
     */
    public function testMark(int $remainingCount, string $expectedMark)
    {
        $level = new VacancyLevel($remainingCount);
        $this->assertSame($expectedMark, $level->mark());
    }

    /**
     * @dataProvider dataSlug
     * @param int $remainingCount
     * @param string $expectedSlug
     */
    public function testSlug(int $remainingCount, string $expectedSlug)
    {
        $level = new VacancyLevel($remainingCount);
        $this->assertSame($expectedSlug, $level->slug());
    }

    public function dataMark()
    {
        return [
            '空きなし' => [
                'remainingCount' => 0,
                'expectedMark' => '×',
            ],
            '残りわずか' => [
                'remainingCount' => 4,
                'expectedMark' => '△',
            ],
            '空き十分　' => [
                'remainingCount' => 5,
                'expectedMark' => '◎',
            ],
        ];
    }

    public function dataSlug()
    {
        return [
            '空きなし' => [
                'remainingCount' => 0,
                'expectedSlug' => 'empty',
            ],
            '残りわずか' => [
                'remainingCount' => 4,
                'expectedSlug' => 'few',
            ],
            '空き十分　' => [
                'remainingCount' => 5,
                'expectedSlug' => 'enough',
            ],
        ];
    }
}
