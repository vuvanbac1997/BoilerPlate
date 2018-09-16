<script src="{!! \URLHelper::asset('vendor/jquery/jquery.min.js', 'admin') !!}"></script>
<script src="{!! \URLHelper::asset('vendor/bootstrap/js/bootstrap.bundle.min.js', 'admin') !!}"></script>

<!-- Core plugin JavaScript-->
<script src="{!! \URLHelper::asset('vendor/jquery-easing/jquery.easing.min.js', 'admin') !!}"></script>
<script src="{!! \URLHelper::asset('libs/plugins/toastr/toastr.min.js', 'admin') !!}"></script>

<!-- Custom scripts for all pages-->
<script src="{!! \URLHelper::asset('js/sb-admin.min.js', 'admin') !!}"></script>
<script src="{!! \URLHelper::asset('libs/plugins/flagstrap/dist/js/jquery.flagstrap.min.js', 'admin') !!}"></script>
<script src="{!! \URLHelper::asset('libs/adminlte/js/app.min.js', 'admin') !!}"></script>
<script src="{!! \URLHelper::asset('libs/bootstrap/js/bootstrap.min.js', 'admin') !!}"></script>

{{--Data table--}}
<script src="{!! \URLHelper::asset('vendor/datatables/jquery.dataTables.js', 'admin') !!}"></script>
<script src="{!! \URLHelper::asset('vendor/datatables/dataTables.bootstrap4.js', 'admin') !!}"></script>
<script src="{!! \URLHelper::asset('js/demo/datatables-demo.js', 'admin') !!}"></script>

<script type="text/javascript">
    var Boilerplate = {
        'csrfToken': "{!! csrf_token() !!}"
    };

    $('#language-switcher').flagStrap({
        countries: {
            "GB": "English",
            "VN": "Tiếng Việt"
        },
        buttonSize: "btn-sm",
        buttonType: "btn-primary",
        labelMargin: "10px",
        scrollable: false,
        placeholder: false,
        scrollableHeight: "350px",
        onSelect: function (value, element) {
            url = window.location.href.split('?')[0] + '?locale=' + value.toLowerCase();
            window.location.href = url;
        }
    });

    @if(Session::has('message-success'))
        toastr["success"]("{{ Session::get('message-success') }}", "Successfully !!!");
    @endif
        @if(Session::has('message-failed'))
        toastr["error"]("{{ Session::get('message-failed') }}", "Error !!!");

    @endif
</script>