@extends('Layout.app')
@section('title','Review')
@section('content')



<div id="mainDivReview" class="container d-none">
<div class="row">
<div class="col-md-12 p-3">

	<button id="addNewReviewBtnId" class="btn my-3 btn-sm btn-danger">Add New Review</button>



<table id="reviewDataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th class="th-sm">Image</th>
  	  <th class="th-sm">Name</th>
  	  <th class="th-sm">Description</th>
  	  <th class="th-sm">Edit</th>
  	  <th class="th-sm">Delete</th>
    </tr>
  </thead>
  <tbody id="review_table">

	
  </tbody>
</table>

</div>
</div>
</div>


<div id="loaderDivReview" class="container">
	<div class="row">
		<div class="col-md-12 text-center p-5">
			<img class="loading-icon m-5" src="{{asset('images/loader.svg')}}">
		</div>
	</div>
</div>


<div id="wrongDivReview" class="container d-none">
	<div class="row">
    	<div class="col-md-12 text-center p-5">
			<h3>Something went wrong!</h3>
		</div>
	</div>
</div>





<!---Delete Modal--->
<div class="modal fade" id="deleteModalReview" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body p-3 text-center">
        <h5 class="mt-4">Do You Want To Delete?</h5>
        <h5  id="reviewDeleteId" class="mt-4"></h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">No</button>
        <button id="reviewDeleteConfirmBtn" type="button" class="btn  btn-sm  btn-danger">Yes</button>
      </div>
    </div>
  </div>
</div>

<!---Edit Modal--->
<div class="modal fade" id="editModalReview" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body p-5 text-center">

      	<h5 id="reviewEditId" class="mt-4">  </h5>

      	<div id="reviewEditForm" class="w-100 d-none">
	      	<input id="reviewNameID" type="text" id="" class="form-control mb-4" placeholder="Service Name">
	        <input id="reviewDesID" type="text" id="" class="form-control mb-4" placeholder="Service Description">
	        <input id="reviewImgID" type="text" id="" class="form-control mb-4" placeholder="Service Image Link">
        </div>
		<img id="reviewEditLoader" class="loading-icon m-5" src="{{asset('images/loader.svg')}}">		
		<h5 id="reviewEditWrong" class="d-none">Something went wrong!</h5>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Cancel</button>
        <button id="reviewEditConfirmBtn" type="button" class="btn  btn-sm  btn-danger">Save</button>
      </div>
    </div>
  </div>
</div>



<!--Add Service-->
<div class="modal fade" id="addModalReview" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body p-5 text-center">
 		
      	<div id="reviewAddForm" class="w-100">
      		<h6 class="mb-4">Add New Reviews</h6>
	      	<input id="reviewNameAddID" type="text" id="" class="form-control mb-4" placeholder="Service Name">
	        <input id="reviewDesAddID" type="text" id="" class="form-control mb-4" placeholder="Service Description">
	        <input id="reviewImgAddID" type="text" id="" class="form-control mb-4" placeholder="Service Image Link">
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Cancel</button>
        <button id="reviewAddConfirmBtn" type="button" class="btn  btn-sm  btn-danger">Save</button>
      </div>
    </div>
  </div>
</div>


@endsection


@section('script')

<script type="text/javascript">
	getReviewData();


//for review table
function getReviewData(){
  axios.get('/getReviewData')
    .then(function (response) {
    if (response.status==200) {
      $('#mainDivReview').removeClass('d-none');
      $('#loaderDivReview').addClass('d-none');

      $('#reviewDataTable').DataTable().destroy();
      $('#review_table').empty();

      var JsonData=response.data;

      $.each(JsonData, function(i, item) {
      $('<tr>').html(     
        "<td><img class='table-img' src="+JsonData[i].review_img +"></td>"+
        "<td>"+ JsonData[i].review_name +"</td>"+
        "<td>"+ JsonData[i].review_des +"</td>"+
        "<td><a class='reviewEditBtn' data-id="+JsonData[i].id+" ><i class='fas fa-edit'></i></a></td>"+
        "<td><a class='reviewDeleteBtn' data-id="+JsonData[i].id+"  ><i class='fas fa-trash-alt'></i></a></td>"
        ).appendTo('#review_table');
   });
      //review table open delete icon click
      $('.reviewDeleteBtn').click(function(){
        var id = $(this).data('id');
        $('#reviewDeleteId').html(id);
        $('#deleteModalReview').modal('show');
      })
 
      //review table edit open when icon click
      $('.reviewEditBtn').click(function(){
        var id = $(this).data('id');
        $('#reviewEditId').html(id);
        ReviewUpdateDetails(id);
        $('#editModalReview').modal('show');
      })

      $('#serviceDataTable').DataTable({"order":false});
      $('.dataTables_length').addClass('bs-select');

    }else{

      $('#loaderDivReview').addClass('d-none');
      $('#wrongDivReview').removeClass('d-none');
    }

}).catch(function (error) {

  $('#loaderDivReview').addClass('d-none');
    $('#wrongDivReview').removeClass('d-none');
});

}

  //service delete modal yes button
      $('#reviewDeleteConfirmBtn').click(function(){
        var id = $('#reviewDeleteId').html();
        ReviewDelete(id);
      });

