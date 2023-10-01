<script src="{{ asset('js/swall.js') }}"></script>
<script src="{{ asset('all.js') }}"></script>
<script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>
<!-- Stack array for including inline js or scripts -->
<script>
    $("#success-alert").fadeTo(2000, 20000).slideUp(500, function() {
        $("#success-alert").slideUp(15000);
    });
    $(".alert").fadeTo(5000, 20000).slideUp(500, function() {
        $(".alert").slideUp(15000);
    });
</script>
@stack('script')

<script src="{{ asset('dist/js/theme.js') }}"></script>
{{-- <script src="{{ asset('js/chat.js') }}"></script> --}}

@stack('form')
@stack('scriptdinamis')
