@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">User</p>
                                <h5 class="font-weight-bolder">
                                    {{ $users->count() }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                <i class="fa fa-users text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between">
                    <h6>Users' table</h6>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">Create
                        New User</button>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table m-2">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <th>{{ $user->id }}</th>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary edit-user-btn" data-user-id="{{ $user->id }}" data-bs-toggle="modal" data-bs-target="#editUserModal">Edit</button>
                                            <button class="btn btn-sm btn-danger delete-user" data-user-id="{{ $user->id }}">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="createUserModalLabel">Create New User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form id="createUserForm" method="POST" action="{{ route('users.store') }}">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input id="name" type="text"
                                            class="form-control form-control-lg @error('name') is-invalid @enderror"
                                            placeholder="Name" name="name" value="{{ old('name') }}" aria-label="Name">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input id="email" type="email"
                                            class="form-control form-control-lg @error('email') is-invalid @enderror"
                                            placeholder="Email" name="email" value="{{ old('email') }}"
                                            aria-label="Email">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Password</label>
                                        <input id="password" type="password"
                                            class="form-control form-control-lg @error('password') is-invalid @enderror"
                                            placeholder="Password" aria-label="Password" name="password" required
                                            autocomplete="current-password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Create User</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Edit User Modal -->
                <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="editUserForm" method="POST" action="{{ route('users.update', ['user' => '__USER_ID__']) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="editName" class="form-label">Name</label>
                                        <input id="editName" type="text" class="form-control" name="name"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="editEmail" class="form-label">Email</label>
                                        <input id="editEmail" type="email" class="form-control" name="email"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="editPassword" class="form-label">Password</label>
                                        <input id="editPassword" type="password" class="form-control" name="password">
                                    </div>
                                    <input type="hidden" id="editUserId" name="user_id">
                                </form>
                            </div>
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" id="updateUserBtn">Update User</button>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const deleteButtons = document.querySelectorAll(".delete-user");
        const form = document.getElementById("createUserForm");
        const editNameInput = document.getElementById('editName');
        const editEmailInput = document.getElementById('editEmail');
        const editPasswordInput = document.getElementById('editPassword');
        const editUserIdInput = document.getElementById('editUserId');
        const updateUserBtn = document.getElementById('updateUserBtn');

        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            // Perform an AJAX form submission
            fetch(form.action, {
                    method: form.method,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: new FormData(form)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        location.reload();
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });

        // Event listener to handle the modal opening
        editUserModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const userId = button.getAttribute('data-user-id'); // Extract user ID from the button
            
            // Replace the placeholder with the actual user ID in the form action
            const formAction = `api/users/${userId}`;
            const actualFormAction = editUserForm.action.replace('__USER_ID__', userId);
            editUserForm.action = actualFormAction;

            // Fetch user data using AJAX and populate the form fields
            fetch(`api/users/${userId}`)
                .then(response => response.json())
                .then(data => {
                    editUserIdInput.value = data.user.id;
                    editNameInput.value = data.user.name;
                    editEmailInput.value = data.user.email;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });

        // Event listener to handle form submission
        updateUserBtn.addEventListener('click', function () {
            const form = document.getElementById('editUserForm');
            
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: new FormData(form)
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    // Close the modal and refresh the page
                    editUserModal.classList.remove('show');
                    document.body.classList.remove('modal-open');
                    document.querySelector('.modal-backdrop').remove();
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        deleteButtons.forEach(button => {
            button.addEventListener("click", function() {
                const userId = button.getAttribute("data-user-id");
                if (confirm("Are you sure you want to delete this user?")) {
                    // Make the API request
                    fetch(`/api/users/${userId}`, {
                            method: "DELETE",
                            headers: {
                                'X-CSRF-Token': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                                "Content-Type": "application/json",
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.message) {
                                location.reload();
                                alert(data.message);
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                        });
                }
            });
        });
    });
</script>
