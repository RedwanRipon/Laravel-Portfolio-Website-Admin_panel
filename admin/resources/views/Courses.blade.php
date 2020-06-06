@extends('Layout.app')
@section('title','Courses')
@section('content')



<div id="mainDivCourse" class="container d-none">
    <div class="row">
	     <div class="col-md-12 p-3">
	     	<button id="addNewCourseBtnId" class="btn my-3 btn-sm btn-danger">Add New Courses</button>
	        <table id="courseDataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
	            <thead>
	                <tr>
		                <th class="th-sm">Course Name</th>
		                <th class="th-sm">Course Fee</th>
		                <th class="th-sm">Total Class</th>
		                <th class="th-sm">Total Enroll</th>
		                <th class="th-sm">Edit</th>
		                <th class="th-sm">Delete</th>
	                </tr>
	            </thead>
	            <tbody id="course_table">

              </tbody>	  
	              
	        </table>
	    </div>
    </div>
</div>


<div id="loaderDivCourse" class="container">
	<div class="row">
		<div class="col-md-12 text-center p-5">
			<img class="loading-icon m-5" src="{{asset('images/loader.svg')}}">
		</div>
	</div>
</div>


<div id="wrongDivCourse" class="container d-none">
	<div class="row">
    	<div class="col-md-12 text-center p-5">
			<h3>Something went wrong!</h3>
		</div>
	</div>
</div>

<!---Add Course Modal--->

<div class="modal fade" id="addCourseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Add New Course</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body  text-center">
       <div class="container">
       	<div class="row">
       		<div class="col-md-6">
             	<input id="CourseNameId" type="text" id="" class="form-control mb-3" placeholder="Course Name">
          	 	<input id="CourseDesId" type="text" id="" class="form-control mb-3" placeholder="Course Description">
    		 	    <input id="CourseFeeId" type="text" id="" class="form-control mb-3" placeholder="Course Fee">
     			    <input id="CourseEnrollId" type="text" id="" class="form-control mb-3" placeholder="Total Enroll">
       		</div>
       		<div class="col-md-6">
     			<input id="CourseClassId" type="text" id="" class="form-control mb-3" placeholder="Total Class">      
     			<input id="CourseLinkId" type="text" id="" class="form-control mb-3" placeholder="Course Link">
     			<input id="CourseImgId" type="text" id="" class="form-control mb-3" placeholder="Course Image">
       		</div>
       	</div>
       </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Cancel</button>
        <button  id="CourseAddConfirmBtn" type="button" class="btn  btn-sm  btn-danger">Save</button>
      </div>
    </div>
  </div>
</div>




<!---Delete Modal--->
<div class="modal fade" id="deleteCourseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body p-3 text-center">
        <h5 class="mt-4">Do You Want To Delete?</h5>
        <h5  id="CourseDeleteId" class="mt-4"></h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">No</button>
        <button id="CourseDeleteConfirmBtn" type="button" class="btn  btn-sm  btn-danger">Yes</button>
      </div>
    </div>
  </div>
</div>



<!---Edit Modal--->
<div class="modal fade" id="updateCourseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Update Course</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body  text-center">

      	<h5 id="courseEditId" class="mt-4">  </h5>

       <div id="courseEditForm" class="container d-none">
       	<div class="row">
       		<div class="col-md-6">
             	<input id="CourseNameUpdateId" type="text" id="" class="form-control mb-3" placeholder="Course Name">
          	 	<input id="CourseDesUpdateId" type="text" id="" class="form-control mb-3" placeholder="Course Description">
    		 	    <input id="CourseFeeUpdateId" type="text" id="" class="form-control mb-3" placeholder="Course Fee">
     			    <input id="CourseEnrollUpdateId" type="text" id="" class="form-control mb-3" placeholder="Total Enroll">
       		</div>
       		<div class="col-md-6">
     			<input id="CourseClassUpdateId" type="text" id="" class="form-control mb-3" placeholder="Total Class">      
     			<input id="CourseLinkUpdateId" type="text" id="" class="form-control mb-3" placeholder="Course Link">
     			<input id="CourseImgUpdateId" type="text" id="" class="form-control mb-3" placeholder="Course Image">
       		</div>
       	</div>
       </div>
       <img id="courseEditLoader" class="loading-icon m-5" src="{{asset('images/loader.svg')}}">		
		<h5 id="courseEditWrong" class="d-none">Something went wrong!</h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Cancel</button>
        <button  id="CourseUpdateConfirmBtn" type="button" class="btn  btn-sm  btn-danger">Save</button>
      </div>
    </div>
  </div>
</div>


