@extends('Layout.app')
@section('title','Projects')
@section('content')



<div id="mainDivProject" class="container d-none">
    <div class="row">
	     <div class="col-md-12 p-3">
	     	<button id="addNewProjectBtnId" class="btn my-3 btn-sm btn-danger">Add New Project</button>
	        <table id="projectDataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
	            <thead>
	                <tr>
		                <th class="th-sm">Name</th>
		                <th class="th-sm">Description</th>
		                <th class="th-sm">Edit</th>
		                <th class="th-sm">Delete</th>
	                </tr>
	            </thead>
	            <tbody id="project_table">	 

              </tbody>               
	        </table>
	    </div>
    </div>
</div>


<div id="loaderDivProject" class="container">
	<div class="row">
		<div class="col-md-12 text-center p-5">
			<img class="loading-icon m-5" src="{{asset('images/loader.svg')}}">
		</div>
	</div>
</div>


<div id="wrongDivProject" class="container d-none">
	<div class="row">
    	<div class="col-md-12 text-center p-5">
			<h3>Something went wrong!</h3>
		</div>
	</div>
</div>

<!---Add Course Modal--->

<div class="modal fade" id="addProjectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
       		<div class="col-md-12">
             	<input id="ProjectNameId" type="text" id="" class="form-control mb-3" placeholder="Project Name">
          	 	<input id="ProjectDesId" type="text" id="" class="form-control mb-3" placeholder="Project Description">
    		 	    <input id="ProjectLinkId" type="text" id="" class="form-control mb-3" placeholder="Project Link">
     			    <input id="ProjectImgId" type="text" id="" class="form-control mb-3" placeholder="Project Image">
       		</div>
       	</div>
       </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Cancel</button>
        <button  id="ProjectAddConfirmBtn" type="button" class="btn  btn-sm  btn-danger">Save</button>
      </div>
    </div>
  </div>
</div>




<!---Delete Modal--->
<div class="modal fade" id="deleteProjectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body p-3 text-center">
        <h5 class="mt-4">Do You Want To Delete?</h5>
        <h5  id="ProjectDeleteId" class="mt-4"></h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">No</button>
        <button id="ProjectDeleteConfirmBtn" type="button" class="btn  btn-sm  btn-danger">Yes</button>
      </div>
    </div>
  </div>
</div>



<!---Edit Modal--->
<div class="modal fade" id="updateProjectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Update Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body  text-center">

      	<h5 id="projectEditId" class="mt-4">  </h5>

       <div id="projectEditForm" class="container d-none">
       	<div class="row">
       		<div class="col-md-12">
             	<input id="ProjectNameUpdateId" type="text" id="" class="form-control mb-3" placeholder="Course Name">
          	 	<input id="ProjectDesUpdateId" type="text" id="" class="form-control mb-3" placeholder="Course Description">
    		 	    <input id="ProjectLinkUpdateId" type="text" id="" class="form-control mb-3" placeholder="Course Fee">
     			    <input id="ProjectImgUpdateId" type="text" id="" class="form-control mb-3" placeholder="Total Enroll">
       		</div>
       	</div>
       </div>
       <img id="projectEditLoader" class="loading-icon m-5" src="{{asset('images/loader.svg')}}">		
		   <h5 id="projectEditWrong" class="d-none">Something went wrong!</h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Cancel</button>
        <button  id="ProjectUpdateConfirmBtn" type="button" class="btn  btn-sm  btn-danger">Save</button>
      </div>
    </div>
  </div>
</div>


@endsection

@section('script')
<script type="text/javascript">
getProjectData();




function getProjectData(){
  axios.get('/getProjectData')
  .then(function (response){
    if (response.status==200){
      $('#mainDivProject').removeClass('d-none');
      $('#loaderDivProject').addClass('d-none');
      $('#projectDataTable').DataTable().destroy();
      $('#project_table').empty();
      var JsonData=response.data;

      $.each(JsonData, function(i, item) {
        $('<tr>').html(     
          "<td>"+ JsonData[i].project_name +"</td>"+
          "<td>"+ JsonData[i].project_desc +"</td>"+
          "<td><a class='projectEditBtn' data-id="+JsonData[i].id+" ><i class='fas fa-edit'></i></a></td>"+
          "<td><a class='projectDeleteBtn' data-id="+JsonData[i].id+"  ><i class='fas fa-trash-alt'></i></a></td>"
          ).appendTo('#project_table');
      });
      //services table open delete icon click
      $('.projectDeleteBtn').click(function(){
        var id = $(this).data('id');
        $('#ProjectDeleteId').html(id);
        $('#deleteProjectModal').modal('show');
      })
      //services table edit open when icon click
      $('.projectEditBtn').click(function(){
        var id = $(this).data('id');
        $('#projectEditId').html(id);
        ProjectUpdateDetails(id);
        $('#updateProjectModal').modal('show');
      })
      $('#projectDataTable').DataTable({"order":false});
      $('.dataTables_length').addClass('bs-select');
    }
    else{
      $('#loaderDivProject').addClass('d-none');
      $('#wrongDivProject').removeClass('d-none');
    }
  }).catch(function (error) {
    $('#loaderDivProject').addClass('d-none');
    $('#wrongDivProject').removeClass('d-none');
  });
}

//service delete modal yes button
    $('#ProjectDeleteConfirmBtn').click(function(){
        var id = $('#ProjectDeleteId').html();
        ProjectDelete(id);
    });

