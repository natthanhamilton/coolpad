<?php
$editor = apply_filters('submit_job_form_wp_editor_args', [
	'textarea_name' => isset($field['name']) ? $field['name'] : $key,
	'media_buttons' => FALSE,
	'textarea_rows' => 8,
	'quicktags'     => FALSE,
	'tinymce'       => [
		'plugins'                       => 'lists,paste,tabfocus,wplink,wordpress',
		'paste_as_text'                 => TRUE,
		'paste_auto_cleanup_on_paste'   => TRUE,
		'paste_remove_spans'            => TRUE,
		'paste_remove_styles'           => TRUE,
		'paste_remove_styles_if_webkit' => TRUE,
		'paste_strip_class_attributes'  => TRUE,
		'toolbar1'                      => 'bold,italic,|,bullist,numlist,|,link,unlink,|,undo,redo',
		'toolbar2'                      => '',
		'toolbar3'                      => '',
		'toolbar4'                      => ''
	],
]);
wp_editor(isset($field['value']) ? wp_kses_post($field['value']) : '', $key, $editor);
if (!empty($field['description'])) : ?>
	<small class="description"><?php echo $field['description']; ?></small><?php endif; ?>
