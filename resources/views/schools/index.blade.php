<!DOCTYPE html>
<html>
<head>
    <title>School Management</title>
</head>
<body>

    <h1>School Management</h1>
    @if (session('success'))
        <p style="color: green;">
            {{ session('success') }}
        </p>
    @endif
    <a href="{{ route('schools.create') }}">
        Add New School
    </a>

    <h2>School List</h2>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>School Name</th>
                <th>Contact Number</th>
                <th>State</th>
                <th>District</th>
                <th>City</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($schools as $school)
                <tr>
                    <td>{{ $school->id }}</td>
                    <td>{{ $school->name }}</td>
                    <td>{{ $school->contact_number }}</td>
                    <td>{{ $school->state->name }}</td>
                    <td>{{ $school->district->name }}</td>
                    <td>{{ $school->city->name }}</td>
                    <td>
                        <a href="{{ route('schools.edit', $school) }}">Edit</a>
                        <a href="{{ route('schools.show', $school) }}">View</a>
                        <a href="{{ route('schools.pdf', $school->id) }}">Download PDF</a>
                        <form action="{{ route('schools.destroy', $school) }}"
                            method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this school?')">

                            @csrf
                            @method('DELETE')

                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No schools available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $schools->links() }}
</body>
</html>