//service delete when click yes
function ProjectDelete(deleteID){
  $('#ProjectDeleteConfirmBtn').html("<div class='spinner-border spinner-border-sm' role='status'></div>") //Animation
  axios.post('/ProjectDelete',{
    id:deleteID
  })
  .then(function(response){
    $('#ProjectDeleteConfirmBtn').html("Yes");

    if (response.status==200) {

      if (response.data==1) {
      $('#deleteProjectModal').modal('hide');
      toastr.success('Deleted Successfully');
      getProjectData();
    }else{
      $('#deleteProjectModal').modal('hide');
      toastr.error('Operation Failed!');
      getProjectData();
    }

  }else{
      $('#deleteProjectModal').modal('hide');
      toastr.error('Something went wrong!'); 
  }

  }).catch(function (error) {
      $('#deleteProjectModal').modal('hide');
      toastr.error('Something went wrong!');
  });

}

//service edit data show when click edit icon
function ProjectUpdateDetails(detailsID){
  axios.post('/ProjectDetails',{
    id: detailsID
   })
  .then(function(response){
    if (response.status==200) {
      $('#projectEditForm').removeClass('d-none');
      $('#projectEditLoader').addClass('d-none');
      var JsonData=response.data;
      $('#ProjectNameUpdateId').val(JsonData[0].project_name);
      $('#ProjectDesUpdateId').val(JsonData[0].project_desc);
      $('#ProjectLinkUpdateId').val(JsonData[0].project_link);
      $('#ProjectImgUpdateId').val(JsonData[0].project_img);
    }else{
      $('#projectEditLoader').addClass('d-none');
      $('#projectEditWrong').removeClass('d-none');
    }
    
  }).catch(function (error) {
  
    $('#projectEditLoader').addClass('d-none');
    $('#projectEditWrong').removeClass('d-none');
  });

}

  //service edit update modal save button
  $('#ProjectUpdateConfirmBtn').click(function(){
     var id   = $('#projectEditId').html();
     var project_name  = $('#ProjectNameUpdateId').val();
     var project_desc = $('#ProjectDesUpdateId').val();
     var project_link = $('#ProjectLinkUpdateId').val();
     var project_img  = $('#ProjectImgUpdateId').val();
     ProjectUpdate(id,project_name ,project_desc,project_link,project_img );
  })

  //service update when save clicked
function ProjectUpdate(projectID,projectName,projectDesc,projectLink,projectImg){
  if (projectName.length==0) {
        toastr.error('Project Name is Required');
  }
  else if (projectDesc.length==0) {
        toastr.error('Project Description is Required');
  }
  else if (projectLink.length==0) {
        toastr.error('Project Link is Required');
  }
  else if (projectImg.length==0) {
    toastr.error('Project Image is Required');
  }
  else{
      $('#ProjectUpdateConfirmBtn').html("<div class='spinner-border spinner-border-sm' role='status'></div>") //Animation
      axios.post('/ProjectUpdate',{

      id: projectID,
      project_name: projectName,
      project_desc: projectDesc,
      project_link: projectLink,
      project_img :projectImg
   })
  .then(function(response){
    $('#ProjectUpdateConfirmBtn').html("Save");

    if (response.status==200) {

      if (response.data==1) {
      $('#updateProjectModal').modal('hide');
      toastr.success('Updated Successfully');
      getProjectData();
    }else{
      $('#updateProjectModal').modal('hide');
      toastr.error('Operation Failed!');
      getProjectData();
    }

  }else{
    $('#updateProjectModal').modal('hide');
    toastr.error('Something went wrong!');
  }
    
  }).catch(function (error) {
  
    $('#updateProjectModal').modal('hide');  
    toastr.error('Something went wrong!');
  });

  }
}


//add confirm 
$('#addNewProjectBtnId').click(function(){
  $('#addProjectModal').modal('show');
});

//service add update modal save button
  $('#ProjectAddConfirmBtn').click(function(){

      var projectName = $('#ProjectNameId').val();
      var projectDesc = $('#ProjectDesId').val();
      var projectLink = $('#ProjectLinkId').val();
      var projectImg = $('#ProjectImgId').val();
      ProjectAdd(projectName,projectDesc,projectLink,projectImg);
  })

// Project Add method 
function ProjectAdd(projectName,projectDesc,projectLink,projectImg){

  if (projectName.length==0) {
        toastr.error('Project Name is Required');
  }
  else if (projectDesc.length==0) {
        toastr.error('Project Description is Required');
  }
  else if (projectLink.length==0) {
    toastr.error('Project Link is Required');
  }
  else if (projectImg.length==0) {
    toastr.error('Project Image is Required');
  }
  else{
        $('#ProjectAddConfirmBtn').html("<div class='spinner-border spinner-border-sm' role='status'></div>") //Animation
        axios.post('/ProjectAdd',{

          project_name: projectName,
          project_desc: projectDesc,
          project_link: projectLink,
          project_img :projectImg
   })
  .then(function(response){
    $('#ProjectAddConfirmBtn').html("Save");

    if (response.status==200) {

      if (response.data==1) {
      $('#addProjectModal').modal('hide');
      toastr.success('Added Successfully');
      getProjectData();
    }else{
      $('#addProjectModal').modal('hide');
      toastr.error('Operation Failed!');
      getProjectData();
    }

  }else{
    $('#addProjectModal').modal('hide');
    toastr.error('Something went wrong!');
  }
    
  }).catch(function (error) { 
    $('#addProjectModal').modal('hide');  
    toastr.error('Something went wrong!');
  });

  }
}

</script>
@endsection