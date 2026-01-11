@extends('admin.admin_master')
@section('admin')

<div class="content">
    <div class="container-xxl">

        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="m-0 fs-18 fw-semibold">All Return Purchase</h4>
            </div>

            <div class="text-end">
                <ol class="py-0 m-0 breadcrumb">
                     <a href="{{ route('add.return.purchase') }}" class="btn btn-secondary">Add Return Purchase</a>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th width="5%">Sl</th>
                                    <th>WareHouse</th>
                                    <th>Status</th>
                                    <th>Item Return</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($allData as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->warehouse->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-danger">{{ $item->status }}</span>
                                    </td>
                                    <td>
                                        <span class="text-primary fw-medium">
                                            @if($item->purchaseItems)
                                                {{ $item->purchaseItems->sum('quantity') }} Item
                                            @else
                                                0 Item
                                            @endif
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }}</td>
                                    <td>
                                        <a title="Details" href="{{ route('details.return.purchase', $item->id) }}" class="btn btn-info btn-sm">
                                            <span class="mdi mdi-eye-circle mdi-18px"></span>
                                        </a>

                                        <a title="PDF Invoice" href="{{ route('invoice.return.purchase', $item->id) }}" class="btn btn-primary btn-sm">
                                            <span class="mdi mdi-download-circle mdi-18px"></span>
                                        </a>

                                        <a title="Edit" href="{{ route('edit.return.purchase', $item->id) }}" class="btn btn-success btn-sm">
                                            <span class="mdi mdi-book-edit mdi-18px"></span>
                                        </a>

                                        <a title="Delete" href="{{ route('delete.return.purchase', $item->id) }}" class="btn btn-danger btn-sm" id="delete">
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

    </div>
</div>

@endsection
