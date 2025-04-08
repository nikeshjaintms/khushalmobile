<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        @if(session('success'))
            $.notify({
                // Notification content
                icon: 'fas fa-check-circle', 
                message: "{{ session('success') }}"
            },{
                // Notification options
                type: 'success', // 'success', 'danger', 'info', 'warning'
                allow_dismiss: true,
                delay: 3000,
                placement: {
                    from: "top", // 'top', 'bottom'
                    align: "right" // 'left', 'right', 'center'
                },
                offset: {
                    x: 20, // Horizontal offset
                    y: 70  // Vertical offset
                }
            });
        @elseif(session('warning'))
        $.notify({
            // Notification content
            icon: 'fas fa-exclamation-triangle', 
            message: "{{ session('warning') }}"
        },{
            // Notification options
            type: 'warning', // 'success', 'danger', 'info', 'warning'
            allow_dismiss: true,
            delay: 3000,
            placement: {
                from: "top", // 'top', 'bottom'
                align: "right" // 'left', 'right', 'center'
            },
            offset: {
                x: 20, // Horizontal offset
                y: 70  // Vertical offset
            }
        });
        @elseif(session('error'))
            $.notify({
                icon: 'fas fa-times-circle', 
                message: "{{ session('error') }}"
            },{
                type: 'danger',
                allow_dismiss: true,
                delay: 3000,
                placement: {
                    from: "top",
                    align: "right"
                },
                offset: {
                    x: 20,
                    y: 70
                }
            });
        @endif
    });
</script>
