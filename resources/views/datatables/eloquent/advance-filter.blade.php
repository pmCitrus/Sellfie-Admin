@extends('layouts.master')

@section('content')

    
    <table class="table table-bordered" id="users-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Created At</th>
                <th></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th rowspan="1" colspan="1">
                    <input>
                </th>
                <th rowspan="1" colspan="1">
                    <input>
                </th>
            </tr>
        </tfoot>
    </table>
@stop
<script>
function testFn(){
    alert('hi');
}
</script>

@push('scripts')
<script>
//$('#users-table').DataTable({
//        processing: true,
//        serverSide: true,
//        ajax: '{!! route('datatables.data') !!}',
//        columns: [
//            {data: 'users_id', name: 'users_id'},
//            {data: 'username', name: 'username'},
//            {data: 'created_at', name: 'created_at'},
//        ],
//        initComplete: function () {
//            this.api().columns().every(function () {
//                var column = this;
//                var input = document.createElement("input");
//                $(input).appendTo($(column.footer()).empty())
//                .on('keyup', function () {
//                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
//
//                    column.search(val ? val : '', true, false).draw();
//                });
//            });
//        },
//        buttons: ["create", {
//            "extend": "collection",
//            "text": "<i class=\"fa fa-download\"><\/i> Export",
//            "buttons": ["csv", "excel", "pdf"]
//        }, "print", "reset", "reload"]
//    });
</script>

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
<script src="/vendor/datatables/buttons.server-side.js"></script>
<script>
//    (function(window, $) {
//    window.LaravelDataTables = window.LaravelDataTables || {};
//    window.LaravelDataTables["dataTableBuilder"] = $("#users-table").DataTable({
//        "serverSide": true,
//        "processing": true,
//        "ajax": '{!! route('datatables.data') !!}',
//        columns: [
//            {data: 'users_id', name: 'users_id'},
//            {data: 'username', name: 'username'},
//            {data: 'created_at', name: 'created_at'},
//        ],
//        "order": [
//            [0, "desc"]
//        ],
//        "buttons": ["create", {
//            "extend": "collection",
//            "text": "<i class=\"fa fa-download\"><\/i> Export",
//            "buttons": ["csv", "excel", "pdf"]
//        }]
//    });
//})(window, jQuery);
</script>


<script type="text/javascript">
    (function(window, $) {
        window.LaravelDataTables = window.LaravelDataTables || {};
        window.LaravelDataTables["dataTableBuilder"] = $("#users-table").DataTable({
            "serverSide": true,
            "processing": true,
            "ajax": "",
            "columns": [{
                "name": "users_id",
                "data": "users_id",
                "title": "Id",
                "orderable": true,
                "searchable": true
            }, {
                "name": "username",
                "data": "username",
                "title": "USERNAME",
                "orderable": true,
                "searchable": true
            }, {
                "name": "created_at",
                "data": "created_at",
                "title": "TIME",
                "orderable": true,
                "searchable": true
            },
            {data: 'action', "title": "TIME",name: 'action', orderable: false, searchable: false}
        ],
            "dom": "Bfrtip",
            "buttons": ["csv", "excel", "pdf", "print", "reset", "reload"]
        });
    })(window, jQuery);
</script>



@stop