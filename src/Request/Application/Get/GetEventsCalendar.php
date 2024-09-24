<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Application\Get;


final class GetEventsCalendar
{

    public function __construct()
    {

    }

    public function __invoke(GetEventsCalendarRequest $getEventsCalendarRequest)
    {
        $events = [];

        foreach ($getEventsCalendarRequest->requests() as $request) {
            $getRequestsResponse =  new GetEventsCalendarResponse($request);
            $events[] = $getRequestsResponse->jsonSerialize();
        }

        return json_encode($events);
    }
}
