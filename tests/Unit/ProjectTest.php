<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use SebastianBergmann\FileIterator\Factory;

use App\Project;
use App\User;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */

    function it_has_a_path()
    {
        $project = factory('App\Project')->create();

        $this->assertEquals('/projects/' . $project->id, $project->path());
    }

    /** @test */

    function it_belongs_to_an_owner()
    {
        $project = factory('App\Project')->create();

        $this->assertInstanceOf('App\User', $project->owner);
    }

    /** @test */

    function it_can_add_a_task()
    {
        $project = factory('App\Project')->create();

        $task = $project->addTask('Test task');

        $this->assertCount(1, $project->tasks);
        $this->assertTrue($project->tasks->contains($task));
    }

    /** @task */

    function it_can_invite_a_user()
    {
        $project = factory(Project::class)->create();

        $project->invite($user = Factory(User::class)->create());

        $this->assertTrue($project->members->contains($user));
    }
}
