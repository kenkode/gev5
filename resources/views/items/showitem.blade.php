@extends('template')
@section('content')

<div class="row">
    <div class="col-lg-6">
      @if ( count( $errors ) > 0 )
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>        
            @endforeach
        </div>
        @endif
     <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Approve Item</h3>
        <div class="box-tools pull-right">
    
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
      </div>
    </div>
      <!-- /.box-header -->
      <div class="box-body">

         <form method="POST" action="{{{ URL::to('notificationapproveitemupdate') }}}" accept-charset="UTF-8">
         {{ csrf_field() }}
         <input type="hidden" name="id" value="{{$id}}">
         <input type="hidden" name="key" value="{{$key}}">
         <input type="hidden" name="confirmer" value="{{$confirmer}}">
         <input type="hidden" name="receiver" value="{{$receiver}}">
    <fieldset>
        <div class="form-group">
            <label for="username">Item Name <span style="color:red">*</span> :</label>
            <input class="form-control" placeholder="" type="text" readonly name="name" id="name" value="{{$name}}" required>
        </div>

        <div class="form-group">
            <label for="username">Item Size <span style="color:red">*</span> :</label>
            <input class="form-control" placeholder="" type="text" readonly name="item_size" id="item_size" value="{{$size}}" required>
        </div>

         <div class="form-group">
            <label for="username">Description:</label>
            <textarea rows="5" class="form-control" name="description" readonly id="description" >{{$description}}</textarea>
        </div>

        <div class="form-group">
            <label for="username">Purchase Price <span style="color:red">*</span> :</label>
            <input class="form-control" placeholder="" type="text" readonly name="pprice" id="pprice" value="{{$pprice}}" required>
        </div>

        <div class="form-group">
            <label for="username">Selling price <span style="color:red">*</span> :</label>
            <input class="form-control" placeholder="" type="text" readonly name="sprice" id="sprice" value="{{$sprice}}" required>
        </div>

        <div class="form-group">
            <label for="username">Store Keeping Unit:</label>
            <input class="form-control" placeholder="" type="text" readonly name="sku" id="sku" value="{{$sku}}">
        </div>

        <div class="form-group">
            <label for="username">Tag Id:</label>
            <input class="form-control" placeholder="" type="text" readonly name="tag" id="tag" value="{{$tagid}}">
        </div>
        
        <div class="form-group">
            <label for="username">Reorder Level:</label>
            <input class="form-control" placeholder="" type="text" readonly name="reorder" id="reorder" value="{{$reorderlevel}}">
        </div>

        <div class="form-actions form-group">
        
          <button type="submit" class="btn btn-success btn-sm">Approve Item Update</button>
          <!-- <button type="submit" class="btn btn-danger btn-sm">Reject Item Update</button> -->
        </div>

    </fieldset>
</form>
   </div>
  </div>
</div>
</div>

@stop