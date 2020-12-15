@extends('layouts.backend')
@section('style')
@endsection
@section('section')
<main>
<div class="backend container" style="color:black;">
  <h2 class="setting-title">Application Settings</h2>
  <br>
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist" style="display: flex; overflow: auto hidden;">
    <li class="nav-item active">
      <a class="nav-link active" data-toggle="tab" href="#campaign">Campaigns</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#question">Questions</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#profile">Profile</a>
    </li>
    @if (Auth::user()->is_admin)
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#company">Companies</a>
    </li>
    @endif
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div id="campaign" class="tab-pane active"><br>
        <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger py-0" style="font-size: 0.8em; width:100px;" id="createNewCampaign">Add Campaign</a>
        <div class="flow-auto">
            <table class="table table-hover campaign">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Company</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="campaign_modal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="CampaignCrudModal"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <form id="campaigndata">
                    <div class="modal-body">
                        <input type="hidden" id="campaign_id" name="campaign_id" value="">
                        <input type="text" class="form-control" id="name" name="name" value="">
                    </div>
                    <div class="modal-footer">
                        <input type="submit" value="Submit" id="campaign_submit" class="btn btn-sm btn-outline-danger py-0">
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="question" class="tab-pane fade"><br>
        <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger py-0" style="font-size: 0.8em; width:100px;" id="createNewQuestion">Add Question</a>
        <label>Campaigns : &nbsp;</label> <select name="question_campaign" id="question_campaign">
        </select>
        <div class="flow-auto">
            <table class="table table-hover question">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Campaign</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="question_modal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="QuestionCrudModal"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>

                    <div class="modal-body">
                        <form id="questiondata">
                            <input type="hidden" id="question_id" name="question_id" value="">
                            <input type="text" id="name" name="name" value="" class="form-control">
                            <select name="campaign_id" id="campaign_select" class="form-control">
                            </select>
                        </form>
                    </div>

                    <div class="modal-footer">
                    <input type="submit" value="Save" id="question_submit" class="btn btn-sm btn-outline-danger py-0" style="font-size: 0.8em;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="profile" class="tab-pane fade"><br>
        <form id="profiledata">
            <input type="hidden" id="id" name="id" value="{{ Auth::user()->id }}">
            <input type="hidden" id="is_admin" name="is_admin" value="{{ Auth::user()->is_admin }}">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" required>
            <label for="profile_image">Upload Profile Image</label>
            <input type="file" name="profile_image" class="form-control" id="profile_image">
            <div class="profile_container">
                <img id='profile-preview' width="150" height="150" src="{{ asset('storage/Company/'.Auth::user()->logo) }}"/>
            </div>
            <label for="back_image">Upload Background Image</label>
            <input type="file" name="back_image" class="form-control" id="back_image">
            <div class="back_container">
                <img id='back-preview' width="150" height="200" src="{{ asset('storage/Background/'.Auth::user()->background) }}"/>
            </div>
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
            <label for="password">New Password:</label>
            <input type="password" class="form-control" id="password" name="password" value="" required>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" value="" required>
            <input type="submit"  class="form-control" value="Save" id="profile_submit" class="btn btn-sm btn-outline-danger py-0" >
        </form>
    </div>
    @if (Auth::user()->is_admin)
    <div id="company" class="tab-pane fade"><br>
        <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger py-0" style="font-size: 0.8em; width:100px;" id="createNewCompany">Add Company</a>
        <div class="flow-auto">
            <table class="table table-hover company">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="company_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="CompanyCrudModal"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <form id="companydata">
                        <div class="modal-body">
                            <input type="hidden" id="company_id" name="company_id" value="">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" value="" required>
                            <label for="logo_image">Upload Logo Image</label>
                            <input type="file" name="logo_image" class="form-control" id="logo_image">
                            <div class="logo_container">
                                <img id='logo-preview' width="150" height="150" src="#"/>
                            </div>
                            <label for="back_image">Upload Background Image</label>
                            <input type="file" name="back_image" class="form-control" id="back_image">
                            <div class="back_container">
                                <img id='back-preview' width="150" height="200" src="#"/>
                            </div>
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" value="" required>
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" value="" required>
                            <label for="confirm_password">Confirm Password:</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" value="" required>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" value="Submit" id="company_submit" class="btn btn-sm btn-outline-danger py-0">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
  </div>
