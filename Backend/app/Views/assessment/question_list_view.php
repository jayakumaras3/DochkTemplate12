<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a
							href="<?php echo base_url($main_header_link) ?>"><?php echo $main_header ?></a></li>
					<li class="breadcrumb-item"><a href="<?php echo base_url($header_link) ?>"><?php echo $header ?></a>
					</li>
				</ol>
			</div>
			<h4 class="page-title"><?php echo $sub_header_1; ?> - <?php echo $pagerow['page_number'] ?></h4>
		</div>
	</div>
</div>
<!-- <div class="row">
	<?php
	$sub_page_main = $pagerow['sub_page_main'];
	if ($sub_page_main == 0) {
	?>
		<div class="form-group col-md-8 mb-2">
			<?php if ($prev_page) { ?>
				<form class="form-horizontal"
					action="<?php echo base_url('SCORM/course_builder/scorm_course_pages/page_edit_view') ?>" method="POST"><?= csrf_field() ?>
					<?= csrf_field() ?>
					<input type="hidden" name="page_id" value="<?php echo $prev_page[0]['page_id'] ?>">
					<input type="hidden" name="page_number" value="<?php echo $prev_page[0]['page_number'] ?>">
					<input type="hidden" name="page_name" value="<?php echo $prev_page[0]['page_name'] ?>">
					<button type="submit" alt="Next" class="" style="all: unset; cursor: pointer;"><i
							class="mdi mdi-arrow-left-circle-outline font-22"></i></button>
				</form>
			<?php } ?>
		</div>
		<div class="form-group col-md-4 mb-2 ribbon ribbon-blue float-start">
			<?php if ($next_page) { ?>
				<form class="form-horizontal  float-end mt-0"
					action="<?php echo base_url('SCORM/course_builder/scorm_course_pages/page_edit_view') ?>" method="POST"><?= csrf_field() ?>
					<?= csrf_field() ?>
					<input type="hidden" name="page_id" value="<?php echo $next_page[0]['page_id'] ?>">
					<input type="hidden" name="page_number" value="<?php echo $next_page[0]['page_number'] ?>">
					<input type="hidden" name="page_name" value="<?php echo $next_page[0]['page_name'] ?>">
					<button type="submit" alt="Next" style="all: unset; cursor: pointer;"><i
							class="mdi mdi-arrow-right-circle-outline font-22"></i></button>
				</form>
				<?php } else {
				if ($sub_page_main == 0) {
					$nxt_page = $pagerow['page_number'] + 1;
				?>
					<form class="form-horizontal  float-end mt-0"
						action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/page_add_view') ?>" method="POST"><?= csrf_field() ?>
						<?= csrf_field() ?>
						<input type="hidden" name="nxt_pageid" value="<?php echo $nxt_page; ?>">
						<input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
						<button type="submit" class="btn btn-danger rounded-pill waves-effect waves-light">Create New Page <i
								class="mdi mdi-arrow-right-bold-circle-outline"></i></button>
					</form>
			<?php
				}
			} ?>
		</div>
	<?php
	} else {
	?>
		<form class="form-horizontal mb-2"
			action="<?php echo base_url('SCORM/course_builder/scorm_course_pages/page_edit_view') ?>" method="POST"><?= csrf_field() ?>
			<input type="hidden" name="page_id" value="<?php echo $pagerow['sub_page_main']; ?>">
			<input type="hidden" name="page_number" value="<?php echo $pagerow['sub_page_main']; ?>">
			<input type="hidden" name="course_id" value="<?php echo $scourse_id ?>">
			<button type="submit" class="btn btn-success rounded-pill waves-effect waves-light"><i
					class="mdi mdi-arrow-left-bold-circle-outline"></i> Main</button>
		</form>
		<div class="row">
			<div class="col-6 col-md-6 col-lg-12">
				<div class="card">
					<div class="card-body">
						<form class="form-horizontal" action="<?php echo base_url($editsubpage) ?>" method="POST"
							id="submitForm"><?= csrf_field() ?>
							<?= csrf_field() ?>
							<div class="row">
								<div class="form-group col-md-4 mb-2">
									<label>Page Name</label>
									<input type="text" class="form-control col-md-12" name="page_name"
										placeholder="Page Name" value="<?php echo $pagerow['page_name'] ?>" />
								</div>
								<div class="form-group col-md-2 mb-2">
									<label>Page Type</label>
									<select name="type" class="form-control">
										 <option value="1" <?php echo ($pagerow['type'] == 1) ? 'selected' : ''; ?>>Articulate</option>
								<option value="9" <?php echo ($pagerow['type'] == 9) ? 'selected' : ''; ?>>Audio Version</option>
								<option value="2" <?php echo ($pagerow['type'] == 2) ? 'selected' : ''; ?>>Video</option>
								<option value="8" <?php echo ($pagerow['type'] == 8) ? 'selected' : ''; ?>>Video Sub Page</option>
								<option value="3" <?php echo ($pagerow['type'] == 3) ? 'selected' : ''; ?>>Html</option> 
										<option value="4" <?php echo ($pagerow['type'] == 4) ? 'selected' : ''; ?>>Quiz
										</option>
										 <option value="5" <?php echo ($pagerow['type'] == 5) ? 'selected' : ''; ?>>SCQ</option>
										<option value="6" <?php echo ($pagerow['type'] == 6) ? 'selected' : ''; ?>>MCQ</option> 
									</select>
								</div>

								<div class="form-group col-md-2 mb-2">
									<label>Page Number</label>
									<input type="text" step="0.1" class="form-control col-md-12" name="page_number"
										placeholder="Page Number" value="<?php echo $pagerow['page_number'] ?>" />
								</div>
								<?php if ($sub_page_main != 0) { ?>
									<div class="form-group col-md-2 mb-2">
										<label>Return Page</label>
										<input type="text" step="0.1" class="form-control col-md-12" name="sub_page_main"
											placeholder="Return Page" value="<?php echo $pagerow['sub_page_main'] ?>" />
									</div>
								<?php } else {
									echo '<input type="hidden" name="sub_page_main" value="0" />';
								} ?>
								<div class="form-group col-md-2 mb-2">
									<label>Status</label>
									<select name="status" class="form-control">
										<option value="1" <?php echo ($pagerow['status'] == 1) ? 'selected' : ''; ?>>Editing
										</option>
										<!-- 
								<option value="2" <?php echo ($pagerow['status'] == 2) ? 'selected' : ''; ?>>CE Rev</option>
								<option value="3" <?php echo ($pagerow['status'] == 3) ? 'selected' : ''; ?>>CE Fix</option>
								<option value="4" <?php echo ($pagerow['status'] == 4) ? 'selected' : ''; ?>>Client Rev</option>
								<option value="5" <?php echo ($pagerow['status'] == 5) ? 'selected' : ''; ?>>Client Fix</option> -->
										<option value="6" <?php echo ($pagerow['status'] == 6) ? 'selected' : ''; ?>>Ready for
											Dev</option>
										<option value="0" <?php echo ($pagerow['status'] == 0) ? 'selected' : ''; ?>>Delete
										</option>
									</select>
								</div>
								<div class="form-group col-md-2 mt-3 mb-2">
									<?php if (isset($coursevalidation)): ?>
										<div class=col-12 col-sm-4>
											<div class="alert alert-white" role="alert">
												<?= $coursevalidation->listErrors() ?>
											</div>
										</div>
									<?php endif; ?>
									<input type="hidden" name="page_id" value="<?php echo $pagerow['page_id']; ?>">
									<!-- <input type="hidden" name="status" value="1"> 
									<input type="hidden" name="returnUrl" value="1">
									<button type="submit"
										class="btn btn-outline-warning waves-effect btn-sm waves-light mb-3"
										id="submitButton">
										Update
									</button>
								</div>

						</form>
					</div>
				</div>
			</div>
		</div>
	<?php
	}
	?>

