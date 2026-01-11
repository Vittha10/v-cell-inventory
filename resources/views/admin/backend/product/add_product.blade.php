@extends('admin.admin_master')
@section('admin')

<div class="content d-flex flex-column flex-column-fluid">
   <div class="d-flex flex-column-fluid">
      <div class="my-0 container-fluid">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h2 class="m-0 fs-22 fw-semibold">Add Product</h2>
            </div>

            <div class="text-end">
                <ol class="py-0 m-0 breadcrumb">
                     <a href="{{ route('all.product') }}" class="btn btn-dark">Back</a>
                </ol>
            </div>
        </div>
         <div class="card">
            <div class="card-body">
<form action="{{ route('store.product') }}" method="post" enctype="multipart/form-data">
   @csrf
   <div class="row">
      <div class="col-xl-8">
         <div class="card">
            <div class="row">
               <div class="mb-3 col-md-6">
                  <label class="form-label">Product Name:  <span class="text-danger">*</span></label>
                  <input type="text" name="name" placeholder="Enter Name" class="form-control">
               </div>
               <div class="mb-3 col-md-6">
                  <label class="form-label">Code: <span class="text-danger">*</span></label>
                  <input type="text" name="code" class=" form-control" placeholder="Enter Code">

               </div>
               <div class="mb-3 col-md-6">
                  <div class="form-group w-100">
                     <label class="form-label" for="formBasic">Product Category : <span class="text-danger">*</span></label>
                     <select name="category_id" id="category_id" class="form-control form-select">
                        <option value="">Select Category</option>
                        @foreach ($categories as $item)
                        <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                        @endforeach
                     </select>

                  </div>
               </div>
               <div class="mb-3 col-md-6">
                  <div class="form-group w-100">
                     <label class="form-label" for="formBasic">Brand : <span class="text-danger">*</span></label>
                     <select name="brand_id" id="brand_id" class="form-control form-select">
                        <option value="">Select Brand</option>
                        @foreach ($brands as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                     </select>

                  </div>
               </div>
               <div class="mb-3 col-md-6">
                  <label class="form-label">Product Price: </label>
                  <input type="text" name="price" class="form-control" placeholder="Enter product price">

               </div>


               <div class="mb-3 col-md-6">
                  <label class="form-label">Stock Alert: <span class="text-danger">*</span></label>
                  <input type="number" name="stock_alert" class="form-control" placeholder="Enter Stock Alert" min="0" required>

               </div>

               <div class="col-md-12">
                  <label class="form-label">Notes: </label>
                  <textarea class="form-control" name="note" rows="3" placeholder="Enter Notes"></textarea>
               </div>
            </div>
         </div>
      </div>
      <div class="col-xl-4">
         <div class="card">
            <label class="form-label">Multiple Image: <span class="text-danger">*</span></label>
            <div class="mb-3">
               <input name="image[]" accept=".png, .jpg, .jpeg" multiple="" type="file" id="multiImg" class="upload-input-file form-control">
            </div>

            <div class="row" id="preview_img"></div>
         </div>
         <div>
            <div class="mb-3 col-md-12">
               <h4 class="text-center">Add Stock : </h4>
            </div>
            <div class="mb-3 col-md-12">
               <div class="form-group w-100">
                  <label class="form-label" for="formBasic">Warehouse : <span class="text-danger">*</span></label>
                  <select name="warehouse_id" id="warehouse_id" class="form-control form-select">
                     <option value="">Select Warehouse</option>
                     <option value="1" selected>Pontianak</option>
                  </select>

               </div>
            </div>
            <div class="mb-3 col-md-12">
               <div class="form-group w-100">
                  <label class="form-label" for="formBasic">Supplier : <span class="text-danger">*</span></label>
                  <select name="supplier_id" id="supplier_id" class="form-control form-select">
                     <option value="">Select Supplier</option>
                     @foreach ($suppliers as $item)
                     <option value="{{ $item->id }}">{{ $item->name }}</option>
                     @endforeach
                  </select>

               </div>
            </div>

            <div class="mb-3 col-md-12">
               <label class="form-label">Product Quantity: <span class="text-danger">*</span></label>
               <input type="number" name="product_qty" class="form-control" placeholder="Enter Product Quantity" min="1" required>

            </div>

            <div class="col-md-12">
               <div class="form-group w-100">
                  <label class="form-label" for="formBasic">Status : <span class="text-danger">*</span></label>
                  <select name="status" id="status" class="form-control form-select">
                     <option value="Received">Received</option>
                  </select>
               </div>
            </div>
         </div>
      </div>
      <div class="col-xl-12">
         <div class="mt-5 d-flex justify-content-start">
            <button class="btn btn-primary me-3" type="submit">Save</button>
            <a class="btn btn-secondary" href="{{ route('all.product') }}">Cancel</a>
         </div>
      </div>
   </div>
</form>
</div>
         </div>
      </div>
   </div>
</div>


<script>
    document.getElementById('multiImg').addEventListener('change', function(event) {
        const previewContainer = document.getElementById('preview_img');
        previewContainer.innerHTML = '';

        const files = Array.from(event.target.files);
        const input = event.target;

        files.forEach((file, index) => {

            if (file.type.match('image.*')) {
                const reader = new FileReader();

                reader.onload = function(e) {

                    const col = document.createElement('div');
                    col.className = 'col-md-3 mb-3';


                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-fluid rounded';
                    img.style.maxHeight = '150px';
                    img.alt = 'Image Preview';


                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'btn btn-danger btn-sm position-absolute';
                    removeBtn.style.top = '10px';
                    removeBtn.style.right = '10px';
                    removeBtn.innerHTML = '&times;';
                    removeBtn.title = 'Remove Image';


                    removeBtn.addEventListener('click', function() {
                        col.remove();
                        const newFiles = files.filter((_, i) => i !== index);
                        const dataTransfer = new DataTransfer();
                        newFiles.forEach(f => dataTransfer.items.add(f));
                        input.files = dataTransfer.files;
                    });


                    const wrapper = document.createElement('div');
                    wrapper.style.position = 'relative';
                    wrapper.appendChild(img);
                    wrapper.appendChild(removeBtn);

                    col.appendChild(wrapper);
                    previewContainer.appendChild(col);
                };

                reader.readAsDataURL(file);
            }
        });
    });
</script>


@endsection