@endsection

@section('script')
<script type="text/javascript">
getCoursesData();


function getCoursesData(){
  axios.get('/getCoursesData')
  .then(function (response){
    if (response.status==200){
      $('#mainDivCourse').removeClass('d-none');
      $('#loaderDivCourse').addClass('d-none');
      $('#courseDataTable').DataTable().destroy();
      $('#course_table').empty();
      var JsonData=response.data;

      $.each(JsonData, function(i, item) {
        $('<tr>').html(     
          "<td>"+ JsonData[i].course_name +"</td>"+
          "<td>"+ JsonData[i].course_fee +"</td>"+
          "<td>"+ JsonData[i].course_totalclass +"</td>"+
          "<td>"+ JsonData[i].course_totalenroll +"</td>"+
          "<td><a class='courseEditBtn' data-id="+JsonData[i].id+" ><i class='fas fa-edit'></i></a></td>"+
          "<td><a class='courseDeleteBtn' data-id="+JsonData[i].id+"  ><i class='fas fa-trash-alt'></i></a></td>"
          ).appendTo('#course_table');
      });
      //services table open delete icon click
      $('.courseDeleteBtn').click(function(){
        var id = $(this).data('id');
        $('#CourseDeleteId').html(id);
        $('#deleteCourseModal').modal('show');
      })
      //services table edit open when icon click
      $('.courseEditBtn').click(function(){
        var id = $(this).data('id');
        $('#courseEditId').html(id);
        CourseUpdateDetails(id);
        $('#updateCourseModal').modal('show');
      })
      $('#courseDataTable').DataTable({"order":false});
      $('.dataTables_length').addClass('bs-select');
    }
    else{
      $('#loaderDivCourse').addClass('d-none');
      $('#wrongDivCourse').removeClass('d-none');
    }
  }).catch(function (error) {
    $('#loaderDivCourse').addClass('d-none');
    $('#wrongDivCourse').removeClass('d-none');
  });
}

//service delete modal yes button
    $('#CourseDeleteConfirmBtn').click(function(){
        var id = $('#CourseDeleteId').html();
        CourseDelete(id);
    });

//service delete when click yes
function CourseDelete(deleteID){
  $('#CourseDeleteConfirmBtn').html("<div class='spinner-border spinner-border-sm' role='status'></div>") //Animation
  axios.post('/CoursesDelete',{
    id:deleteID
  })
  .then(function(response){
    $('#CourseDeleteConfirmBtn').html("Yes");
    if (response.status==200) {
      if (response.data==1) {
      $('#deleteCourseModal').modal('hide');
      toastr.success('Deleted Successfully');
      getCoursesData();
    }else{
      $('#deleteCourseModal').modal('hide');
      toastr.error('Operation Failed!');
      getCoursesData();
    }

  }else{
      $('#deleteCourseModal').modal('hide');
      toastr.error('Something went wrong!'); 
  }

  }).catch(function (error) {
      $('#deleteCourseModal').modal('hide');
      toastr.error('Something went wrong!');
  });

}

//service edit data show when click edit icon
function CourseUpdateDetails(detailsID){
  axios.post('/CoursesDetails',{
    id: detailsID
   })
  .then(function(response){
    if (response.status==200) {
      $('#courseEditForm').removeClass('d-none');
      $('#courseEditLoader').addClass('d-none');
      var JsonData=response.data;
      $('#CourseNameUpdateId').val(JsonData[0].course_name );
      $('#CourseDesUpdateId').val(JsonData[0].course_des );
      $('#CourseFeeUpdateId').val(JsonData[0].course_fee );
      $('#CourseEnrollUpdateId').val(JsonData[0].course_totalenroll);
       $('#CourseClassUpdateId').val(JsonData[0].course_totalclass  );
      $('#CourseLinkUpdateId').val(JsonData[0].course_link );
      $('#CourseImgUpdateId').val(JsonData[0].course_img );
    }else{
      $('#courseEditLoader').addClass('d-none');
      $('#courseEditWrong').removeClass('d-none');
    }
    
  }).catch(function (error) {
  
    $('#courseEditLoader').addClass('d-none');
    $('#courseEditWrong').removeClass('d-none');
  });

}

  //service edit update modal save button
  $('#CourseUpdateConfirmBtn').click(function(){
     var id   = $('#courseEditId').html();
     var course_name   = $('#CourseNameUpdateId').val();
     var course_des  = $('#CourseDesUpdateId').val();
     var course_fee  = $('#CourseFeeUpdateId').val();
     var course_totalenroll  = $('#CourseEnrollUpdateId').val();
     var course_totalclass  = $('#CourseClassUpdateId').val();
     var course_link  = $('#CourseLinkUpdateId').val();
     var course_img   = $('#CourseImgUpdateId').val();
     CourseUpdate(id,course_name,course_des,course_fee,course_totalenroll,course_totalclass,course_link,course_img);
  })

  //service update when save clicked
