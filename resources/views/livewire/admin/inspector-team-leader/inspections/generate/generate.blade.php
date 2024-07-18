<div>
    <div class="content">
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mt-4 mb-4">
                <h1 class="h3 mb-0 text-gray-800">{{$title}}</h1>
                <div class="p-0 m-0" wire:click="add('addModaltoggler')" >
                </div>
            </div>

            <?php 
                $total_assessment_fee = 0;
                $building_fee = 0;
                $sanitary_fee = 0;
                $signage_fee = 0;
                foreach ($issue_inspection['inspection_building_billings'] as $key => $value) {
                    $total_assessment_fee+= floatval($value->fee);
                    $building_fee  += floatval($value->fee);
                }
                
                foreach ($issue_inspection['inspection_sanitary_billings'] as $key => $value) {
                    $total_assessment_fee+= floatval($value['fee']);
                    $sanitary_fee  += floatval($value['fee']);
                }
                foreach ($issue_inspection['inspection_signage_billings'] as $key => $value) {
                    $total_assessment_fee+= floatval($value->fee);
                    $signage_fee  += floatval($value->fee);
                }


            ?>

            <form >
                <section class="sheet-container">
                    <section class="sheet d-flex position-relative">
                        <img src="./../../../assets/img/list-of-equipments.jpg" alt="list-of-equipment" class="sheet-image">
                        <section class="section d-flex flex-column">
                            <div class="section-header">
                                <div class="d-flex justify-content-between w-100">
                                    <div class="d-flex flex-column align-items-center justify-content-center owner-container p-0">
                                        <p class="owner-name">
                                            {{strtoupper(
                                                $issue_inspection['inspection']->first_name.' '.
                                                $issue_inspection['inspection']->middle_name.' '.
                                                $issue_inspection['inspection']->last_name.' '.
                                                $issue_inspection['inspection']->suffix)}}
                                        </p>
                                        <p>Name of Owner (Signature over Printed Name)</p>
                                    </div>
                                    <p>Date: <u>{{strtoupper(date_format(date_create(date("Y/m/d")),"M d, Y")) }}</u></p>
                                </div>
                                <div class="d-flex justify-content-between m-0">
                                    <p class="m-0">Autorized Representative: </p>
                                    <span class="underline"></span>
                                    <p class="m-0">Contact No: <u>{{$issue_inspection['inspection']->contact_number}}</u></p>
                                </div>
                                <div class="d-flex justify-content-between m-0">    
                                    <p class="m-0">Name of Business: </p>
                                    <span class="underline">
                                        <span class="ml-2">{{$issue_inspection['inspection_business_name']}}</span>
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between m-0">
                                    <p class="m-0">Type of Business: </p>
                                    <span class="underline"><span class="ml-2">{{$issue_inspection['inspection']->business_type_name}}</span></span>
                                </div>

                                <div class="d-flex justify-content-between m-0">
                                    <p class="m-0">Business Address: </p>
                                    <span class="underline"><span class="ml-2">
                                        @if($issue_inspection['inspection']->street_address) {{strtoupper($issue_inspection['inspection']->street_address.', ')}}
                                        @endif {{strtoupper($issue_inspection['inspection']->barangay)}}, GENERAL SANTOS CITY, SOUTH COTABATO
                                </span></span>
                                </div>

                                <div class="d-flex justify-content-between mb-2">
                                    <p class="m-0">Application Type: </p>
                                    <span class="underline">
                                        <span class="ml-2">
                                            {{($issue_inspection['inspection']->application_type_name ? strtoupper($issue_inspection['inspection']->application_type_name) : "N/A")}}
                                        </span>
                                    </span>
                                </div>
                            </div>

                            <table class="section-table w-100 mb-3">
                                <capton class="mt-4">
                                    <b>NOTE: This list is subject to verification by inspectors of the Office of the
                                        Building
                                        Official.</b>
                                </capton>
                                <?php 
                                    $categories = DB::table('categories')
                                        ->get()
                                        ->toArray();
                                    $category_fee = [];
                                  
                                    foreach ($categories as $key => $value) {
                                        $category_fee[$value->name] = 0;
                                    }
                                ?>
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="width: 42%">
                                            Item
                                            Description
                                        </th>
                                        <th rowspan="2" style="width: 13%">Power Rating</th>
                                        <th rowspan="2">QTY</th>
                                        <th colspan="3" style="width: 37%;">Fees</th>
                                    </tr>

                                    <tr>
                                        @foreach($categories as $key =>$value)
                                            <th class="font-weight-normal"><i>{{$value->name}}</i></th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                    @foreach($issue_inspection['inspection_items'] as $key =>$value)
                                        <tr>
                                            <td>{{$value['name']}}</td>
                                            <td>{{$value['power_rating']}}</td>
                                            <td>{{$value['quantity']}}</td>
                                            @foreach($categories as $catkey =>$catvalue)
                                                @if($catvalue->name == $value['category_name'] )
                                                    <th class="font-weight-normal"><i>{{number_format(($value['fee'] * $value['quantity']), 2)}}</i></th>
                                                @else 
                                                    <th class="font-weight-normal"><i>{{number_format(0, 2)}}</i></th>
                                                @endif
                                            @endforeach
                                        </tr>
                                        <?php  
                                            $category_fee[$value['category_name']]+= $value['fee'] * $value['quantity'];
                                            $total_assessment_fee += $value['fee'] * $value['quantity'];
                                        ?>
                                    @endforeach
                                    <tr>
                                        <td class="text-right px-2"><b>TOTAL</b></td>
                                        <td></td>
                                        <td></td>
                                        @foreach($categories as $key =>$value)
                                            <th class="font-weight-normal"><i><b>{{number_format($category_fee[$value->name], 2)}}</b></i></th>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>

                            <section class="section-footer d-flex justify-content-between position-absolute bottom-0 pr-4">
                                <div class="left w-50">
                                    <div class="inspector-container mb-2 d-flex justify-content-between flex-gap">
                                        <div class="inspector-names w-50 d-flex flex-column align-items-center px-1">
                                            <div>
                                                <b>Inspector Name & Signature</b>
                                            </div>
                                            @foreach($issue_inspection['inspectors'] as $key => $value)
                                            <div class="d-flex justify-content-center m-0">
                                                {{strtoupper(
                                                $value->first_name.' '.
                                                $value->middle_name.' '.
                                                $value->last_name.' '.
                                                $value->suffix)}}
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="date-inspected w-50 d-flex flex-column align-items-center px-1">
                                            <div><b>Remarks/Date Inspected</b></div>

                                            <div class="d-flex justify-content-center m-0">
                                                <span>{{strtoupper(date_format(date_create(date("Y/m/d")),"M d, Y")) }}</span>
                                            </div>

                                            <div class="d-flex justify-content-center m-0">
                                                <span>
                                                @if(count($issue_inspection['inspection_violations']))
                                                    With Violation
                                                @else
                                                    Without Violation
                                                @endforelse
                                                </span>
                                            </div>
                                            <div></div>
                                            <div></div>


                                        </div>
                                    </div>
                                    <div class="inspected-payment-container px-2">
                                        <div class="mb-3">
                                            <p class="font-weight-bolder m-0 text-center inspected-title pl-3 py-0">
                                                INSPECTED
                                            </p>
                                            <p class="font-weight-bolder m-0 text-center payment-title">for
                                                payment</p>
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <div class="w-50">
                                                <div class="text-center city-building-official">ENGR. AUREA M. PASCUAL
                                                </div>
                                                <div class="text-center city-building-title">CITY BUILDING OFFICIAL
                                                </div>
                                            </div>
                                            <div class="w-50 d-flex flex-column align-items-center justify-content-end">
                                                <div class="w-75 underline"></div>
                                                <div class="date">Date</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="address-notice">
                                        PLEASE SKETCH CLEARLY THE LOCATION/ADDRESS OF THE ESTABLISHMENT AT THE BACK OF
                                        THIS
                                        FORM.
                                    </div>
                                </div>
                               
                                <div class="right w-50">
                                    <div class="d-flex justify-content-between m-0">
                                        <p class="m-0">Floor Area: </p>
                                        <span class="underline"><span class="ml-2">@if(isset($issue_inspection['inspection']->floor_area)){{$issue_inspection['inspection']->floor_area}}@endif</span>
                                        </span> <span>m<sup>2</sup> </span>
                                    </div>

                                    <div class="d-flex flex-column align-items-center m-0 mb-2">
                                        <div class="d-flex justify-content-between w-100">
                                            <p class="m-0">Sinage Area: </p>
                                            <span class="underline">
                                                <span class="ml-2">@if(isset($issue_inspection['inspection']->signage_area)){{$issue_inspection['inspection']->signage_area}} @endif</span>
                                            </span>
                                            <span>m<sup>2</sup> </span>
                                        </div>
                                        <div>(Painted/Lighted)</div>

                                    </div>

                                    <div class="d-flex flex-column align-items-start other-fee">
                                        
                                        <div>Building Fee = ₱ {{number_format($building_fee, 2);}}</div>
                                        <div>Plumbing/Sanitary Fee = ₱ {{number_format($sanitary_fee, 2);}}</div>
                                        <div>Signage Fee = ₱ {{number_format($signage_fee, 2);}}</div>
                                    </div>

                                    <div>
                                        <div class="d-flex justify-content-start assessment-fee-title">
                                            <div>
                                                TOTAL ASSESSMENT FEE = ₱ {{number_format($total_assessment_fee, 2);}}
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column align-items-center violation-container">
                                            <div><b>VIOLATION/S:</b> (PLEASE CHECK)</div>
                                            <ul class="align-self-start">
                                                @forelse($issue_inspection['inspection_violations'] as $key => $value)
                                                    <li>{{$value['description']}}</li>
                                                @empty
                                                    <li>Without Violation</li>
                                                @endforelse


                                            </ul>
                                        </div>
                                    </div>
                                    <div class="number">
                                        (083) 554-1570 | 09335436999
                                    </div>
                                </div>
                            </section>
                        </section>
                        <section class="position-absolute left bottom-0 start-50 translate-middle d-print-none">
                             <button class="btn btn-primary btn-md-block mr-3 px-3" id="print-button" onclick="window.print()">Print
                            </button>
                        </section>
                    </section>
                </section>
            </form>

            <a class="scroll-to-top rounded d-print-none" href="#page-top">
                <i class="fas fa-angle-up"></i>
            </a>

        </div>
    </div>
</div>
