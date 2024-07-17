<!DOCTYPE html>
    <html lang="en">
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

    </head>

    <body id="page-top">
    <section class="section d-flex flex-column" style="margin-top:3.7cm">
        ANNUAL INSPECTION REPORT
        <br>
        <br>
        Date of Inspection:  {{date_format(date_create($issue_inspection['inspection']->schedule_date),"M d, Y")}}
        <br>
        Owner of Building: {{$issue_inspection['inspection']->first_name.' '.$issue_inspection['inspection']->middle_name.' '.$issue_inspection['inspection']->last_name.' '.$issue_inspection['inspection']->suffix}}
        <br>
        Name of Lessee:   {{$issue_inspection['inspection_business_name']}}
        <br>
        Location of Building:  @if(isset($issue_inspection['inspection']->street_address)) {{$issue_inspection['inspection']->street_address}}, @endif {{$issue_inspection['inspection']->barangay}} , GENERAL SANTOS CITY
        <br>
        <br>
        <br>
        INSPECTORS
        <br>
        @foreach($issue_inspection['violation_category'] as $key => $value )
            <br>
            Category :{{$value->name}} 
            <br>
            @foreach($issue_inspection['email_inspection_inspector_team_leaders'] as $v_key => $v_value )
                @if($v_value->category_id == $value->id)
                    {{$v_value->first_name.' '.$v_value->middle_name.' '.$v_value->last_name.' '.$v_value->suffix}}
                    <br>
                @endif
            @endforeach
            @foreach($issue_inspection['email_inspection_inspector_members'] as $v_key => $v_value )
                @if($v_value->category_id == $value->id)
                    {{$v_value->first_name.' '.$v_value->middle_name.' '.$v_value->last_name.' '.$v_value->suffix}}
                    <br>    
                @endif
            @endforeach  
        @endforeach

        <br>
        <br>
        <br>
        REMARKS
        <br>
        @if(count($issue_inspection['inspection_violations'])>0)
            Violation/s :
            <br>
            @foreach($issue_inspection['violation_category'] as $key => $value )
                @foreach($issue_inspection['violations'] as $v_key => $v_value )
                    @if($v_value->category_id == $value->id)
                        @foreach($issue_inspection['inspection_violations'] as $iv_key => $iv_value )
                            @if($iv_value['violation_id'] == $v_value->id)
                                &#10003;&nbsp;  &nbsp;  &nbsp; 
                                {{$v_value->description}}
                                <br>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endforeach
        @else
            No Violation
        @endif
        <br>
        <br>
        <br>
        @if(count($issue_inspection['inspection_violations'])==0)
            YOU MAY CLAIM YOUR CERTIFICATE OF ANNUAL INSPECTION AT THE OFFICE OF THE BUILDING OFFICIAL ON @if(count($issue_inspection['inspection_violations'])==0) <strong class="underline">{{date_format(date_add(date_create($issue_inspection['inspection']->schedule_date),date_interval_create_from_date_string("3 days")),"M d, Y")}} </strong> @endif
        @endif
        <br>
        <br>
        @if(count($issue_inspection['inspection_violations'])>0)
            THIS SERVES AS YOUR FINAL NOTICE OF VIOLATION. COMPLY ON OR BEFORE  <strong class="underline">{{date_format(date_add(date_create($issue_inspection['inspection']->schedule_date),date_interval_create_from_date_string("90 days")),"M d, Y")}} </strong> 
            <br>
            FOR THE ISSUANCE OF ANNUAL CERTIFICATE OF ANNUAL INSPECTION AS A REQUIREMENT FOR THE RENEWAL OF YOUR BUSINESS PERMIT.
        @endif
        <br>
        <br>
    </section>
                            

    </body>
</html>
