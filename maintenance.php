<?php 
include('db_connect.php'); 
include('header.php'); 
?>

<div class="container-fluid px-4 mt-4">
    <div class="mb-4">
        <h3 class="fw-bold text-dark">Vehicle Maintenance</h3>
        <p class="text-muted small">Track service history and bus health</p>
    </div>

    <div class="row g-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm p-4" style="background-color: #e1bee7; border-radius: 15px; border-bottom: 5px solid #7b1fa2;">
                <h4 class="fw-bold text-dark mb-4"><i class="fas fa-tools me-2 text-purple"></i>Service Logs</h4>
                <div class="table-responsive bg-white rounded-3 p-2">
                    <table class="table table-hover align-middle fs-5">
                        <thead class="text-uppercase small fw-bold">
                            <tr><th>Bus No</th><th>Last Service</th><th>Cost</th><th>Next Due</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold">LES-5566</td>
                                <td>01-Feb-2026</td>
                                <td>Rs. 12,000</td>
                                <td class="text-danger fw-bold">15-Mar-2026</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div></body></html>