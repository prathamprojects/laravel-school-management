<!DOCTYPE html>
<html>
<head>
    <title>Add New School</title>
</head>
<body>

    <h1>Add New School</h1>
    {{-- @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}
    <form action="{{ route('schools.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label>School Name</label>
            <input type="text" name="name">
            @error('name')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>School Address</label>
            <textarea name="address"></textarea>
            @error('address')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>State</label>

            <select name="state_id" id="state_id">
                <option value="">Select State</option>

                @foreach($states as $state)
                    <option value="{{ $state->id }}">
                        {{ $state->name }}
                    </option>
                @endforeach

            </select>
            @error('state_id')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label>District</label>

            <select name="district_id" id="district_id" disabled>
                <option value="">Select District</option>
            </select>
            @error('district_id')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label>City / Village</label>

            <select name="city_id" id="city_id" disabled>
                <option value="">Select City</option>
            </select>
            @error('city_id')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label>Establishment Date</label>
            <input type="date" name="establishment_date">
            @error('establishment_date')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label>Contact Number</label>
            <input
                type="tel"
                name="contact_number"
                maxlength="20"
                placeholder="Enter contact number"
            >
            @error('contact_number')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label>Login ID</label>
            <input
                type="text"
                name="login_id"
                placeholder="Enter login ID"
                autocomplete="username"
            >
            @error('login_id')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label>Password</label>
            <input
                type="password"
                name="password"
                placeholder="Enter password"
                autocomplete="new-password"
            >
            @error('password')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label>School Photos</label>
            <input
                type="file"
                name="school_photos[]"
                accept="image/*"
                multiple
            >
        </div>

        <hr>

<h2>Student Details</h2>

<div id="students-container">

    <div class="student-row">

        <h3>Student 1</h3>

        <div>
            <label>Student Name</label>
            <input type="text" name="students[0][name]">
        </div>

        <div>
            <label>Standard</label>
            <select name="students[0][standard_id]">
                <option value="">Select Standard</option>

                @foreach($standards as $standard)
                    <option value="{{ $standard->id }}">
                        {{ $standard->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Gender</label>

            <input type="radio" name="students[0][gender]" value="Male">
            Male

            <input type="radio" name="students[0][gender]" value="Female">
            Female
        </div>

        <div>
            <label>Year</label>
            <input type="number" name="students[0][year]">
        </div>

        <div>
            <label>Student Photo</label>
            {{-- <input type="file" name="students[0][photo]" accept="image/*"> --}}
            <input
                type="file"
                name="students[0][photo]"
                accept="image/*"
                class="student-photo"
            >

            <img
                class="photo-preview"
                src=""
                alt="Student Photo Preview"
                style="display:none; width:120px; height:120px; object-fit:cover;"
            >
        </div>
        <button type="button" class="remove-student">
            Remove Student
        </button>

    </div>

</div>

<button type="button" id="add-student">
    + Add More Student
</button>

        <button type="submit">Save School</button>

    </form>
</body>
</html>
<script>
    // State → District
    const stateDropdown = document.getElementById('state_id');
    const districtDropdown = document.getElementById('district_id');
    const cityDropdown = document.getElementById('city_id');

    stateDropdown.addEventListener('change', async function () {

        const stateId = this.value;

        districtDropdown.innerHTML =
            '<option value="">Select District</option>';

        cityDropdown.innerHTML =
            '<option value="">Select City</option>';

        districtDropdown.disabled = true;
        cityDropdown.disabled = true;

        if (!stateId) return;

        try {
            const response = await fetch(`/states/${stateId}/districts`);

            if (!response.ok) {
                throw new Error('Unable to fetch districts');
            }

            const districts = await response.json();

            districts.forEach(function (district) {
                const option = document.createElement('option');
                option.value = district.id;
                option.textContent = district.name;

                districtDropdown.appendChild(option);
            });

            districtDropdown.disabled = false;

        } catch (error) {
            console.error(error);
            alert('Unable to load districts');
        }
    });


    // District → City
    districtDropdown.addEventListener('change', async function () {

        const districtId = this.value;

        cityDropdown.innerHTML =
            '<option value="">Select City</option>';

        cityDropdown.disabled = true;

        if (!districtId) return;

        try {
            const response = await fetch(`/districts/${districtId}/cities`);

            if (!response.ok) {
                throw new Error('Unable to fetch cities');
            }

            const cities = await response.json();

            cities.forEach(function (city) {
                const option = document.createElement('option');
                option.value = city.id;
                option.textContent = city.name;

                cityDropdown.appendChild(option);
            });

            cityDropdown.disabled = false;

        } catch (error) {
            console.error(error);
            alert('Unable to load cities');
        }
    });

    // for students
    let studentIndex = 1;

    const studentsContainer = document.getElementById('students-container');
    const addStudentButton = document.getElementById('add-student');

    addStudentButton.addEventListener('click', function () {

        const studentHtml = `
            <div class="student-row">

                <hr>
                <h3>Student ${studentIndex + 1}</h3>

                <div>
                    <label>Student Name</label>
                    <input type="text" name="students[${studentIndex}][name]">
                </div>

                <div>
                    <label>Standard</label>
                    <select name="students[${studentIndex}][standard_id]">
                        <option value="">Select Standard</option>

                        @foreach($standards as $standard)
                            <option value="{{ $standard->id }}">
                                {{ $standard->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Gender</label>

                    <input type="radio"
                        name="students[${studentIndex}][gender]"
                        value="Male"> Male

                    <input type="radio"
                        name="students[${studentIndex}][gender]"
                        value="Female"> Female
                </div>

                <div>
                    <label>Year</label>
                    <input type="number" name="students[${studentIndex}][year]">
                </div>

                <div>
                    <label>Student Photo</label>

                    <input
                        type="file"
                        name="students[${studentIndex}][photo]"
                        accept="image/*"
                        class="student-photo"
                    >

                    <img
                        class="photo-preview"
                        src=""
                        alt="Student Photo Preview"
                        style="display:none; width:120px; height:120px; object-fit:cover;"
                    >
                </div>

                <button type="button" class="remove-student">
                    Remove Student
                </button>

            </div>
        `;

        studentsContainer.insertAdjacentHTML('beforeend', studentHtml);

        studentIndex++;
    });

    // Remove student
    studentsContainer.addEventListener('click', function (event) {

        const removeButton = event.target.closest('.remove-student');

        if (!removeButton) {
            return;
        }

        const studentRow = removeButton.closest('.student-row');

        if (studentRow) {
            studentRow.remove();
        }

    });

    studentsContainer.addEventListener('change', function (event) {

        if (event.target.classList.contains('student-photo')) {

            const file = event.target.files[0];

            const studentRow = event.target.closest('.student-row');

            const preview = studentRow.querySelector('.photo-preview');

            if (!file) {
                preview.src = '';
                preview.style.display = 'none';
                return;
            }

            if (!file.type.startsWith('image/')) {
                alert('Please select a valid image.');
                event.target.value = '';
                preview.src = '';
                preview.style.display = 'none';
                return;
            }

            const reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };

            reader.readAsDataURL(file);
        }

    });
</script>
