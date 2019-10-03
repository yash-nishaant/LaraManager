<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Project;
use Facades\Tests\Setup\ProjectFactory;

class ProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */

    function guests_cannot_create_project()
    {
        $attributes = factory('App\Project')->raw();

        $this->post('/projects', $attributes)->assertRedirect('login');
    }

    /** @test */

    function guests_cannot_view_projects()
    {
        $this->get('/projects')->assertRedirect('login');
    }
    
    /** @test */

    function guests_cannot_view_single_project()
    {
        $project =  factory('App\Project')->create();

        $this->get($project->path())->assertRedirect('login');
    }

    /** @test */

    function guests_cannot_use_create_view()
    {
        $this->get('/projects/create')->assertRedirect('login');
    }

    /** @test */

    function guests_cannot_use_edit_view()
    {
        $project =  factory('App\Project')->create();
        
        $this->get($project->path().'/edit')->assertRedirect('login');
    }

    /** @test */

    function a_user_can_create_a_project()
    {
        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $attributes = factory(Project::class)->raw();

        $this->followingRedirects()->post('/projects', $attributes)
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }

    /** @test */

    function a_user_can_update_a_project()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->patch($project->path(), $attributes = [
            'notes' => 'Notes Changed',
            'title' => 'Title Changed',
            'description' => 'Description Changed'
        ])->assertRedirect($project->path());

        $this->get($project->path().'/edit')->assertOk();
        
        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */

    function a_user_can_update_a_projects_general_notes()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->patch($project->path(), $attributes = ['notes' => 'Notes Changed']);
        
        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */

    function a_user_can_view_their_project()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /** @test */

    function an_authenticated_user_cannot_view_the_projects_of_others()
    {
        $this->signIn();

        $project = factory('App\Project')->create();

        $this->get($project->path())->assertStatus(403);

    }

    /** @test */

    function a_user_can_see_all_the_projects_they_are_invited_to()
    {
        $user = $this->signIn();

        $project = tap(ProjectFactory::create())->invite($user);

        $this->get('/projects')->assertSee($project->title);
    }

    /** @test */

    function unauthorized_user_cannot_delete_project()
    {
        $project = ProjectFactory::create();

        $this->delete($project->path())
            ->assertRedirect('login');

        $user = $this->signIn();

        $this->delete($project->path())->assertStatus(403);

        $project->invite($user);

        $this->actingAs($user)->delete($project->path())->assertStatus(403);
    }
    
    /** @test */

    function an_user_can_delete_a_project()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->delete($project->path())
            ->assertRedirect('/projects');
        
        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    /** @test */

    function an_authenticated_user_cannot_update_the_projects_of_others()
    {
        $this->signIn();

        $project = factory('App\Project')->create();

        $this->patch($project->path(), [])->assertStatus(403);

    }

    /** @test */

    function a_project_requires_a_title()
    {
        $this->signIn();
        
        $attributes = factory('App\Project')->raw(['title' => '']);
        
        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */

    function a_project_requires_a_description()
    {
        $this->signIn();
        
        $attributes = factory('App\Project')->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }
}
