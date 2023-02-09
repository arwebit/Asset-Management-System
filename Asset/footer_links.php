<script src="../assets/js/jquery.min.js?v=<?php echo time(); ?>"></script>
<script src="../assets/js/bootstrap.min.js?v=<?php echo time(); ?>"></script>
<script src="../assets/js/vendor.bundle.base.js?v=<?php echo time(); ?>"></script>
<script src="../assets/js/off-canvas.js?v=<?php echo time(); ?>"></script>
<<script src="../assets/js/hoverable-collapse.js?v=<?php echo time(); ?>"></script>
<script src="../assets/js/template.js?v=<?php echo time(); ?>"></script>
<script src="../assets/js/settings.js?v=<?php echo time(); ?>"></script>
<script src="../assets/js/dashboard.js?v=<?php echo time(); ?>"></script>
<script src="../assets/js/select2/select2.min.js?v=<?php echo time(); ?>"></script>
<script src="../assets/js/select2.js?v=<?php echo time(); ?>"></script>
<script src="../assets/js/digital_clock.js?v=<?php echo time(); ?>"></script>
<script src="../assets/js/html5-qrcode.min.js?v=<?php echo time(); ?>"></script>
<script src="../assets/js/fancyTable.min.js?v=<?php echo time(); ?>"></script>
<script>
                    function print_area(divName) {
                        document.getElementById("report_header").style.display="block";
                        document.getElementById("report_footer").style.display="block";
                    var printContents = document.getElementById(divName).innerHTML;
                    var originalContents = document.body.innerHTML;
                    document.body.innerHTML = printContents;
                    window.print();
                    document.body.innerHTML = originalContents;
                    window.location.href = "#";
                }
                    </script>
<script>
    $(document).ready(function () {
        $(".sampleTable").fancyTable({
            /* Setting pagination or enabling */
            pagination: true,
            /* Rows per page kept for display */
            perPage: 5,
            globalSearch: true
        });

    });
    $(document).ready(function () {
        $("a:contains('Unlicensed copy of the Froala Editor. Use it legally by purchasing a license.')").text("");
        $("a:contains('Unlicensed copy of the Froala Editor. Use it legally by purchasing a license.')").attr('style', 'visibility: hidden');
        $("p[data-f-id='pbf']").text("");
        $("p[data-f-id='pbf']").hide();
    });
</script>


