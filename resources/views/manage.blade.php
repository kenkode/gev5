@extends('template')

@section('content')
<div class="row">
  <div class="col-md-4">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Manage Gases</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div class="form-group">
          <label>Gas Type:</label>
          <select class="form-control select2" style="width: 100%;">
            <option selected="selected">Alabama</option>
            <option>Alaska</option>
            <option disabled="disabled">California (disabled)</option>
            <option>Delaware</option>
            <option>Tennessee</option>
            <option>Texas</option>
            <option>Washington</option>
          </select>
        </div>
        <div class="form-group">
          <label>Gas Size:</label>
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-database"></i>
            </div>
            <input type="text" class="form-control pull-right" id="reservationtime">
          </div>
        </div>
        <div class="form-group">
          <label>Gas Price:</label>

          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-dollar"></i>
            </div>
            <input type="text" class="form-control pull-right" id="reservation">
          </div>
        </div>
        <button type="button" class="btn btn-primary btn-block pull-right">Submit</button>
      </div>
      <div class="box-footer">
        <form action="#" method="post">
          <div class="input-group">
            <input type="text" name="message" placeholder="Add Gas Type" class="form-control">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-primary btn-flat">Add</button>
                </span>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="box box-warning direct-chat direct-chat-success">
      <div class="box-header with-border">
        <h3 class="box-title">Product</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div class="col-md-12">
          <div class="form-group">
            <label>Type:</label>
            <select class="form-control select2" style="width: 100%;">
              <option selected="selected">Service</option>
              <option>Accessory</option>
            </select>
          </div>
          <div class="form-group">
            <label>Name:</label>
            <div class="input-group">
              <!-- <div class="input-group-addon">
                <i class="fa fa-dollar"></i>
              </div> -->
              <input type="text" class="form-control" id="reservation">
            </div>
          </div>
          <div class="form-group">
            <label>Price:</label>
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-dollar"></i>
              </div>
              <input type="text" class="form-control pull-right" id="reservation">
            </div>
          </div>
        </div>
      </div>
      <div class="box-footer">
        <button type="button" class="btn btn-primary btn-block pull-right">Submit</button>
      </div>
    </div>
    <!--/.direct-chat -->
  </div>
  <!-- /.col -->
  <div class="col-md-4">
    <div class="box box-warning direct-chat direct-chat-success">
      <div class="box-header with-border">
        <h3 class="box-title">Bulk Gas</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div class="col-md-12">
          <div class="form-group">
            <label>Current Price: 125</label>
          </div>          
          <div class="form-group">
            <label>Price:</label>
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-dollar"></i>
              </div>
              <input type="text" class="form-control pull-right" id="reservation">
            </div>
          </div>
        </div>
      </div>
      <div class="box-footer">
        <button type="button" class="btn btn-primary btn-block pull-right">Submit</button>
      </div>
    </div>
    <!--/.direct-chat -->
  </div>
</div>
@endsection