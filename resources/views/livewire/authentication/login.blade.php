<div>
    <div class="container">
        <div class="vh-100 d-flex align-items-center justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-9 col-sm-12 d-flex flex-column flex-sm-row justify-content-center align-items-center login-flexgap">
                <div class="col-xl-4 col-lg-5 col-md-8 col-sm-6 login-image m-0">
                    <img src="{{url('/assets')}}/img/lgu_logo.png" class="img img-fluid" alt="obos-logo">
                </div>
                <div class="card o-hidden border-0 shadow-lg my-2 my-sm-2 col-xl-4 col-lg-5 col-md-8 col-sm-6">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <form wire:submit.prevent="login()" class="user">
                                        <div class="form-group">
                                            <input type="text" wire:model="user.username" required class="form-control form-control-user" id="exampleInputusername" aria-describedby="usernameHelp" placeholder="Enter Username...">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" wire:model="user.password" required class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">
                                        </div>
                                        <input type="submit" name="submit" value="Login" class="btn btn-primary btn-user btn-block">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
