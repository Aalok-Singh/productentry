@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Laravel 7|8 Yajra Datatables Example </h2>
<a class="import btn btn-primary btn-sm"> Import Product</a>
<a href="{{ route('productExport') }}" class="export btn btn-primary btn-sm"> Export Product</a>
<a href="{{ route('productCreate') }}" class="export btn btn-primary btn-sm"> Insert Product By Form</a>
    <table id="my-datatable" class="table table-bordered yajra-datatable">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Title</th>
                <th>Description</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<!-- Model to insert product -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="importModel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Product Import</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="modal-body">
            <form id="export_excel" enctype="multipart/form-data">
            @csrf
                    <div class="form-group">
                        <label>Import File</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".csv" />
                    </div>
                    <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </form>
            </div>
           
        </div>
    </div>
</div>
<!-- Model to update product -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="updateModel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Product Updated</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="modal-body">
                <div id="Message"> </div>
                <form>
                    <input type="hidden" id="id" name="product_id" hidden>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" id="name" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label><strong>Title :</strong></label>
                        <input type="text" name="title" id="title" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label><strong>Description :</strong></label>
                        <textarea class="ckeditor form-control" id="description" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label><strong>Price :</strong></label>
                        <input type="number" name="price" id="price" class="form-control" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="product_update" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
    $(function() {

        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('products.list') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'price',
                    name: 'price'
                },

                {
                    data: 'action',
                    name: 'action',
                    orderable: true,
                    searchable: true
                },
            ]
        });

    });
    $(document).on("click", ".import", function() {
        $('#importModel').modal('show');
    });
    // $(document).on("click", ".export", function() {
      
    //     $.ajax({
    //         type: 'GET',
    //         url: "{{ route('productExport') }}",
    //         success: function(data) {
                
    //         }
    //     });
    // });
    $(document).on("click", ".edit", function() {
        $(".modal-body #id").val($(this).data('id'));
        $(".modal-body #name").val($(this).data('name'));
        $(".modal-body #title").val($(this).data('title'));
        $(".modal-body #description").val($(this).data('description'));
        $(".modal-body #price").val($(this).data('price'));
        $('#updateModel').modal('show');
    });
    $(document).on("click", ".delete", function() {
        var id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: "{{ route('productDelete') }}",
            data: {
                _token: "{{ csrf_token() }}",
                id: id
            },
            success: function(data) {
                if (data.Success == "Product deleted") {
                    $('#my-datatable').DataTable().ajax.reload();
                } else {
                }
            }
        });
    });

    $("#product_update").click(function(e) {
        e.preventDefault();
        var id = $("input[name=product_id]").val();
        var name = $("input[name=name]").val();
        var title = $("input[name=title]").val();
        var description = $("textarea[name=description]").val();
        var price = $("input[name=price]").val();
        $.ajax({
            type: 'POST',
            url: "{{ route('productUpdate') }}",
            data: {
                _token: "{{ csrf_token() }}",
                id: id,
                name: name,
                title: title,
                description: description,
                price: price
            },
            success: function(data) {
                if (data.Success == "Product updated") {
                    $('#updateModel').modal('hide');
                    $('#my-datatable').DataTable().ajax.reload();
                } else {
                    $("#Message").html("Product not updated").show();
                }
            }
        });
    });
   
    $('#file').change(function(){  
           $('#export_excel').submit();  
      });  
    $('#export_excel').on('submit', function(event){  
           event.preventDefault();  
           $.ajax({  
                url:"{{ route('productImport') }}",  
                method:"POST",  
                data:new FormData(this),  
                contentType:false,  
                processData:false,  
                success:function(data){ 
                    if (data.Success == "Product imported") { 
                    $('#importModel').modal('hide');
                    $('#my-datatable').DataTable().ajax.reload();
                    }else{
                        $("#Message").html(data.Error).show();
                    }
                }  
           });   
        }); 
</script>
@endsection