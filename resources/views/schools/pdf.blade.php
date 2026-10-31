
<!DOCTYPE html>
<html>
<head>
    <title>School Report</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h1, h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }

        img {
            width: 100px;
            height: 80px;
        }
    </style>
</head>
<body>

    <h1>School Information Report</h1>

    <h2>{{ $school->name }}</h2>

    <table>
        <tr><th>School Name</th><td>{{ $school->name }}</td></tr>
        <tr><th>Address</th><td>{{ $school->address }}</td></tr>
        <tr><th>State</th><td>{{ $school->state->name }}</td></tr>
        <tr><th>District</th><td>{{ $school->district->name }}</td></tr>
        <tr><th>City</th><td>{{ $school->city->name }}</td></tr>
        <tr><th>Establishment Date</th><td>{{ $school->establishment_date->format('d-m-Y') }}</td></tr>
        <tr><th>Contact Number</th><td>{{ $school->contact_number }}</td></tr>
        <tr><th>Login ID</th><td>{{ $school->login_id }}</td></tr>
    </table>

    <h2>School Photos</h2>

    @foreach($school->photos as $photo)
        <img src="{{ public_path('storage/' . $photo->photo_path) }}">
    @endforeach

    <h2>Student Details</h2>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Standard</th>
                <th>Gender</th>
                <th>Year</th>
            </tr>
        </thead>

        <tbody>
            @foreach($school->students as $student)
                <tr>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->standard->name }}</td>
                    <td>{{ $student->gender }}</td>
                    <td>{{ $student->year }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
