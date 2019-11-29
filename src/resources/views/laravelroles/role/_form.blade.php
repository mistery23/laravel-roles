<form action="{{ route('laravelroles::roles.store') }}" method="POST" accept-charset="utf-8" id="store_role_form" class="mb-0 needs-validation" enctype="multipart/form-data" role="form" >
    {{ method_field('POST') }}
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-8">
            <div class="form-group has-feedback row {{ $errors->has('name') ? ' has-error ' : '' }}">
                <label for="name" class="col-12 control-label">
                    {{ trans("laravelroles::laravelroles.forms.roles-form.role-name.label") }}
                </label>
                <div class="col-12">
                    <input type="text" id="name" name="name" class="form-control" value="test" placeholder="{{ trans('laravelroles::laravelroles.forms.roles-form.role-name.placeholder') }}">
                </div>
                @if ($errors->has('name'))
                    <div class="col-12">
            <span class="help-block">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
                    </div>
                @endif
            </div>
            <div class="form-group has-feedback row {{ $errors->has('slug') ? ' has-error ' : '' }}">
                <label for="slug" class="col-12 control-label">
                    {{ trans("laravelroles::laravelroles.forms.roles-form.role-slug.label") }}
                </label>
                <div class="col-12">
                    <input type="text" id="slug" name="slug" class="form-control" value="test-slug" onkeypress="return numbersAndLettersOnly()" placeholder="{{ trans('laravelroles::laravelroles.forms.roles-form.role-slug.placeholder') }}">
                </div>
                @if ($errors->has('slug'))
                    <div class="col-12">
            <span class="help-block">
                <strong>{{ $errors->first('slug') }}</strong>
            </span>
                    </div>
                @endif
            </div>
            <div class="form-group has-feedback row {{ $errors->has('description') ? ' has-error ' : '' }}">
                <label for="description" class="col-12 control-label">
                    {{ trans("laravelroles::laravelroles.forms.roles-form.role-desc.label") }}
                </label>
                <div class="col-12">
                    <textarea id="description" name="description" class="form-control" placeholder="{{ trans('laravelroles::laravelroles.forms.roles-form.role-desc.placeholder') }}">test</textarea>
                </div>
                @if ($errors->has('description'))
                    <div class="col-12">
            <span class="help-block">
                <strong>{{ $errors->first('description') }}</strong>
            </span>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="form-group has-feedback row {{ $errors->has('level') ? ' has-error ' : '' }}">
                <label for="level" class="col-12 control-label">
                    {{ trans("laravelroles::laravelroles.forms.roles-form.role-level.label") }}
                </label>
                <div class="col-12">
                    <input type="number" id="level" name="level" min="0" step="1" onkeypress="return event.charCode >= 48" class="form-control" value="1" placeholder="{{ trans('laravelroles::laravelroles.forms.roles-form.role-level.placeholder') }}">
                </div>
                @if ($errors->has('level'))
                    <div class="col-12">
            <span class="help-block">
                <strong>{{ $errors->first('level') }}</strong>
            </span>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="card-body">

    </div>
    <div class="card-footer">
        <div class="row ">
            <div class="col-md-6">
                <span data-toggle="tooltip" title="{!! trans('laravelroles::laravelroles.tooltips.save-role') !!}">
                    <button type="submit" class="btn btn-success btn-lg btn-block" value="save" name="action">
                        <i class="fa fa-save fa-fw">
                            <span class="sr-only">
                                 {!! trans("laravelroles::laravelroles.forms.roles-form.buttons.save-role.sr-icon") !!}
                            </span>
                        </i>
                        {!! trans("laravelroles::laravelroles.forms.roles-form.buttons.save-role.name") !!}
                    </button>
                </span>
            </div>
        </div>
    </div>
</form>