//service delete when click yes
function ReviewDelete(deleteID){

  $('#reviewDeleteConfirmBtn').html("<div class='spinner-border spinner-border-sm' role='status'></div>") //Animation
  axios.post('/ReviewDelete',{
    id:deleteID
  })
  .then(function(response){
    $('#reviewDeleteConfirmBtn').html("Yes");
    if (response.status==200) {
      if (response.data==1) {
      $('#deleteModalReview').modal('hide');
      toastr.success('Deleted Successfully');
      getReviewData();
    }else{
      $('#deleteModalReview').modal('hide');
      toastr.error('Operation Failed!');
      getReviewData();
    }

  }else{

      $('#deleteModalReview').modal('hide');
    toastr.error('Something went wrong!'); 
  }

  }).catch(function (error) {

      $('#deleteModalReview').modal('hide');
    toastr.error('Something went wrong!');
});

}


//service edit data show when click edit icon
function ReviewUpdateDetails(detailsID){
  axios.post('/ReviewDetails',{
    id: detailsID
   })
  .then(function(response){
    if (response.status==200) {
      $('#reviewEditForm').removeClass('d-none');
      $('#reviewEditLoader').addClass('d-none');

      var JsonData=response.data;
      $('#reviewNameID').val(JsonData[0].review_name);
      $('#reviewDesID').val(JsonData[0].review_des);
      $('#reviewImgID').val(JsonData[0].review_img);
    }else{
      $('#reviewEditLoader').addClass('d-none');
      $('#reviewEditWrong').removeClass('d-none');
    }
    
  }).catch(function (error) {
  
    $('#reviewEditLoader').addClass('d-none');
    $('#reviewEditWrong').removeClass('d-none');
  });

}

  //service edit update modal save button
  $('#reviewEditConfirmBtn').click(function(){

      var id = $('#reviewEditId').html();
      var review_name = $('#reviewNameID').val();
      var review_des = $('#reviewDesID').val();
      var review_img = $('#reviewImgID').val();
      ReviewUpdate(id,review_name,review_des,review_img);
  })


//service update when save clicked
function ReviewUpdate(reviewID,reviewName,reviewDes,reviewImg){
  if (reviewName.length==0) {
        toastr.error('Review Name is Required');
  }
  else if (reviewDes.length==0) {
        toastr.error('Review Description is Required');
  }
  else if (reviewImg.length==0) {
    toastr.error('Review Image is Required');
  }
  else{
        $('#reviewEditConfirmBtn').html("<div class='spinner-border spinner-border-sm' role='status'></div>") //Animation
    axios.post('/ReviewUpdate',{
    id: reviewID,
    review_name: reviewName,
    review_des: reviewDes,
    review_img: reviewImg
   })
  .then(function(response){
    $('#reviewEditConfirmBtn').html("Save");

    if (response.status==200) {

      if (response.data==1) {
      $('#editModalReview').modal('hide');
      toastr.success('Updated Successfully');
      getReviewData();
    }else{
      $('#editModalReview').modal('hide');
      toastr.error('Operation Failed!');
      getReviewData();
    }

  }else{
    $('#editModalReview').modal('hide');
    toastr.error('Something went wrong!');
  }
    
  }).catch(function (error) {
  
    $('#editModalReview').modal('hide');  
    toastr.error('Something went wrong!');
  });

  }
}

// Service Add New Btn Click

$('#addNewReviewBtnId').click(function(){
  $('#addModalReview').modal('show');
});


  //service add update modal save button
  $('#reviewAddConfirmBtn').click(function(){

      var review_name = $('#reviewNameAddID').val();
      var review_des = $('#reviewDesAddID').val();
      var review_img = $('#reviewImgAddID').val();
      ReviewAdd(review_name,review_des,review_img);
  })


// Review Add method 

function ReviewAdd(reviewName,reviewDes,reviewImg){

  if (reviewName.length==0) {
        toastr.error('Review Name is Required');
  }
  else if (reviewDes.length==0) {
        toastr.error('Review Description is Required');
  }
  else if (reviewImg.length==0) {
    toastr.error('Review Image is Required');
  }
  else{
        $('#reviewAddConfirmBtn').html("<div class='spinner-border spinner-border-sm' role='status'></div>") //Animation
    axios.post('/ReviewAdd',{

    review_name: reviewName,
    review_des: reviewDes,
    review_img: reviewImg
   })
  .then(function(response){
    $('#reviewAddConfirmBtn').html("Save");

    if (response.status==200) {

      if (response.data==1) {
      $('#addModalReview').modal('hide');
      toastr.success('Added Successfully');
      getReviewData();
    }else{
      $('#addModalReview').modal('hide');
      toastr.error('Operation Failed!');
      getReviewData();
    }

  }else{
    $('#addModalReview').modal('hide');
    toastr.error('Something went wrong!');
  }
    
  }).catch(function (error) { 
    $('#addModalReview').modal('hide');  
    toastr.error('Something went wrong!');
  });

  }
}


</script>

@endsection