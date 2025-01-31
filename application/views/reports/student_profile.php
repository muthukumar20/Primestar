<?php $this->load->view('includes/header3'); ?>
<style>
    .badge{font-size: 100% !important;}
    a.badge {color: #fff !important;}
</style>

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title" style="color: green"><?php echo $title;?></h3>
                <div class="row breadcrumbs-top">
                  <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="<?php echo site_url(); ?>">Reports</a></li>
                      <li class="breadcrumb-item"><a href="#"><?php echo $title;?></a></li>
                    </ol>
                  </div>
                </div>
            </div>
            <?php if($role == 'admin' || $role == 'superadmin'):?>
            <div class="content-header-right col-md-6 col-12">
                <div class="media width-250 float-right">
                    <media-left class="media-middle">
                        <div id="sp-bar-total-sales"></div>
                    </media-left>
                    <div class="media-body media-right text-right">
                        <ul class="list-inline mb-0">
                            <li>
                                <a href="<?php echo base_url();?>Students" class="float-right btn btn-primary btn-sm" style="margin: 4px;"><i class="fa fa-plus"></i> Add</a>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        <?php endif;?>
           
        </div>
        <div class="content-body">
            <!-- Zero configuration table -->
            <section id="configuration">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo $title;?></h4>
                                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>

                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body card-dashboard">
                                    <div class="mainbox col-sm-12">
                                        <div class="panel panel-info">
                                        
                                            <table id="studentListing" class="table table-bordered table-hover small">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Student ID</th>
                                                        <th scope="col">Student Name</th>
                                                        <th scope="col">Category</th>
                                                        <th scope="col">Parent Id</th>
                                                        <th scope="col">Parent Name</th>
                                                        <th scope="col">Mobile</th>
                                                        <th scope="col">Email ID</th>
                                                        <th scope="col">Registration fees</th>
                                                        <th scope="col">Status</th>
                                                        <th scope="col">Approval</th>
                                                        <th scope="col">View</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(isset($studentList)){
                                                        //echo "<pre>"; print_r($studentList); die;
                                                        foreach ($studentList as $stud) { ?>
                                                    <tr>
                                                        <td></td>
                                                        <td><?php echo $stud['sid'];?></td>
                                                        <td><?php echo $stud['name'];?></td>
                                                        <td><?php echo $stud['role'];?></td>
                                                        <td><?php echo 'PSA00'.$stud['parent_user_id'];?></td>
                                                        <td><?php echo $stud['parent_name'];?></td>
                                                        <td><?php echo $stud['parent_mobile'];?></td>
                                                        <td><?php echo $stud['parent_email_id'];?></td>
                                                        <td><a data-toggle="modal" data-target=""><?php echo $stud['fees_paid'];?></a></td>
                                                        <td><?php if($stud['status'] == 'Active'){
                                                            $tag ='success';
                                                            $setval = 'Inactive';
                                                        }else{
                                                            $tag ='danger';
                                                            $setval = 'Active';
                                                        };?>
                                                            <a class='badge badge-<?php echo $tag;?>' onclick="changestatus('<?php echo $stud['id'];?>','status','<?php echo $setval;?>')"><?php echo $stud['status'];?></a>
                                                        </td>
                                                        <td><?php if($stud['approval_status'] == 'Approved'){
                                                            $approvaltag ='success';
                                                            $setvalue = 'Pending';
                                                        }else{
                                                            $approvaltag ='danger';
                                                            $setvalue = 'Approved';
                                                        };?>
                                                            <a class='badge badge-<?php echo $approvaltag;?>' onclick="changestatus('<?php echo $stud['id'];?>','approval_status','<?php echo $setvalue;?>')"><?php echo ucfirst($stud['approval_status']);?></a>
                                                        </td>
                                                        <td>
                                                            <a  href="<?php echo base_url('Students/edit/'.$stud['id']); ?>" title="Edit student details" class="edit-transaction ml-1 btn-ext-small btn btn-sm btn-warning"  data-schoolid="' + row[0] + '"><i class="fas fa-edit"></i></a>
                                                        </td>


                                                    </tr>
                                                    <?php }
                                                        }
                                                        ?>
                                                </tbody>
                                                

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!-- Modal confirm -->
<div class="modal" id="confirmModal" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content panel panel-primary">
            <div class="modal-header panel-heading">
                    <h4 class="modal-title -remove-title">Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            <div class="modal-body" id="confirmMessage">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="confirmOk">Ok</button>
                <button type="button" class="btn btn-danger" id="confirmCancel">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- fees details -->
<div class="modal fade rotate" id="feesDetails" style="display:none;">
    <div class="modal-dialog modal-lg"> 
        <form id="voucher_reverse-form" method="post">   
            <div class="modal-content panel panel-success">
                <div class="modal-header panel-heading">
                    <h4 class="modal-title -remove-title">Registration Fees details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body panel-body">
                    <p><span class="alertMsg"></span></p>
                    <table class="table table-striped table-bordered" >
                        <tr>
                            <td><b>PSA ID</b></td>
                            <td><p class="fees-student_id"></p></td>
                        </tr>
                        <tr>
                            <td><b>Name</b></td>
                            <td><p class="fees-name"></p></td>
                        </tr>
                        <tr>
                            <td><b>Date of Birth</b></td>
                            <td><p class="fees-dob"></p></td>
                        </tr>
                        <tr>
                            <td><b>Age</b></td>
                            <td><p class="fees-age"></p></td>
                        </tr>
                        <tr>
                            <td><b>Category</b></td>
                            <td><p class="fees-category"></p></td>
                        </tr>
                        <tr>
                            <td><b>Last paid on</b></td>
                            <td><p class="fees-last_paid"></p></td>
                        </tr>
                        <tr>
                            <td><b>Next Payable on</b></td>
                            <td><p class="fees-next_payable"></p></td>
                        </tr>
                       
                    </table>
                
                </div>
                
            </div>
        </form>      
   
    </div>
</div>
<?php
$this->load->view('users/popup/display');
$this->load->view('users/popup/edit');
$this->load->view('users/popup/add');
$this->load->view('users/popup/delete');
$this->load->view('templates/footer');
?>
<script type="text/javascript">
jQuery(document).ready(function() {
    var t = jQuery('#studentListing').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            { extend: 'print', 
            footer: true, 
            messageTop: 'Student Profile Report ', 
            title: 'Student Profile Report', 
            exportOptions: {
                    columns: [ 1, 2, 3, 4,5,6,7,8,9,10 ]
                },
            },
            { extend: 'pdf', 
            footer: true, 
            messageTop: 'Student Profile Report ', 
            title: 'Student Profile Report', 
            exportOptions: {
                    columns: [ 1, 2, 3, 4,5,6,7,8,9,10 ]
                },
            },
            { extend: 'excel', 
            footer: true, 
            messageTop: 'Student Profile Report ', 
            title: 'Student Profile Report', 
            exportOptions: {
                    columns: [ 1, 2, 3, 4,5,6,7,8,9,10 ]
                },
            }
        ],
        "fnRowCallback" : function(nRow, aData, iDisplayIndex ){
                var info = $(this).DataTable().page.info();
                $("td:first", nRow).html(info.start + iDisplayIndex +1);
               return nRow;
            },
     
    } );
} );

function changestatus(id,field,value){
    confirmDialog('Are you sure to change the status?', function(){
        jQuery.ajax({
            type:'POST',
            url:baseurl+'index.php/students/changestatus/'+id+'/'+field+'/'+value,
            dataType:'json',    
                   
            success: function (json) {
                $('.text-danger').remove();
                if(json['status']){
                    if(json['status']=='success'){
                        location.reload();
                    }
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }          
        });
    });
}

function confirmDialog(message, onConfirm){
    var fClose = function(){
        modal.modal("hide");
    };
    var modal = $("#confirmModal");
    modal.modal("show");
    $('.modal-backdrop').addClass('show');
    $('.modal-backdrop').addClass('in');
    $("#confirmMessage").empty().append(message);
    $("#confirmOk").unbind().one('click', onConfirm).one('click', fClose);
    $("#confirmCancel").unbind().one("click", fClose);
}
</script>
