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
 * triptych block
 *
 * @package   block_triptych
 * @copyright 2018 Oliver Redding (oliverredding@catalyst.net.nz)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * triptych block
 *
 * @copyright 2018 Oliver Redding (oliverredding@catalyst.net.nz)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_triptych extends block_base {

    /**
     * Init
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_triptych');
    }

    /**
     * Can appear on any page
     */
    public function applicable_formats() {
        return array('all' => true);
    }

    /**
     * Hide the header
     * @return boolean
     */
    public function hide_header() {
        return true;
    }
    /**
     * Unless we are in editing mode, remove all visual block chrome
     *
     * @return array attribute name => value.
     */
    public function html_attributes() {
        if ($this->page->user_is_editing()) {
            return parent::html_attributes();
        }
        $attributes = array(
            'id' => 'inst' . $this->instance->id,
            'class' => 'block_' . $this->name(),
            'role' => $this->get_aria_role()
        );
        return $attributes;
    }

    /**
     * We could have multiple triptychs
     *
     * @return bool
     */
    public function instance_allow_multiple() {
        return true;
    }

    /**
     * The html for the triptych
     */
    public function get_content() {
        global $CFG;

        require_once($CFG->libdir . '/filelib.php');

        $blockid = $this->context->id;
        $html = html_writer::start_tag('div', array('id' => 'triptych' . $blockid));

        if ($this->content !== null) {
            return $this->content;
        }

        $config = $this->config;
        $this->content = new stdClass;

        if (empty($config)) {
            $this->content->text = '';
            return $this->content;
        }

        $fs = get_file_storage();

        for ($c = 0; $c < count($config->title); $c++) {
            $title = $config->title[$c];
            $text = $config->text[$c];
            $url = $config->url[$c];
            $html .= html_writer::start_tag('div'); // This will be modified by slick.
            $files   = $fs->get_area_files($this->context->id, 'block_triptych', 'box', $c);

            $image = '';
            foreach ($files as $file) {

                if ($file->get_filesize() == 0) {
                    continue; // TODO fix the broken dud records.
                }

                $image = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(),
                        $file->get_filearea(), $file->get_itemid(), $file->get_filepath(), $file->get_filename());
            }

            // Wrapping the box in an object is a neat trick allowing the box to be a link
            // and for the text within it to also have sub-links.
            if ($url) {
                $html .= html_writer::start_tag('a', array('href' => $url, 'class' => 'boxlink'));
                $html .= html_writer::start_tag('object');
            }
            $show = ($c == 0) ? 'block' : 'none';
            $html .= html_writer::start_tag('div', array(
                'class' => 'boxwrap',
                'style' => "padding-bottom: $height; background-image: url($image); display: $show;"
            ));
            if ($title) {
                $html .= html_writer::tag('h4', $title, array('class' => 'title'));
            }
            if ($text) {
                $html .= html_writer::tag('div', $text, array('class' => 'text'));
            }
            $html .= html_writer::end_tag('div');
            if ($url) {
                $html .= html_writer::end_tag('object');
                $html .= html_writer::end_tag('a');
            }
            $html .= html_writer::end_tag('div');
        }

        $html .= html_writer::end_tag('div');
        $this->content->text = $html;

        return $this->content;
    }

    /**
     * Can never be docked
     *
     * @return bool
     */
    public function instance_can_be_docked() {
        return false;
    }

    /**
     * Serialize and store config data
     * @param object $data Form data
     * @param boolean $nolongerused boolean Not used
     */
    public function instance_config_save($data, $nolongerused = false) {
        $config = clone($data);
        for ($c = 0; $c < count($data->image); $c++) {
            file_save_draft_area_files($data->image[$c], $this->context->id, 'block_triptych', 'box', $c);
        }
        parent::instance_config_save($config, $nolongerused);
    }

    /**
     * Delete an instance
     */
    public function instance_delete() {
        $fs = get_file_storage();
        $fs->delete_area_files($this->context->id, 'block_triptych');
        return true;
    }
}
