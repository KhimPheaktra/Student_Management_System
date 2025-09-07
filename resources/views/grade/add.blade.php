@extends('layouts.admin')
@section('title','Grade')
@section('content')

<h1>Grade</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Add Grade</li>
    </ol>
    <form method="post" action="{{ route('grade.create') }}" enctype="multipart/form-data">
        @csrf
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Add Subject Scores</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="gen_id" class="form-label">Generation</label>
                        <select class="form-select" id="gen_id" name="gen_id" required>
                            <option value="">Select Generation</option>
                            @foreach($generations as $generation)
                                <option value="{{ $generation->id }}">{{ $generation->gen }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="major_id" class="form-label">Major</label>
                        <select class="form-select" id="major_id" name="major_id" required disabled>
                            <option value="">Select Major</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="student_id" class="form-label">Student</label>
                        <select class="form-select" id="student_id" name="student_id" required disabled>
                            <option value="">Select Student</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="term_id" class="form-label">Term</label>
                        <select class="form-select" id="term_id" name="term_id" required disabled>
                            <option value="">Select Term</option>
                            @foreach($terms as $term)
                                <option value="{{ $term->id }}">{{ $term->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr class="my-4">
                <h6 class="mb-3">Subject Scores</h6>

                <div id="subjects-container">
                    <div class="text-center py-4 text-muted" id="no-subjects-message">
                        Please select a generation, major, student, and term to view subjects
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary" type="submit" id="submit-btn" disabled>Save All Scores</button>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const genSelect = document.getElementById('gen_id');
            const majorSelect = document.getElementById('major_id');
            const studentSelect = document.getElementById('student_id');
            const termSelect = document.getElementById('term_id');
            const subjectsContainer = document.getElementById('subjects-container');
            const submitBtn = document.getElementById('submit-btn');

            function showError(message) {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger mt-3';
                alertDiv.textContent = message;
                subjectsContainer.parentNode.insertBefore(alertDiv, subjectsContainer.nextSibling);
                setTimeout(() => alertDiv.remove(), 5000);
            }

            function attachEventListeners() {
                document.querySelectorAll('.subject-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function () {
                        const row = this.closest('.subject-row');
                        const midtermInput = row.querySelector('.midterm-input');
                        const finalInput = row.querySelector('.final-input');
                        const totalDisplay = row.querySelector('.total-display');
                        const gradeDisplay = row.querySelector('.grade-display');
                        const gradeInput = row.querySelector('.grade-input');

                        if (this.checked) {
                            midtermInput.removeAttribute('disabled');
                            finalInput.removeAttribute('disabled');
                            gradeInput.removeAttribute('disabled');
                            midtermInput.setAttribute('required', 'required');
                            finalInput.setAttribute('required', 'required');
                        } else {
                            midtermInput.value = '';
                            finalInput.value = '';
                            totalDisplay.value = '';
                            gradeDisplay.value = '';
                            gradeInput.value = '';
                            midtermInput.setAttribute('disabled', 'disabled');
                            finalInput.setAttribute('disabled', 'disabled');
                            gradeInput.setAttribute('disabled', 'disabled');
                            midtermInput.removeAttribute('required');
                            finalInput.removeAttribute('required');
                        }
                    });
                });

                function calculateTotalAndGrade(row) {
                    const midtermInput = row.querySelector('.midterm-input');
                    const finalInput = row.querySelector('.final-input');
                    const totalDisplay = row.querySelector('.total-display');
                    const gradeDisplay = row.querySelector('.grade-display');
                    const gradeInput = row.querySelector('.grade-input');
                    
                    // Clamp values and alert if over limits
                    let midterm = parseFloat(midtermInput.value) || 0;
                    let final = parseFloat(finalInput.value) || 0;
                    if (midterm > 60) {
                        alert('Midterm score cannot exceed 60');
                        midterm = 60;
                        midtermInput.value = 60;
                    }
                    if (final > 40) {
                        alert('Final score cannot exceed 40');
                        final = 40;
                        finalInput.value = 40;
                    }

                    const total = midterm + final;
                    totalDisplay.value = total.toFixed(1);

                    let grade = '';
                    if (total > 0) {
                        if (total >= 90) grade = 'A';
                        else if (total >= 80) grade = 'B';
                        else if (total >= 70) grade = 'C';
                        else if (total >= 60) grade = 'D';
                        else grade = 'F';
                    }

                    gradeDisplay.value = grade;
                    gradeInput.value = grade;
                }

                document.querySelectorAll('.midterm-input').forEach(input => {
                    input.addEventListener('input', function () {
                        const row = this.closest('.subject-row');
                        calculateTotalAndGrade(row);
                    });
                });

                document.querySelectorAll('.final-input').forEach(input => {
                    input.addEventListener('input', function () {
                        const row = this.closest('.subject-row');
                        calculateTotalAndGrade(row);
                    });
                });
            }

            // Populate majors based on selected generation
            genSelect.addEventListener('change', function () {
                const genId = this.value;
                majorSelect.innerHTML = '<option value="">Select Major</option>';
                studentSelect.innerHTML = '<option value="">Select Student</option>';
                termSelect.value = '';
                termSelect.setAttribute('disabled', 'disabled');
                studentSelect.setAttribute('disabled', 'disabled');
                subjectsContainer.innerHTML = '<div class="text-center py-4 text-muted">Please select a generation, major, student, and term to view subjects</div>';
                submitBtn.setAttribute('disabled', 'disabled');

                if (!genId) {
                    majorSelect.setAttribute('disabled', 'disabled');
                    return;
                }
                majorSelect.removeAttribute('disabled');

                // Fetch majors for the selected generation
                fetch(`/api/majors-by-generation/${genId}`)
                    .then(response => response.json())
                    .then(majors => {
                        majorSelect.innerHTML = '<option value="">Select Major</option>';
                        if (majors.length === 0) {
                            majorSelect.innerHTML = '<option value="">No majors found</option>';
                            return;
                        }
                        majors.forEach(major => {
                            const option = document.createElement('option');
                            option.value = major.id;
                            option.textContent = major.name;
                            majorSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching majors:', error);
                        showError('Failed to load majors.');
                    });
            });

            // Populate students based on selected major and generation
            majorSelect.addEventListener('change', function () {
                const genId = genSelect.value;
                const majorId = this.value;
                studentSelect.innerHTML = '<option value="">Select Student</option>';
                termSelect.value = '';
                termSelect.setAttribute('disabled', 'disabled');
                studentSelect.setAttribute('disabled', 'disabled');
                subjectsContainer.innerHTML = '<div class="text-center py-4 text-muted">Please select a generation, major, student, and term to view subjects</div>';
                submitBtn.setAttribute('disabled', 'disabled');

                if (!majorId) return;

                studentSelect.innerHTML = '<option>Loading...</option>';

                fetch(`/api/students-by-generation-and-major?gen_id=${genId}&major_id=${majorId}`)
                    .then(res => res.json())
                    .then(students => {
                        studentSelect.innerHTML = '<option value="">Select Student</option>';
                        if (students.length === 0) {
                            studentSelect.innerHTML = '<option value="">No students found</option>';
                            return;
                        }
                        studentSelect.removeAttribute('disabled');
                        students.forEach(s => {
                            const opt = document.createElement('option');
                            opt.value = s.id;
                            opt.textContent = `${s.first_name} ${s.last_name}`;
                            studentSelect.appendChild(opt);
                        });
                    })
                    .catch(err => {
                        showError('Failed to load students.');
                        console.error(err);
                    });
            });

            studentSelect.addEventListener('change', function () {
                if (this.value) {
                    termSelect.removeAttribute('disabled');
                } else {
                    termSelect.setAttribute('disabled', 'disabled');
                    subjectsContainer.innerHTML = '<div class="text-center py-4 text-muted">Please select a generation, major, student, and term to view subjects</div>';
                    submitBtn.setAttribute('disabled', 'disabled');
                }
            });

            termSelect.addEventListener('change', function () {
                const termId = this.value;
                const studentId = studentSelect.value;
                const majorId = majorSelect.value;

                if (!termId || !studentId || !majorId) return;

                subjectsContainer.innerHTML = '<div class="text-center py-4 text-muted">Loading subjects...</div>';
                submitBtn.setAttribute('disabled', 'disabled');

                fetch(`/api/subjects-by-term?term_id=${termId}&major_id=${majorId}`)
                    .then(res => res.json())
                    .then(subjects => {
                        if (subjects.length === 0) {
                            subjectsContainer.innerHTML = '<div class="text-center py-4 text-muted">No subjects found for the selected term and major.</div>';
                            return;
                        }

                        let html = '<div class="row mb-2 fw-bold">';
                        html += '<div class="col-md-1 text-center">Select</div>';
                        html += '<div class="col-md-3">Subject</div>';
                        html += '<div class="col-md-2">Midterm</div>';
                        html += '<div class="col-md-2">Final</div>';
                        html += '<div class="col-md-2">Total</div>';
                        html += '<div class="col-md-2">Grade</div>';
                        html += '</div>';

                        subjects.forEach(subject => {
                            html += `
                                <div class="row subject-row mb-3 align-items-center">
                                    <div class="col-md-1 text-center">
                                        <input type="checkbox" class="form-check-input subject-checkbox" name="subjects[${subject.id}][checked]">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="hidden" name="subjects[${subject.id}][subject_id]" value="${subject.id}">
                                        <span>${subject.name}</span>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" step="0.01" max="60" min="0" class="form-control midterm-input" name="subjects[${subject.id}][midterm]" placeholder="Midterm" disabled>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" step="0.01" max="40" min="0" class="form-control final-input" name="subjects[${subject.id}][final]" placeholder="Final" disabled>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control total-display" placeholder="Total" disabled readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control grade-display" placeholder="Grade" disabled readonly>
                                        <input type="hidden" class="grade-input" name="subjects[${subject.id}][grade]">
                                    </div>
                                </div>
                            `;
                        });

                        subjectsContainer.innerHTML = html;
                        attachEventListeners();
                        submitBtn.removeAttribute('disabled');
                    })
                    .catch(err => {
                        showError('Failed to load subjects.');
                        console.error(err);
                    });
            });

        });
    </script>
@endsection