</div> -->
<?php if ($sub_page_main == 0) { ?>
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<form class="form-horizontal" action="<?php echo base_url($editpage) ?>" method="POST"><?= csrf_field() ?>
					<?= csrf_field() ?>
					<div class="row">
						<div class="form-group col-md-4 mb-2">
							<label>Page Name</label>
							<input type="text" class="form-control col-md-12" name="page_name" placeholder="Page Name"
								value="<?php echo $pagerow['page_name'] ?>" />
						</div>
						<div class="form-group col-md-2 mb-2">
							<label>Page Type</label>
							<select name="type" class="form-control">
								<!-- <option value="5" <?php echo ($pagerow['type'] == 5) ? 'selected' : ''; ?>>SCQ</option>
								<option value="6" <?php echo ($pagerow['type'] == 6) ? 'selected' : ''; ?>>MCQ</option> -->
								<option value="4" <?php echo ($pagerow['type'] == 4) ? 'selected' : ''; ?>>Quiz</option>
							</select>
						</div>
						<div class="form-group col-md-2 mb-2">
							<label>Page Number</label>
							<input type="number" class="form-control col-md-12" name="page_number" placeholder="Page Number"
								value="<?php echo $pagerow['page_number'] ?>" />
						</div>
						<div class="form-group col-md-2 mb-2">
							<label>Status</label>
							<select name="status" class="form-control">
								<option value="1" <?php echo ($pagerow['status'] == 1) ? 'selected' : ''; ?>>Editing</option>
								<!-- <option value="2" <?php echo ($pagerow['status'] == 2) ? 'selected' : ''; ?>>CE Rev</option>
								<option value="3" <?php echo ($pagerow['status'] == 3) ? 'selected' : ''; ?>>CE Fix</option>
								<option value="4" <?php echo ($pagerow['status'] == 4) ? 'selected' : ''; ?>>Client Rev</option>
								<option value="5" <?php echo ($pagerow['status'] == 5) ? 'selected' : ''; ?>>Client Fix</option> -->
								<option value="7" <?php echo ($pagerow['status'] == 7) ? 'selected' : ''; ?>>Dev Completed
								</option>
								<option value="0" <?php echo ($pagerow['status'] == 0) ? 'selected' : ''; ?>>Delete</option>
							</select>
						</div>
						<div class="form-group col-md-2 mt-3 mb-2">
							<?php if (isset($coursevalidation)): ?>
								<div class=col-12 col-sm-4>
									<div class="alert alert-white" role="alert">
										<?= $coursevalidation->listErrors() ?>
									</div>
								</div>
							<?php endif; ?>
							<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
							<!-- <input type="hidden" name="status" value="1"> -->
							<!-- <button type="submit" class="btn btn-sm btn-warning col-md-12">
								Update
							</button> -->
							<div class="form-group">
								<button type="submit"
									class="btn btn-outline-warning  btn-xs rounded-pill waves-effect waves-light">Update</button>
							</div>
						</div>

				</form>
			</div>
		</div>
	</div>
<?php } ?>
<!-- <div class="page-title">
	<div class="title_left">
		<h3></h3>
	</div>

	<?php //print_r(count($getQuestiondata));
	//exit(); 
	?>
	<div class="col-md-12 col-sm-12 mb-2">
		<div class="row">
			<?php if ($pagetype[0]['type'] == '5' || $pagetype[0]['type'] == '6') {
				if (empty($getQuestiondata)) { ?>
					<div class="col-md-3 col-sm-3 form-group pull-right">
						<form class="form-horizontal" action="<?php echo base_url('Assessment/trainings/add_new_question') ?>"
							method="POST"><?= csrf_field() ?>
							<?= csrf_field() ?>
							<input type="hidden" name="type" value="<?php echo $pagetype[0]['type'] ?>">
							<div class="form-group">
								<button type="submit"
									class="btn btn-outline-success  btn-xs rounded-pill waves-effect waves-light">Add New
									Question</button>
							</div>
						</form>
					</div>
			<?php }
			} ?>
			<?php if ($pagetype[0]['type'] == '4') { ?>
				<div class="col-md-2 col-sm-2 form-group pull-right">
					<form class="form-horizontal" action="<?php echo base_url('Assessment/trainings/add_new_question') ?>"
						method="POST"><?= csrf_field() ?>
						<?= csrf_field() ?>
						<input type="hidden" name="type" value="<?php echo $pagetype[0]['type'] ?>">
						<div class="form-group">
							<button type="submit"
								class="btn btn-outline-success  btn-xs rounded-pill waves-effect waves-light">Add New
								Question</button>
						</div>
					</form>
				</div>
				<div class="col-md-2 col-sm-2 form-group pull-right">
					<a href="<?php echo base_url('Assessment/trainings/assessment_settings') ?>">
						<div class="form-group">
							<button type="submit"
								class="btn btn-outline-warning  btn-xs rounded-pill waves-effect waves-light">Assessment
								Settings</button>
						</div>
					</a>
				</div>
			<?php } ?>
			<div class="col-md-2 col-sm-2 form-group pull-right">
				<form class="form-horizontal"
					action="<?php echo base_url('Assessment/trainings/export_questions_excel') ?>" method="POST"><?= csrf_field() ?>
					<?= csrf_field() ?>
					<input type="hidden" name="type" value="<?php echo $pagetype[0]['type'] ?>">
					<input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
					<input type="hidden" name="page_id" value="<?php echo $pagerow['page_id'] ?>">
					<input type="hidden" name="course_name" value="<?php echo $pagerow['course_name']; ?>">
					<div class="form-group">
						<button type="submit"
							class="btn btn-outline-danger  btn-xs rounded-pill waves-effect waves-light">Export
							Question's</button>
					</div>
				</form>
			</div>
			<div class="col-md-2 col-sm-2 form-group pull-right">
				<form class="form-horizontal"
					action="<?php echo base_url('Assessment/trainings/importQuestionsOptions_view') ?>" method="POST"><?= csrf_field() ?>
					<?= csrf_field() ?>
					<input type="hidden" name="type" value="<?php echo $pagetype[0]['type'] ?>">
					<input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
					<input type="hidden" name="page_id" value="<?php echo $pagerow['page_id'] ?>">
					<input type="hidden" name="course_name" value="<?php echo $pagerow['course_name']; ?>">
					<div class="form-group">
						<button type="submit"
							class="btn btn-outline-info  btn-xs rounded-pill waves-effect waves-light">Import
							Question's</button>
					</div>
				</form>
			</div>
			 <div class="col-md-2 col-sm-2 form-group pull-right">
				<form class="form-horizontal" action="<?php echo base_url('Assessment/trainings/review_quiz') ?>" method="POST"><?= csrf_field() ?>
					<input type="hidden" name="type" value="<?php echo $pagetype[0]['type'] ?>">
					<input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
					<input type="hidden" name="page_id" value="<?php echo $pagerow['page_id'] ?>">
					<input type="hidden" name="course_name" value="<?php echo $pagerow['course_name']; ?>">
					<div class="form-group">
						<button type="submit" class="btn btn-outline-primary  btn-xs rounded-pill waves-effect waves-light">Review Quiz</button>
					</div>
				</form>
			</div> 

		</div>
	</div>

