@extends('layouts.app')

@section('content')

<div class="page-header mt-4">
 
    <h1 style='text-align: center;'> HIVST Dashboard</h1> 

</div>
     
<div  class="tab-content" width = "100%"  height="500px">
    <ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link active" href="#" >Recruited by OW</a>
    </li>
    <li class="nav-item">
        <a  class="nav-link" href="#" style="color:#ff0023">Recruited through web</a>
    </li>
    
    </ul>
    
</div>
			
<div class="main">

    <div class="page-header">

        <h3 style='text-align: center;' id='hSummary'> Summary </h3> 

    </div>
</div>

@endsection
