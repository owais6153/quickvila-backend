@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Verification Requests</h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">All Users</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead class="bg-primary text-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('afterScripts')
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('user.requests.get') }}",
                    type: 'GET',
                },
                columns: [{
                        data: 'id',
                        name: 'id',
                        'visible': false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
                order: [
                    [0, 'desc']
                ]
            });
        });
    </script>
@endpush

@push('afterScripts')
<div class="modal fade" id="identityCard" tabindex="-1" role="dialog" aria-labelledby="identityCardLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">

        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="identityCardCancel" tabindex="-1" role="dialog" aria-labelledby="identityCardCancelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">
            <form action="{{route('user.requests.cancel', ['user' => ':uid'])}}">
                <div class="form-group">
                    <input type="text" name="reason" class="form-control" placeholder="Reason"/>
                </div>
                <input type="submit" value="Reject" class="btn btn-primary w-100" />
            </form>
        </div>
      </div>
    </div>
  </div>

<script>
    $(document).ready(function(){
        $(document).on('click', '.identitycard', function(e){
            e.preventDefault();
            $('#identityCard .modal-body').html($(`<img src="${$(this).data('image')}" class="w-100" />`))
            $('#identityCard').modal('show');
        })

        $(document).on('click', '.cancelRequest', function(e){
            e.preventDefault();
            $('#identityCardCancel form').attr('action', $('#identityCardCancel form').attr('action').replace(':uid', $(this).data('id')))
            $('#identityCardCancel').modal('show');
        })

    })
</script>
@endpush
