@extends(config('roles.bladeExtended'))

@section(config('roles.titleExtended'))
    {!! trans('laravelroles::laravelroles.titles.dashboard') !!}
@endsection

@php
    switch (config('roles.bootstapVersion')) {
        case '3':
            $rolesContainerClass = 'panel';
            $rolesContainerHeaderClass = 'panel-heading';
            $rolesContainerBodyClass = 'panel-body padding-0';
            break;
        case '4':
        default:
            $rolesContainerClass = 'card';
            $rolesContainerHeaderClass = 'card-header';
            $rolesContainerBodyClass = 'card-body p-0';
            break;
    }

    $bootstrapCardClasses = (is_null(config('roles.bootstrapCardClasses')) ? '' : config('roles.bootstrapCardClasses'));

    /*
    $totalUserItems = count($sortedRolesWithPermissionsAndUsers);
    $modulus = $totalUserItems % 3;
    if($modulus == 0) {
        $cardColClass = 'col-sm-6 col-md-4';
    } elseif($modulus == 1) {
        $cardColClass = 'col-sm-6 col-md-3';
    } elseif($modulus == 2) {
        $cardColClass = 'col-sm-6 col-md-4';
    } else {
        $cardColClass = 'col-sm-6';
    }
    */
@endphp

@section(config('roles.bladePlacementCss'))
    @if(config('roles.enabledDatatablesJs'))
        <link rel="stylesheet" type="text/css" href="{{ config('roles.datatablesCssCDN') }}">
    @endif
    @if(config('roles.enableFontAwesomeCDN'))
        <link rel="stylesheet" type="text/css" href="{{ config('roles.fontAwesomeCDN') }}">
    @endif
    @include('laravelroles::laravelroles.partials.styles')
    @include('laravelroles::laravelroles.partials.bs-visibility-css')
@endsection

@section('content')

    @include('laravelroles::laravelroles.partials.flash-messages')
        <div class="{{ $rolesContainerClass }} {{ $bootstrapCardClasses }}">
            <div class="container-fluid">
                <div class="table-responsive roles-table">
                    <table class="table table-sm table-striped data-table roles-table">
                        <thead>
                            <tr>
                                <th>{{ __('laravelroles::laravelroles.forms.roles-form.role-name.label') }}</th>
                                <th>{{ __('laravelroles::laravelroles.forms.roles-form.role-slug.label') }}</th>
                                <th>{{ __('laravelroles::laravelroles.forms.roles-form.role-desc.label') }}</th>
                                <th>{{ __('laravelroles::laravelroles.forms.roles-form.role-level.label') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->slug }}</td>
                                <td>{{ $role->level }}</td>
                                <td class="text-center">
                                    <a href="{{ route('laravelroles::roles.edit', $role->id) }}"
                                       class="btn btn-xs  btn-flat p-1 px-2 btn-primary text-white"
                                       title="{{ __('table.edit') }}">
                                        <span class="fi flaticon-edit"></span>
                                    </a>
                                    <a href="{{ route('laravelroles::roles.show', $role->id) }}"
                                       class="btn btn-xs  btn-flat p-1 px-2 btn-default text-dark"
                                       title="{{ __('table.view') }}">
                                        <span class="fa fa-eye"></span>
                                    </a>

                                    <form class="display-inline" method="POST"
                                          action="{{ route('admin.users.delete', $role->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-xs btn-flat p-1 px-2 btn-danger text-white"
                                                title="{{ __('table.delete') }}">
                                            <span class="fi flaticon-trash"></span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="clearfix mb-4"></div>

                @include('laravelroles::laravelroles.modals.confirm-modal',[
                    'formTrigger' => 'confirmDelete',
                    'modalClass' => 'danger',
                    'actionBtnIcon' => 'fa-trash-o'
                ])
            </div>
        </div>

@endsection

@section(config('roles.bladePlacementJs'))
    @if(config('roles.enablejQueryCDN'))
        <script type="text/javascript" src="{{ config('roles.JQueryCDN') }}"></script>
    @endif
    @include('laravelroles::laravelroles.scripts.confirm-modal', ['formTrigger' => '#confirmDelete'])
    @if (config('roles.enabledDatatablesJs'))
        @include('laravelroles::laravelroles.scripts.datatables')
    @endif
    @if(config('roles.tooltipsEnabled'))
        @include('laravelroles::laravelroles.scripts.tooltips')
    @endif
@endsection

@yield('inline_template_linked_css')
@yield('inline_footer_scripts')
