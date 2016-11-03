<textarea cols="20" rows="3" class="input-text"
          name="<?php echo esc_attr(isset($field['name']) ? $field['name'] : $key); ?>"
          id="<?php echo esc_attr($key); ?>" placeholder="<?php echo esc_attr($field['placeholder']); ?>"
          maxlength="<?php echo !empty($field['maxlength']) ? $field['maxlength']
	          : ''; ?>" <?php if (!empty($field['required'])) echo 'required'; ?>><?php echo isset($field['value'])
		? esc_textarea($field['value']) : ''; ?></textarea>
<?php if (!empty($field['description'])) : ?>
	<small class="description"><?php echo $field['description']; ?></small><?php endif; ?>