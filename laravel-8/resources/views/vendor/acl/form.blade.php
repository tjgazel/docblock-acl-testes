<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        @if ($form['type'] == 'create')
        ACL - {{ __('acl::view.new_group') }}
      @else
        ACL - {{ __('acl::view.editing_group', ['group' => $group->name]) }}
      @endif
    </h2>
  </x-slot>

  <div class="max-w-7xl rounded mx-auto px-4 sm:px-6 lg:px-8 pt-8 bg-white">
    <form action="{{ $form['action'] }}" method="post">
      @csrf @method( $form['method'] )
      <div class="form-row">
        <div class="form-group col-12 col-md-8 col-lg-6">
          <label>{{ __('acl::view.name') }}</label>
          <input type="text" name="name" value="{{ old('name') ?? ($group->name ?? '') }}"
            class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}">
          @if ($errors->has('name'))
            <small class="invalid-feedback" role="alert"><strong>{{ $errors->first('name') }}</strong></small>
          @endif
        </div>
        <div class="form-group col-12">
          <label>{{ __('acl::view.description') }}</label>
          <input type="text" name="description" value="{{ old('description') ?? ($group->description ?? '') }}"
            class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}">
          @if ($errors->has('description'))
            <small class="invalid-feedback" role="alert"><strong>{{ $errors->first('description') }}</strong>
            </small>
          @endif
        </div>
      </div>

      <h3 class="h4 my-3 border-bottom">{{ __('acl::view.group_permissions') }}</h3>

      @foreach ($resourcesPermissions as $resource => $permissions)
        <fieldset class="border px-3 pb-2 mb-4 shadow">
          <legend class="border bg-light h5 py-1 font-weight-bold">
            <div class="custom-control custom-checkbox ml-3">
              <input type="checkbox" id="customCheck{{ $resource }}" data-title="{{ $resource }}"
                class="custom-control-input toggle-box" onclick="selectAll(event)">
              <label class="custom-control-label" for="customCheck{{ $resource }}">
                {{ $resource }}
              </label>
            </div>
          </legend>
          <div class="flex">
            @foreach ($permissions as $permission)
              <div class="w-6/12 lg:w-3/12 xl:w-4/12 my-6">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" name="permissions[{{ $permission->id }}]" value="{{ $permission->id }}"
                    id="customCheck{{ $permission->id }}" class="custom-control-input {{ $resource }}"
                    {{ (old("permissions[{$permission->id}]") ? 'checked' : isset($group) && $group->permissions->where('id', $permission->id)->count() > 0) ? 'checked' : '' }}>
                  <label class="custom-control-label" for="customCheck{{ $permission->id }}">
                    {{ $permission->name }}
                  </label>
                </div>
              </div>
            @endforeach
          </div>
        </fieldset>
      @endforeach

      <div class="row mt-5">
        <div class="col-12 text-right">
          <a href="{{ route(aclPrefixRoutName() . 'index') }}" class="btn btn-outline-secondary">
            {{ __('acl::view.cancel') }}
          </a>&nbsp;&nbsp;&nbsp;
          <button type="submit"
            class="btn {{ $form['type'] == 'create' ? 'btn-outline-primary' : 'btn-outline-warning' }}">
            {{ __('acl::view.save') }}
          </button>
        </div>
      </div>
    </form>
  </div>

  <script type="text/javascript">
    window.onload = function() {
      Array.from(document.getElementsByClassName('toggle-box')).forEach(function(toggle) {
        var all = true;
        Array.from(document.getElementsByClassName(toggle.dataset.title)).forEach(function(permission) {
          if (!permission.checked) {
            all = false;
          }
        });
        if (all) {
          toggle.checked = true;
        }
      });
    };

    function selectAll(event) {
      var toggle = event.target;
      Array.from(document.getElementsByClassName(toggle.dataset.title)).forEach(function(permission) {
        if (toggle.checked) {
          permission.checked = true;
        } else {
          permission.checked = false;
        }
      });
    };

  </script>
</x-app-layout>
