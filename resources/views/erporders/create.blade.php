@extends('template')
@section('content')


<script type="text/javascript">
// Get the <datalist> and <input> elements.
var dataList = document.getElementById('json-datalist');
var input = document.getElementById('ajax');

// Create a new XMLHttpRequest.
var request = new XMLHttpRequest();

// Handle state changes for the request.
request.onreadystatechange = function(response) {
  if (request.readyState === 4) {
    if (request.status === 200) {
      // Parse the JSON
      var jsonOptions = JSON.parse(request.responseText);
  
      // Loop over the JSON array.
      jsonOptions.forEach(function(item) {
        // Create a new <option> element.
        var option = document.createElement('option');
        // Set the value using the item in the JSON array.
        option.value = item;
        // Add the <option> element to the <datalist>.
        dataList.appendChild(option);
      });
      
      // Update the placeholder text.
      input.placeholder = "e.g. datalist";
    } else {
      // An error occured :(
      input.placeholder = "Couldn't load datalist options :(";
    }
  }
};

// Update the placeholder text.
input.placeholder = "Loading options...";

// Set up and make the request.
request.open('GET', 'https://s3-us-west-2.amazonaws.com/s.cdpn.io/4621/html-elements.json', true);
request.send();

</script>

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
        <h3 class="box-title">New Sales Order</h3>
        <div class="box-tools pull-right">
          
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
      </div>
    </div>

      <!-- /.box-header -->
      <div class="box-body">
        <font color="red"><i>All fields marked with * are mandatory</i></font>
		 <form method="POST" action="{{{ URL::to('erporders/create') }}}" accept-charset="UTF-8">
   {{ csrf_field() }}
    <fieldset>
         <div class="form-group">
            <label for="username">Order Number:</label>
            <input type="text" name="order_number" value="{{$order_number}}" class="form-control" readonly>
        </div>

        <div class="form-group">
                        <label for="username">Date</label>
                        <div class="right-inner-addon ">
                        <i class="glyphicon glyphicon-calendar"></i>
                        <input class="form-control datepicker"  readonly="readonly" placeholder="" type="text" name="date" id="date" value="{{date('Y-M-d')}}">
                        </div>
          </div>


          <div class="form-group" id="default">
            <label for="username">Client <span style="color:red">*</span> :</label>
            <select name="client" class="form-control select2" id="languages" list="clients" required>
                @foreach($clients as $client)
                @if($client->type == 'Customer')
                    <option value="{{$client->id}}">{{$client->name}}</option>
                    @endif
                @endforeach
            </select>
        </div>


        <div class="form-group">
            <label for="username">Sale Type <span style="color:red">*</span> :</label>
            <select name="payment_type" class="form-control select2" required>
                
                    <option value="cash">Cash</option>
                    <option value="credit">Credit</option>
                    
            </select>
        </div>

        

        <div class="form-actions form-group">
        
          <button type="submit" class="btn btn-primary btn-sm">Create</button>
        </div>

    </fieldset>
</form>
		</div>
  </div>

  </div>

</div>

@stop