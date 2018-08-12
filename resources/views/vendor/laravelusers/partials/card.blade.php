<div class="row">
    @foreach($users as $user)
        <div class="{{ config('constants.classes.card') }}">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $user->name}}</h5>
                    @if($user->email)
                        <p class="text-center" data-toggle="tooltip" data-placement="top" title="@lang('users.tooltips.email-user', ['user' => $user->email])">
                            {{ Html::mailto($user->email, $user->email) }}
                        </p>
                    @endif
                    @if(config('laravelusers.rolesEnabled'))
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                {{ __('users.role') }}:
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="bootstrap-tagsinput">
                                    @foreach ($user->roles as $userRole)
                                        <span class="badge badge-info">{{ $userRole->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                {{ __('users.created') }}:
                            </div>
                            <div class="col-md-6 text-right">
                                {{ formatDate($user->created_at) }}
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                {{ __('users.updated') }}:
                            </div>
                            <div class="col-md-6 text-right">
                                {{ formatDate($user->updated_at) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a class="btn btn-edit" title="{{ __('common.edit') }} {{ __('users.account') }}"
                       href="{{ url("/users/".$user->id."/edit") }}">
                        <i class="fa fa-edit"></i> {{ __('common.edit') }}
                    </a>
                    <a class="btn btn-remove" title="{{ __('common.remove') }} {{ __('users.account') }}"
                       href="{{ url("/users/".$user->id."/confirm") }}">
                        <i class="fa fa-trash"></i> {{ __('common.remove') }}
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>