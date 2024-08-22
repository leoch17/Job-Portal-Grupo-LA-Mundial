@extends('frontend.layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Usuarios</a></li>
                            <li class="breadcrumb-item active">Editar</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    @include('admin.sidebar')
                </div>
                <div class="col-lg-9">
                    @include('frontend.layouts.message')
                    <div class="card border-0 shadow mb-4">
                        <div class="card-body card-form">
                            <form action="" method="post" id="userForm" name="userForm">
                                <div class="card-body  p-4">
                                    <h3 class="fs-4 mb-1">Editar / Usuario</h3>
                                    <div class="mb-4">
                                        <label for="" class="fs-5 mb-2">Nombre Completo</label>
                                        <input type="text" name="name" id="name" placeholder="Ingrese Nombre"
                                            class="form-control" value="{{ $user->name }}">
                                        <p></p>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="fs-5 mb-2">Correo Electrónico</label>
                                        <input type="text" name="email" id="email" class="form-control"
                                            value="{{ $user->email }}">
                                        <p></p>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="fs-5 mb-2">Formación</label>
                                        <input type="text" name="designation" id="designation"
                                            placeholder="Ingrese Carrera" class="form-control"
                                            value="{{ $user->designation }}">
                                    </div>
                                    <div class="mb-4">
                                        <label for="mobile" class="fs-5 mb-2">Número de Teléfono</label>
                                        <input type="text" name="mobile" id="mobile"
                                            placeholder="Ingrese Número de Teléfono" class="form-control"
                                            value="{{ $user->mobile }}">
                                    </div>
                                    <div class="mb-4">
                                        <label for="text" class="fs-5 mb-2">Rol de Usuario</label>
                                        <input type="text" name="role" id="role"
                                            placeholder="Ingrese Rol de Usuario" class="form-control"
                                            value="{{ $user->role }}">
                                    </div>
                                </div>
                                <div class="p-4">
                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script type="text/javascript">
        $("#userForm").submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route('admin.users.update', $user->id) }}',
                type: 'put',
                dataType: 'json',
                data: $("#userForm").serializeArray(),
                success: function(response) {

                    if (response.status == true) {

                        $("#name").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('')

                        $("#email").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('')

                        window.location.href = "{{ route('admin.users') }}";

                    } else {
                        var errors = response.errors;

                        if (errors.name) {
                            $("#name").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.name)
                        } else {
                            $("#name").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }

                        if (errors.email) {
                            $("#email").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.email)
                        } else {
                            $("#email").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }
                    }
                }
            });
        });
    </script>
@endsection
