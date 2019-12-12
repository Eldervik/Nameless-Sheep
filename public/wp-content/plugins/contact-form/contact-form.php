<?php
/**
 * Plugin Name: contact Form
 * Plugin URI:  https://narimanalkurdi.com
 * Description: This plugin adds a shortcode to display the related posts for a post.
 * Version:     0.1
 * Author:      Nariman Alkurdi
 * Author URI:  https://narimanalkurdi.com
 * License:     WTFPL
 * License URI: http://www.wtfpl.net/
 * Text Domain: Contact Form
 * Domain Path: /languages
 */

class contact_Form {

    /**
     * Class constructor
     */
    public function __construct() {

        $this->define_hooks();
    }

    public function controller() {

        if( isset( $_POST['submit'] ) ) { // Submit button

            $full_name   = filter_input( INPUT_POST, 'full_name', FILTER_SANITIZE_STRING );
            $email       = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_STRING | FILTER_SANITIZE_EMAIL );
            
            $title = filter_input( INPUT_POST, 'title', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );
            $comments    = filter_input( INPUT_POST, 'comments', FILTER_SANITIZE_STRING );

            // Send an email and redirect user to "Thank you" page.
        }
    }

    /**
     * Display form
     */
    public function display_form() {

        $full_name   = filter_input( INPUT_POST, 'full_name', FILTER_SANITIZE_STRING );
        $email       = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_STRING | FILTER_SANITIZE_EMAIL );
        $title = filter_input( INPUT_POST, 'title', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );
        $comments    = filter_input( INPUT_POST, 'comments', FILTER_SANITIZE_STRING );

        // Default empty array
        $title = ( $title === null ) ? array() : $title;

        $output = '';

        $output .= '<form method="post">';
        $output .= '    <p>';
        $output .= '        ' . $this->display_text( 'full_name', 'Name', $full_name );
        $output .= '    </p>';
        $output .= '    <p>';
        $output .= '        ' . $this->display_text( 'email', 'Email', $email );
        $output .= '    </p>';
        
        $output .= '    <p>';
        $output .= '        ' . $this->display_checkboxes( 'title', 'Title', $this->get_available_title(), $title );
        $output .= '    </p>';
        $output .= '    <p>';
        $output .= '        ' . $this->display_textarea( 'comments', 'comments', $comments );
        $output .= '    </p>';
        $output .= '    <p>';
        $output .= '        <input type="submit" name="submit" value="Submit" />';
        $output .= '    </p>';
        $output .= '</form>';

        return $output;
    }

    /**
     * Display text field
     */
    private function display_text( $name, $label, $value = '' ) {

        $output = '';

        $output .= '<label>' . esc_html__( $label, 'contact' ) . '</label>';
        $output .= '<input type="text" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '">';

        return $output;
    }

    /**
     * Display textarea field
     */
    private function display_textarea( $name, $label, $value = '' ) {

        $output = '';

        $output .= '<label> ' . esc_html__( $label, 'contact' ) . '</label>';
        $output .= '<textarea name="' . esc_attr( $name ) . '" >' . esc_html( $value ) . '</textarea>';

        return $output;
    }

    /**
     * Display radios field
     */
    private function display_radios( $name, $label, $options, $value = null ) {

        $output = '';

        $output .= '<label>' . esc_html__( $label, 'contact' ) . '</label>';

        foreach ( $options as $option_value => $option_label ):
            $output .= $this->display_radio( $name, $option_label, $option_value, $value );
        endforeach;

        return $output;
    }

    /**
     * Display single checkbox field
     */
    private function display_radio( $name, $label, $option_value, $value = null ) {

        $output = '';

        $checked = ( $option_value === $value ) ? ' checked' : '';

        $output .= '<label>';
        $output .= '    <input type="radio" name="' . esc_attr( $name ) . '" value="' . esc_attr( $option_value ) . '"' . esc_attr( $checked ) . '>';
        $output .= '    ' . esc_html__( $label, 'contact' );
        $output .= '</label>';

        return $output;
    }

    /**
     * Display checkboxes field
     */
    private function display_checkboxes( $name, $label, $options, $values = array() ) {

        $output = '';

        $name .= '[]';

        $output .= '<label>' . esc_html__( $label, 'contact' ) . '</label>';

        foreach ( $options as $option_value => $option_label ):
            $output .= $this->display_checkbox( $name, $option_label, $option_value, $values );
        endforeach;

        return $output;
    }

    /**
     * Display single checkbox field
     */
    private function display_checkbox( $name, $label, $available_value, $values = array() ) {

        $output = '';

        $checked = ( in_array($available_value, $values) ) ? ' checked' : '';

        $output .= '<label>';
        $output .= '    <input type="checkbox" name="' . esc_attr( $name ) . '" value="' . esc_attr( $available_value ) . '"' . esc_attr( $checked ) . '>';
        $output .= '    ' . esc_html__( $label, 'contact' );
        $output .= '</label>';

        return $output;
    }


    /**
     * Get available title
     */
    private function get_available_title() {

        return array(
            'betalning' => 'Betalning',
            'bestalning' => 'Bestälning',
            'frakt' => 'Frakt',
            'annat' => 'Annat',
        );
    }

    /**
     * Define hooks related to plugin
     */
    private function define_hooks() {

        /**
         * Add action to send email
         */
        add_action( 'wp', array( $this, 'controller' ) );

        /**
         * Add shortcode to display form
         */
        add_shortcode( 'contact', array( $this, 'display_form' ) );
    }
}

new contact_Form();