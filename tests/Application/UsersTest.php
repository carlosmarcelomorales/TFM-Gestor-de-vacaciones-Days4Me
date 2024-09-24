<?php
declare(strict_types=1);

namespace TFM\Tests\HolidaysManagement\Application;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use TFM\HolidaysManagement\User\Application\Search\GetUserRequest;
use TFM\HolidaysManagement\User\Domain\Model\Aggregate\User;
use TFM\HolidaysManagement\User\Domain\Model\Exception\UserNotExistsException;

final class UsersTest extends WebTestCase
{
    private string $fakeIncorrectUser;
    private string $fakeCorrectUser;

    protected function setUp(): void
    {
        $this->fakeIncorrectUser = '62ad27ed-afd7-44bc-875b-cb5736781f75';
        $this->fakeCorrectUser = '7008dd77-a5d8-41e1-a056-d263924bc428';
    }

    /**
     * @test
     */
    public function itShouldAuthenticateWhenTheUserExist()
    {
        self::bootKernel();

        $user = self::$container->get('tactician.commandbus.default')->handle(
            new GetUserRequest($this->fakeCorrectUser));

        $this->assertInstanceOf(User::class, $user);
    }

    /**
     * @test
     */
    public function itShouldThrowAnExceptionWhenTheUserDoesNotExist()
    {
        $this->expectException(UserNotExistsException::class);

        self::bootKernel();

        self::$container->get('tactician.commandbus.default')->handle(
            new GetUserRequest($this->fakeIncorrectUser));
    }
}
