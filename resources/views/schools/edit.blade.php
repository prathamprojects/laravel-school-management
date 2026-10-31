<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit School</title>
</head>
<body>

    <h1>Edit School</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('schools.update', $school->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div>
            <label>School Name</label>
            <input type="text" name="name" value="{{ old('name', $school->name) }}">
            @error('name')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>School Address</label>
            <textarea name="address">{{ old('address', $school->address) }}</textarea>
            @error('address')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>State</label>
            <select name="state_id" id="state_id">
                <option value="">Select State</option>
                @foreach($states as $state)
                    <option value="{{ $state->id }}"
                        {{ (string) old('state_id', $school->state_id) === (string) $state->id ? 'selected' : '' }}>
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
            <input type="date" name="establishment_date"
                value="{{ old('establishment_date', optional($school->establishment_date)->format('Y-m-d')) }}">
            @error('establishment_date')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>Contact Number</label>
            <input type="tel" name="contact_number" maxlength="20"
                placeholder="Enter contact number"
                value="{{ old('contact_number', $school->contact_number) }}">
            @error('contact_number')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>Login ID</label>
            <input type="text" name="login_id"
                placeholder="Enter login ID" autocomplete="username"
                value="{{ old('login_id', $school->login_id) }}">
            @error('login_id')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>Password</label>
            <input type="password" name="password"
                placeholder="Leave blank to keep current password"
                autocomplete="new-password">
            <small>Leave blank if you do not want to change the password.</small>
            @error('password')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <hr>

        <h2>Existing School Photos</h2>
        @forelse($school->photos as $photo)
            <div style="display: inline-block; margin: 5px;">
                <img src="{{ asset('storage/' . $photo->photo_path) }}"
                    alt="School Photo" width="120" height="100"
                    style="object-fit: cover;">
            </div>
        @empty
            <p>No school photos uploaded.</p>
        @endforelse

        <div>
            <label>Add More School Photos</label>
            <input type="file" name="school_photos[]" accept="image/*" multiple>
            <small>New photos will be added. Existing photos will remain.</small>
            @error('school_photos.*')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <hr>

        <h2>Student Details</h2>

        <div id="students-container">
            @forelse($school->students as $index => $student)
                <div class="student-row" data-existing="1">
                    <hr>
                    <h3>Student {{ $index + 1 }}</h3>

                    <input type="hidden" name="students[{{ $index }}][id]" value="{{ $student->id }}">
                    <input type="hidden" name="students[{{ $index }}][_delete]" class="delete-student-flag" value="0">

                    <div>
                        <label>Student Name</label>
                        <input type="text" name="students[{{ $index }}][name]"
                            value="{{ old("students.$index.name", $student->name) }}">
                    </div>

                    <div>
                        <label>Standard</label>
                        <select name="students[{{ $index }}][standard_id]">
                            <option value="">Select Standard</option>
                            @foreach($standards as $standard)
                                <option value="{{ $standard->id }}"
                                    {{ (string) old("students.$index.standard_id", $student->standard_id) === (string) $standard->id ? 'selected' : '' }}>
                                    {{ $standard->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label>Gender</label>
                        <label>
                            <input type="radio" name="students[{{ $index }}][gender]" value="Male"
                                {{ old("students.$index.gender", $student->gender) === 'Male' ? 'checked' : '' }}>
                            Male
                        </label>
                        <label>
                            <input type="radio" name="students[{{ $index }}][gender]" value="Female"
                                {{ old("students.$index.gender", $student->gender) === 'Female' ? 'checked' : '' }}>
                            Female
                        </label>
                    </div>

                    <div>
                        <label>Year</label>
                        <input type="number" name="students[{{ $index }}][year]"
                            value="{{ old("students.$index.year", $student->year) }}">
                    </div>

                    <div>
                        <label>Current Student Photo</label><br>
                        @if($student->photo_path)
                            <img src="{{ asset('storage/' . $student->photo_path) }}"
                                alt="Student Photo" width="120" height="120"
                                style="object-fit: cover;">
                        @else
                            <span>No photo uploaded</span>
                        @endif
                    </div>

                    <div>
                        <label>Upload New Student Photo</label>
                        <input type="file" name="students[{{ $index }}][photo]"
                            accept="image/*" class="student-photo">
                        <img class="photo-preview" src="" alt="New Photo Preview"
                            style="display:none; width:120px; height:120px; object-fit:cover;">
                    </div>

                    <button type="button" class="remove-student">Remove Student</button>
                </div>
            @empty
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
                                <option value="{{ $standard->id }}">{{ $standard->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label>Gender</label>
                        <label><input type="radio" name="students[0][gender]" value="Male"> Male</label>
                        <label><input type="radio" name="students[0][gender]" value="Female"> Female</label>
                    </div>
                    <div>
                        <label>Year</label>
                        <input type="number" name="students[0][year]">
                    </div>
                    <div>
                        <label>Student Photo</label>
                        <input type="file" name="students[0][photo]" accept="image/*" class="student-photo">
                        <img class="photo-preview" src="" alt="Student Photo Preview"
                            style="display:none; width:120px; height:120px; object-fit:cover;">
                    </div>
                    <button type="button" class="remove-student">Remove Student</button>
                </div>
            @endforelse
        </div>

        <button type="button" id="add-student">+ Add More Student</button>

        <br><br>
        <button type="submit">Update School</button>
        <a href="{{ route('schools.index') }}">Cancel</a>
    </form>

    <script>
        const stateDropdown = document.getElementById('state_id');
        const districtDropdown = document.getElementById('district_id');
        const cityDropdown = document.getElementById('city_id');

        const selectedDistrictId = @json(old('district_id', $school->district_id));
        const selectedCityId = @json(old('city_id', $school->city_id));

        async function loadDistricts(stateId, districtToSelect = null) {
            districtDropdown.innerHTML = '<option value="">Select District</option>';
            cityDropdown.innerHTML = '<option value="">Select City</option>';
            districtDropdown.disabled = true;
            cityDropdown.disabled = true;

            if (!stateId) return;

            try {
                const response = await fetch(`/states/${stateId}/districts`);
                if (!response.ok) throw new Error('Unable to fetch districts');

                const districts = await response.json();

                districts.forEach(function (district) {
                    const option = document.createElement('option');
                    option.value = district.id;
                    option.textContent = district.name;
                    districtDropdown.appendChild(option);
                });

                districtDropdown.disabled = false;

                if (districtToSelect) {
                    districtDropdown.value = districtToSelect;
                    await loadCities(districtToSelect, selectedCityId);
                }
            } catch (error) {
                console.error(error);
                alert('Unable to load districts');
            }
        }

        async function loadCities(districtId, cityToSelect = null) {
            cityDropdown.innerHTML = '<option value="">Select City</option>';
            cityDropdown.disabled = true;

            if (!districtId) return;

            try {
                const response = await fetch(`/districts/${districtId}/cities`);
                if (!response.ok) throw new Error('Unable to fetch cities');

                const cities = await response.json();

                cities.forEach(function (city) {
                    const option = document.createElement('option');
                    option.value = city.id;
                    option.textContent = city.name;
                    cityDropdown.appendChild(option);
                });

                cityDropdown.disabled = false;

                if (cityToSelect) {
                    cityDropdown.value = cityToSelect;
                }
            } catch (error) {
                console.error(error);
                alert('Unable to load cities');
            }
        }

        stateDropdown.addEventListener('change', function () {
            loadDistricts(this.value);
        });

        districtDropdown.addEventListener('change', function () {
            loadCities(this.value);
        });

        // Load the school's existing district and city when the edit page opens.
        document.addEventListener('DOMContentLoaded', function () {
            loadDistricts(stateDropdown.value, selectedDistrictId);
        });

        // Start new student indexes after the existing student rows.
        let studentIndex = {{ $school->students->count() > 0 ? $school->students->count() : 1 }};
        const studentsContainer = document.getElementById('students-container');
        const addStudentButton = document.getElementById('add-student');

        addStudentButton.addEventListener('click', function () {
            const studentHtml = `
                <div class="student-row">
                    <hr>
                    <h3>New Student ${studentIndex + 1}</h3>

                    <div>
                        <label>Student Name</label>
                        <input type="text" name="students[${studentIndex}][name]">
                    </div>

                    <div>
                        <label>Standard</label>
                        <select name="students[${studentIndex}][standard_id]">
                            <option value="">Select Standard</option>
                            @foreach($standards as $standard)
                                <option value="{{ $standard->id }}">{{ $standard->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label>Gender</label>
                        <label><input type="radio" name="students[${studentIndex}][gender]" value="Male"> Male</label>
                        <label><input type="radio" name="students[${studentIndex}][gender]" value="Female"> Female</label>
                    </div>

                    <div>
                        <label>Year</label>
                        <input type="number" name="students[${studentIndex}][year]">
                    </div>

                    <div>
                        <label>Student Photo</label>
                        <input type="file" name="students[${studentIndex}][photo]"
                            accept="image/*" class="student-photo">
                        <img class="photo-preview" src="" alt="Student Photo Preview"
                            style="display:none; width:120px; height:120px; object-fit:cover;">
                    </div>

                    <button type="button" class="remove-student">Remove Student</button>
                </div>
            `;

            studentsContainer.insertAdjacentHTML('beforeend', studentHtml);
            studentIndex++;
        });

        // Existing students are marked for hiding; new unsaved rows are removed from the form.
        studentsContainer.addEventListener('click', function (event) {
            const removeButton = event.target.closest('.remove-student');
            if (!removeButton) return;

            const studentRow = removeButton.closest('.student-row');
            const deleteFlag = studentRow.querySelector('.delete-student-flag');

            if (deleteFlag) {
                deleteFlag.value = '1';
                studentRow.style.display = 'none';
            } else {
                studentRow.remove();
            }
        });

        // Preview newly selected student photos.
        studentsContainer.addEventListener('change', function (event) {
            if (!event.target.classList.contains('student-photo')) return;

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
        });
    </script>
</body>
</html>
