@extends('layouts.app')

@section('content')
    <h2>Complaints List</h2>
    @foreach($complaints as $complaint)
        <div>
            <p>Description: {{ $complaint->description }}</p>
            <a href="{{ route('complaints.show', $complaint->complaint_id) }}">View Details</a>
        </div>
    @endforeach
@endsection
