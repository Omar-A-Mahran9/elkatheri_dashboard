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
                    let campaign_id = $("#kt_datatable").data("campaign-id");
                    console.log(campaign_id); // Check if this prints the correct campaign_id

                    // Check if the constructed URL is correct
                    let url = `/dashboard/campaignsresults/${campaign_id}?page=${
                        info.page + 1
                    }&per_page=${info.length}`;
                    console.log(url); // Check if the URL includes the campaign_id

                    // Update the AJAX URL with campaign_id
                    datatable.DataTable().ajax.url(url);
                },
            },
            columns: [
                { data: "id" },
                { data: "campaign.name" },
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
             // handleFilterDatatable();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDatatable.init();
});
