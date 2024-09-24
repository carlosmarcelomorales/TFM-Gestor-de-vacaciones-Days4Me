<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Application\Calculate;

use TFM\HolidaysManagement\Holiday\Application\Get\GetHolidaysByRange;
use TFM\HolidaysManagement\Holiday\Application\Get\GetHolidaysByRangeRequest;
use TFM\HolidaysManagement\User\Application\Search\GetUser;
use TFM\HolidaysManagement\User\Application\Search\GetUserRequest;

final class CalculateAvailableDays
{
    private GetUser $getUserService;
    private GetHolidaysByRange $getHolidaysByRangeService;

    public function __construct(GetUser $getUserService, GetHolidaysByRange $getHolidaysByRangeService)
    {
        $this->getUserService = $getUserService;
        $this->getHolidaysByRangeService = $getHolidaysByRangeService;
    }

    public function __invoke(CalculateAvailableDaysRequest $request)
    {
        $permitRequest = false;
        $accumulatedDays = 0;
        $businessDays = false;

        $user = ($this->getUserService)(new GetUserRequest($request->user()));

        $availableDays = $user->availableDays();
        $workPlace = $user->departments()->workPlace();

        $daysToConsume = $request->startDateRequest()->diff($request->endDateRequest())->days + 1;

        if ($user->companies()->businessDays()) {

            $daysToConsume = $this->getBusinessDays($request->startDateRequest()->getTimestamp(),
                $request->endDateRequest()->getTimestamp());
            $businessDays = true;
        }

        $holidays = ($this->getHolidaysByRangeService)(new GetHolidaysByRangeRequest(
            [
                'dateStart' => $request->startDateRequest(),
                'dateEnd' => $request->startDateRequest(),
            ]
        ));
        $rangeHolidays = [];

        foreach ($holidays as $holiday) {
            $rangeHolidays = array_merge($rangeHolidays, $this->dateRange($holiday->startDay()->getTimeStamp(),
                $holiday->endDay()->getTimeStamp()));

        }

        $daysToConsume = $this->getDaysToConsumeWithoutHolidaysDays($request->startDateRequest()->getTimestamp(),
            $request->endDateRequest()->getTimestamp(),
            $rangeHolidays, $daysToConsume, $businessDays);

        $totalDaysToConsume = $daysToConsume;
        $newAccumulatedDays = 0;

        if ($workPlace->permitAccumulate()) {

            if (date("m") <= $workPlace->monthPermittedToAccumulate()) {

                $accumulatedDays = $user->accumulatedDays();

                $newAccumulatedDays = $accumulatedDays - $daysToConsume;
                $newAccumulatedDays = $newAccumulatedDays < 0 ? 0 : $newAccumulatedDays;

                $daysToConsume -= $accumulatedDays;
            }
        }

        $availableDays -= $daysToConsume > 0 ? $daysToConsume : 0;

        $permitRequest = ($availableDays >= 0);

        return new CalculateAvailableDaysResponse(
            $permitRequest,
            intval($availableDays),
            intval($newAccumulatedDays),
            intval($totalDaysToConsume)
        );
    }

    private function dateRange(int $first, int $last, string $step = '+1 day', string $format = 'Y-m-d')
    {
        $datesRange = [];
        $current = $first;

        while ($current <= $last) {
            $datesRange[] = date($format, $current);
            $current = strtotime($step, $current);
        }
        return $datesRange;
    }

    private function getDaysToConsumeWithoutHolidaysDays($startDate, $endDate, $holidays, $daysToConsume, $businessDays)
    {
        foreach ($holidays as $holiday) {

            $timeStamp = strtotime($holiday);

            if ($startDate <= $timeStamp && $timeStamp <= $endDate) {

                if (date("N", $timeStamp) >= 6) {

                    if ($businessDays) {
                        continue;
                    }
                }
                $daysToConsume--;
            }
        }

        return $daysToConsume;
    }

    private function getBusinessDays($startDate, $endDate)
    {
        $days = ($endDate - $startDate) / 86400 + 1;

        $noFullWeeks = floor($days / 7);
        $noRemainingDays = fmod($days, 7);

        $firstDayOfWeek = date("N", $startDate);
        $lastDayOfWeek = date("N", $endDate);

        if ($firstDayOfWeek <= $lastDayOfWeek) {

            if ($firstDayOfWeek <= 6 && 6 <= $lastDayOfWeek) {
                $noRemainingDays--;
            }

            if ($firstDayOfWeek <= 7 && 7 <= $lastDayOfWeek) {
                $noRemainingDays--;
            }

        } else {

            if ($firstDayOfWeek === 7) {
                $noRemainingDays--;

                if ($lastDayOfWeek === 6) {
                    $noRemainingDays--;
                }

            } else {
                $noRemainingDays -= 2;
            }
        }

        $workingDays = $noFullWeeks * 5;

        if ($noRemainingDays > 0) {
            $workingDays += $noRemainingDays;
        }

        return $workingDays;
    }
}