function CourseUpdate(courseID,courseName,courseDes,courseFee,courseEnroll,courseClass,courseLink,courseImg){
  if (courseName.length==0) {
        toastr.error('Course Name is Required');
  }
  else if (courseDes.length==0) {
        toastr.error('Course Description is Required');
  }
  else if (courseFee.length==0) {
        toastr.error('Course Fee is Required');
  }
  else if (courseEnroll.length==0) {
    toastr.error('Course Enroll is Required');
  }
  else if (courseClass.length==0) {
        toastr.error('Course Class is Required');
  }
  else if (courseLink.length==0) {
        toastr.error('Course Link is Required');
  }
  else if (courseImg.length==0) {
    toastr.error('Course Image is Required');
  }
  else{
      $('#CourseUpdateConfirmBtn').html("<div class='spinner-border spinner-border-sm' role='status'></div>") //Animation
      axios.post('/CoursesUpdate',{

      id: courseID,
      course_name: courseName,
      course_des: courseDes,
      course_fee: courseFee,
      course_totalenroll : courseEnroll,
      course_totalclass: courseClass,
      course_link: courseLink,
      course_img : courseImg
   })
  .then(function(response){
    $('#CourseUpdateConfirmBtn').html("Save");
    if (response.status==200) {
      if (response.data==1) {
      $('#updateCourseModal').modal('hide');
      toastr.success('Updated Successfully');
      getCoursesData();
    }else{
      $('#updateCourseModal').modal('hide');
      toastr.error('Operation Failed!');
      getCoursesData();
    }

  }else{
    $('#updateCourseModal').modal('hide');
    toastr.error('Something went wrong!');
  }
    
  }).catch(function (error) {
  
    $('#updateCourseModal').modal('hide');  
    toastr.error('Something went wrong!');
  });

  }
}

//add confirm 
$('#addNewCourseBtnId').click(function(){
$('#addCourseModal').modal('show');
});

//service add update modal save button
  $('#CourseAddConfirmBtn').click(function(){

      var courseName = $('#CourseNameId').val();
      var courseDes = $('#CourseDesId').val();
      var courseFee = $('#CourseFeeId').val();
      var courseEnroll = $('#CourseEnrollId').val();
      var courseClass = $('#CourseClassId').val();
      var courseLink = $('#CourseLinkId').val();
      var courseImg = $('#CourseImgId').val();
      CourseAdd(courseName,courseDes,courseFee,courseEnroll,courseClass,courseLink,courseImg);
  })

// Course Add method 
function CourseAdd(courseName,courseDes,courseFee,courseEnroll,courseClass,courseLink,courseImg){

  if (courseName.length==0) {
        toastr.error('Course Name is Required');
  }
  else if (courseDes.length==0) {
        toastr.error('Course Description is Required');
  }
  else if (courseFee.length==0) {
    toastr.error('Course Fee is Required');
  }
  else if (courseEnroll.length==0) {
    toastr.error('Course Enroll is Required');
  }
  else if (courseClass.length==0) {
        toastr.error('Course Class is Required');
  }
  else if (courseLink.length==0) {
    toastr.error('Course Link is Required');
  }
  else if (courseImg.length==0) {
    toastr.error('Course Image is Required');
  }
  else{
        $('#CourseAddConfirmBtn').html("<div class='spinner-border spinner-border-sm' role='status'></div>") //Animation
        axios.post('/CoursesAdd',{

          course_name : courseName,
          course_des: courseDes,
          course_fee : courseFee,
          course_totalenroll  :courseEnroll,
          course_totalclass : courseClass,
          course_link : courseLink,
          course_img  :courseImg
   })
  .then(function(response){
    $('#CourseAddConfirmBtn').html("Save");
    if (response.status==200) {
      if (response.data==1) {
      $('#addCourseModal').modal('hide');
      toastr.success('Added Successfully');
      getCoursesData();
    }else{
      $('#addCourseModal').modal('hide');
      toastr.error('Operation Failed!');
      getCoursesData();
    }

  }else{
    $('#addCourseModal').modal('hide');
    toastr.error('Something went wrong!');
  }
    
  }).catch(function (error) { 
    $('#addCourseModal').modal('hide');  
    toastr.error('Something went wrong!');
  });

  }
}


</script>
@endsection