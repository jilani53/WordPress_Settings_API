<?php

namespace Vidiow\Plugin\Admin;

/**
 * Plugin dashboard menu handler
 */
class Menu {

    function __construct() {
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
        add_action( 'admin_init', [ $this, 'vidiow_settings_fields' ] );
    }

    public function admin_menu() {
        $parent_slug = 'vidiow_plugin';
        $capability = 'manage_options';

        add_menu_page( __( 'Vidiow Plugin', 'vidiow' ), __( 'Vidiow Plugin', 'vidiow' ), $capability, $parent_slug, [ $this, 'registration_page' ], 'dashicons-video-alt3' );
        add_submenu_page( $parent_slug, __( 'Registration', 'vidiow' ), __( 'Registration', 'vidiow' ), $capability, $parent_slug, [ $this, 'registration_page' ], 'dashicons-video-alt3' );

        add_submenu_page( $parent_slug, __( 'Options', 'vidiow' ), __( 'Options', 'vidiow' ), $capability, 'options', [ $this, 'options_page' ], 'dashicons-screenoptions' );
        add_submenu_page( $parent_slug, __( 'Settings', 'vidiow' ), __( 'Settings', 'vidiow' ), $capability, 'settings', [ $this, 'settings_page' ], 'dashicons-admin-settings
        ' );
    }

    public function registration_page() {
        include_once __DIR__ . '/pages/register-page.php';
    }
    
    public function vidiow_settings_fields() {

        // Created variables to make the things clearer
        $page_slug = 'vidiow_plugin';
        $option_group = 'vidiow_plugin_settings';

        // Sections
        add_settings_section(
            'vidiow_plugin_register_section_id', // Section ID
            __( 'Section Titlte', 'vidiow' ), // Title (optional)
            '', // Callback function to display the section (optional)
            $page_slug
        );

        // Add fields
       $fields = [

            [
                'label'             => __( 'Text Field', 'vidiow' ),
                'id'                => 'text_field_name',
                'type'              => 'text',
                'section'           => 'vidiow_plugin_register_section_id',
                'sanitize_callback' => ''
            ],

            [
                'label'             => __( 'Image Upload', 'vidiow' ),
                'id'                => 'upload_single_img',
                'type'              => 'image',
                'section'           => 'vidiow_plugin_register_section_id',
                'sanitize_callback' => ''
            ],
            
            [
                'label'             => __( 'Date', 'vidiow' ),
                'id'                => 'add_date',
                'type'              => 'date',
                'section'           => 'vidiow_plugin_register_section_id',
                'sanitize_callback' => ''
            ],

            [
                'label'             => __( 'Color', 'vidiow' ),
                'id'                => 'add_color',
                'type'              => 'color',
                'section'           => 'vidiow_plugin_register_section_id',
                'sanitize_callback' => ''
            ],
            
            [
                'label'             => __( 'Select Field', 'vidiow' ),
                'id'                => 'add_select_field',
                'type'              => 'select',
                'section'           => 'vidiow_plugin_register_section_id',
                'options' => [
                    'val_1' => 'Value 1',
                    'val_2' => 'Value 2',
                    'val_3' => 'Value 3'
                ],
                'sanitize_callback' => ''
            ],

            [
                'label'             => __( 'Radio Field', 'vidiow' ),
                'id'                => 'select_radio_field',
                'type'              => 'radio',
                'section'           => 'vidiow_plugin_register_section_id',
                'radio_field' => [
                    'r_1' => 'Radio 1',
                    'r_2' => 'Radio 2',
                    'r_3' => 'Radio 3',
                    'r_4' => 'Radio 4',
                    'r_5' => 'Radio 5'
                ],
                'sanitize_callback' => ''
            ],

            [
                'label'             => __( 'Checkbox', 'vidiow' ),
                'id'                => 'feature_on',
                'type'              => 'checkbox',
                'section'           => 'vidiow_plugin_register_section_id',
                'sanitize_callback' => [ $this, 'sanitize_checkbox' ]
            ],
            
            [
                'label'             => __( 'Multi Checkbox', 'vidiow' ),
                'id'                => 'multi_checkbox_field',
                'type'              => 'multi_checkbox',
                'section'           => 'vidiow_plugin_register_section_id',
                'select_field' => [
                    'mcheck_1' => 'Multi Checkbox 1',
                    'mcheck_2' => 'Multi Checkbox 2',
                    'mcheck_3' => 'Multi Checkbox 3',
                    'mcheck_4' => 'Multi Checkbox 4',
                    'mcheck_5' => 'Multi Checkbox 5'
                ],
                'sanitize_callback' => ''
            ],

            [
                'label'             => __( 'Password Field', 'vidiow' ),
                'id'                => 'password_field_type',
                'type'              => 'password',
                'section'           => 'vidiow_plugin_register_section_id',
                'sanitize_callback' => ''
            ],

            [
                'label'             => __( 'Textarea field', 'vidiow' ),
                'id'                => 'textarea_field',
                'type'              => 'textarea',
                'section'           => 'vidiow_plugin_register_section_id',
                'sanitize_callback' => ''
            ],

        ];

        foreach( $fields as $field ) {

            // Settings fields
            add_settings_field(
                $field['id'], // field id
                $field['label'], // label
                [ $this, 'print_settings_field' ], // function to print the field
                $page_slug, // page slug
                $field['section'], // section id
                $field
            );

            // Register settings fields
            register_setting(
                $option_group, 
                $field['id'], // field id 
                $field['sanitize_callback']
            );

        }

    }

    public function print_settings_field( $field ) {

        $value = get_option( $field['id'] );

        /**
         * All field types input fields
         */
        switch ( $field['type'] ) {

            case 'multi_checkbox':
				if( ! empty ( $field['select_field'] ) && is_array( $field['select_field'] ) ) {

                    $options_markup = '';
                    foreach( $field['select_field'] as $key => $label ){

                        $selected = '';
                        if( is_array( $value ) && in_array( $key, $value )  ) {
                            $selected = 'checked';                            
                        }                        
                        
                        $options_markup .= sprintf(
                            '
                            <input type="checkbox" id="%1$s" name="%2$s[]" value="%1$s" %3$s />
                            <label for="%1$s"> %4$s </label><br>',

                            $key,
                            $field['id'],
                            $selected,
                            $label
                        );
                    }
                    printf( '<fieldset> %1$s </fieldset>', $options_markup );

                }
                break;

            case 'image':
				printf( 
                    '
                    <div id="vidiow_img_display_%1$s" class="display-img-container"></div>
                    <input type="hidden" name="%1$s" id="vidiow_img_uploader_field_%1$s" value="%2$s"/>

                    <button id="vidiow_img_upload_btn_%1$s" class="button button-secondary">Upload Image</button>                    
                    ',

					$field['id'],
					$value
				);

                ?>

                <script type="text/javascript">
 
                    "use strict";

                    var VidiowFrame, vidiow_jq = jQuery;

                    // Single logo uploader
                    var image_url = vidiow_jq("#vidiow_img_uploader_field_<?php echo esc_attr( $field['id'] ); ?>").val();

                    if (image_url) {
                        vidiow_jq("#vidiow_img_display_<?php echo esc_attr( $field['id'] ); ?>").html(`<img src="${image_url}" /> <span id="vidiow_img_remove_<?php echo esc_attr( $field['id'] ); ?>" class="remove-img">x</span>`);
                    }

                    /**
                     * Image remove event
                     */
                    vidiow_jq("#vidiow_img_remove_<?php echo esc_attr( $field['id'] ); ?>").on("click", function () {
                        vidiow_jq("#vidiow_img_uploader_field_<?php echo esc_attr( $field['id'] ); ?>").val("");
                        vidiow_jq("#vidiow_img_display_<?php echo esc_attr( $field['id'] ); ?>").html("");
                        return false;
                    });                    

                    // Single image uploader processing
                    vidiow_jq("#vidiow_img_upload_btn_<?php echo esc_attr( $field['id'] ); ?>").on("click", function () {
                        if ( VidiowFrame ) {
                            VidiowFrame.open();
                            return false;
                        }

                        VidiowFrame = wp.media({
                            title: "Upload Image",
                            button: {
                                text: "Insert Image",
                            },
                            multiple: false,
                        });

                        VidiowFrame.on("select", function() {
                            var attachment = VidiowFrame.state().get("selection").first().toJSON();
                            
                            vidiow_jq("#vidiow_img_uploader_field_<?php echo esc_attr( $field['id'] ); ?>").val(attachment.url);
                            vidiow_jq("#vidiow_img_display_<?php echo esc_attr( $field['id'] ); ?>").html(`<img src="${attachment.url}" alt=${attachment.alt} /> <span id="vidiow_img_remove_<?php echo esc_attr( $field['id'] ); ?>" class="remove-img">x</span>`);
                        
                            /**
                             * Image remove event
                             */
                            vidiow_jq("#vidiow_img_remove_<?php echo esc_attr( $field['id'] ); ?>").on("click", function() {
                                vidiow_jq("#vidiow_img_uploader_field_<?php echo esc_attr( $field['id'] ); ?>").val("");
                                vidiow_jq("#vidiow_img_display_<?php echo esc_attr( $field['id'] ); ?>").html("");
                            });
                        
                        });

                        VidiowFrame.open();
                        return false;
                    });

                </script>

                <?php
				break;

            case 'radio':
				if( ! empty ( $field['radio_field'] ) && is_array( $field['radio_field'] ) ) {

                    $options_markup = '';
                    foreach( $field['radio_field'] as $key => $label ){
                        $options_markup .= sprintf(
                            '
                            <input type="%1$s" id="%2$s" name="%3$s" value="%2$s" %4$s />
                            <label for="%2$s"> %5$s </label><br>',

                            $field['type'], 
                            $key,
                            $field['id'],
                            checked( $value, $key, false ),
                            $label
                        );
                    }
                    printf( '<fieldset> %1$s </fieldset>', $options_markup );

                }
                break;

            case 'select':
				if( ! empty ( $field['options'] ) && is_array( $field['options'] ) ) {

                    $options_markup = '';
                    foreach( $field['options'] as $key => $label ){
                        $options_markup .= sprintf( 
                            '<option value="%s" %s> %s </option>', 
                            $key, 
                            selected( $value, $key, false ), 
                            $label 
                        );
                    }
                    printf( '<select name="%1$s" id="%1$s">%2$s</select>', $field['id'], $options_markup );

                }
                break;

            case 'color':
				printf( '<input name="%1$s" id="%1$s" type="%2$s" value="%3$s" />',
					$field['id'],
                    $field['type'],
					$value
				);
				break;

            case 'date':
				printf( '<input name="%1$s" id="%1$s" type="%2$s" value="%3$s" />',
					$field['id'],
                    $field['type'],
					$value
				);
				break;

            case 'checkbox':
				printf( '<input name="%1$s" id="%1$s" type="%2$s" %3$s /> <label for="%1$s">' . __( 'Active', 'vidiow' ) . '</label>',
					$field['id'],
                    $field['type'],
					$value
				);
				break;
                
			case 'textarea':
				printf( '<textarea class="regular-text" name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50"> %3$s </textarea>',
					$field['id'],
					isset( $field['placeholder'] ) ? $field['placeholder'] : '',
					$value
				);
				break;

			default:
				printf( '<input class="regular-text" name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />',
					$field['id'],
					$field['type'],
					isset( $field['placeholder'] ) ? $field['placeholder'] : '',
					$value
				);
        }
    }

    // Custom sanitization function for a checkbox field
    public function sanitize_checkbox( $value ) {
        return 'on' === $value ? 'checked' : '';
    }

    public function options_page() {
        echo "hello options";
    }

    public function settings_page() {
        include_once __DIR__ . '/pages/settings-page.php';
    }

}