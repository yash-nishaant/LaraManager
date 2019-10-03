<?php

namespace Tests\Unit;

use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection;

use App\User;

class UserTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */

    function a_user_has_projects()
    {
        $user = factory('App\User')->create();

        $this->assertInstanceOf(Collection::class, $user->projects);
    }  

    /** @test */

    function a_user_has_accessible_projects()
    {
        $user = $this->signIn();
        
        ProjectFactory::ownedBy($user)->create();

        $this->assertCount(1, $user->accessibleProjects());

        $anotherUser = factory(User::class)->create();
        $yetAnotherUser = factory(User::class)->create();

        $project = tap(ProjectFactory::ownedBy($anotherUser)->create())->invite($yetAnotherUser);

        $this->assertCount(1, $user->accessibleProjects());

        $project->invite($user);

        $this->assertCount(2, $user->accessibleProjects());
    }
}
