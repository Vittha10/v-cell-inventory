@extends('admin.admin_master')
@section('admin')

<div class="content">

    <div class="container-xxl">

        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="m-0 fs-18 fw-semibold">All Purchase</h4>
            </div>

            <div class="text-end">
                <ol class="py-0 m-0 breadcrumb">
                     <a href="{{ route('add.purchase') }}" class="btn btn-secondary">Add Purchase</a>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">

                    </div><div class="card-body">
                        <table id="datatable" class="table table-bordered dt-responsive table-responsive nowrap">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>WareHouse</th>
                                <th>Status</th>
                                <th>Stok</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
    @foreach ($allData as $key=> $item)
    <tr>
        <td>{{ $key+1 }}</td>


        <td>{{ $item->warehouse->name ?? 'Pontianak' }}</td>

        <td>
            @if($item->status == 'Received')
                <span class="badge bg-success">Received</span>
            @else
                <span class="badge bg-warning">{{ $item->status }}</span>
            @endif
        </td>


        <td class="fw-bold text-primary">
    {{ $item->purchaseItems->sum('quantity') }} Item
</td>


        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }}</td>

        <td>
            <a title="Details" href="{{ route('details.purchase',$item->id) }}" class="btn btn-info btn-sm">
                <span class="mdi mdi-eye-circle mdi-18px"></span>
            </a>

            <a title="PDF Invoice" href="{{ route('invoice.purchase',$item->id) }}" class="btn btn-primary btn-sm">
                <span class="mdi mdi-download-circle mdi-18px"></span>
            </a>

            <a title="Edit" href="{{ route('edit.purchase',$item->id) }}" class="btn btn-success btn-sm">
                <span class="mdi mdi-book-edit mdi-18px"></span>
            </a>

            <a title="Delete" href="{{ route('delete.purchase',$item->id) }}" class="btn btn-danger btn-sm" id="delete">
                <span class="mdi mdi-delete-circle mdi-18px"></span>
            </a>
        </td>
    </tr>
    @endforeach
</tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div> </div> @endsection
