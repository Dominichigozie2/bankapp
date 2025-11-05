        @extends('account.admin.layout.apps')
        @section('content')
        <div class="page-content">
                        <div class="container-fluid">

                        <form id="toggleDepositCodeForm">
            <label>Require deposit code?</label>
            <input type="checkbox" id="depositCodeToggle" {{ $depositCodeRequired == '1' ? 'checked' : '' }}>
        </form>



        </div>
        <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        @endsection
        @section('scripts')
        <script>
            $('#depositCodeToggle').change(function() {
                $.post("{{ route('admin.settings.depositCodeToggle') }}", {
                    value: $(this).is(':checked') ? 1 : 0,
                    _token: '{{ csrf_token() }}'
                })
                .done(function(){
                    iziToast.success({ title:'Saved', message:'Setting updated', position:'topRight' });
                })
                .fail(function(){
                    iziToast.error({ title:'Error', message:'Could not save', position:'topRight' });
                });
            });
        </script>

        @endsection