    <!-- Global Script -->
    <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('js/jquery.toaster.js')}}"></script>
    <script src="{{ asset('js/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('js/datatable.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                $.toaster({
                    priority: 'danger',
                    title: 'Error',
                    message: '{{ $error }}'
                });
            @endforeach
        @endif
        @if (Session::has('success'))
            $.toaster({
                priority: 'success',
                title: 'Success',
                message: '{{ session()->get('success') }}'
            });
        @endif

        $(document).ready(function() {
            // ALert On Delete
            $(document).on('click', '.delete', function(e) {
                let item = $(this);
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: ($(this).data('action') != '' && $(this).data('action') !=
                            undefined && $(this).data('action') != null) ? $(this).data('action') :
                        'Yes, delete it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(item).parents('form').submit();
                    }
                })
            })
            $(document).on('click', '.image-uploader', function(e) {
                $(this).prev('input').click();
            })

            function readURL(input, preview, multiple) {
                $(preview).html('');
                if (multiple == true) {
                    if (input.files && input.files[0]) {
                        for (i = 0; i <= input.files.length; i++) {
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                if(e.target.result.includes("video")){

                                    $(preview).append($(`<video width="320" height="240" controls>
                                        <source src="${e.target.result}" type="video/mp4">
                                        Your browser does not support the video tag.
                                        </video>`));
                                }
                                else{

                                    $(preview).append($('<img src="' + e.target.result + '">'));
                                }

                                $(preview).hide();
                                $(preview).fadeIn(650);
                            }
                            reader.readAsDataURL(input.files[i]);
                        }
                    }
                }

                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        if(e.target.result.includes("video")){
                            $(preview).append($(`<video width="320" height="240" controls>
                            <source src="${e.target.result}" type="video/mp4">
                            Your browser does not support the video tag.
                            </video>`));
                        }
                        else{
                            $(preview).append($('<img src="' + e.target.result + '">'));
                        }
                        $(preview).hide();
                        $(preview).fadeIn(650);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $(".uploader input[type='file']").change(function() {
                let multiple = false;
                var attr = $(this).attr('multiple');
                if (typeof attr !== 'undefined' && attr !== false) {
                    multiple = true;
                }
                readURL(this, $(this).next('.image-uploader'), multiple);
            });
        })
    </script>
    @auth
    <script src='https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js'></script>
    <script src='https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js'></script>
    <script type="text/javascript">
        const firebaseConfig = {
            apiKey: "{{ config('fcm_config.firebase_config.apiKey') }}",
            authDomain: "{{ config('fcm_config.firebase_config.authDomain') }}",
            projectId: "{{ config('fcm_config.firebase_config.projectId') }}",
            storageBucket: "{{ config('fcm_config.firebase_config.storageBucket') }}",
            messagingSenderId: "{{ config('fcm_config.firebase_config.messagingSenderId') }}",
            appId: "{{ config('fcm_config.firebase_config.appId') }}",
        };

        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        messaging
            .requestPermission()
            .then(() =>
                messaging.getToken()
            )
            .then(function(res) {
                fetch("{{ route('register-token') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            _token: csrfToken,
                            token: res
                        })
                    })
                    .catch(error => console.error(error));
            })
            .catch(function(err) {
                console.error('catch', err);
            });
    </script>
    @endauth

    <!-- Page Level Script -->
    @stack('afterScripts')