</div> -->
<style>
	.question-cell {
		max-width: 500px;
		padding-left: 100px;
		/* Adjust as needed */
		overflow: hidden;
		/* text-overflow: ellipsis; */
		white-space: nowrap;

		vertical-align: top;
	}
</style>
<!-- <div class="row">
	<?php $userlevel = session()->get('userlevel');
	$array = array_map('intval', str_split($userlevel)); ?>
	<div class="card">
		<div class="card-body">
			<p class="text-muted font-13 mb-4"></p>
			<table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
				<thead>
					<tr>
						<th>#</th>
						<th>Question</th>
						<th>Edit</th>
						<?php if ($pagetype[0]['type'] == '4') { ?>
							<th>Copy</th>
						<?php } ?>
						<th>Delete</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$j = 0;
					// print_r($getQuestiondata);
					foreach ($getQuestiondata as $eachQuestion) {
						// print_r($eachQuestion);
						$j = $j + 1;

					?>
						<tr>
							<td width="5%"><?php echo $j; ?></td>
							 <td title="<?php echo $eachQuestion['question']; ?>">
								<?php echo strlen($eachQuestion['question']) > 100 ? substr($eachQuestion['question'], 0, 50) . '...' : $eachQuestion['question']; ?>
							</td> 

							<td class="question-cell"><?php echo $eachQuestion['question']; ?></td>
							 <td></td> 
							<td><?php echo $eachQuestion['categoryname']; ?></td> 
							<td></td> 
							 <td>
								<form class="form-horizontal" action="<?php echo base_url('Assessment/trainings/edit_quiz_quetion_view') ?>" method="POST"><?= csrf_field() ?>
									<input type="hidden" name="type" value="<?php echo $pagetype[0]['type']; ?>">
									<input type="hidden" name="question_id" value="<?php echo $eachQuestion['q_id']; ?>">
									<button type="submit" class="btn btn-sm widget-icon btn-warning"><span class="icon-pencil"></span></button>
								</form>
							</td> 
							<td width="10%">
								<form class="form-horizontal"
									action="<?php echo base_url('Assessment/trainings/add_quiz_option_view') ?>"
									method="POST"><?= csrf_field() ?>
									<?= csrf_field() ?>
									<input type="hidden" name="type" value="<?php echo $pagetype[0]['type']; ?>">
									<input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
									<input type="hidden" name="page_id" value="<?php echo $eachQuestion['page_id']; ?>">
									<input type="hidden" name="question_id" value="<?php echo $eachQuestion['q_id']; ?>">
									<button class="btn btn-outline-warning waves-effect btn-xs waves-light"><span class="mdi mdi-square-edit-outline"></span> Edit</button>
								</form>
							</td>
							<?php if ($pagetype[0]['type'] == '4') { ?>
								<td width="10%">
									<form class="form-horizontal" action="<?php echo base_url($copyQuestion_link) ?>"
										method="POST"><?= csrf_field() ?>
										<?= csrf_field() ?>
										<input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
										<input type="hidden" name="question_id" value="<?php echo $eachQuestion['q_id']; ?>">
										<button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span
												class="mdi mdi-content-copy"></span> Copy</button>
									</form>
								</td>
							<?php } ?>
							<td width="10%">
								<form class="form-horizontal" action="<?php echo base_url($delete_link) ?>" method="POST"><?= csrf_field() ?>
									<?= csrf_field() ?>
									<input type="hidden" name="question_id" value="<?php echo $eachQuestion['q_id']; ?>">
									<button type="submit"
										onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"
										class="btn btn-outline-danger waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span> Delete</button>
								</form>
							</td>

						</tr>
					<?php
					} ?>
				</tbody>
			</table>
		</div>
	</div>
</div> -->
</div>

<script>
	$(document).ready(function() {

		$('#dynamic-table').DataTable();

	});
</script>