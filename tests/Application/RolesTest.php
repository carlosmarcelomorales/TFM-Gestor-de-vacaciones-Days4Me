<?php
declare(strict_types=1);

namespace TFM\Tests\HolidaysManagement\Application;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use TFM\HolidaysManagement\Role\Application\Search\GetRoleRequest;
use TFM\HolidaysManagement\Role\Domain\Model\Aggregate\Role;

final class RolesTest extends WebTestCase
{

    /**
     * @test
     */
    public function itShouldGetAllExistsRoles()
    {
        self::bootKernel();

        $roles = self::$container->get('tactician.commandbus.default')->handle(
            new GetRoleRequest(['name' => 'ROLE_USER']));

        $this->assertInstanceOf(Role::class, $roles);
    }

}
