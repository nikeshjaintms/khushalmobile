
<script src="{{ asset('backend/assets/js/core/popper.min.js')}}"></script>
<script src="{{ asset('backend/assets/js/core/bootstrap.min.js')}}"></script>

<!-- jQuery Scrollbar -->
<script src="{{ asset('backend/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js')}}"></script>

<!-- Chart JS -->
<script src="{{ asset('backend/assets/js/plugin/chart.js/chart.min.js')}}"></script>

<!-- jQuery Sparkline -->
<script src="{{ asset('backend/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js')}}"></script>

<!-- Chart Circle -->
<script src="{{ asset('backend/assets/js/plugin/chart-circle/circles.min.js')}}"></script>

<!-- Datatables -->
<script src="{{ asset('backend/assets/js/plugin/datatables/datatables.min.js')}}"></script>

<!-- Bootstrap Notify -->
<script src="{{ asset('backend/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js')}}"></script>

<!-- jQuery Vector Maps -->
<script src="{{ asset('backend/assets/js/plugin/jsvectormap/jsvectormap.min.js')}}"></script>
<script src="{{ asset('backend/assets/js/plugin/jsvectormap/world.js')}}"></script>

<!-- Sweet Alert -->
<script src="{{ asset('backend/assets/js/plugin/sweetalert/sweetalert.min.js')}}"></script>

<!-- Kaiadmin JS -->
<script src="{{ asset('backend/assets/js/kaiadmin.min.js')}}"></script>

<!-- Kaiadmin DEMO methods, don't include it in your project! -->
<script src="{{ asset('backend/assets/js/setting-demo.js')}}"></script>
<script>
    function loadDashboardData() {
        $.ajax({
            url: '{{ route('dashboard.data') }} ',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                $('#productCount').text(response.product_count);
                $('#customerCount').text(response.customer_count);
                $('#transactionin').text('₹'+response.transactionin);
                $('#transactionout').text('₹'+response.transactionout);
                $('#sales').text('₹'+response.sales);
                $('#salesAmountCash').text('₹'+response.salesAmountCash);
                $('#salesAmountOnline').text('₹'+response.salesAmountOnline);
                $('#transactionInCash').text('₹'+response.transactionInCash);
                $('#transactionInOnline').text('₹'+response.transactionInOnline);
                $('#deductionAmountOnline').text('₹'+response.deductionAmountOnline);
                $('#deductionAmountCash').text('₹'+response.deductionAmountCash);
                $('#transactionOutCash').text('₹'+response.transactionOutCash);
                $('#transactionOutOnline').text('₹'+response.transactionOutOnline);
            },
            error: function () {
                console.error("Failed to fetch dashboard data.");
            }
        });
    }

    $(document).ready(function () {
        loadDashboardData(); // Load once when page loads
        setInterval(loadDashboardData, 10000); // Refresh every 10 seconds
    });
</script>
<script src="{{ asset('backend/assets/js/demo.js')}}"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js"></script>
<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
<script>
  $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
    type: "line",
    height: "70",
    width: "100%",
    lineWidth: "2",
    lineColor: "#177dff",
    fillColor: "rgba(23, 125, 255, 0.14)",
  });

  $("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
    type: "line",
    height: "70",
    width: "100%",
    lineWidth: "2",
    lineColor: "#f3545d",
    fillColor: "rgba(243, 84, 93, .14)",
  });

  $("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
    type: "line",
    height: "70",
    width: "100%",
    lineWidth: "2",
    lineColor: "#ffa534",
    fillColor: "rgba(255, 165, 52, .14)",
  });

  $(document).ready(function () {
    $("#basic-datatables").DataTable({
        order: [[0, 'desc']]
    });
  });


</script>

