 <!-- Footer -->
 <?php 

$year = date("Y"); 
 ?>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website {{$year}}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
            <!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="{{route('logout')}}">Logout</a>
        </div>
    </div>
</div>
</div>


<script src="{{asset('/public/site/js/common.min.js')}}"></script>
<script src="{{asset('/public/site/js/jquery/jquery.min.js')}}"></script>
<script src="{{asset('/public/site/js/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('/public/site/js/jquery-easing/jquery.easing.min.js')}}"></script>
<script src="{{asset('/public/site/js/sb-admin-2.min.js')}}"></script>
<script  src="{{asset('/public/site/js/chart.js/Chart.min.js')}}"></script>
<script src="{{asset('/public/site/js/demo/chart-area-demo.js')}}"></script>
<script src="{{asset('/public/site/js/demo/chart-pie-demo.js')}}"></script>

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->