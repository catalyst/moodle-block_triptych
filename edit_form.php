<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Form for editing a triptych block instance.
 *
 * @package   block_triptych
 * @copyright 2018 Oliver Redding (oliverredding@catalyst.net.nz)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Form for editing triptych block instances.
 *
 * @copyright 2018 Oliver Redding (oliverredding@catalyst.net.nz)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_triptych_edit_form extends block_edit_form {

    /**
     * Form def
     * @param object $mform the form being built.
     */
    protected function specific_definition($mform) {

        $mform->addElement('header', 'configheaderboxes', get_string('boxheader', 'block_triptych'));
        $mform->setExpanded('configheaderboxes');

        $options = array();
        $boxgroup = array();

        $boxgroup[] = $mform->createElement('text', 'config_title',
                get_string('boxtitle', 'block_triptych'));
        $options['config_title']['type'] = PARAM_TEXT;

        $boxgroup[] = $mform->createElement('textarea', 'config_text',
                get_string('boxtext', 'block_triptych'));
        $options['config_text']['type'] = PARAM_TEXT;

        $boxgroup[] = $mform->createElement('text', 'config_url',
                get_string('boxurl', 'block_triptych'));
        $options['config_url']['type'] = PARAM_URL;

        $boxgroup[] = $mform->createElement('filepicker', 'config_image',
                get_string('boximage', 'block_triptych'), null, array('accepted_types' => 'image'));
        $options['config_image']['type'] = PARAM_FILE;

        $boxgroup[] = $mform->createElement('html', '<hr>');

        $this->repeat_elements($boxgroup, 3, $options, 'boxes', 'add_boxes', 1,
                get_string('addbox', 'block_triptych'), true);

    }

}
