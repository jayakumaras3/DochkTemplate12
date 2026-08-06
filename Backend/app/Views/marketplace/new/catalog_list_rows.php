<?php foreach ($courses as $idx => $course):
    if (empty($course['scourse_id']) || trim((string) $course['course_name']) === '') {
        continue;
    }
?>
    <tr>
        <td><?= $startRank + $idx ?></td>
        <td class="title-cell"><?= esc($course['course_name']) ?></td>
        <td><?= esc($course['language']) ?></td>
        <td><?= (int) $course['duration'] ?> <?= lang('UI_Text.Minutes') ?></td>
        <td>
            <form class="mb-0" action="<?= base_url('my_training/read_more') ?>" method="POST"><?= csrf_field() ?>
                <input type="hidden" name="crid" value="<?= $course['scourse_id'] ?>">
                <input type="hidden" name="detail_type" value="5">
                <input type="hidden" name="tab" value="1">
                <button type="submit" class="btn btn-outline-info waves-effect btn-xs rounded-pill waves-light"><?= lang('Buttons.View') ?></button>
            </form>
        </td>
    </tr>
<?php endforeach; ?>
