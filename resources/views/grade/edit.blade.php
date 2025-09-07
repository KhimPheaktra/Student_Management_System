@extends('layouts.admin')
@section('title','Edit Grade')
@section('content')

<h1>Edit Grade</h1>
<div class="container-fluid px-4">
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Edit Subject Scores</li>
  </ol>

  <form method="POST"
        action="{{ route('grade.update', ['student' => $selectedStudent->id, 'term_id' => $selectedTerm->id]) }}">
    @csrf
    @method('PUT')

    <div class="card mb-4">
      <div class="card-header bg-light">
        <h5 class="mb-0">
          Edit Scores for
          {{ $selectedStudent->first_name }} {{ $selectedStudent->last_name }}
        </h5>
      </div>

      <div class="card-body">
        <div class="row mb-4">
          {{-- Generation --}}
          <div class="col-md-3">
            <label for="gen_id" class="form-label">Generation</label>
            <select class="form-select" id="gen_id" name="gen_id" required>
              <option value="">Select Generation</option>
              @foreach($generations as $gen)
                <option value="{{ $gen->id }}"
                  {{ $gen->id === $selectedGeneration->id ? 'selected' : '' }}>
                  {{ $gen->gen }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- Major --}}
          <div class="col-md-3">
            <label for="major_id" class="form-label">Major</label>
            <select class="form-select" id="major_id" name="major_id" required>
              <option value="">Select Major</option>
              @foreach($majors as $major)
                <option value="{{ $major->id }}"
                  {{ $major->id === $selectedMajor->id ? 'selected' : '' }}>
                  {{ $major->name }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- Student --}}
          <div class="col-md-3">
            <label for="student_id" class="form-label">Student</label>
            <select class="form-select" id="student_id" name="student_id" required>
              <option value="">Select Student</option>
              @foreach($students as $student)
                <option value="{{ $student->id }}"
                  {{ $student->id === $selectedStudent->id ? 'selected' : '' }}>
                  {{ $student->first_name }} {{ $student->last_name }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- Term --}}
          <div class="col-md-3">
            <label for="term_id" class="form-label">Term</label>
            <select class="form-select" id="term_id" name="term_id" required>
              <option value="">Select Term</option>
              @foreach($terms as $term)
                <option value="{{ $term->id }}"
                  {{ $term->id === $selectedTerm->id ? 'selected' : '' }}>
                  {{ $term->name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <hr class="my-4">
        <h6 class="mb-3">Subject Scores</h6>

        <div id="subjects-container" class="text-center py-4 text-muted">
          Loading subjects & scores…
        </div>
      </div>

      <div class="card-footer">
        <button type="submit" class="btn btn-warning">Update Scores</button>
      </div>
    </div>
  </form>


<script>
document.addEventListener('DOMContentLoaded', () => {
  const genSelect     = document.getElementById('gen_id');
  const majorSelect   = document.getElementById('major_id');
  const studentSelect = document.getElementById('student_id');
  const termSelect    = document.getElementById('term_id');
  const container     = document.getElementById('subjects-container');

  // from controller: existingScores by subject_id
  const oldScores = @json($existingScores);

  function loadSubjects() {
    const genId   = genSelect.value;
    const majorId = majorSelect.value;
    const termId  = termSelect.value;

    if (!genId || !majorId || !termId) {
      container.innerHTML = '<div class="text-center py-4 text-muted">Please select all dropdowns.</div>';
      return;
    }

    container.innerHTML = '<div class="text-center py-4 text-muted">Loading…</div>';

    fetch(`/api/subjects-by-term?term_id=${termId}&major_id=${majorId}`)
      .then(r => r.json())
      .then(subjects => {
        if (!subjects.length) {
          container.innerHTML = '<div class="text-center py-4 text-muted">No subjects found.</div>';
          return;
        }

        let html = `
          <div class="row mb-2 fw-bold">
            <div class="col-1 text-center">Select</div>
            <div class="col-3">Subject</div>
            <div class="col-2">Midterm</div>
            <div class="col-2">Final</div>
            <div class="col-2">Total</div>
            <div class="col-2">Grade</div>
          </div>`;

        subjects.forEach(s => {
          const existing = oldScores[s.id] || { midterm: '', final: '', grade: '' };
          const mid   = existing.midterm;
          const fin   = existing.final;
          const total = (parseFloat(mid) || 0) + (parseFloat(fin) || 0);
          const grd   = existing.grade;

          html += `
            <div class="row subject-row mb-3 align-items-center">
              <div class="col-1 text-center">
                <input type="checkbox"
                       class="form-check-input subject-checkbox"
                       name="subjects[${s.id}][checked]"
                       ${mid||fin ? 'checked' : ''}>
              </div>
              <div class="col-3">
                <input type="hidden"
                       name="subjects[${s.id}][subject_id]"
                       value="${s.id}">
                ${s.name}
              </div>
              <div class="col-2">
                <input type="number" step="0.01" max="60" min="0"
                       class="form-control midterm-input"
                       name="subjects[${s.id}][midterm]"
                       placeholder="Midterm"
                       value="${mid}"
                       ${mid||fin ? '' : 'disabled'}>
              </div>
              <div class="col-2">
                <input type="number" step="0.01" max="40" min="0"
                       class="form-control final-input"
                       name="subjects[${s.id}][final]"
                       placeholder="Final"
                       value="${fin}"
                       ${mid||fin ? '' : 'disabled'}>
              </div>
              <div class="col-2">
                <input type="text"
                       class="form-control total-display"
                       readonly
                       value="${ total ? total.toFixed(1) : '' }">
              </div>
              <div class="col-2">
                <input type="text"
                       class="form-control grade-display"
                       readonly
                       value="${grd}">
                <input type="hidden"
                       class="grade-input"
                       name="subjects[${s.id}][grade]"
                       value="${grd}">
              </div>
            </div>`;
        });

        container.innerHTML = html;
        attachListeners();
      })
      .catch(() => {
        container.innerHTML = '<div class="alert alert-danger">Failed to load subjects.</div>';
      });
  }

  function attachListeners() {
    document.querySelectorAll('.subject-checkbox').forEach(cb => {
      cb.addEventListener('change', function () {
        const row = this.closest('.subject-row');
        const inputs = row.querySelectorAll('.midterm-input, .final-input, .grade-input');

        if (this.checked) {
          inputs.forEach(input => {
            input.disabled = false;
            if (!input.classList.contains('grade-input')) {
              input.required = true;
            }
          });
        } else {
          inputs.forEach(input => {
            input.disabled = true;
            input.value = '';
            input.removeAttribute('required');
          });
          row.querySelector('.total-display').value = '';
          row.querySelector('.grade-display').value = '';
        }
      });
    });

    document.querySelectorAll('.midterm-input, .final-input').forEach(inp => {
      inp.addEventListener('input', function () {
        const row = this.closest('.subject-row');
        const mid = parseFloat(row.querySelector('.midterm-input').value) || 0;
        const fin = parseFloat(row.querySelector('.final-input').value) || 0;
        const total = Math.min(mid,60) + Math.min(fin,40);
        let grade = '';
        if      (total >= 90) grade = 'A';
        else if (total >= 80) grade = 'B';
        else if (total >= 70) grade = 'C';
        else if (total >= 60) grade = 'D';
        else if (total > 0)   grade = 'F';

        row.querySelector('.total-display').value = total.toFixed(1);
        row.querySelector('.grade-display').value = grade;
        row.querySelector('.grade-input').value   = grade;
      });
    });
  }

  [genSelect, majorSelect, studentSelect, termSelect].forEach(el =>
    el.addEventListener('change', loadSubjects)
  );

  // Initial fetch
  loadSubjects();
});
</script>

@endsection
