"use strict";

// Class definition
let KTDatatable = (function () {
    // Shared variables
    let table;
    let datatable;

    // Private functions
    let initDatatable = function (campaign_id) {
        datatable = $("#kt_datatable").DataTable({
            orderable: false,
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [],
            stateSave: false,
            select: {
                style: "os",
                selector: "td:first-child",
                className: "row-selected",
            },
            ajax: {
                url: `/dashboard/campaignsresults/${campaign_id}`,
                data: function (d) {
                    let info = $("#kt_datatable").DataTable().page.info();
                    d.page = info.page + 1;
                    d.per_page = info.length;
                },
            },

            columns: [
                { data: "id" },
                { data: "campaign.campaign_name" },
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
        init: function (campaign_id) {
            initDatatable(campaign_id);
            handleSearchDatatable();
            // handleFilterDatatable();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDatatable.init();
});
