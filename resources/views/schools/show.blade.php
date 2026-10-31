
<!DOCTYPE html>
<html>
<head>
    <title>School Details</title>
</head>
<body>

    <h1>School Details</h1>

    <h2>{{ $school->name }}</h2>

    <p><strong>Address:</strong> {{ $school->address }}</p>
    <p><strong>State:</strong> {{ $school->state->name }}</p>
    <p><strong>District:</strong> {{ $school->district->name }}</p>
    <p><strong>City:</strong> {{ $school->city->name }}</p>

    <p>
        <strong>Establishment Date:</strong>
        {{ $school->establishment_date->format('d-m-Y') }}
    </p>

    <p><strong>Contact:</strong> {{ $school->contact_number }}</p>
    <p><strong>Login ID:</strong> {{ $school->login_id }}</p>

    <hr>

    <h2>School Photos</h2>

    @forelse($school->photos as $photo)
        <img
            src="{{ asset('storage/' . $photo->photo_path) }}"
            width="150"
            height="120"
            style="object-fit: cover; margin: 5px;"
            alt="School Photo"
        >
    @empty
        <p>No school photos available.</p>
    @endforelse

    <hr>

    <h2>Student Details</h2>

    @forelse($school->students as $student)

        <div style="border: 1px solid #ccc; padding: 10px; margin: 10px 0;">

            <h3>{{ $student->name }}</h3>

            <p>
                <strong>Standard:</strong>
                {{ $student->standard->name }}
            </p>

            <p><strong>Gender:</strong> {{ $student->gender }}</p>
            <p><strong>Year:</strong> {{ $student->year }}</p>

            @if($student->photo_path)
                <img
                    src="{{ asset('storage/' . $student->photo_path) }}"
                    width="120"
                    height="120"
                    style="object-fit: cover;"
                    alt="Student Photo"
                >
            @endif

        </div>

    @empty
        <p>No students available.</p>
    @endforelse

    <a href="{{ route('schools.index') }}">Back to Schools</a>

</body>
</html>
