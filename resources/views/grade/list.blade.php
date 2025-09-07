@extends('layouts.admin')
@section('title','Class')
@section('content')

<div class="container">
    <h2 class="fw-bold mb-4">Grade Reports</h2>
{{-- Search --}}
<div class="row">
    <div class="col-md-10">
        <div class="form-group">
            <form method="get" action="{{ url('/grade') }}">
                <input type="hidden" name="term_id" value="{{ $selectedTermId }}">
                <input type="hidden" name="gen_id" value="{{ $selectedGenId }}">
                <input type="hidden" name="major_id" value="{{ $selectedMajorId }}">
                <div class="input-group">
                    <input type="text" class="form-controll" name="search" placeholder="Search..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-2">
        <a href="{{url('/grade/add')}}" type="button" class="btn btn-primary mb-2">Add New</a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <label for="term">Select Term:</label>
        <select name="term_id" id="term" class="form-select" style="max-width: 300px;">
            <option value="">Select Year And Term</option>
            @foreach($terms as $term)
                <option value="{{ $term->id }}" {{ $term->id == $selectedTermId ? 'selected' : '' }}>
                    {{ $term->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label for="generation">Select Generation:</label>
        <select name="gen_id" id="generation" class="form-select" style="max-width: 300px;">
            <option value="">Select Generation</option>
            @foreach($generations as $generation)
                <option value="{{ $generation->id }}" {{ $generation->id == $selectedGenId ? 'selected' : '' }}>
                    {{ $generation->gen }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label for="major_id">Select Major:</label>
        <select class="form-select" id="major_id" name="major_id" style="max-width: 300px;" {{ !$selectedGenId ? 'disabled' : '' }}>
            <option value="">Select Major</option>
            @foreach($majors as $major)
                <option value="{{ $major->id }}" {{ $selectedMajorId == $major->id ? 'selected' : '' }}>
                    {{ $major->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

    <div class="card">
        <div class="card-body">
            <h4 class="mb-4">All Students Report for Selected Term, Generation and Major</h4>
    
            @if($selectedTermId && $selectedGenId && $studentGrades->count() > 0)
                @foreach($studentGrades as $studentData)
                    <div class="row mb-4">
                        <div class="col-md-10">
                            <h3>Student Name</h3>
                            <h5 class="mb-3">{{ $studentData['student']->first_name }} {{ $studentData['student']->last_name }}</h5>
                        </div>
                    <div class="col-md-2 d-flex justify-content-end align-items-center">
                        <a href="{{ route('grade.edit', ['student' => $studentData['student']->id, 'term_id' => $selectedTermId]) }}"
                            class="btn btn-warning btn-sm me-1">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                        <a href="{{ route('grade.show', ['student' => $studentData['student']->id, 'term_id' => $selectedTermId]) }}"
                            class="btn btn-danger btn-sm">
                            <i class="fa-solid fa-trash"></i> Delete
                        </a>
                    </div>

                    </div>
    
                    @if($studentData['subjectScores']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr style="background-color: #f8f9fa;">
                                        <th>Subject</th>
                                        <th class="text-center">Midterm</th>
                                        <th class="text-center">Final</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($studentData['subjectScores'] as $score)
                                    @php
                                        $bgColor = $score->total < 60 ? '#db5c5c' : ($loop->iteration % 2 == 0 ? '#e6f3f8' : '#e8f8e8');
                                    @endphp
                                    <tr style="background-color: {{ $bgColor }}">
                                        <td>{{ $score->subject->name }}</td>
                                        <td class="text-center">{{ number_format($score->midterm, 1) }}</td>
                                        <td class="text-center">{{ number_format($score->final, 1) }}</td>
                                        <td class="text-center">{{ number_format($score->total, 1) }}</td>
                                        <td class="text-center">{{ $score->grade }}</td>
                                    </tr>
                                    @endforeach
                                    
                                    <tr>
                                        <td><strong>Total Average</strong></td>
                                        <td colspan="2"></td>
                                        <td class="text-center">
                                            <strong>{{ $studentData['termGrade'] ? number_format($studentData['termGrade']->average_score, 1) : 'N/A' }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <strong>{{ $studentData['termGrade']->grade ?? 'N/A' }}</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning">No subject scores found for this student.</div>
                    @endif

    
                    <hr class="mt-4">
                @endforeach
    
            @elseif($selectedTermId && $selectedGenId)
                <div class="alert alert-warning mt-3">No data found for the selected term, generation and major.</div>
            @elseif($selectedTermId && !$selectedGenId)
                <div class="alert alert-info mt-3">Please select a generation to view student grades.</div>
            @elseif(!$selectedTermId && $selectedGenId)
                <div class="alert alert-info mt-3">Please select a term to view student grades.</div>
            @else
                <div class="alert alert-info mt-3">Please select both term and generation to view student grades.</div>
            @endif
        </div>
    </div>
    
    {{-- Only show pagination if there are students --}}
    @if($selectedTermId && $selectedGenId && $students->count() > 0)
        {{$students->appends(['term_id' => $selectedTermId, 'gen_id' => $selectedGenId, 'major_id' => $selectedMajorId])->links()}}
    @endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const termSelect = document.getElementById('term');
    const generationSelect = document.getElementById('generation');
    const majorSelect = document.getElementById('major_id');
    
    termSelect.addEventListener('change', function() {
        const genId = generationSelect.value || '';
        const majorId = majorSelect.value || '';
        window.location.href = `/grade?term_id=${this.value}&gen_id=${genId}&major_id=${majorId}`;
    });
    
    generationSelect.addEventListener('change', function() {
        const termId = termSelect.value || '';
        
        // Clear major selection when generation changes
        majorSelect.innerHTML = '<option value="">Select Major</option>';
        majorSelect.disabled = !this.value;
        
        // Redirect to update gen_id and clear major_id
        window.location.href = `/grade?term_id=${termId}&gen_id=${this.value}&major_id=`;
    });

    majorSelect.addEventListener('change', function() {
        const termId = termSelect.value || '';
        const genId = generationSelect.value || '';
        window.location.href = `/grade?term_id=${termId}&gen_id=${genId}&major_id=${this.value}`;
    });
});
</script>
@endsection