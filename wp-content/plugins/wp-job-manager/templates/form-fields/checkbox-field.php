<input type="checkbox" class="input-checkbox"
       name="<?php echo esc_attr(isset($field['name']) ? $field['name'] : $key); ?>"
       id="<?php echo esc_attr($key); ?>" <?php checked(! empty($field['value']), TRUE); ?>
       value="1" <?php if (! empty($field['required'])) echo 'required'; ?> />
<?php if (! empty($field['description'])) : ?>
    <small class="description"><?php echo $field['description']; ?></small><?php endif; ?>