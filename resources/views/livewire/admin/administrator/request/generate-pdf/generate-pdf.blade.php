<div>
    <div class="content">
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mt-4 mb-4">
                <h1 class="h3 mb-0 text-gray-800">{{$title}}</h1>
            </div>
            <form >
                <section class="sheet-container">
                    <section class="sheet d-flex position-relative">
                        <img src="./../../../../../assets/img/header.jpg" alt="list-of-equipment" class="sheet-image">
                        <section class="section d-flex flex-column">
                            <div class="section-header">
                                <div class="d-flex justify-content-end w-100">
                                    {{date("M d ,Y");}}
                                </div>
                                <div class="d-flex justify-content-start w-100 h5 bold my-2 mt-5" style="font-size:22px;font-weight:bold">
                                    {{$establishment}}
                                </div>
                                <div class=" justify-content-start w-100">
                                    <span class="d-inline bold" style="font-size:16px;font-weight:bold">
                                        Sir/Madam {{$owner_f1}},
                                    </span>
                                </div>
                                <br>
                                <br>
                                Pursuant to PD 1096, otherwise known as the National Building Code of the Philippines and its IRR, the Building Official shall undertake annual inspection of all buildings/structures and keep an updated record of their status. Also in the performance of his duties, a Building Official may enter any building or its premises at all reasonable times to inspect and determine compliance with the requirements of the NBPC.
                                <br>
                                <br>
                                You are hereby inform that the OBO Inspectorate team will conduct an Annual Inspection of your establishment on  <strong>{{date_format(date_create($this->schedule_date),"M d, Y ")}}</strong> , to ensure safety of your building and update the fees and status of your equipment.
                                <br>
                                <br>
                                Please prepare the approve plans (Structural, Electrical, Mechanical, Plumbing & Electronics), Occupancy Permit, Update site Development Plan, and a consolidated list of equipment during the scheduled inspection
                                <br>
                                <br>
                                A certificate of Annual Inspection will be issued to you after we found your building to be safe for use and/or after the compliance of deficiencies and payment of necessary fees have been made
                                <br>
                                <br>
                                Very truly yours,
                                <br>
                                <br>
                                <span class="bold h6" style="font-size:16px;font-weight:bold">
                                ENGR.AUREA M. PASCUAL, DM, MBA, CE, GE, ENP, REB
                                </span>
                                <br>
                                CITY BUILDING OFFICIAL
                                <br>
                                <br>
                                <br>
                                <br>
                                <div class="d-flex justify-content-start m-0 p-0">
                                    Received by:__________________________________
                                </div>
                                <div class="d-flex justify-content-start m-0 p-0">
                                    Date:________________________________________
                                </div>
                                <div class="d-flex justify-content-start m-0 p-0">
                                    Contact #:____________________________________
                                </div>
                                <div class="d-flex justify-content-start m-0 p-0">

                              
                                    
                        </section>
                        <section class="position-absolute left bottom-0 start-50 translate-middle ">
                            <div class="d-flex justify-content-center mt-5">
                                <p class="p-0 m-0">Best Regards,</p>
                            </div>
                            <div class="d-flex justify-content-center">
                                <p class="p-0 m-0">OBO Office : 554-1570 / 0933 5436 999</p>
                            </div>
                            <div class="d-flex inline justify-content-center" >
                                <p class="p-0 m-0">OBO Office 2/F GSC Investment Action Center, Cityhall Compound</p>
                            </div>
                            <div class="d-flex inline justify-content-center">
                                <p class="p-0 m-0">General Santos City</p>
                            </div>
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
