<?php 
include('db_connect.php'); 
include('header.php'); 

// 1. RESOLVE & REPLY LOGIC
if(isset($_POST['update_status'])){
    $id = intval($_POST['complaint_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $reply = mysqli_real_escape_string($conn, $_POST['admin_reply']);
    
    mysqli_query($conn, "UPDATE complaints SET status = '$status', admin_reply = '$reply' WHERE id = $id");
    echo "<script>alert('Complaint Updated!'); window.location.href='view_complaints.php';</script>";
}

// 2. DELETE LOGIC
if(isset($_GET['delete_complaint'])){
    $id = intval($_GET['delete_complaint']);
    mysqli_query($conn, "DELETE FROM complaints WHERE id = $id");
    echo "<script>alert('Complaint Deleted!'); window.location.href='view_complaints.php';</script>";
}
?>

<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
        <h5 class="fw-bold text-secondary mb-4"><i class="fas fa-inbox me-2"></i> Manage Student Support Tickets</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light small fw-bold text-uppercase">
                    <tr>
                        <th>Student</th>
                        <th>Subject & Message</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM complaints ORDER BY id DESC");
                    while($row = mysqli_fetch_assoc($res)){
                        $badge = 'bg-warning text-dark';
                        if($row['status'] == 'Under Process') $badge = 'bg-info text-white';
                        if($row['status'] == 'Resolved') $badge = 'bg-success text-white';
                        ?>
                        <tr>
                            <td>
                                <strong><?php echo $row['name']; ?></strong><br>
                                <small class="text-muted"><?php echo $row['phone']; ?></small>
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo $row['subject']; ?></div>
                                <div class="small text-muted"><?php echo $row['message']; ?></div>
                                <?php if($row['admin_reply']): ?>
                                    <div class="mt-1 p-2 bg-light border-start border-primary small">
                                        <strong>Reply:</strong> <?php echo $row['admin_reply']; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge <?php echo $badge; ?>"><?php echo $row['status']; ?></span></td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#replyModal<?php echo $row['id']; ?>">
                                    <i class="fas fa-reply"></i>
                                </button>
                                <a href="view_complaints.php?delete_complaint=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this ticket?')">
                                    <i class="fas fa-trash"></i>
                                </a>

                                <div class="modal fade" id="replyModal<?php echo $row['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form method="POST" class="modal-content text-start">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Reply to Complaint</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="complaint_id" value="<?php echo $row['id']; ?>">
                                                <div class="mb-3">
                                                    <label class="small fw-bold">Update Status</label>
                                                    <select name="status" class="form-select form-select-sm">
                                                        <option value="Pending" <?php if($row['status']=='Pending') echo 'selected'; ?>>Pending</option>
                                                        <option value="Under Process" <?php if($row['status']=='Under Process') echo 'selected'; ?>>Under Process</option>
                                                        <option value="Resolved" <?php if($row['status']=='Resolved') echo 'selected'; ?>>Resolved</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="small fw-bold">Admin Reply</label>
                                                    <textarea name="admin_reply" class="form-control" rows="3"><?php echo $row['admin_reply']; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="update_status" class="btn btn-success btn-sm w-100">Update Ticket</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>