<div>
    <div class="container">
        <div class="vh-100 d-flex align-items-center justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-9 col-sm-12 d-flex flex-column flex-sm-row justify-content-center align-items-center login-flexgap">
                <div class="card o-hidden border-0 shadow-lg my-2 my-sm-2 col-lg-8 col-sm-6">
                    <div class="card-body p-0">
                        @if($request_inspection['request'])
                            @if($request_inspection['request']->is_responded == 0)
                                  @if($reponse == 'accept')
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="p-5">
                                                <div class="text-center">
                                                    <h1 class="h4 text-gray-900 mb-4 text-center">{{$request_inspection['business']->name}}<br>{{' ( '.$request_inspection['business']->first_name.' '.$request_inspection['business']->middle_name.' '.$request_inspection['business']->last_name.' '.$request_inspection['business']->suffix.' )'}}</h1>
                                                </div>
                                                <p>
                                                    <?php echo $content?>
                                                </p>
                                                <form wire:submit.prevent="accept()" class="user">
                                                    <div class="row d-flex justify-content-center">
                                                    </div>
                                                    <input type="submit" name="submit" value="Accept" class="btn btn-primary btn-user btn-block">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="d-flex justify-content-center">
                                            <strong><p class="p-0 m-0">Please response within  {{ date_format(date_create($request_inspection['request']->request_date),"M d, Y ").' to '.date_format(date_create($request_inspection['request']->expiration_date),"M d, Y ") }} </strong> </p>
                                        </div>
                                    </div>
                                @else 
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="p-5">
                                                <div class="text-center">
                                                    <h1 class="h4 text-gray-900 mb-4 text-center">{{$request_inspection['business']->name}}<br>{{' ( '.$request_inspection['business']->first_name.' '.$request_inspection['business']->middle_name.' '.$request_inspection['business']->last_name.' '.$request_inspection['business']->suffix.' )'}}</h1>
                                                </div>
                                                <p>
                                                    To decline, please state the reason:
                                                </p>
                                                <form wire:submit.prevent="decline()" class="user">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlTextarea1" class="form-label">Reason <span class="text-danger">*</span></label>
                                                        <textarea class="form-control" id="exampleFormControlTextarea1" wire:model="reason" rows="3"></textarea>
                                                    </div>
                                                    <input type="submit" name="submit" value="Decline" class="btn btn-danger btn-user btn-block">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="d-flex justify-content-center">
                                            <strong><p class="p-0 m-0">Please response within  {{ date_format(date_create($request_inspection['request']->request_date),"M d, Y ").' to '.date_format(date_create($request_inspection['request']->expiration_date),"M d, Y ") }} </strong> </p>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="p-5">
                                            <div class="text-center">
                                                <h1 class="h4 text-gray-900 mb-4">You have responded!</h1>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                                    <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else 
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4">Invalid Link!</h1>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="50px" height="50px" viewBox="0 0 24 24" fill="none">
                                                <path d="M12 21C10.22 21 8.47991 20.4722 6.99987 19.4832C5.51983 18.4943 4.36628 17.0887 3.68509 15.4442C3.0039 13.7996 2.82567 11.99 3.17294 10.2442C3.5202 8.49836 4.37737 6.89472 5.63604 5.63604C6.89472 4.37737 8.49836 3.5202 10.2442 3.17294C11.99 2.82567 13.7996 3.0039 15.4442 3.68509C17.0887 4.36628 18.4943 5.51983 19.4832 6.99987C20.4722 8.47991 21 10.22 21 12C21 14.387 20.0518 16.6761 18.364 18.364C16.6761 20.0518 14.387 21 12 21ZM12 4.5C10.5166 4.5 9.0666 4.93987 7.83323 5.76398C6.59986 6.58809 5.63856 7.75943 5.07091 9.12988C4.50325 10.5003 4.35473 12.0083 4.64411 13.4632C4.9335 14.918 5.64781 16.2544 6.6967 17.3033C7.7456 18.3522 9.08197 19.0665 10.5368 19.3559C11.9917 19.6453 13.4997 19.4968 14.8701 18.9291C16.2406 18.3614 17.4119 17.4001 18.236 16.1668C19.0601 14.9334 19.5 13.4834 19.5 12C19.5 10.0109 18.7098 8.10323 17.3033 6.6967C15.8968 5.29018 13.9891 4.5 12 4.5Z" fill="#000000"/>
                                                <path d="M9.00001 15.75C8.90147 15.7504 8.80383 15.7312 8.71282 15.6934C8.62181 15.6557 8.53926 15.6001 8.47001 15.53C8.32956 15.3893 8.25067 15.1987 8.25067 15C8.25067 14.8012 8.32956 14.6106 8.47001 14.47L14.47 8.46997C14.6122 8.33749 14.8002 8.26537 14.9945 8.26879C15.1888 8.27222 15.3742 8.35093 15.5116 8.48835C15.649 8.62576 15.7278 8.81115 15.7312 9.00545C15.7346 9.19975 15.6625 9.38779 15.53 9.52997L9.53001 15.53C9.46077 15.6001 9.37822 15.6557 9.2872 15.6934C9.19619 15.7312 9.09855 15.7504 9.00001 15.75Z" fill="#000000"/>
                                                <path d="M15 15.75C14.9015 15.7504 14.8038 15.7312 14.7128 15.6934C14.6218 15.6557 14.5392 15.6001 14.47 15.53L8.47 9.52997C8.33752 9.38779 8.2654 9.19975 8.26882 9.00545C8.27225 8.81115 8.35097 8.62576 8.48838 8.48835C8.62579 8.35093 8.81118 8.27222 9.00548 8.26879C9.19978 8.26537 9.38782 8.33749 9.53 8.46997L15.53 14.47C15.6704 14.6106 15.7493 14.8012 15.7493 15C15.7493 15.1987 15.6704 15.3893 15.53 15.53C15.4608 15.6001 15.3782 15.6557 15.2872 15.6934C15.1962 15.7312 15.0985 15.7504 15 15.75Z" fill="#000000"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="row my-5">
                            <div class="d-flex justify-content-center">
                                <p class="p-0 m-0">OBO Office : 554-1570 / 0933 5436 999</p>
                            </div>
                            <div class="d-flex inline justify-content-center">
                                <p class="p-0 m-0">OBO Office 2/F GSC Investment Action Center, Cityhall Compound</p>
                            </div>
                            <div class="d-flex inline justify-content-center">
                                <p class="p-0 m-0">General Santos City</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
