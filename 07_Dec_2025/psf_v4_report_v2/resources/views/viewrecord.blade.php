@extends('layouts.app')

@section('content')
  
<h3 class="page-title">View Records</h3>
    
        <div class="panel panel-default">
            <div class="panel-heading">
                @lang('global.app_list')
            </div>
            <div class="panel-body table-responsive">
		        <table class='table table-bordered table-striped' >
                        <thead>
                            <th style="color:white"> Select form </th>
                            <th style="color:white"> Fields </th>
                            <th style="color:white"> Operator </th>
                            <th style="color:white"> Value </th>
                            <th style="color:white"> Add Filter </th>
                        </thead>
                     
                        <tr>
                        <td>
                            <select id="odkForm">
                                <option value="view_patient_feedback"> PSF </option>
                                <option value="view_sd_working_day"> Stigma and Discremination</option>
                                <option value="view_patient_feedback"> F3_PDI_Result</option>
                                <option value="view_patient_feedback"> F1_PDI_Confirm</option>
                            </select>
                        </td>
                        <td>
                           
                            <select id="odkFields"></select>
                        </td>
                        

                        <td>
                            
                            <select id="operator" >
                                <option value="=">=</option>
                                <option value=">">></option>
                                <option value="<"><</option>
                                <option value="like">like</option>
                            </select>
                        </td>
                        <td>
                            
                            <input type="text" id="value"> 
                        </td>
                        <td>
                            
                            <!--<input type="button" onclick="addFilter()" value="Add"> -->
                            <a href="#" class="btn btn-xs btn-info">Add</a>
                        </td>
                         <td>
                           
                            <select id="filters" size="4">
                                
                            </select>
                                <!--<input type="button" onclick="clearFilter()" value="Clear"> -->
                                <a href="#" class="btn btn-xs btn-info">Clear</a>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="9">  
                             <input type="button" class="btn btn-xs btn-info" onclick="show()" value="show"> 
                          <!--   <a href="#" class="btn btn-xs btn-info">Search</a>-->
                                   
                        </td>
                    </tr>
                </table>


			</div>
        </div>
         <div    style="bottom: 0;
                                height: 90%;
                                width:100%;
                                text-align: left;">
                
                <iframe id="viewRecord" src="" width="100%" height="100%" frameBorder="0"></iframe>
            </div>
        </div>
   
        
@endsection

@section('javascript')

<script>
function laodFields(tableID)
{
   var apiURL = "http://127.0.0.1:8004/api/v1/"+ tableID +"/getFields";
    $.ajax({
        crossDomain: true,
        url: apiURL,
        method: 'GET',
        success: function(data) {
            $('#odkFields').empty();
            $('#odkFields').html(data);
            console.log (data);
        },
        error: function (error) {
            alert('error; ' + error.toString());
            }
       
    });
}

$(document).ready(function() {

    laodFields("view_patient_feedback");
});

$('#odkForm').on('change',function(e)
{
    laodFields(e.target.value);
});


function loadData()
{

    var apiURL = "http://127.0.0.1:8004/api/v1/"+ tableID +"/getFields";
    $.ajax({
        crossDomain: true,
        url: apiURL,
        method: 'GET',
        success: function(data) {
            $('#odkFields').empty();
            $('#odkFields').html(data);
            console.log (data);
        },
        error: function (error) {
            alert('error; ' + error.toString());
            }
       
    });

}

</script>
@endsection