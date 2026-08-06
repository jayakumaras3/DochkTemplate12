<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Clients</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table border="1" width="50%">
                    <tr>
                        <th>Menu ID</th>
                        <th>Menu Name</th>
                        <th>Access</th>
                    </tr>

                    <?php foreach ($menus as $menu): ?>
                        <tr>
                            <td><?= $menu['id'] ?></td>
                            <td><?= esc($menu['menu_name']) ?></td>
                            <td>
                                <input type="checkbox"
                                    <?= $menu['access'] ? 'checked' : '' ?>
                                    onchange="toggleAccess(<?= $menu['id'] ?>, this.checked)">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    function toggleAccess(menuId, status) {
        fetch("<?= base_url('menu-access/toggle') ?>", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `menu_id=${menuId}&client_id=<?= $clientId ?>&access=${status ? 1 : 0}`
        });
    }
</script>