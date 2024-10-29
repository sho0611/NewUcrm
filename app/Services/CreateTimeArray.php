<?php
namespace App\Services;

class CreateTimeArray 
{
    /**
     * 15分間隔の時間の配列を作成
     *
     * @return array
     */
    public function createTimeArray(): array
    {
        $startTime = new \DateTime('09:00');
        $endTime = new \DateTime('18:00');

        $interval = new \DateInterval('PT15M');
        $times = [];
        while ($startTime < $endTime) 
        {
            $times[] = $startTime->format('H:i');
            $startTime->add($interval);
        }
        return $times;
    }
}
