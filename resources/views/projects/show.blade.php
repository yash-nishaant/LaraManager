@extends('layouts.app')

@section('content')
<header class="flex items-center mb-3 py-4">
    <div class="flex justify-between w-full items-end">
        <p class="text-sm text-grey font-normal mb-6">
            <a href="/projects" class="text-grey text-sm font-normal no-underline">My Projects </a> / {{$project->title}}
        </p>

        <div class="flex items-center">
            @foreach ($project->members as $member)
                <div class="flex flex-col"><img 
                    src="{{ gravatarUrl($member->email) }}" 
                    alt="{{ $member->name }}'s avatar" 
                    class="rounded-full w-8 mr-2">
                <span class="text-sm"><strong>{{$member->name}}</strong></span></div>
            @endforeach

            <div class="flex flex-col"><img 
                src="{{ gravatarUrl($project->owner->email) }}" 
                alt="{{ $project->owner->name }}'s avatar" 
                class="rounded-full w-8 mr-2">
            <span class="text-sm underline"><strong>{{$project->owner->name}}</strong></span></div>
            <a href="{{$project->path().'/edit'}}" class="button ml-4 mb-5">Edit Project</a>
        </div>
    </div>
</header>
<main>
    <div class="lg: flex -mx-3">
        <div class="lg: w-3/4 px-3 mb-6">
            <div class="mb-3">
                <h2 class="text-lg text-grey font-normal mb-3">Tasks</h2>
                @foreach($project->tasks as $task)
                    <div class="card mb-3">
                        <form action="{{$task->path()}}" method="post">
                            @method('PATCH')
                            @csrf

                            <div class="flex">
                                <input type="text" name="body" value="{{$task->body}}" class="w-full">
                                <input type="checkbox" name="completed" onChange="this.form.submit()"  {{$task->completed ? 'checked' : ''}}>
                            </div>
                        </form>
                    </div>   
                @endforeach

                <div class="card mb-3">
                    <form action="{{$project->path() . '/tasks'}}" method="post">
                        @csrf
                        <input type="text" placeholder="Add a New Task" class="w-full" name="body">
                    </form>
                </div>
            </div>

            <div>
                <h2 class="text-lg text-grey font-normal mb-3">General Notes</h2>
                <form action="{{$project->path()}}" method="POST">
                    @csrf
                    @method('PATCH')
                    <textarea 
                        name="notes"
                        class="card w-full mb-3" 
                        style="min-height: 200px;" 
                        placeholder="Your notes here..."
                    >{{$project->notes}}</textarea>
                    <button type="submit" class="button">Save</button>
                </form>

                @include('errors')
            </div>
        </div>
        <div class="lg:w-1/4 px-3">
            @include('projects.card')

            @include('projects.activity.card')

            @can('manage', $project)
                @include('projects.invite')
            @endcan
        </div>                
    </div>
</main>

@endsection