</div>
</main>
@endsection

@section('script')
<script src="{{ asset('assets/js/sweetalert2@9.js') }}"></script>
<script>

var company_root_url = <?php echo json_encode(route('companies')) ?>;
var company_store = <?php echo json_encode(route('createCompany')) ?>;
var campaign_root_url = <?php echo json_encode(route('campaigns')) ?>;
var campaign_store = <?php echo json_encode(route('createCampaign')) ?>;
var question_root_url = <?php echo json_encode(route('questions')) ?>;
var question_store = <?php echo json_encode(route('createQuestion')) ?>;
var setting_root_url = <?php echo json_encode(route('setting')) ?>;
var setting_store = <?php echo json_encode(route('saveSetting')) ?>;

$(document).ready(function () {
    $('[data-toggle="popover"]').popover();
    get_company_data();
    get_campaign_data();

    $.ajaxSetup({
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });

    //Get all Campaign
    function get_company_data() {
        $.ajax({
            url: company_root_url,
            type:'GET',
            data: { }
        }).done(function(data){
            company_table_data_row(data)
        });
    }

    //Get all Campaign
    function get_campaign_data() {

        $.ajax({
            url: campaign_root_url,
            type:'GET',
            data: { }
        }).done(function(data){
            campaign_table_data_row(data)
            append_select_option_row(data)
        });
    }

    //Get all Question
    function get_question_data(id) {
        $.ajax({
            url: question_root_url,
            type:'GET',
            data: { id : id }
        }).done(function(data){
            question_table_data_row(data)
        });
    }

    //Company table row
    function company_table_data_row(data) {
        var	rows = '';

        $.each( data, function( key, value ) {

            rows = rows + '<tr>';
            rows = rows + '<td>'+(key+1)+'</td>';
            rows = rows + '<td>'+value.name+'</td>';
            rows = rows + '<td>'+value.email+'</td>';
            rows = rows + '<td data-id="'+value.id+'">';
            rows = rows + '<a class="btn btn-sm btn-outline-danger py-0" style="font-size: 0.8em;" id="editCompany" data-id="'+value.id+'" data-toggle="modal" data-target="#company_modal"><i class="fas fa-edit"></i></a> ';
            rows = rows + '<a class="btn btn-sm btn-outline-danger py-0" style="font-size: 0.8em;" id="deleteCompany" data-id="'+value.id+'" ><i class="fas fa-times"></i></a> ';
            rows = rows + '</td>';
            rows = rows + '</tr>';
        });

        $(".company tbody").html(rows);
    }
    //Campaign table row
    function campaign_table_data_row(data) {

        var	rows = '';

        $.each( data, function( key, value ) {

            rows = rows + '<tr>';
            rows = rows + '<td>'+(key+1)+'</td>';
            rows = rows + '<td>'+value.company+'</td>';
            rows = rows + '<td>'+value.name+'</td>';
            rows = rows + '<td data-id="'+value.id+'">';
                    rows = rows + '<a class="btn btn-sm btn-outline-danger py-0" style="font-size: 0.8em;" id="editCampaign" data-id="'+value.id+'" data-toggle="modal" data-target="#campaign_modal"><i class="fas fa-edit"></i></a> ';
                    rows = rows + '<a class="btn btn-sm btn-outline-danger py-0" href="#" style="font-size: 0.8em;" data-toggle="popover" title="Share link" data-id="'+value.id+'" data-placement="top" data-content="'+'https://'+window.location.host+'/campaigns/'+value.slug+'" id="shareLink"><i class="fas fa-share-alt"></i></a> ';
                    rows = rows + '<a class="btn btn-sm btn-outline-danger py-0" style="font-size: 0.8em;" id="deleteCampaign" data-id="'+value.id+'" ><i class="fas fa-times"></i></a> ';
                    rows = rows + '</td>';
            rows = rows + '</tr>';
        });

        $(".campaign tbody").html(rows);
    }

    //Question table row
    function question_table_data_row(data) {

        var	rows = '';

        $.each( data, function( key, value ) {

            rows = rows + '<tr>';
            rows = rows + '<td>'+(key+1)+'</td>';
            rows = rows + '<td>'+value.campaign_name+'</td>';
            rows = rows + '<td>'+value.name+'</td>';
            rows = rows + '<td data-id="'+value.id+'">';
                    rows = rows + '<a class="btn btn-sm btn-outline-danger py-0" style="font-size: 0.8em;" id="editQuestion" data-id="'+value.id+'" data-toggle="modal" data-target="#question_modal"><i class="fas fa-edit"></i></a> ';
                    rows = rows + '<a class="btn btn-sm btn-outline-danger py-0" style="font-size: 0.8em;" id="deleteQuestion" data-id="'+value.id+'" ><i class="fas fa-times"></i></a> ';
                    rows = rows + '</td>';
            rows = rows + '</tr>';
        });

        $(".question tbody").html(rows);
    }

    //Campaign table row
    function append_select_option_row(data) {

        var	rows = '';

        $.each( data, function( key, value ) {
            if ( key == 0 )
            {
                rows = rows + '<option value='+ value.id +' selected>'+value.name+'</option>';
                get_question_data(value.id);
            }

            else
            {
                rows = rows + '<option value='+ value.id +'>'+value.name+'</option>';
            }

        });

        $("#campaign_select").html(rows);
        $("#question_campaign").html(rows);
    }

    $("body").on("change", "#question_campaign", function(e) {
        var campaign_id = $("#question_campaign").val();
        get_question_data(campaign_id);
    });

    //Insert Campaign data
    $("body").on("click","#createNewCompany",function(e){
        e.preventDefault;
        $('#CompanyCrudModal').html("Create Company");
        $('#company_submit').val("Create Company");
        $('#company_modal').modal('show');
        $('#logo-preview').hide();
        $('#companydata #back-preview').hide();
        $('#company_id').val('');
        $('#companydata').trigger("reset");
    });

    //Insert Campaign data
    $("body").on("click","#createNewCampaign",function(e){
        e.preventDefault;
        $('#CampaignCrudModal').html("Create Campaign");
        $('#campaign_submit').val("Create Campaign");
        $('#campaign_modal').modal('show');
        $('#campaign_id').val('');
        $('#campaigndata').trigger("reset");

    });

    //Insert Question data
    $("body").on("click","#createNewQuestion",function(e){
        e.preventDefault;
        $('#QuestionCrudModal').html("Create Question");
        $('#question_submit').val("Create Question");
        $('#question_modal').modal('show');
        $('#question_id').val('');
        $('#questiondata').trigger("reset");

    });

    //Save data into database
    $('body').on('click', '#company_submit', function (event) {
        event.preventDefault()
        var id = $("#companydata #company_id").val();
        var name = $("#companydata #name").val();
        var email = $("#companydata #email").val();
        var password = $("#companydata #password").val();
        var confirm_password = $("#companydata #confirm_password").val();

        var form_data = new FormData();
        form_data.append('id',  id);
        form_data.append('name',  name);
        form_data.append('email',  email);
        form_data.append('password',  password);
        form_data.append('confirm_password',  confirm_password);
        form_data.append('is_admin',  0);
        form_data.append('logo',  $("#logo_image").prop('files')[0]);
        form_data.append('background',  $("#companydata #back_image").prop('files')[0]);

        if (name == '' || email == '' ){
            alert("Please fill all fields!");
            return false;
        }

        if(password != confirm_password)
        {
            alert("Confirmation Password incorrect!");
        }

        else {
            $.ajax({
                url: company_store,
                type: "POST",
                data: form_data,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (data) {
                    $('#companydata').trigger("reset");
                    $('#company_modal').modal('hide');
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Success',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    get_company_data()
                },
                error: function (data) {
                    console.log('Error......');
                }
            });
        }
    });

    //Save data into database
    $('body').on('click', '#campaign_submit', function (event) {
        event.preventDefault();
        var id = $("#campaigndata #campaign_id").val();
        var name = $("#campaigndata #name").val();

        $.ajax({
            url: campaign_store,
            type: "POST",
            data: {
                id: id,
                name: name,
            },
            dataType: 'json',
            success: function (data) {

                $('#campaigndata').trigger("reset");
                $('#campaign_modal').modal('hide');
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Success',
                    showConfirmButton: false,
                    timer: 1500
                })
                get_campaign_data()
            },
            error: function (data) {
                console.log('Error......');
            }
        });
    });

    //Save data into database
    $('body').on('click', '#question_submit', function (event) {
        event.preventDefault();
        var id = $("#question_id").val();
        var name = $("#questiondata #name").val();
        var campaign_id = $("#questiondata #campaign_select").val();
        var current_campaign = $("#question_campaign").val();

        $.ajax({
            url: question_store,
            type: "POST",
            data: {
                id: id,
                name: name,
                campaign_id: campaign_id,
            },
            dataType: 'json',
            success: function (data) {

                $('#questiondata').trigger("reset");
                $('#question_modal').modal('hide');
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Success',
                    showConfirmButton: false,
                    timer: 1500
                })
                get_question_data(current_campaign);
            },
            error: function (data) {
                console.log('Error......');
            }
        });
    });

    //Save data into database
    $('body').on('click', '#profile_submit', function (event) {
        event.preventDefault()
        var id = $("#profiledata #id").val();
        var name = $("#profiledata #name").val();
        var email = $("#profiledata #email").val();
        var password = $("#profiledata #password").val();
        var confirm_password = $("#profiledata #confirm_password").val();
        var is_admin = $("#profiledata #is_admin").val();

        var form_data = new FormData();

        form_data.append('id',  id);
        form_data.append('name',  name);
        form_data.append('email',  email);
        form_data.append('password',  password);
        form_data.append('confirm_password',  confirm_password);
        form_data.append('is_admin',  is_admin);
        form_data.append('logo',  $("#profile_image").prop('files')[0]);
        form_data.append('background',  $("#profiledata #back_image").prop('files')[0]);

        if (name == '' || email == '' ){
            alert("Please fill all fields!");
            return false;
        }

        if(password != confirm_password)
        {
            alert("Confirmation Password incorrect!");
        }

        else {
            $.ajax({
                url: company_store,
                type: "POST",
                data: form_data,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (data) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Success',
                        showConfirmButton: false,
                        timer: 1500
                    })
                },
                error: function (data) {
                    console.log('Error......');
                }
            });
        }
    });

    //Edit modal window
    $('body').on('click', '#editCompany', function (event) {
        event.preventDefault();
        var id = $(this).data('id');

        $.get(company_store+'/'+ id+'/edit', function (data) {
            $('#CompanyCrudModal').html("Edit Company");
            $('#company_submit').val("Edit Company");
            $('#company_modal').modal('show');
            $('#companydata #company_id').val(data.data.id);
            $('#companydata #name').val(data.data.name);
            $('#companydata #email').val(data.data.email);
            $('#logo-preview').show();
            $('#logo-preview').attr('src','/storage/Company/' + data.data.logo);
            $('#companydata #back-preview').show();
            $('#companydata #back-preview').attr('src','/storage/Background/' + data.data.background);
        })
    });

    //Edit modal window
    $('body').on('click', '#editCampaign', function (event) {

        event.preventDefault();
        var id = $(this).data('id');

        $.get(campaign_store+'/'+ id+'/edit', function (data) {
            $('#CampaignCrudModal').html("Edit Campaign");
            $('#campaign_submit').val("Edit Campaign");
            $('#campaign_modal').modal('show');
            $('#campaigndata #campaign_id').val(data.data.id);
            $('#campaigndata #name').val(data.data.name);
        })
    });

    //Edit modal window
    $('body').on('click', '#editQuestion', function (event) {
        event.preventDefault();
        var id = $(this).data('id');

        $.get(question_store+'/'+ id+'/edit', function (data) {
            $('#QuestionCrudModal').html("Edit Question");
            $('#question_submit').val("Edit Question");
            $('#question_modal').modal('show');
            $('#question_id').val(data.data.id);
            $('#questiondata #campaign_select').val(data.data.campaign_id);
            $('#questiondata #name').val(data.data.name);
        })
    });

    //Edit modal window
    $('body').on('click', '#editCampaign', function (event) {

        event.preventDefault();
        var id = $(this).data('id');

        $.get(campaign_store+'/'+ id+'/edit', function (data) {
            $('#CampaignCrudModal').html("Edit Campaign");
            $('#campaign_submit').val("Edit Campaign");
            $('#campaign_modal').modal('show');
            $('#campaigndata #campaign_id').val(data.data.id);
            $('#campaigndata #name').val(data.data.name);
        })
    });

    //Edit modal window
    $('body').on('click', '#editQuestion', function (event) {
        event.preventDefault();
        var id = $(this).data('id');

        $.get(question_store+'/'+ id+'/edit', function (data) {
            $('#QuestionCrudModal').html("Edit Question");
            $('#question_submit').val("Edit Question");
            $('#question_modal').modal('show');
            $('#question_id').val(data.data.id);
            $('#questiondata #campaign_select').val(data.data.campaign_id);
            $('#questiondata #name').val(data.data.name);
        })
    });

    //DeleteCampaign
    $('body').on('click', '#deleteCompany', function (event) {
        if(!confirm("Do you really want to do this?")) {
        return false;
        }

        event.preventDefault();
        var id = $(this).attr('data-id');

        $.ajax(
            {
            url: company_store+'/'+id,
            type: 'DELETE',
            data: {
                    id: id
            },
            success: function (response){

                Swal.fire(
                'Remind!',
                'Company deleted successfully!',
                'success'
                )
                get_company_data()
            }
        });
        return false;
    });

    //DeleteCampaign
    $('body').on('click', '#deleteCampaign', function (event) {
        if(!confirm("Do you really want to do this?")) {
        return false;
        }

        event.preventDefault();
        var id = $(this).attr('data-id');

        $.ajax(
            {
            url: campaign_store+'/'+id,
            type: 'DELETE',
            data: {
                    id: id
            },
            success: function (response){

                Swal.fire(
                'Remind!',
                'Campaign deleted successfully!',
                'success'
                )
                get_campaign_data()
                get_question_data()
            }
        });
        return false;
    });
    //DeleteQuestion
    $('body').on('click', '#deleteQuestion', function (event) {
        if(!confirm("Do you really want to do this?")) {
        return false;
        }

        event.preventDefault();
        var id = $(this).attr('data-id');

        var campaign_id = $("#question_campaign").val();

        $.ajax(
            {
            url: question_store+'/'+id,
            type: 'DELETE',
            data: {
                    id: id
            },
            success: function (response){

                Swal.fire(
                'Remind!',
                'Question deleted successfully!',
                'success'
                )
                get_question_data(campaign_id);
            }
        });
        return false;
    });

    //Save data into database
    $('body').on('click', '#shareLink', function (event) {
        $('[data-toggle="popover"]').popover();
    });

    //Edit modal window
    $('body').on('click', '.popover-content', function (event) {
        var copyText = this.innerHTML;
        copyText.select();
        copyText.setSelectionRange(0, 99999)
        document.execCommand("copy");
        alert(copyText);
    });

    $("#logo_image").on('change', function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#logo-preview').show();
                $('#logo-preview').attr('src', e.target.result);
            }

            reader.readAsDataURL(this.files[0]);
        }
    });

    $("#profile_image").on('change', function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#profile-preview').show();
                $('#profile-preview').attr('src', e.target.result);
            }

            reader.readAsDataURL(this.files[0]);
        }
    });

    $("#companydata #back_image").on('change', function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#companydata #back-preview').show();
                $('#companydata #back-preview').attr('src', e.target.result);
            }

            reader.readAsDataURL(this.files[0]);
        }
    });

    $("#profiledata #back_image").on('change', function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#profiledata #back-preview').show();
                $('#profiledata #back-preview').attr('src', e.target.result);
            }

            reader.readAsDataURL(this.files[0]);
        }
    });

});
</script>
@endsection
