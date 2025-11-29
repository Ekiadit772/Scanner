<div x-data="{
    show: {{ session('success') || session('error') ? 'true' : 'false' }},
    type: '{{ session('success') ? 'success' : (session('error') ? 'error' : '') }}',
    message: '{{ session('success') ?? session('error') }}'
}" x-init="if (show && type && message) {
    const toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        padding: '2em',
        customClass: 'sweet-alerts',
    });

    toast.fire({
        icon: type,
        title: message,
    });
}"></div>
