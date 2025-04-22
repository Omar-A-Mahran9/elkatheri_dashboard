"use strict";

// Class definition
let KTDatatable = (function () {
    // Shared variables
    let table;
    let datatable;

    // Private functions
    let initDatatable = function () {
        datatable = $("#kt_datatable").DataTable({
            orderable: false,
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [], // display records number and ordering type
            stateSave: false,
            select: {
                style: "os",
                selector: "td:first-child",
                className: "row-selected",
            },
            ajax: {
                data: function () {
                    let datatable = $("#kt_datatable");
                    let info = datatable.DataTable().page.info();
                    datatable
                        .DataTable()
                        .ajax.url(
                            `/dashboard/campaignsresults?page=${
                                info.page + 1
                            }&per_page=${info.length}`
                        );
                },
            },
            columns: [
                { data: "id" },
                { data: "campaign_id" },
                { data: "ip_address" },
                { data: "user_agent" },
                { data: "created_at" },
             ],
            columnDefs: [],
        });

        table = datatable.$;

        datatable.on("draw", function () {
            KTMenu.createInstances();
        });
    };

    // Public methods
    return {
        init: function () {
            initDatatable();
            handleSearchDatatable();
            // handleFilterDatatable();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDatatable.init();
});
