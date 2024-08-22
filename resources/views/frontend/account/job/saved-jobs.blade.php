@extends('frontend.layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                            <li class="breadcrumb-item active">Ajustes de Cuenta</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    @include('frontend.account.sidebar')
                </div>
                <div class="col-lg-9">
                    @include('frontend.layouts.message')
                    <div class="card border-0 shadow mb-4 p-3">
                        <div class="card-body card-form">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="fs-4 mb-1">Empleos Guardados</h3>
                                </div>

                            </div>
                            <div class="table-responsive">
                                <table class="table ">
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col">Título</th>
                                            <th scope="col">Candidatos</th>
                                            <th scope="col">Estado</th>
                                            <th scope="col">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-0">
                                        @if ($savedJobs->isNotEmpty())
                                            @foreach ($savedJobs as $savedJob)
                                                <tr class="active">
                                                    <td>
                                                        <div class="job-name fw-500">{{ $savedJob->job->title }}
                                                        </div>
                                                        <div class="info1">{{ $savedJob->job->jobType->name }} .
                                                            {{ $savedJob->job->location }}
                                                        </div>
                                                    </td>
                                                    <td>{{ $savedJob->job->applications->count() }} Candidatos</td>
                                                    <td>
                                                        @if ($savedJob->job->status == 1)
                                                            <div class="job-status text-capitalize">Activo</div>
                                                        @else
                                                            <div class="job-status text-capitalize">Bloqueado</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="action-dots">
                                                            <button href="#" class="btn" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a class="dropdown-item"
                                                                        href="{{ route('jobDetail', $savedJob->job_id) }}"
                                                                        target="_blank">
                                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                                        Ver</a></li>
                                                                <li><button class="dropdown-item" href="#"
                                                                        onclick="removeJob({{ $savedJob->id }})"><i
                                                                            class="fa fa-trash" aria-hidden="true"></i>
                                                                        Remover</button></li>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5">Empleos no encontrados</td>
                                            </tr>
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                            <div>
                                {{ $savedJobs->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script type="text/javascript">
        function removeJob(id) {
            if (confirm("¿Estas seguro que quieres remover este empleo?")) {
                $.ajax({
                    url: '{{ route('account.removeSavedJob') }}',
                    type: 'post',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        window.location.href = '{{ route('account.savedJobs') }}';
                    }
                });
            }
        }
    </script>
@endsection
