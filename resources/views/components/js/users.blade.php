<script src="{{ asset('assets/js/jquery.js') }}"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script>
    let userTable = null;
    const token = $("meta[name='api-token']").attr("content");

    $(document).ready(function() {
        $("#filter_role").select2({
            width: "100%",
            allowClear: true,
            placeholder: "Pilih Role"
        });

        $("#filter_opd").select2({
            width: "100%",
            allowClear: true,
            placeholder: "Pilih Perangkat Daerah"
        });

        $('#search').on('keyup', function() {
            loadUserTable();
        });

        $("#filter_role, #filter_opd").on("change", function() {
            loadUserTable();
        });

        loadUserTable();
    });

    function loadUserTable() {

        $.ajax({
            url: "{{ url('/api/users') }}",
            method: "GET",
            data: {
                role: $("#filter_role").val(),
                opd: $("#filter_opd").val(),
                search: $("#search").val(),
            },
            headers: {
                "Authorization": `Bearer ${token}`
            },
            success: function(res) {

                console.log(res);

                if (userTable) userTable.destroy();

                const rows = res.data.map(item => [
                    item.no,
                    `<div class="space-y-1">
                        <div class="font-semibold text-gray-800">
                            ${item.nama}
                        </div>
                        <div>
                            <span class="badge badge-outline-primary">
                                ${item.perangkat_daerah_nama}
                            </span>
                        </div>
                        ${
                            item.bidang_nama
                            ? `
                                <div>
                                    <span class="badge badge-outline-success">
                                        ${item.bidang_nama}
                                    </span>
                                </div>
                            `
                            : ""
                        }
                    </div>`,
                    item.email,
                    item.nip,
                    item.phone,
                    `<b>${item.role}</b>`,
                    item.buttons

                ]);

                userTable = new simpleDatatables.DataTable("#myTable2", {
                    data: {
                        headings: ["No", "Nama", "Email", "NIP", "Telepon", "Role", "Aksi"],
                        data: rows
                    },
                    columns: [{
                        select: 6,
                        sortable: false,
                        render: (data) => data
                    }],
                    perPage: 10,
                    searchable: false,
                });
            }
        });
    }

    function deleteUser(id) {

        Swal.fire({
            title: "Hapus user?",
            icon: "warning",
            showCancelButton: true
        }).then(res => {

            if (!res.isConfirmed) return;

            fetch(`{{ url('/api/users') }}/${id}/destroy`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
                        "Authorization": `Bearer ${token}`
                    }
                })
                .then(r => r.json())
                .then(data => {
                    Swal.fire("Berhasil!", data.message, "success");
                    loadUserTable();
                });
        });
    }

    function editUser(id) {

        fetch(`{{ url('/api/users') }}/${id}/edit`, {
                headers: {
                    "Authorization": `Bearer ${token}`
                }
            })
            .then(r => r.json())
            .then(res => {

                if (res.status !== "success") {
                    Swal.fire("Gagal!", res.message, "error");
                    return;
                }

                const u = res.data.user;

                console.log(u);

                $("#userForm")[0].reset();
                $("#userForm").attr("data-id", id);

                $("#name").val(u.name);
                $("#email").val(u.email);
                $("#phone").val(u.phone);
                $("#nip").val(u.nip);

                $("#role").val(res.data.userRole).trigger("change");

                setTimeout(() => {

                    if (u.perangkat_daerah_id !== null) {
                        $("#perangkat_daerah_id")
                            .val(u.perangkat_daerah_id)
                            .trigger("change");
                    } else {
                        $("#perangkat_daerah_id").val(null).trigger("change");
                    }

                }, 150);

                setTimeout(() => {
                    const $bidang = $("#penyedia_layanan_id");
                    $bidang.empty();

                    if (u.penyedia_layanan_id == -1) {
                        const opt = new Option("Semua Bidang", -1, true, true);
                        $bidang.append(opt).trigger("change");

                    } else if (u.penyedia_layanan_id && u.penyedia_layanan) {
                        const opt = new Option(
                            u.penyedia_layanan.nama_bidang,
                            u.penyedia_layanan_id,
                            true,
                            true
                        );
                        $bidang.append(opt).trigger("change");

                    } else {
                        $bidang.select2({
                            width: "100%",
                            placeholder: "Pilih Bidang",
                            data: [{
                                id: "",
                                text: "Pilih perangkat daerah terlebih dahulu",
                                disabled: true
                            }]
                        });
                    }

                }, 300);

                $("#modal-title").text("Edit User");
                window.dispatchEvent(new CustomEvent("open-edit-modal"));
            });
    }


    function detailUser(id) {

        fetch(`{{ url('/api/users') }}/${id}/edit`, {
                headers: {
                    "Authorization": `Bearer ${token}`
                }
            })
            .then(r => r.json())
            .then(res => {

                const u = res.data.user;

                $("#userForm")[0].reset();
                $("#name").val(u.name).prop("disabled", true);
                $("#email").val(u.email).prop("disabled", true);
                $("#phone").val(u.phone).prop("disabled", true);
                $("#nip").val(u.nip).prop("disabled", true);

                $("#role").val(res.data.userRole).trigger("change").prop("disabled", true);

                setTimeout(() => {

                    if (u.perangkat_daerah_id !== null) {
                        $("#perangkat_daerah_id")
                            .val(u.perangkat_daerah_id)
                            .trigger("change");
                    } else {
                        $("#perangkat_daerah_id").val(null).trigger("change");
                    }

                }, 150);

                setTimeout(() => {
                    const $bidang = $("#penyedia_layanan_id");
                    $bidang.empty();

                    if (u.penyedia_layanan_id == -1) {
                        const opt = new Option("Semua Bidang", -1, true, true);
                        $bidang.append(opt).trigger("change");

                    } else if (u.penyedia_layanan_id && u.penyedia_layanan) {
                        const opt = new Option(
                            u.penyedia_layanan.nama_bidang,
                            u.penyedia_layanan_id,
                            true,
                            true
                        );
                        $bidang.append(opt).trigger("change");

                    } else {
                        $bidang.select2({
                            width: "100%",
                            placeholder: "Pilih Bidang",
                            data: [{
                                id: "",
                                text: "Pilih perangkat daerah terlebih dahulu",
                                disabled: true
                            }]
                        });
                    }

                }, 300);

                $("#modal-title").text("Detail User");
                window.dispatchEvent(new CustomEvent("open-detail"));
            });
    }

    $(document).on("submit", "#userForm", function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        const userId = $(form).attr("data-id");

        let url = "";
        let msg = "";

        if (userId) {
            url = `{{ url('/api/users/update') }}/${userId}`;
            formData.append("_method", "PUT");
            msg = "User berhasil diperbarui!";
        } else {
            url = "{{ url('/api/users') }}";
            msg = "User berhasil ditambahkan!";
        }

        fetch(url, {
                method: "POST",
                headers: {
                    "Authorization": `Bearer ${token}`,
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: formData
            })
            .then(r => r.json().then(data => ({
                ok: r.ok,
                data
            })))
            .then(({
                ok,
                data
            }) => {

                if (!ok) throw data;

                window.dispatchEvent(new CustomEvent("close-modal"));

                Swal.fire("Berhasil", msg, "success");

                loadUserTable();
            })
            .catch(err => {
                $(".error-text").remove();
                $(".is-invalid").removeClass("is-invalid");

                if (err.errors) {
                    Object.keys(err.errors).forEach(field => {
                        const input = $(`[name="${field}"]`);

                        if (input.length) {
                            input.addClass("is-invalid");
                            input.after(`
                                <p class="text-red-500 text-sm mt-1 error-text">${err.errors[field][0]}</p>
                            `);
                        }
                    });

                    return;
                }

                Swal.fire("Gagal!", err.message || "Terjadi kesalahan tak terduga", "error");
            });
    });
</script>
