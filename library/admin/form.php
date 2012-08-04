<?php
/**
 * Form helper functions for theme options page.
 * Based from Codeigniter's Form Helper Class (http://www.codeigniter.com).
 */

/**
 * Input text field helper function.
 * @since 1.3
 */
function ar2_form_input( $data = null, $value = null, $extra = null ) {
	/*$defaults = array('type' => 'text', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);
	return "<input "._parse_form_attributes($data, $defaults).$extra." />";*/
	$_defaults = array (
		'type'	=> 'text',
		'name'	=> ( ( !is_array( $data ) ) ? $data : '' ),
		'value'	=> $value
	);
	
	return '<input ' . ar2_parse_form_attributes( $data, $_defaults ) . $extra . ' />';
}

/**
 * Textarea helper function.
 * @since 1.3
 */
function ar2_form_textarea( $data = null, $value = null, $extra = null ) {
	$_defaults = array (
		'name'	=> ( ( !is_array( $data ) ) ? $data : ' ' ),
		'cols'	=> '80',
		'rows'	=> '5'
	);
	
	if ( !is_array( $data ) || !isset( $data['value'] ) ) {
		$val = $value;
	} else {
		$val = $data['value'];
		unset( $data['value'] ); // Textareas don't use the value attribute.	
	}
	
	return '<textarea ' . ar2_parse_form_attributes( $data, $_defaults ) . $extra . '>' . $val . '</textarea>';
}

/**
 * Select dropdown menus helper function.
 * @since 1.3
 */
function ar2_form_dropdown( $name = null, $options = array(), $selected = array(), $extra = null ) {
	if ( !is_array( $selected ) )
		$selected = array( $selected );
	
	if ( empty( $options ) )
		return false;
	
	// If no selected state was submitted we will attempt to set it automatically
	if ( count( $selected ) === 0 )
		// If the form name appears in the $_POST array we have a winner!
		if ( isset( $_POST[$name] ) )
			$selected = array( $_POST[$name] );
	
	if ( $extra != '' ) $extra = ' ' . $extra;
	
	$multiple = ( count( $selected ) > 1 && strpos( $extra, 'multiple' ) === false ) ? ' multiple="multiple"' : '';
	$form = '<select name="' . $name . '"' . $extra . $multiple . '>' . "\n";
	
	foreach ( $options as $key => $val ) {
		$key = (string) $key;
		if ( is_array( $val ) ) {
			$form .= '<optgroup label="' . $key . '">' . "\n";
			foreach ( $val as $optgroup_key => $optgroup_val ) {
				$form .= '<option value="' . $optgroup_key . '"' . selected( in_array( $optgroup_key, $selected ), true, false ) . '>' . (string) $optgroup_val . '</option>' . "\n";
			}
			$form .= '</optgroup>' . "\n";
		} else {
			$form .= '<option value="' . $key . '" ' . selected( in_array( $key, $selected), true, false ) . '>' . (string)	$val . '</option>' . "\n";
		}
	}
	$form .= '</select>';
	
	return $form;
}

/**
 * Checkbox helper function.
 * @since 1.3
 */
function ar2_form_checkbox( $data = null, $value = null, $checked = false, $extra = null ) {
	$_defaults = array (
		'type'	=> 'checkbox',
		'name'	=> ( ( !is_array( $data ) ) ? $data : '' ),
		'value'	=> $value
	);
	
	if ( is_array( $data ) && array_key_exists( 'checked', $data ) ) {
		$data['checked'] = $checked;
		unset( $data['checked'] );		
	}
	
	return '<input ' . ar2_parse_form_attributes( $data, $_defaults ) . checked( $checked, $value, false ) . $extra . ' />';
}

/**
 * Radio button helper function.
 * @since 1.3
 */
function ar2_form_radio( $data = null, $value = null, $checked = false, $extra = null ) {
	if ( !is_array( $data ) )
		$data = array ( 'name' => $data );

	$data['type'] = 'radio';
	return ar2_form_checkbox( $data, $value, $checked, $extra );
}

/**
 * Deprecated. Use checked() instead.
 * @deprecated deprecated since 1.6 
 * @since 1.3 
 */
function ar2_set_radio( $field = null, $value = null, $default = false ) {
	return checked( $field, $value, false );	
}

/**
 * Internal function to parse form attributes. 
 * 
 * Originally known as _parse_form_attributes() before 1.6.
 * 
 * @since 1.6
 */
function ar2_parse_form_attributes( $attributes, $default ) {
	if ( is_array( $attributes ) ) {
		
		foreach ( $default as $key => $val ) {
			if ( isset( $attributes[$key] ) ) {
				$default[$key] = $attributes[$key];
				unset( $attributes[$key] );
			}
		}
			
		if ( count( $attributes ) > 0 ) {
			$default = array_merge( $default, $attributes );
		}
	}
	
	$att = '';
	
	foreach ( $default as $key => $val ) {
		if ( $key == 'value' ) {
			$val = esc_attr( $val );
		}
		
		$att .= $key . '="' . $val . '" ';
	}
	
	return $att;
}

/* End of file form.php */
/* Location: ./admin/form.php */