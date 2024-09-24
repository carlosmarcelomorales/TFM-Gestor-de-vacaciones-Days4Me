<?php
declare(strict_types=1);


namespace TFM\Tests\HolidaysManagement\Application;

use DateTimeImmutable;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use TFM\HolidaysManagement\Holiday\Application\Create\CreateHolidayRequest;
use TFM\HolidaysManagement\Holiday\Application\Get\GetHolidayRequest;
use TFM\HolidaysManagement\Holiday\Domain\Exception\HolidayIdNotExistsException;
use TFM\HolidaysManagement\Holiday\Domain\Exception\HolidayServiceIsNotAvailableException;
use TFM\HolidaysManagement\Holiday\Domain\Model\Aggregate\Holiday;

final class HolidaysTest extends WebTestCase
{
    private string $holidayName;
    private DateTimeImmutable $startDate;
    private DateTimeImmutable $endDate;
    private string $workPlace;
    private string $fakeHolidayId;

    protected function setUp(): void
    {
        $randomDate = mt_rand(15, time());

        $this->holidayName = 'Christmas';
        $this->workPlace = '72856d5a-06ac-4ba9-aafd-4c98764ad21c';
        $this->startDate = new DateTimeImmutable(date("d M Y", $randomDate));
        $this->endDate = new DateTimeImmutable(date("d M Y", $randomDate));
        $this->fakeHolidayId = 'cac0edfe-a52d-453a-856f-75ec730932ac';
    }

    /**
     * @test
     */
    public function shouldCreateHoliday()
    {

        self::bootKernel();

        $holiday = self::$container->get('tactician.commandbus.default')->handle(new CreateHolidayRequest(
            $this->holidayName,
            $this->startDate,
            $this->endDate,
            $this->workPlace
        ));


        $this->assertInstanceOf(Holiday::class, $holiday);
    }

    /**
     * @test
     */
    public function showHolidayIdNotExistsException()
    {
        $this->expectException(HolidayIdNotExistsException::class);
        self::bootKernel();

        self::$kernel->getContainer()->get('tactician.commandbus.default')->handle(
            new GetHolidayRequest($this->fakeHolidayId)
        );


    }
}
