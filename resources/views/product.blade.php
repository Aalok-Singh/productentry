@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-2 mt-5">
               <div id="Message"> </div>
                <div class="card">
                    <div class="card-header bg-info">
                        <h6 class="text-white">Create Product Form</h6><a href="{{ route('product') }}" class="export btn btn-primary btn-sm">Product List</a>
                    </div>
                   
                    <div class="card-body">
                        <form id="form" name="form">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" id="name" class="form-control"/>
                                <div class="alert-message" id="nameError"></div>
                            </div>  
                            <div class="form-group">
                                <label><strong>Title :</strong></label>
                                <input type="text" name="title" class="form-control"/>
                                <div class="alert-message" id="titleError"></div>
                            </div>
                            <div class="form-group">
                                <label><strong>Description :</strong></label>
                                <textarea class="ckeditor form-control" name="description"></textarea>
                                <div class="alert-message" id="descriptionError"></div>
                            </div>
                            <div class="form-group">
                                <label><strong>Price :</strong></label>
                                <input type="number" name="price" class="form-control"/>
                                <div class="alert-message" id="priceError"></div>
                            </div>
                            <div class="text-center" style="margin-top: 10px;">
                                <button type="button" id="product_add" class="btn btn-success">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>    
@endsection 
@section('script')
<script type="text/javascript">

    $("#product_add").click(function(e){
  $("#form").validate();
  var name = $("input[name=name]").val();
  var title = $("input[name=title]").val();
  var description = $("textarea[name=description]").val();
  var price = $("input[name=price]").val();
  $.ajax({
     type:'POST',
     url:"{{ route('productData') }}",
     data:{_token: "{{ csrf_token() }}",name:name, title:title, description:description, price:price},
     success:function(data){
       console.log(data.Error_Message)
         if(data.Success == "Product stored"){
            $("#Message").html("Product stored").show();
            window.location.href = '/path';
            
         }else if(data.Error_Message.length){
            $('#nameError').text(data.Error_Message.name);
            $('#titleError').text(data.Error_Message.title);
            $('#descriptionError').text(data.Error_Message.description);
            $('#priceError').text(data.Error_Message.price);
         }
     },
  });
});
</script>
@endsection