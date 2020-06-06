@extends('Layout.app')
@section('title','Contact')
@section('content')


<div id="mainDivContact" class="container d-none">
<div class="row">
<div class="col-md-12 p-3">

<table id="contactDataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th class="th-sm">Name</th>
	  <th class="th-sm">Mobile</th>
	  <th class="th-sm">Email</th>
	  <th class="th-sm">Message</th>
	  <th class="th-sm">Delete</th>
    </tr>
  </thead>
  <tbody id="contact_table">

	
  </tbody>
</table>

</div>
</div>
</div>


<div id="loaderDivContact" class="container">
	<div class="row">
		<div class="col-md-12 text-center p-5">
			<img class="loading-icon m-5" src="{{asset('images/loader.svg')}}">
		</div>
	</div>
</div>


<div id="wrongDivContact" class="container d-none">
	<div class="row">
    	<div class="col-md-12 text-center p-5">
			<h3>Something went wrong!</h3>
		</div>
	</div>
</div>


<!---Delete Modal--->
<div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body p-3 text-center">
        <h5 class="mt-4">Do You Want To Delete?</h5>
        <h5  id="contactDeleteId" class="mt-4"></h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">No</button>
        <button id="contactDeleteConfirmBtn" type="button" class="btn  btn-sm  btn-danger">Yes</button>
      </div>
    </div>
  </div>
</div>


@endsection



@section('script')

<script type="text/javascript">
	getContactData();

//for contact table
function getContactData(){
  axios.get('/getContactData')
    .then(function (response) {
     if (response.status==200) {
      $('#mainDivContact').removeClass('d-none');
      $('#loaderDivContact').addClass('d-none');

      $('#contactDataTable').DataTable().destroy();
      $('#contact_table').empty();

      var JsonData=response.data;
      $.each(JsonData, function(i, item) {
      $('<tr>').html(     
        "<td>"+ JsonData[i].contact_name +"</td>"+
        "<td>"+ JsonData[i].contact_mobile +"</td>"+
        "<td>"+ JsonData[i].contact_email +"</td>"+
        "<td>"+ JsonData[i].contact_msg +"</td>"+
        "<td><a class='contactDeleteBtn' data-id="+JsonData[i].id+"  ><i class='fas fa-trash-alt'></i></a></td>"
        ).appendTo('#contact_table');
   });
      //contact table open delete icon click
      $('.contactDeleteBtn').click(function(){
        var id = $(this).data('id');
        $('#contactDeleteId').html(id);
        $('#contactModal').modal('show');
      })
 
      $('#contactDataTable').DataTable({"order":false});
      $('.dataTables_length').addClass('bs-select');

    }else{

      $('#loaderDivContact').addClass('d-none');
      $('#wrongDivContact').removeClass('d-none');
    }

}).catch(function (error) {

  $('#loaderDivContact').addClass('d-none');
    $('#wrongDivContact').removeClass('d-none');
});

}

//contact delete modal yes button
      $('#contactDeleteConfirmBtn').click(function(){
        var id = $('#contactDeleteId').html();
        ContactDelete(id);
    });

//contact delete when click yes
function ContactDelete(deleteID){
  $('#contactDeleteConfirmBtn').html("<div class='spinner-border spinner-border-sm' role='status'></div>") //Animation
  axios.post('/ContactDelete',{
    id:deleteID
  })
  .then(function(response){
    $('#contactDeleteConfirmBtn').html("Yes");
    if (response.status==200) {
      if (response.data==1) {
      $('#contactModal').modal('hide');
      toastr.success('Deleted Successfully');
      getContactData();
    }else{
      $('#contactModal').modal('hide');
      toastr.error('Operation Failed!');
      getContactData();
    }

  }else{

      $('#contactModal').modal('hide');
      toastr.error('Something went wrong!'); 
  }

  }).catch(function (error) {
      $('#contactModal').modal('hide');
      toastr.error('Something went wrong!');
});

}


</script>

@endsection