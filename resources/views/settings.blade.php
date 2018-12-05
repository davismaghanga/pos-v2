@extends('menu')

@section('style')

    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/square/blue.css')}}">
    <!-- select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">

    <style>
        tr .action {
            display: none;
        }
        tr:hover .action {
            display: inline-block;
        }
    </style>

@endsection

@section('title')

    <h1>
        Settings
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Settings</li>
    </ol>

@endsection

@section('content')

    <!-- Custom Tabs (Pulled to the right) -->
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="@if(session('tab') != 2 && session('tab') != 3 && session('tab') != 4 && session('tab') != 5) active @endif"><a href="#tab-1" data-toggle="tab">Basic</a></li>
                    <li class="@if(session('tab') == 2) active @endif"><a href="#tab-2" data-toggle="tab">Loyalty points</a></li>
                    <li class="@if(session('tab') == 3) active @endif"><a href="#tab-3" data-toggle="tab">SMS</a></li>
                    <li class="@if(session('tab') == 4) active @endif"><a href="#tab-4" data-toggle="tab">Discount</a></li>
                    <li class="@if(session('tab') == 5) active @endif"><a href="#tab-5" data-toggle="tab">Tax</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane @if(session('tab') != 2 && session('tab') != 3 && session('tab') != 4 && session('tab') != 5) active @endif" id="tab-1">
                        <div class="row">
                            <div class="col-md-6 col-sm-12 center-block">
                                <div class="box-body box-profile">
                                    @if($business->logo == NULL)
                                        <img class="profile-user-img img-responsive img-circle" src="{{asset('img/ic_home.png')}}" alt="User profile picture">
                                    @else
                                        <img class="profile-user-img img-responsive img-rounded" src="{{asset('images/'.$business->logo)}}" alt="User profile picture">
                                    @endif
                                    <form action="{{url('settings/upload-logo')}}" enctype="multipart/form-data" method="post" id="logo-form">
                                        <div class="form-group has-feedback @if($errors->has('logo')) has-error @endif" >
                                            <div class="btn btn-default btn-file" >
                                                <i class="fa fa-paperclip"></i> Change Logo
                                                <input id="logo-input" name="logo" type="file" accept="image/*">
                                            </div>
                                            @foreach($errors->get('logo') as $message)
                                                <p class="help-block">{{$message}}</p>
                                            @endforeach
                                        </div>
                                        {{csrf_field()}}
                                    </form>

                                    <ul class="list-group list-group-unbordered">
                                        <li class="list-group-item">
                                            <b>Users</b> <a class="pull-right">{{$users}}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Sales</b> <a class="pull-right">{{$sales}}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Customers</b> <a class="pull-right">{{$customers}}</a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <form action="{{url('settings/basic')}}" method="post">
                                    <div class="form-group has-feedback @if($errors->has('name')) has-error @endif">
                                        <label for="name">Business name</label>
                                        <input name="name" class="form-control" id="name" placeholder="Enter name" value="{{$business->name}}" required>
                                        <span class="help-block">{{$errors->first('name')}}</span>
                                    </div>
                                    <div class="form-group has-feedback @if($errors->has('phone')) has-error @endif">
                                        <label for="phone">Phone number</label>
                                        <input name="phone" class="form-control" id="phone" placeholder="Enter phone" value="{{$business->phone}}" required>
                                        <span class="help-block">{{$errors->first('phone')}}</span>
                                    </div>
                                    <div class="form-group has-feedback @if($errors->has('email')) has-error @endif">
                                        <label for="email">Email</label>
                                        <input name="email" type="email" class="form-control" id="email" placeholder="Enter email" value="{{$business->email}}">
                                        <span class="help-block">{{$errors->first('email')}}</span>
                                    </div>
                                    {{csrf_field()}}
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-primary btn-flat pull-right">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane @if(session('tab') == 2) active @endif" id="tab-2">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 center-block center-block">
                                <form action="{{url('settings/loyalty')}}" method="post">
                                   <div class="col-sm-6">
                                       <div class="form-group has-feedback @if($errors->has('has_loyalty')) has-error @endif">
                                           <div class="checkbox">
                                               <label>
                                                   <input id="has_loyalty" name="has_loyalty" type="checkbox" @if($business->has_loyalty) checked @endif>
                                                   Enable Loyalty Points
                                               </label>
                                           </div>
                                       </div>
                                       <div class="form-group has-feedback @if($errors->has('loyalty_earn_rate')) has-error @endif">
                                           <label for="loyalty_earn_rate">Amount spent to earn 1 point</label>
                                           <input name="loyalty_earn_rate" class="form-control" id="loyalty_earn_rate" placeholder="Enter Amount (Sh)"
                                                  @if(!$business->has_loyalty) disabled @else value="{{$business->loyalty_earn_rate}}" @endif>
                                           <span class="help-block">{{$errors->first('loyalty_earn_rate')}}</span>
                                       </div>
                                       <div class="form-group has-feedback @if($errors->has('loyalty_redeem_rate')) has-error @endif">
                                           <label for="loyalty_redeem_rate">Points redeemable for 1 sh</label>
                                           <input name="loyalty_redeem_rate" class="form-control" id="loyalty_redeem_rate" placeholder="Enter number of points"
                                                  @if(!$business->has_loyalty) disabled @else value="{{$business->loyalty_redeem_rate}}" @endif>
                                           <span class="help-block">{{$errors->first('loyalty_redeem_rate')}}</span>
                                       </div>
                                       <div class="form-group has-feedback @if($errors->has('loyalty_min_earn')) has-error @endif">
                                           <label for="loyalty_min_earn">Minimum amount spent to earn points</label>
                                           <input name="loyalty_min_earn" class="form-control" id="loyalty_min_earn" placeholder="Enter Amount (Sh)"
                                                  @if(!$business->has_loyalty) disabled @else value="{{$business->loyalty_min_earn}}" @endif>
                                           <span class="help-block">{{$errors->first('loyalty_min_earn')}}</span>
                                       </div>
                                       {{csrf_field()}}

                                   </div>

                                    <div class="col-sm-6">
                                        <div class="form-group has-feedback @if($errors->has('minimum_redeemable_points')) has-error @endif">
                                            <label for="loyalty_min_earn">Minimum redeemable points</label>
                                            <input name="minimum_redeemable_points" class="form-control" id="amount_per_point" placeholder="Number of points"
                                                   @if(!$business->has_loyalty) disabled @else value="{{$business->minimum_redeemable_points}}" @endif>
                                            <span class="help-block">{{$errors->first('minimum_redeemable_points')}}</span>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-primary btn-flat pull-right">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane @if(session('tab') == 3) active @endif" id="tab-3">
                        <div class="row">
                            <div class="col-md-6 col-sm-12 center-block center-block">
                                <form action="{{url('settings/sms')}}" method="post">
                                    <div class="form-group has-feedback @if($errors->has('sms_greeting')) has-error @endif">
                                        <label for="sms_greeting">SMS greeting text</label>
                                        <input name="sms_greeting" class="form-control" id="sms_greeting" placeholder="Enter greeting"
                                               value="{{$business->sms_greeting}}" required>
                                        <span class="help-block">{{$errors->first('sms_greeting')}}</span>
                                    </div>
                                    <div class="form-group has-feedback @if($errors->has('sms_extension')) has-error @endif">
                                        <label for="sms_extension">SMS conclusion text</label>
                                        <textarea name="sms_extension" class="form-control" rows="3" placeholder="Enter conclusion" required>{{$business->sms_extension}}</textarea>
                                        <span class="help-block">{{$errors->first('sms_extension')}}</span>
                                    </div>
                                    {{csrf_field()}}
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-primary btn-flat pull-right">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6 col-sm-12 center-block center-block">
                                <?php
                                $state = 9;
                                $exist_Q = \App\SysSmsRequest::where('business', session('business_id'));
                                if($exist_Q->exists()) {
                                    $state = $exist_Q->first()->status;
                                }
                                // state 9=NO REQUEST 0=UNPROCESSED 1=FAILED
                                ?>
                                @if($business->sms_has_custom)
                                    <div class="form-group">
                                        <a href="{{url('settings/sms-cancel-custom')}}" class="btn btn-default btn-flat">Cancel custom SMS handle</a>
                                    </div>
                                @elseif($state == 0)
                                    <div class="callout callout-info">
                                        <h4>Custom SMS handle</h4>

                                        <p>Your request is being processed.</p>
                                    </div>
                                @elseif($state == 1)
                                    <div class="callout callout-danger">
                                        <h4>Custom SMS handle</h4>

                                        <p>Your request failed. Contact System administrator for help</p>
                                    </div>
                                @elseif($state == 9)
                                    <form action="{{url('settings/sms-custom-handle')}}" method="post">
                                        <div class="form-group has-feedback @if($errors->has('sms_sender')) has-error @endif">
                                            <label for="sms_sender">Custom SMS handle</label>
                                            <input name="sms_sender" class="form-control" id="sms_greeting" placeholder="Enter handle"
                                                   value="{{$business->sms_sender}}" required>
                                            <span class="help-block">{{$errors->first('sms_sender')}}</span>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-flat pull-right">Request</button>
                                        {{csrf_field()}}
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane @if(session('tab') == 4) active @endif" id="tab-4">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <form action="{{url('settings/set-discount')}}" method="post">
                                    <label>Set Discount</label>
                                    <div class="form-group has-feedback @if($errors->has('discount_category')) has-error @endif">
                                        <select id="discount_category" name="discount_category" class="form-control select2" style="width: 100%" value="{{old('discount_category')}}">
                                            <option value="0" disabled selected> -- Product or Service Category -- </option>
                                            @foreach($categories as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block">{{$errors->first('discount_category')}}</span>
                                    </div>
                                    <div class="form-group has-feedback @if($errors->has('discount')) has-error @endif">
                                        <div class="input-group" >
                                            <input name="discount" type="text"  placeholder="Enter Discount (in %) eg 12" class="form-control" value="{{old('discount')}}" required>
                                            <span class="input-group-btn">
                                                <button type="submit" class="btn btn-info btn-flat">Set</button>
                                            </span>
                                        </div>
                                        <span class="help-block">{{$errors->first('discount')}}</span>
                                    </div>
                                    {{csrf_field()}}
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane @if(session('tab') == 5) active @endif" id="tab-5">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <form action="{{url('settings/set-tax')}}" method="post">
                                    <label>Create Tax type</label>
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12">
                                            <div class="form-group has-feedback @if($errors->has('tax_category')) has-error @endif">
                                                <select id="tax_category" name="tax_category" class="form-control select2" style="width: 100%" value="{{old('category')}}">
                                                    <option value="0" disabled selected> -- Product or Service Category -- </option>
                                                    @foreach($categories as $category)
                                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block">{{$errors->first('tax_category')}}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <div class="form-group has-feedback @if($errors->has('tax_type')) has-error @endif">
                                                <input name="tax_type" type="text" class="form-control" placeholder="Tax name" value="{{old('tax_type')}}" required>
                                                <span class="help-block">{{$errors->first('tax_type')}}</span>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-sm-6">
                                            <div class="form-group has-feedback @if($errors->has('tax')) has-error @endif">
                                                <input name="tax" type="text" class="form-control" placeholder="Value (in %)" value="{{old('tax')}}" required>
                                                <span class="help-block">{{$errors->first('tax')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8 col-sm-6">
                                            <div class="form-group has-feedback pull-right">
                                                <div class="checkbox">
                                                    <label>
                                                        <input id="tax_is_inclusive" name="tax_is_inclusive" type="checkbox">
                                                        Inclusive
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <button type="submit" class="btn btn-primary btn-flat btn-block pull-right">Set</button>
                                        </div>
                                    </div>
                                    {{csrf_field()}}
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    <!-- END CUSTOM TABS -->

@endsection

@section('script')

    <!-- Select2 -->
    <script src="{{asset('plugins/select2/select2.full.min.js')}}"></script>

    <script>
        //Enable check and uncheck all functionality

    </script>

    <script>
        $(function () {
            $(".select2").select2();

            $('#logo-input').change(function () {
                $('#logo-form').submit();
            });

            function toggleLoyaltyInputs(state) {
                $("input[name='loyalty_earn_rate']").attr("disabled", !state);
                $("input[name='loyalty_redeem_rate']").attr("disabled", !state);
                $("input[name='loyalty_min_earn']").attr("disabled", !state);
            }

            $('#has_loyalty').on('ifChecked', function (event) {
                toggleLoyaltyInputs(true);
            }).on('ifUnchecked', function (event) {
                toggleLoyaltyInputs(false);
            });

            $('#discount_category').change(function () {
                $.get('settings/discount-for-category/' + $(this).val(), function (response) {
                    $("input[name='discount']").val(response * 100);
                });
            })

            function setTaxInclusive(incl) {
                $("#is_inclusiver").prop('checked', true);
            }


            $('#tax_category').change(function () {
                $.get('settings/tax-for-category/' + $(this).val(), function (response) {
                    $("#tax_is_inclusive").change();
                    $("input[name='tax_type']").val(response[0]);
                    $("input[name='tax']").val(response[1] * 100);
                    $("input[name='tax_is_inclusive']").iCheck(response[2] == 0 ? "uncheck" : "check");
                });
            })

        })
    </script>

@endsection