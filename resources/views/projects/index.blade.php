@extends('layouts.app')

@section('content')
    <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between w-full items-end">
            <h2 class="text-sm text-grey font-normal">My Projects</h2>
            <a href="/projects/create" class="button">New Project</a>
        </div>
    </header>

    <main class="flex flex-wrap -mx-3">
        @forelse ($projects as $project)
            <div class="w-1/3 px-3 pb-6">
                @include('projects.card')
            </div>
        @empty
            <div>No projects yet</div>
        @endforelse
    </main>
@endsection
